<?php

namespace App\Livewire;

use App\Models\Attendance;
use App\Services\ReportFilterService;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AttendanceStatsOverview extends StatsOverviewWidget
{
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
        $cacheKey = 'attendance_stats_' . md5(json_encode($this->filters));

        return Cache::remember($cacheKey, now()->addMinutes(5), function () {
            $filters = $this->filters;
            $period = $filters['period'] ?? 'all';

            // Build query dengan filter
            $query = Attendance::query();

            // Apply date filter
            switch ($period) {
                case 'month':
                    if (!empty($filters['month']) && !empty($filters['year'])) {
                        $query->whereMonth('date', $filters['month'])
                            ->whereYear('date', $filters['year']);
                    }
                    break;

                case 'year':
                    if (!empty($filters['year'])) {
                        $query->whereYear('date', $filters['year']);
                    }
                    break;

                case 'custom':
                    if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
                        $query->whereBetween('date', [
                            $filters['start_date'],
                            $filters['end_date']
                        ]);
                    }
                    break;
            }

            // Single query untuk semua stats
            $stats = $query->selectRaw("
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as hadir,
                    SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as terlambat,
                    SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as alpha,
                    SUM(CASE WHEN status = 'permission' THEN 1 ELSE 0 END) as izin,
                    SUM(CASE WHEN status = 'sick' THEN 1 ELSE 0 END) as sakit
                ")
                ->first();

            // Hitung persentase kehadiran
            $totalAttendance = $stats->total ?? 0;
            $presentCount = ($stats->hadir ?? 0) + ($stats->terlambat ?? 0); // Hadir + Terlambat dianggap hadir
            $attendanceRate = $totalAttendance > 0
                ? round(($presentCount / $totalAttendance) * 100, 1)
                : 0;

            return [
                Stat::make('Total Kehadiran', number_format($stats->total ?? 0))
                    ->description('Total record kehadiran')
                    ->descriptionIcon('heroicon-s-calendar')
                    ->color('primary'),

                Stat::make('Tingkat Kehadiran', $attendanceRate . '%')
                    ->description($presentCount . ' dari ' . $totalAttendance . ' kehadiran')
                    ->descriptionIcon('heroicon-s-chart-bar')
                    ->color($attendanceRate >= 80 ? 'success' : ($attendanceRate >= 60 ? 'warning' : 'danger')),

                Stat::make('Hadir Tepat Waktu', number_format($stats->hadir ?? 0))
                    ->description('Kehadiran tanpa terlambat')
                    ->descriptionIcon('heroicon-s-check-circle')
                    ->color('success'),

                Stat::make('Terlambat', number_format($stats->terlambat ?? 0))
                    ->description('Datang terlambat')
                    ->descriptionIcon('heroicon-s-clock')
                    ->color('warning'),

                Stat::make('Izin', number_format($stats->izin ?? 0))
                    ->description('Izin dengan keterangan')
                    ->descriptionIcon('heroicon-s-document-text')
                    ->color('info'),

                Stat::make('Sakit', number_format($stats->sakit ?? 0))
                    ->description('Sakit dengan surat')
                    ->descriptionIcon('heroicon-s-heart')
                    ->color('info'),

                Stat::make('Alpha', number_format($stats->alpha ?? 0))
                    ->description('Tidak hadir tanpa keterangan')
                    ->descriptionIcon('heroicon-s-x-circle')
                    ->color('danger'),
            ];
        });
    }

    /**
     * Get columns count untuk layout
     */
    protected function getColumns(): int
    {
        return 4; // 4 kolom untuk layout yang rapi
    }
}
