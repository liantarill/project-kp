<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\ReportFilterService;
use Filament\Widgets\ChartWidget;

class GenderDistributionChart extends ChartWidget
{
    protected ?string $heading = 'Distribusi Jenis Kelamin';
    
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

    protected function getData(): array
    {
        $cacheKey = 'gender_distribution_' . md5(json_encode($this->filters));

        return \Illuminate\Support\Facades\Cache::remember($cacheKey, now()->addMinutes(5), function () {
            $data = User::where('role', 'participant')
                ->when(true, fn($q) => ReportFilterService::applyParticipantFilters($q, $this->filters))
                ->selectRaw('gender, count(*) as total')
                ->groupBy('gender')
                ->pluck('total', 'gender');

            // Format data for chart
            // Note: If gender is stored as 'male'/'female', we match them here.
            $male = $data['male'] ?? 0;
            $female = $data['female'] ?? 0;

            return [
                'datasets' => [
                    [
                        'label' => 'Jenis Kelamin',
                        'data' => [$male, $female],
                        'backgroundColor' => ['#3b82f6', '#ec4899'], // Blue, Pink
                    ],
                ],
                'labels' => ['Laki-laki', 'Perempuan'],
            ];
        });
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
