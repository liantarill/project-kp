<?php

namespace App\Livewire;

use App\Models\Attendance;
use App\Services\ReportFilterService;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AttendanceTrendChart extends ChartWidget
{
    protected ?string $heading = 'Trend Kehadiran per Bulan';

    protected $listeners = ['filterUpdated' => 'updateFilters'];

    public array $filters = [];

    public function mount(): void
    {
        $this->filters = ReportFilterService::getDefaults();
        $this->updateHeading();
    }

    public function updateFilters(array $filters): void
    {
        $this->filters = ReportFilterService::sanitize($filters);
        $this->updateHeading();
    }

    protected function updateHeading(): void
    {
        $year = $this->filters['year'] ?? now()->year;
        $this->heading = 'Trend Kehadiran per Bulan - ' . $year;
    }

    protected function getData(): array
    {
        $cacheKey = 'attendance_trend_' . md5(json_encode($this->filters));

        return Cache::remember($cacheKey, now()->addMinutes(10), function () {
            $year = $this->filters['year'] ?? now()->year;

            // Gunakan raw query yang lebih efisien
            $driver = DB::getDriverName();

            if ($driver === 'pgsql') {
                $monthExpr = 'EXTRACT(MONTH FROM date)';
            } else {
                $monthExpr = 'MONTH(date)';
            }

            $data = Attendance::selectRaw("
                    {$monthExpr} as month,
                    SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as hadir,
                    SUM(CASE WHEN status = 'permission' THEN 1 ELSE 0 END) as izin,
                    SUM(CASE WHEN status = 'sick' THEN 1 ELSE 0 END) as sakit,
                    SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as alpha,
                    SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as terlambat
                ")
                ->whereYear('date', $year)
                ->groupByRaw($monthExpr)
                ->orderBy('month')
                ->get()
                ->keyBy('month'); // Key by month untuk mudah mapping

            // Prepare data untuk semua 12 bulan
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

            $hadirData = [];
            $izinData = [];
            $sakitData = [];
            $alphaData = [];
            $terlambatData = [];

            for ($i = 1; $i <= 12; $i++) {
                $monthData = $data->get($i);

                $hadirData[] = $monthData->hadir ?? 0;
                $izinData[] = $monthData->izin ?? 0;
                $sakitData[] = $monthData->sakit ?? 0;
                $alphaData[] = $monthData->alpha ?? 0;
                $terlambatData[] = $monthData->terlambat ?? 0;
            }

            return [
                'datasets' => [
                    [
                        'label' => 'Hadir',
                        'data' => $hadirData,
                        'borderColor' => '#10b981',
                        'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                        'tension' => 0.3,
                        'fill' => true,
                    ],
                    [
                        'label' => 'Izin',
                        'data' => $izinData,
                        'borderColor' => '#f59e0b',
                        'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                        'tension' => 0.3,
                        'fill' => true,
                    ],
                    [
                        'label' => 'Sakit',
                        'data' => $sakitData,
                        'borderColor' => '#3b82f6',
                        'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                        'tension' => 0.3,
                        'fill' => true,
                    ],
                    [
                        'label' => 'Alpha',
                        'data' => $alphaData,
                        'borderColor' => '#ef4444',
                        'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                        'tension' => 0.3,
                        'fill' => true,
                    ],
                    [
                        'label' => 'Terlambat',
                        'data' => $terlambatData,
                        'borderColor' => '#f97316',
                        'backgroundColor' => 'rgba(249, 115, 22, 0.1)',
                        'tension' => 0.3,
                        'fill' => true,
                    ],
                ],
                'labels' => $months,
            ];
        });
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    'labels' => [
                        'padding' => 15,
                        'usePointStyle' => true,
                    ],
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
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
                        'text' => 'Jumlah Kehadiran',
                    ],
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Bulan',
                    ],
                ],
            ],
            'interaction' => [
                'mode' => 'nearest',
                'axis' => 'x',
                'intersect' => false,
            ],
            'responsive' => true,
            // 'maintainAspectRatio' => true,
        ];
    }
}
