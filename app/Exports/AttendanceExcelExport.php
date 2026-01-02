<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class AttendanceExcelExport implements FromQuery, WithColumnWidths, WithEvents, WithHeadings, WithMapping
{
    protected string $userId;

    protected User $user;

    protected array $statusCounts = [];

    public function __construct(string $userId)
    {
        $this->userId = $userId;
        $this->user = User::findOrFail($userId);

        // Hitung rekap status
        $counts = Attendance::where('user_id', $userId)
            ->select('status')
            ->selectRaw('count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $this->statusCounts = [
            'Hadir' => $counts['present'] ?? 0,
            'Izin' => $counts['permission'] ?? 0,
            'Sakit' => $counts['sick'] ?? 0,
            'Alfa' => $counts['absent'] ?? 0,
            'Terlambat' => $counts['late'] ?? 0,
        ];
    }

    public function query()
    {
        return Attendance::query()
            ->where('user_id', $this->userId)
            ->orderBy('date', 'asc');
    }

    public function map($attendance): array
    {
        $status = match ($attendance->status) {
            'present' => 'Hadir',
            'permission' => 'Izin',
            'sick' => 'Sakit',
            'absent' => 'Alfa',
            'late' => 'Terlambat',
            default => '-',
        };

        $photoUrl = $attendance->photo
            ? 'https://drive.google.com/file/d/'.$attendance->photo.'/view'
            : '-';

        return [
            $attendance->date?->locale('id')->translatedFormat('d F Y'),
            $attendance->check_in?->format('H:i') ?? '-',
            $status,
            $photoUrl,
        ];
    }

    public function headings(): array
    {
        return [
            ['LAPORAN DATA KEHADIRAN'],
            [],
            ['Nama', ': '.$this->user->name],
            ['Asal Instansi', ': '.($this->user->institution?->name ?? '-')],
            ['Jurusan', ': '.(($this->user->level ?? '').' '.($this->user->major ?? ''))],
            ['Divisi', ': '.($this->user->department?->name ?? '-')],
            [
                'Periode',
                ': '.$this->user->start_date->locale('id')->translatedFormat('d F Y').' s/d '.
                $this->user->end_date->locale('id')->translatedFormat('d F Y'),
            ],
            [],
            ['Tanggal', 'Jam Masuk', 'Status', 'Bukti Kehadiran'],
        ];
    }

    /**
     * Set lebar kolom custom
     */
    public function columnWidths(): array
    {
        return [
            'A' => 20,  // Tanggal
            'B' => 15,  // Jam Masuk
            'C' => 15,  // Status
            'D' => 50,  // Bukti Kehadiran (URL panjang)
            'E' => 3,   // Kolom kosong (pemisah)
            'F' => 15,  // Rekap - Label
            'G' => 15,  // Rekap - Value
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->getColumnDimension('D')->setAutoSize(true); // kolom D

                // Style untuk judul utama (baris 1)
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->mergeCells('A1:D1');
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // Style untuk informasi (baris 3-7)
                $sheet->getStyle('A3:A7')->getFont()->setBold(true);

                // Style untuk header tabel (baris 9)
                $headerRow = 9;
                $sheet->getStyle("A{$headerRow}:D{$headerRow}")->getFont()->setBold(true);
                $sheet->getStyle("A{$headerRow}:D{$headerRow}")->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFE2E8F0');
                $sheet->getStyle("A{$headerRow}:D{$headerRow}")->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // Border untuk tabel data
                $dataLastRow = $sheet->getHighestRow();
                $sheet->getStyle("A{$headerRow}:D{$dataLastRow}")->getBorders()->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                // Wrap text untuk kolom URL
                $sheet->getStyle("D10:D{$dataLastRow}")->getAlignment()->setWrapText(true);

                // Tambahkan rekap di kolom F (last column + 2)
                $rekapStartRow = $headerRow; // Mulai dari baris yang sama dengan header tabel
                $rekapCol = 'F'; // Kolom F (D + 2 kolom)

                $sheet->setCellValue("{$rekapCol}{$rekapStartRow}", 'REKAP KEHADIRAN');
                $sheet->getStyle("{$rekapCol}{$rekapStartRow}")->getFont()->setBold(true)->setSize(12);
                $sheet->mergeCells("{$rekapCol}{$rekapStartRow}:G{$rekapStartRow}");
                $sheet->getStyle("{$rekapCol}{$rekapStartRow}")->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $rekapStartRow++; // baris kosong

                foreach ($this->statusCounts as $label => $count) {
                    $sheet->setCellValue("{$rekapCol}{$rekapStartRow}", $label);
                    $sheet->setCellValue("G{$rekapStartRow}", ': '.$count.' hari');
                    $sheet->getStyle("{$rekapCol}{$rekapStartRow}")->getFont()->setBold(true);
                    $rekapStartRow++;
                }

                // Total kehadiran
                $rekapStartRow++;
                $totalDays = array_sum($this->statusCounts);
                $sheet->setCellValue("{$rekapCol}{$rekapStartRow}", 'Total');
                $sheet->setCellValue("G{$rekapStartRow}", ': '.$totalDays.' hari');
                $sheet->getStyle("{$rekapCol}{$rekapStartRow}:G{$rekapStartRow}")->getFont()->setBold(true);

                // Set lebar kolom untuk rekap
                $sheet->getColumnDimension('F')->setWidth(15);
                $sheet->getColumnDimension('G')->setWidth(15);

                // Border untuk box rekap
                $rekapEndRow = $rekapStartRow;
                $sheet->getStyle("F9:G{$rekapEndRow}")->getBorders()->getOutline()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                $sheet->getStyle("F11:G{$rekapEndRow}")->getBorders()->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                // Background untuk header rekap
                $sheet->getStyle('F9:G9')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFE2E8F0');
            },
        ];
    }
}
