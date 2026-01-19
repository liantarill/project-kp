<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\ReportFilterService;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class InternshipStatsOverview extends StatsOverviewWidget
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
        // Cache key berdasarkan filter
        $cacheKey = 'internship_stats_' . md5(json_encode($this->filters));

        return Cache::remember($cacheKey, now()->addMinutes(5), function () {
            // Single query untuk semua statistik
            $stats = User::where('role', 'participant')
                ->when(true, fn($q) => ReportFilterService::applyParticipantFilters($q, $this->filters))
                ->selectRaw('
                    COUNT(*) as total,
                    SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as completed,
                    SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as pending
                ', ['active', 'completed', 'pending'])
                ->first();

            return [
                Stat::make('Total Peserta', $stats->total ?? 0)
                    ->description('Semua peserta magang')
                    ->descriptionIcon('heroicon-s-users')
                    ->color('primary'),

                Stat::make('Peserta Aktif', $stats->active ?? 0)
                    ->description('Sedang menjalani magang')
                    ->descriptionIcon('heroicon-s-check-circle')
                    ->color('success'),

                Stat::make('Peserta Selesai', $stats->completed ?? 0)
                    ->description('Telah menyelesaikan magang')
                    ->descriptionIcon('heroicon-s-academic-cap')
                    ->color('info'),

                Stat::make('Menunggu Persetujuan', $stats->pending ?? 0)
                    ->description('Status pending')
                    ->descriptionIcon('heroicon-s-clock')
                    ->color('warning'),
            ];
        });
    }
}
