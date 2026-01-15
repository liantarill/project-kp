<?php

namespace App\Livewire;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class GenderDistributionChart extends ChartWidget
{
    protected ?string $heading = 'Distribusi Jenis Kelamin';

    protected function getData(): array
    {
        $period = request('period', 'all');
        $month = request('month', now()->month);
        $year = request('year', now()->year);

        $query = User::where('role', 'participant');

        if ($period === 'month') {
            $query->whereMonth('start_date', $month)
                ->whereYear('start_date', $year);
        } elseif ($period === 'year') {
            $query->whereYear('start_date', $year);
        }

        // Assuming gender can be derived from name or add gender column
        // For now, this is placeholder logic
        $male = (clone $query)->where('gender', 'male')->count();
        $female = (clone $query)->where('gender', 'female')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jenis Kelamin',
                    'data' => [$male, $female],
                    'backgroundColor' => ['#3b82f6', '#ec4899'],
                ],
            ],
            'labels' => ['Laki-laki', 'Perempuan'],
        ];
    }
    protected function getType(): string
    {
        return 'pie';
    }
}
