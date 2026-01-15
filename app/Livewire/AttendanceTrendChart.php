<?php

namespace App\Livewire;

use App\Models\Attendance;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AttendanceTrendChart extends ChartWidget
{
    protected ?string $heading = 'Trend Kehadiran per Bulan';

    protected function getData(): array
    {
        $year = request('year', now()->year);

        $data = Attendance::whereYear('date', $year)
            ->selectRaw('
    EXTRACT(MONTH FROM date) as month,
    SUM(CASE WHEN status = \'present\' THEN 1 ELSE 0 END) as hadir,
    SUM(CASE WHEN status = \'permission\' THEN 1 ELSE 0 END) as izin,
    SUM(CASE WHEN status = \'sick\' THEN 1 ELSE 0 END) as sakit,
    SUM(CASE WHEN status = \'absent\' THEN 1 ELSE 0 END) as alpha,
    SUM(CASE WHEN status = \'late\' THEN 1 ELSE 0 END) as terlambat
')
            ->whereRaw('EXTRACT(YEAR FROM date) = ?', [2026])
            ->groupByRaw('EXTRACT(MONTH FROM date)')
            ->orderBy('month')
            ->get();


        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        return [
            'datasets' => [
                [
                    'label' => 'Hadir',
                    'data' => $data->pluck('hadir')->toArray(),
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                ],
                [
                    'label' => 'Izin',
                    'data' => $data->pluck('izin')->toArray(),
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                ],
                [
                    'label' => 'Sakit',
                    'data' => $data->pluck('sakit')->toArray(),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ],
                [
                    'label' => 'Alpha',
                    'data' => $data->pluck('alpha')->toArray(),
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                ],
                [
                    'label' => 'Terlambat',
                    'data' => $data->pluck('terlambat')->toArray(),
                    'borderColor' => '#f97316',
                    'backgroundColor' => 'rgba(249, 115, 22, 0.1)',
                ],
            ],
            'labels' => array_slice($months, 0, $data->count()),
        ];
    }
    protected function getType(): string
    {
        return 'line';
    }
}
