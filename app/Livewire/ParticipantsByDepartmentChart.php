<?php

namespace App\Livewire;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ParticipantsByDepartmentChart extends ChartWidget
{
    protected ?string $heading = 'Sebaran Department/Bagian';

    protected function getData(): array
    {
        $period = request('period', 'all');
        $month = request('month', now()->month);
        $year = request('year', now()->year);

        $query = User::join('departments', 'users.department_id', '=', 'departments.id')
            ->where('users.role', 'participant')
            ->select('departments.name', DB::raw('count(*) as total'));

        if ($period === 'month') {
            $query->whereMonth('users.start_date', $month)
                ->whereYear('users.start_date', $year);
        } elseif ($period === 'year') {
            $query->whereYear('users.start_date', $year);
        }

        $data = $query->groupBy('departments.name')
            ->orderBy('total', 'desc')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Peserta',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(139, 92, 246, 0.8)',
                        'rgba(236, 72, 153, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                    ],
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
