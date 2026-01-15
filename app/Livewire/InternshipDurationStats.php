<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InternshipDurationStats extends StatsOverviewWidget
{
    protected ?string $heading = 'Sebaran Durasi Peserta';

    protected function getStats(): array
    {
        $driver = DB::getDriverName();

        // Expression beda untuk tiap DB
        $durationExpr = match ($driver) {
            'pgsql' => 'end_date - start_date',
            'mysql', 'mariadb' => 'DATEDIFF(end_date, start_date)',
            default => 'end_date - start_date',
        };

        $completed = User::where('role', 'participant')
            ->where('status', 'completed')
            ->whereNotNull('start_date')
            ->whereNotNull('end_date')
            ->select(DB::raw("AVG($durationExpr) as avg_duration"))
            ->first();

        $avgDays = (float) ($completed->avg_duration ?? 0);
        $avgWeeks = round($avgDays / 7, 1);

        $ongoing = User::where('role', 'participant')
            ->where('status', 'active')
            ->whereNotNull('start_date')
            ->count();

        return [
            Stat::make('Durasi Rata-rata', $avgWeeks . ' minggu')
                ->description('~' . round($avgDays) . ' hari')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),

            Stat::make('Sedang Berjalan', $ongoing)
                ->description('Magang aktif')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}
