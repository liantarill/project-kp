<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Attendance;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class AttendanceExcelExport implements FromQuery, WithColumnWidths, WithEvents, WithHeadings, WithMapping
{
    protected string $userId;

    protected User $user;

    protected array $statusCounts = [];

    protected int $rowNumber = 0;

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
        $this->rowNumber++;

        $status = match ($attendance->status) {
            'present' => 'Hadir',
            'permission' => 'Izin',
            'sick' => 'Sakit',
            'absent' => 'Alfa',
            'late' => 'Terlambat',
            default => '-',
        };

        return [
            $this->rowNumber,
            $attendance->date?->locale('id')->translatedFormat('l'), // Hari
            $attendance->date?->locale('id')->translatedFormat('d F Y'), // Tanggal
            $attendance->check_in?->format('H:i') ?? '-', // Jam Masuk
            $status, // Status
            $attendance->note ?? '-', // Keterangan
        ];
    }

    public function headings(): array
    {
        return [
            ['LAPORAN DATA KEHADIRAN'],
            [],
            ['Nama', '', ': ' . $this->user->name],
            ['Asal Instansi', '', ': ' . ($this->user->institution?->name ?? '-')],
            ['Jurusan', '', ': ' . (($this->user->level ?? '') . ' ' . ($this->user->major ?? ''))],
            ['Divisi', '', ': ' . ($this->user->department?->name ?? '-')],
            [
                'Periode',
                '',
                ': ' . $this->user->start_date->locale('id')->translatedFormat('d F Y') . ' s/d ' .
                    $this->user->end_date->locale('id')->translatedFormat('d F Y'),
            ],
            [],
            ['No', 'Hari', 'Tanggal', 'Jam Masuk', 'Status', 'Keterangan'],
        ];
    }

    /**
     * Set lebar kolom custom
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 12,  // Hari
            'C' => 20,  // Tanggal
            'D' => 15,  // Jam Masuk
            'E' => 15,  // Status
            'F' => 30,  // Keterangan
            'G' => 3,   // Kolom kosong (pemisah)
            'H' => 15,  // Rekap - Label
            'I' => 15,  // Rekap - Value
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Style untuk judul utama (baris 1)
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->mergeCells('A1:F1');
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Style untuk informasi (baris 3-7)
                $sheet->getStyle('A3:A7')->getFont()->setBold(true);

                // Style untuk header tabel (baris 9)
                $headerRow = 9;
                $sheet->getStyle("A{$headerRow}:F{$headerRow}")->getFont()->setBold(true);
                $sheet->getStyle("A{$headerRow}:F{$headerRow}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFE2E8F0');
                $sheet->getStyle("A{$headerRow}:F{$headerRow}")->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Border untuk tabel data
                $dataLastRow = $sheet->getHighestRow();
                $sheet->getStyle("A{$headerRow}:F{$dataLastRow}")->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // Center align specific columns (No, Hari, Jam Masuk, Status) except Note
                $sheet->getStyle("A10:E{$dataLastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle("B10:C{$dataLastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // Wrap text untuk kolom Keterangan
                $sheet->getStyle("F10:F{$dataLastRow}")->getAlignment()->setWrapText(true);

                // Tambahkan rekap di kolom H (F + 2 kolom)
                $rekapStartRow = $headerRow; // Mulai dari baris yang sama dengan header tabel
                $rekapCol = 'H';

                $sheet->setCellValue("{$rekapCol}{$rekapStartRow}", 'REKAP KEHADIRAN');
                $sheet->getStyle("{$rekapCol}{$rekapStartRow}")->getFont()->setBold(true)->setSize(12);
                $sheet->mergeCells("{$rekapCol}{$rekapStartRow}:I{$rekapStartRow}");
                $sheet->getStyle("{$rekapCol}{$rekapStartRow}")->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $rekapStartRow++; // baris kosong

                foreach ($this->statusCounts as $label => $count) {
                    $sheet->setCellValue("{$rekapCol}{$rekapStartRow}", $label);
                    $sheet->setCellValue("I{$rekapStartRow}", ': ' . $count . ' hari');
                    $sheet->getStyle("{$rekapCol}{$rekapStartRow}")->getFont()->setBold(true);
                    $rekapStartRow++;
                }

                // Total kehadiran
                $rekapStartRow++;
                $totalDays = array_sum($this->statusCounts);
                $sheet->setCellValue("{$rekapCol}{$rekapStartRow}", 'Total');
                $sheet->setCellValue("I{$rekapStartRow}", ': ' . $totalDays . ' hari');
                $sheet->getStyle("{$rekapCol}{$rekapStartRow}:I{$rekapStartRow}")->getFont()->setBold(true);

                // Set lebar kolom untuk rekap
                $sheet->getColumnDimension('H')->setWidth(15);
                $sheet->getColumnDimension('I')->setWidth(15);

                // Border untuk box rekap
                $rekapEndRow = $rekapStartRow;
                $sheet->getStyle("H9:I{$rekapEndRow}")->getBorders()->getOutline()
                    ->setBorderStyle(Border::BORDER_MEDIUM);
                $sheet->getStyle("H11:I{$rekapEndRow}")->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // Background untuk header rekap
                $sheet->getStyle('H9:I9')->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFE2E8F0');
            },
        ];
    }
}
