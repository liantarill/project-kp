<?php

namespace App\Livewire;

use App\Models\Attendance;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AttendanceStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Sebaran Kehadiran Peserta';
    protected function getStats(): array
    {
        $period = request('period', 'month');
        $month = request('month', now()->month);
        $year = request('year', now()->year);

        $query = Attendance::query();

        if ($period === 'month') {
            $query->whereMonth('date', $month)
                ->whereYear('date', $year);
        } elseif ($period === 'year') {
            $query->whereYear('date', $year);
        }

        $present = (clone $query)->where('status', 'present')->count();
        $permission = (clone $query)->where('status', 'permission')->count();
        $sick = (clone $query)->where('status', 'sick')->count();
        $absent = (clone $query)->where('status', 'absent')->count();
        $late = (clone $query)->where('status', 'late')->count();

        $total = $present + $permission + $sick + $absent + $late;
        $attendanceRate = $total > 0 ? round(($present / $total) * 100, 1) : 0;

        return [
            Stat::make('Tingkat Kehadiran', $attendanceRate . '%')
                ->description('Persentase kehadiran')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make('Total Hadir', $present)
                ->description('Kehadiran tepat waktu')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Terlambat', $late)
                ->description('Datang terlambat')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Izin', $permission)
                ->description('Izin/sakit')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),

            Stat::make('Alpha', $absent)
                ->description('Tanpa keterangan')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }
}
