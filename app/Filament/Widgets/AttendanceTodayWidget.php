<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Support\Icons\Heroicon;

class AttendanceTodayWidget extends BaseWidget
{
    protected static ?int $sort = 2; // Display after StatsDashboard

    protected function getStats(): array
    {
        $today = now()->format('Y-m-d');

        $presentCount = Attendance::whereDate('date', $today)
            ->whereIn('status', ['present', 'late'])
            ->count();

        $lateCount = Attendance::whereDate('date', $today)
            ->where('status', 'late')
            ->count();

        $excusedCount = Attendance::whereDate('date', $today)
            ->whereIn('status', ['permission', 'sick'])
            ->count();
            
        $absentCount = Attendance::whereDate('date', $today)
            ->where('status', 'absent')
            ->count();

        return [
            Stat::make('Hadir Hari Ini', $presentCount)
                ->description('Termasuk terlambat')
                ->descriptionIcon(Heroicon::UserGroup)
                ->color('success')
                ->icon(Heroicon::CheckCircle),

            Stat::make('Terlambat', $lateCount)
                ->description('Datang terlambat hari ini')
                ->descriptionIcon(Heroicon::Clock)
                ->color('warning')
                ->icon(Heroicon::ExclamationCircle),

            Stat::make('Izin / Sakit', $excusedCount)
                ->description('Tidak masuk dengan keterangan')
                ->color('info')
                ->icon(Heroicon::DocumentText),
                
            Stat::make('Tanpa Keterangan', $absentCount)
                ->description('Alpha hari ini')
                ->color('danger')
                ->icon(Heroicon::XCircle),
        ];
    }
}
