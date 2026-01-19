<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\ReportFilterService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InternshipDurationStats extends StatsOverviewWidget
{
    protected ?string $heading = 'Sebaran Durasi Peserta';

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

    protected function getStats(): array
    {
        $cacheKey = 'internship_duration_' . md5(json_encode($this->filters));

        return Cache::remember($cacheKey, now()->addMinutes(5), function () {
            $driver = DB::getDriverName();

            // Expression untuk menghitung durasi (days)
            $durationExpr = match ($driver) {
                'pgsql' => 'end_date - start_date',
                'mysql', 'mariadb' => 'DATEDIFF(end_date, start_date)',
                default => 'end_date - start_date',
            };

            // Query untuk completed participants
            $completedQuery = User::where('role', 'participant')
                ->where('status', 'completed')
                ->whereNotNull('start_date')
                ->whereNotNull('end_date');

            // Apply filters
            $completedQuery = $this->applyFilters($completedQuery);

            $completed = $completedQuery
                ->selectRaw("
                    COUNT(*) as total,
                    AVG({$durationExpr}) as avg_duration,
                    MIN({$durationExpr}) as min_duration,
                    MAX({$durationExpr}) as max_duration
                ")
                ->first();

            // Query untuk active participants
            $ongoingQuery = User::where('role', 'participant')
                ->where('status', 'active')
                ->whereNotNull('start_date');

            $ongoingQuery = $this->applyFilters($ongoingQuery);
            $ongoing = $ongoingQuery->count();

            // Calculate statistics
            $avgDays = (float) ($completed->avg_duration ?? 0);
            $avgWeeks = round($avgDays / 7, 1);
            $avgMonths = round($avgDays / 30, 1);

            $minDays = (int) ($completed->min_duration ?? 0);
            $maxDays = (int) ($completed->max_duration ?? 0);

            $totalCompleted = $completed->total ?? 0;

            return [
                Stat::make('Durasi Rata-rata', $avgWeeks . ' minggu')
                    ->description('≈ ' . round($avgDays) . ' hari atau ' . $avgMonths . ' bulan')
                    ->descriptionIcon('heroicon-m-calendar')
                    ->color('info'),

                Stat::make('Sedang Berjalan', number_format($ongoing))
                    ->description('Magang aktif saat ini')
                    ->descriptionIcon('heroicon-m-arrow-trending-up')
                    ->color('success'),

                Stat::make('Telah Selesai', number_format($totalCompleted))
                    ->description('Total peserta yang sudah selesai')
                    ->descriptionIcon('heroicon-m-check-badge')
                    ->color('primary'),

                Stat::make('Range Durasi', $minDays . ' - ' . $maxDays . ' hari')
                    ->description('Durasi terpendek s/d terpanjang')
                    ->descriptionIcon('heroicon-m-arrows-right-left')
                    ->color('gray')
                    ->visible($totalCompleted > 0),
            ];
        });
    }

    /**
     * Apply filters to query
     */
    protected function applyFilters($query)
    {
        $filters = $this->filters;
        $period = $filters['period'] ?? 'all';

        // Apply date filter pada start_date
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

        // Apply institution type filter
        if (!empty($filters['institution_type']) && $filters['institution_type'] !== 'all') {
            $query->whereHas('institution', function ($q) use ($filters) {
                $q->where('type', $filters['institution_type']);
            });
        }

        // Apply level filter
        if (!empty($filters['level']) && $filters['level'] !== 'all') {
            $query->where('level', $filters['level']);
        }

        return $query;
    }

    protected function getColumns(): int
    {
        return 2; // 2 kolom layout
    }
}
