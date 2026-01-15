<?php

namespace App\Livewire;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ParticipantsByInstitutionChart extends ChartWidget
{
    // protected ?string $heading = 'Participants By Institution Chart';

    protected  ?string $heading = 'Statistik Asal Kampus/Sekolah';

    protected function getData(): array
    {
        $period = request('period', 'all');
        $month = request('month', now()->month);
        $year = request('year', now()->year);

        $query = User::join('institutions', 'users.institution_id', '=', 'institutions.id')
            ->where('users.role', 'participant')
            ->select('institutions.name', DB::raw('count(*) as total'));

        if ($period === 'month') {
            $query->whereMonth('users.start_date', $month)
                ->whereYear('users.start_date', $year);
        } elseif ($period === 'year') {
            $query->whereYear('users.start_date', $year);
        }

        $data = $query->groupBy('institutions.name')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Peserta',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#3b82f6',
                        '#8b5cf6',
                        '#ec4899',
                        '#f59e0b',
                        '#10b981',
                        '#6366f1',
                        '#f97316',
                        '#06b6d4',
                        '#84cc16',
                        '#a855f7'
                    ],
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }


    protected function getType(): string
    {
        return 'bar';
    }
}
