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
            [
                'Jurusan',
                '',
                ': ' . trim(
                    ($this->user->level === 'SMA' ? '' : ($this->user->level ?? '')) .
                        ' ' .
                        ($this->user->major ?? '')
                )
            ],

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
                $dataLastRow = $sheet->getHighestRow();

                // Style untuk judul utama (baris 1)
                $sheet->getStyle('A1:F1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4472C4'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->mergeCells('A1:F1');
                $sheet->getRowDimension(1)->setRowHeight(30);

                // Style untuk informasi peserta (baris 3-7)
                $sheet->getStyle('A3:A7')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F2F2F2'],
                    ],
                ]);

                // Merge cells untuk nilai informasi
                // foreach ([3, 4, 5, 6, 7] as $row) {
                //     $sheet->mergeCells("B{$row}:F{$row}");
                // }

                // Background untuk semua baris informasi
                $sheet->getStyle('A3:F7')->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F8F9FA'],
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D3D3D3'],
                        ],
                    ],
                ]);

                // Style untuk header tabel (baris 9)
                $headerRow = 9;
                $sheet->getStyle("A{$headerRow}:F{$headerRow}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '5B9BD5'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'FFFFFF'],
                        ],
                    ],
                ]);
                $sheet->getRowDimension($headerRow)->setRowHeight(25);

                // Border dan alignment untuk tabel data
                if ($dataLastRow > $headerRow) {
                    $sheet->getStyle("A10:F{$dataLastRow}")->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'D3D3D3'],
                            ],
                        ],
                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ]);

                    // Center align untuk kolom No, Hari, Jam Masuk, Status
                    $sheet->getStyle("A10:A{$dataLastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // No
                    $sheet->getStyle("D10:D{$dataLastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Jam Masuk
                    $sheet->getStyle("E10:E{$dataLastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Status

                    // Left align untuk Hari dan Tanggal
                    $sheet->getStyle("B10:C{$dataLastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                    // Wrap text untuk kolom Keterangan
                    $sheet->getStyle("F10:F{$dataLastRow}")->getAlignment()->setWrapText(true);

                    // Zebra striping untuk data
                    for ($row = 10; $row <= $dataLastRow; $row++) {
                        if ($row % 2 == 0) {
                            $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => ['rgb' => 'F2F2F2'],
                                ],
                            ]);
                        }
                    }
                }

                // REKAP KEHADIRAN
                $rekapStartRow = $headerRow;
                $rekapCol = 'H';

                // Header rekap
                $sheet->setCellValue("{$rekapCol}{$rekapStartRow}", 'REKAP KEHADIRAN');
                $sheet->getStyle("{$rekapCol}{$rekapStartRow}:I{$rekapStartRow}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '70AD47'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->mergeCells("{$rekapCol}{$rekapStartRow}:I{$rekapStartRow}");
                $sheet->getRowDimension($rekapStartRow)->setRowHeight(25);

                // $rekapStartRow++; // Baris kosong
                // $sheet->getRowDimension($rekapStartRow)->setRowHeight(5);

                $rekapDataStart = $rekapStartRow + 1;

                // Detail rekap per status
                foreach ($this->statusCounts as $label => $count) {
                    $rekapStartRow++;
                    $sheet->setCellValue("{$rekapCol}{$rekapStartRow}", $label);
                    $sheet->setCellValue("I{$rekapStartRow}", $count . ' hari');

                    $sheet->getStyle("{$rekapCol}{$rekapStartRow}")->applyFromArray([
                        'font' => ['bold' => true],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                    ]);
                    $sheet->getStyle("I{$rekapStartRow}")->applyFromArray([
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);
                }

                // Baris kosong sebelum total
                // $rekapStartRow++;
                // $sheet->getRowDimension($rekapStartRow)->setRowHeight(5);

                // Total kehadiran
                $rekapStartRow++;
                $totalDays = array_sum($this->statusCounts);
                $sheet->setCellValue("{$rekapCol}{$rekapStartRow}", 'Total');
                $sheet->setCellValue("I{$rekapStartRow}", $totalDays . ' hari');

                $sheet->getStyle("{$rekapCol}{$rekapStartRow}:I{$rekapStartRow}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E2EFDA'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Border untuk box rekap (exclude header dan baris kosong)
                $rekapEndRow = $rekapStartRow;
                $sheet->getStyle("H{$rekapDataStart}:I{$rekapEndRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D3D3D3'],
                        ],
                        'outline' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['rgb' => '70AD47'],
                        ],
                    ],
                ]);

                // Border untuk header rekap
                $sheet->getStyle("H9:I9")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['rgb' => '70AD47'],
                        ],
                    ],
                ]);
            },
        ];
    }
}
