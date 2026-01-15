<?php

namespace App\Livewire;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class EducationLevelChart extends ChartWidget
{
    protected ?string $heading = 'Statistik Level Pendidikan';
    protected function getData(): array
    {
        $period = request('period', 'all');
        $month = request('month', now()->month);
        $year = request('year', now()->year);

        $query = User::where('role', 'participant')
            ->whereNotNull('level')
            ->select('level', DB::raw('count(*) as total'));

        if ($period === 'month') {
            $query->whereMonth('start_date', $month)
                ->whereYear('start_date', $year);
        } elseif ($period === 'year') {
            $query->whereYear('start_date', $year);
        }

        $data = $query->groupBy('level')
            ->orderBy('total', 'desc')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Peserta',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => '#10b981',
                ],
            ],
            'labels' => $data->pluck('level')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
