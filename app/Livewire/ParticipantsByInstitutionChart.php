<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\ReportFilterService;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ParticipantsByInstitutionChart extends ChartWidget
{
    protected ?string $heading = 'Statistik Asal Kampus/Sekolah';

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
        $cacheKey = 'participants_by_institution_' . md5(json_encode($this->filters));

        return Cache::remember($cacheKey, now()->addMinutes(5), function () {
            $data = User::select([
                'institutions.name',
                DB::raw('COUNT(*) as total')
            ])
                ->join('institutions', 'users.institution_id', '=', 'institutions.id')
                ->where('users.role', 'participant')
                ->when(true, fn($q) => ReportFilterService::applyParticipantFilters($q, $this->filters))
                ->groupBy('institutions.name')
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
        });
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
