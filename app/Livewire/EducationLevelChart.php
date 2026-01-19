<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\ReportFilterService;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class EducationLevelChart extends ChartWidget
{
    protected ?string $heading = 'Statistik Level Pendidikan';

    protected $listeners = ['filterUpdated' => 'updateFilters'];

    public array $filters = [];

    public function mount(): void
    {
        $this->filters = ReportFilterService::getDefaults();
    }

    public function updateFilters(array $filters): void
    {
        $this->filters = ReportFilterService::sanitize($filters);
    }

    protected function getData(): array
    {
        $cacheKey = 'education_level_' . md5(json_encode($this->filters));

        return Cache::remember($cacheKey, now()->addMinutes(10), function () {
            $filters = $this->filters;
            $period = $filters['period'] ?? 'all';

            $query = User::select([
                'level',
                DB::raw('COUNT(*) as total')
            ])
                ->where('role', 'participant')
                ->whereNotNull('level');

            // Apply date filter
            switch ($period) {
                case 'month':
                    if (!empty($filters['month']) && !empty($filters['year'])) {
                        $query->whereMonth('start_date', $filters['month'])
                            ->whereYear('start_date', $filters['year']);
                    }
                    break;

                case 'year':
                    if (!empty($filters['year'])) {
                        $query->whereYear('start_date', $filters['year']);
                    }
                    break;

                case 'custom':
                    if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
                        $query->whereBetween('start_date', [
                            $filters['start_date'] . ' 00:00:00',
                            $filters['end_date'] . ' 23:59:59'
                        ]);
                    }
                    break;
            }

            // Apply status filter
            if (!empty($filters['status']) && $filters['status'] !== 'all') {
                $query->where('status', $filters['status']);
            }

            // Apply institution type filter
            if (!empty($filters['institution_type']) && $filters['institution_type'] !== 'all') {
                $query->whereHas('institution', function ($q) use ($filters) {
                    $q->where('type', $filters['institution_type']);
                });
            }

            // Apply level filter (jika user pilih level spesifik)
            if (!empty($filters['level']) && $filters['level'] !== 'all') {
                $query->where('level', $filters['level']);
            }

            $data = $query->groupBy('level')
                ->orderByRaw("CASE 
                    WHEN level = 'S1' THEN 1
                    WHEN level = 'D4' THEN 2
                    WHEN level = 'D3' THEN 3
                    WHEN level = 'D2' THEN 4
                    WHEN level = 'D1' THEN 5
                    WHEN level = 'SMA' THEN 6
                    ELSE 7
                END")
                ->get();

            return [
                'datasets' => [
                    [
                        'label' => 'Jumlah Peserta',
                        'data' => $data->pluck('total')->toArray(),
                        'backgroundColor' => [
                            'rgba(16, 185, 129, 0.8)',   // S1 - Green
                            'rgba(59, 130, 246, 0.8)',   // D4 - Blue
                            'rgba(139, 92, 246, 0.8)',   // D3 - Purple
                            'rgba(245, 158, 11, 0.8)',   // D2 - Amber
                            'rgba(236, 72, 153, 0.8)',   // D1 - Pink
                            'rgba(99, 102, 241, 0.8)',   // SMA - Indigo
                        ],
                        'borderColor' => [
                            'rgba(16, 185, 129, 1)',
                            'rgba(59, 130, 246, 1)',
                            'rgba(139, 92, 246, 1)',
                            'rgba(245, 158, 11, 1)',
                            'rgba(236, 72, 153, 1)',
                            'rgba(99, 102, 241, 1)',
                        ],
                        'borderWidth' => 2,
                    ],
                ],
                'labels' => $data->pluck('level')->toArray(),
            ];
        });
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) {
                            return "Jumlah: " + context.parsed.y + " peserta";
                        }',
                    ],
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                        'precision' => 0,
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Jumlah Peserta',
                    ],
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Jenjang Pendidikan',
                    ],
                ],
            ],
            'responsive' => true,
            // 'maintainAspectRatio' => true,
        ];
    }
}
