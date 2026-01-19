<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\ReportFilterService;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ParticipantsByDepartmentChart extends ChartWidget
{
    protected ?string $heading = 'Sebaran Department/Bagian';



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
        $cacheKey = 'participants_by_department_' . md5(json_encode($this->filters));

        return Cache::remember($cacheKey, now()->addMinutes(10), function () {
            $data = User::select([
                'departments.name',
                DB::raw('COUNT(*) as total')
            ])
                ->join('departments', 'users.department_id', '=', 'departments.id')
                ->where('users.role', 'participant')
                ->when(true, function ($query) {
                    return $this->applyFilters($query);
                })
                ->groupBy('departments.name')
                ->orderBy('total', 'desc')
                ->get();

            // Ambil top 10 dan group sisanya ke "Lainnya" jika ada lebih dari 10
            $topDepartments = $data->take(10);
            $others = $data->skip(10);

            $labels = $topDepartments->pluck('name')->toArray();
            $values = $topDepartments->pluck('total')->toArray();

            // Tambahkan "Lainnya" jika ada
            if ($others->count() > 0) {
                $labels[] = 'Lainnya';
                $values[] = $others->sum('total');
            }

            return [
                'datasets' => [
                    [
                        'label' => 'Jumlah Peserta',
                        'data' => $values,
                        'backgroundColor' => [
                            'rgba(59, 130, 246, 0.8)',   // Blue
                            'rgba(139, 92, 246, 0.8)',   // Purple
                            'rgba(236, 72, 153, 0.8)',   // Pink
                            'rgba(245, 158, 11, 0.8)',   // Amber
                            'rgba(16, 185, 129, 0.8)',   // Green
                            'rgba(99, 102, 241, 0.8)',   // Indigo
                            'rgba(249, 115, 22, 0.8)',   // Orange
                            'rgba(6, 182, 212, 0.8)',    // Cyan
                            'rgba(132, 204, 22, 0.8)',   // Lime
                            'rgba(168, 85, 247, 0.8)',   // Violet
                            'rgba(100, 116, 139, 0.8)',  // Slate (untuk "Lainnya")
                        ],
                        'borderColor' => [
                            'rgba(59, 130, 246, 1)',
                            'rgba(139, 92, 246, 1)',
                            'rgba(236, 72, 153, 1)',
                            'rgba(245, 158, 11, 1)',
                            'rgba(16, 185, 129, 1)',
                            'rgba(99, 102, 241, 1)',
                            'rgba(249, 115, 22, 1)',
                            'rgba(6, 182, 212, 1)',
                            'rgba(132, 204, 22, 1)',
                            'rgba(168, 85, 247, 1)',
                            'rgba(100, 116, 139, 1)',
                        ],
                        'borderWidth' => 2,
                    ],
                ],
                'labels' => $labels,
            ];
        });
    }

    /**
     * Apply all filters to query
     */
    protected function applyFilters($query)
    {
        $filters = $this->filters;
        $period = $filters['period'] ?? 'all';

        // Apply date filter
        switch ($period) {
            case 'month':
                if (!empty($filters['month']) && !empty($filters['year'])) {
                    $query->whereMonth('users.start_date', $filters['month'])
                        ->whereYear('users.start_date', $filters['year']);
                }
                break;

            case 'year':
                if (!empty($filters['year'])) {
                    $query->whereYear('users.start_date', $filters['year']);
                }
                break;

            case 'custom':
                if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
                    $query->whereBetween('users.start_date', [
                        $filters['start_date'] . ' 00:00:00',
                        $filters['end_date'] . ' 23:59:59'
                    ]);
                }
                break;
        }

        // Apply status filter
        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('users.status', $filters['status']);
        }

        // Apply institution type filter
        if (!empty($filters['institution_type']) && $filters['institution_type'] !== 'all') {
            $query->whereHas('institution', function ($q) use ($filters) {
                $q->where('type', $filters['institution_type']);
            });
        }

        // Apply level filter
        if (!empty($filters['level']) && $filters['level'] !== 'all') {
            $query->where('users.level', $filters['level']);
        }

        return $query;
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    /**
     * Chart options untuk better UX
     */
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    'labels' => [
                        'padding' => 15,
                        'font' => [
                            'size' => 12,
                        ],
                        'usePointStyle' => true,
                    ],
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) {
                            let label = context.label || "";
                            let value = context.parsed || 0;
                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                            let percentage = ((value / total) * 100).toFixed(1);
                            return label + ": " + value + " (" + percentage + "%)";
                        }',
                    ],
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
            'cutout' => '60%', // Untuk donut chart yang lebih modern
        ];
    }
}
