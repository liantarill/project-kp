<?php

namespace App\Exports;

use App\Models\User;
use App\Services\ReportFilterService;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class UsersExport implements FromQuery, WithHeadings, WithMapping, WithEvents
{
    protected array $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = User::query()
            ->with(['institution', 'department']);

        $query->where('role', 'participant')
            ->whereNot('status', 'cancelled');

        return ReportFilterService::applyParticipantFilters($query, $this->filters);
    }

    public function headings(): array
    {
        $headings = [
            ['Rekap Peserta'],
        ];

        // Tambahkan info filter jika ada
        $filterInfo = $this->getFilterInfo();
        if (!empty($filterInfo)) {
            $headings[] = ['Filter: ' . $filterInfo];
        }

        // Tambahkan baris kosong sebagai pemisah
        $headings[] = [];

        // Header kolom
        $headings[] = [
            'Nama',
            'Email',
            'No. Telepon',
            'Jenis Kelamin',
            'Asal Instansi',
            'Jenjang',
            'Jurusan',
            'Bagian',
            'Tanggal Mulai',
            'Tanggal Selesai',
        ];

        return $headings;
    }

    protected function getFilterInfo(): string
    {
        $filters = [];
        $period = $this->filters['period'] ?? 'all';

        // Tampilkan filter berdasarkan tipe periode
        if ($period === 'year') {
            // Jika periode year, hanya tampilkan tahun
            if (!empty($this->filters['year'])) {
                $filters[] = 'Tahun: ' . $this->filters['year'];
            }
        } elseif ($period === 'month') {
            // Jika periode month, tampilkan tahun dan bulan
            if (!empty($this->filters['year'])) {
                $filters[] = 'Tahun: ' . $this->filters['year'];
            }
            if (!empty($this->filters['month'])) {
                $monthNames = [
                    1 => 'Januari',
                    2 => 'Februari',
                    3 => 'Maret',
                    4 => 'April',
                    5 => 'Mei',
                    6 => 'Juni',
                    7 => 'Juli',
                    8 => 'Agustus',
                    9 => 'September',
                    10 => 'Oktober',
                    11 => 'November',
                    12 => 'Desember'
                ];
                $filters[] = 'Bulan: ' . ($monthNames[$this->filters['month']] ?? $this->filters['month']);
            }
        } elseif ($period === 'custom' || (!empty($this->filters['start_date']) && !empty($this->filters['end_date']))) {
            // Jika periode custom/date, tampilkan tahun, bulan, dan tanggal
            if (!empty($this->filters['year'])) {
                $filters[] = 'Tahun: ' . $this->filters['year'];
            }
            if (!empty($this->filters['month'])) {
                $monthNames = [
                    1 => 'Januari',
                    2 => 'Februari',
                    3 => 'Maret',
                    4 => 'April',
                    5 => 'Mei',
                    6 => 'Juni',
                    7 => 'Juli',
                    8 => 'Agustus',
                    9 => 'September',
                    10 => 'Oktober',
                    11 => 'November',
                    12 => 'Desember'
                ];
                $filters[] = 'Bulan: ' . ($monthNames[$this->filters['month']] ?? $this->filters['month']);
            }
            if (!empty($this->filters['start_date']) && !empty($this->filters['end_date'])) {
                $startDate = date('d/m/Y', strtotime($this->filters['start_date']));
                $endDate = date('d/m/Y', strtotime($this->filters['end_date']));
                $filters[] = 'Tanggal: ' . $startDate . ' - ' . $endDate;
            }
        } elseif ($period !== 'all') {
            // Untuk periode lain (today, week, dll)
            $periodLabels = [
                'today' => 'Hari Ini',
                'week' => 'Minggu Ini',
                'month' => 'Bulan Ini',
                'year' => 'Tahun Ini',
            ];
            $filters[] = 'Periode: ' . ($periodLabels[$period] ?? $period);
        }

        // Status filter
        if (!empty($this->filters['status']) && $this->filters['status'] !== 'all') {
            $statusLabels = [
                'active' => 'Aktif',
                'completed' => 'Selesai',
                'pending' => 'Menunggu'
            ];
            $filters[] = 'Status: ' . ($statusLabels[$this->filters['status']] ?? $this->filters['status']);
        }

        // Institution type filter
        if (!empty($this->filters['institution_type']) && $this->filters['institution_type'] !== 'all') {
            $filters[] = 'Jenis Instansi: ' . ucfirst($this->filters['institution_type']);
        }

        // Level filter
        if (!empty($this->filters['level']) && $this->filters['level'] !== 'all') {
            $filters[] = 'Jenjang: ' . ucfirst($this->filters['level']);
        }

        return implode(' | ', $filters);
    }

    /**
     * @var User $user
     */
    public function map($user): array
    {
        return [
            $user->name,
            $user->email,
            $user->phone,
            $user->gender === 'male' ? "Laki-laki" : "Perempuan",
            $user->institution?->name ?? '-',
            $user->level,
            $user->major,
            $user->department?->name ?? '-',
            $user->start_date?->format('d/m/Y'),
            $user->end_date?->format('d/m/Y'),
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                // Cek apakah ada info filter
                $hasFilterInfo = !empty($this->getFilterInfo());
                $headerRow = $hasFilterInfo ? 4 : 2; // Row untuk header kolom
                $dataStartRow = $headerRow + 1;

                // Style untuk judul utama (baris 1)
                $sheet->getStyle('A1:J1')->applyFromArray([
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
                $sheet->mergeCells('A1:J1');
                $sheet->getRowDimension(1)->setRowHeight(30);

                // Style untuk info filter jika ada (baris 2)
                if ($hasFilterInfo) {
                    $sheet->getStyle('A2:J2')->applyFromArray([
                        'font' => [
                            'italic' => true,
                            'size' => 10,
                            'color' => ['rgb' => '666666'],
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'E7E6E6'],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ]);
                    $sheet->mergeCells('A2:J2');
                    $sheet->getRowDimension(2)->setRowHeight(20);
                }

                // Style untuk header kolom
                $sheet->getStyle('A' . $headerRow . ':J' . $headerRow)->applyFromArray([
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

                // Style untuk data (baris data ke bawah)
                if ($highestRow > $headerRow) {
                    $sheet->getStyle('A' . $dataStartRow . ':J' . $highestRow)->applyFromArray([
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

                    // Zebra striping untuk baris data
                    for ($row = $dataStartRow; $row <= $highestRow; $row++) {
                        if ($row % 2 == 0) {
                            $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray([
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => ['rgb' => 'F2F2F2'],
                                ],
                            ]);
                        }
                    }
                }

                // Auto-size kolom
                foreach (range('A', 'J') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                // Set minimum width untuk kolom tertentu
                $sheet->getColumnDimension('A')->setWidth(25); // Nama
                $sheet->getColumnDimension('B')->setWidth(30); // Email
                $sheet->getColumnDimension('E')->setWidth(25); // Asal Instansi
            }
        ];
    }
}
