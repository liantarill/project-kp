<?php

namespace App\Livewire;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InternshipStatsOverview extends StatsOverviewWidget
{

    // ✅ Listener untuk menerima event dari page
    protected $listeners = ['filterUpdated' => 'updateFilters'];

    // ✅ Filter properties
    public $filters = [
        'period' => 'all',
        'year' => null,
        'month' => null,
        'start_date' => null,
        'end_date' => null,
        'status' => 'all',
        'institution_type' => 'all',
        'level' => 'all',
    ];


    public function mount(): void
    {
        // Set default year jika null
        if (!$this->filters['year']) {
            $this->filters['year'] = now()->year;
        }
        if (!$this->filters['month']) {
            $this->filters['month'] = now()->month;
        }
    }

    // Method untuk update filters dari event
    public function updateFilters($filters): void
    {
        $this->filters = $filters;
    }

    protected function getStats(): array
    {
        // $period = request('period', 'all'); // all, month, year
        // $month = request('month', now()->month);
        // $year = request('year', now()->year);
        // Extract filter values
        $period = $this->filters['period'];
        $month = $this->filters['month'];
        $year = $this->filters['year'];
        $status = $this->filters['status'];
        $institutionType = $this->filters['institution_type'];
        $level = $this->filters['level'];


        $query = User::where('role', 'participant');


        // Apply period filter
        if ($period === 'month') {
            $query->whereMonth('start_date', $month)
                ->whereYear('start_date', $year);
        } elseif ($period === 'year') {
            $query->whereYear('start_date', $year);
        } elseif ($period === 'custom') {
            $startDate = $this->filters['start_date'];
            $endDate = $this->filters['end_date'];
            if ($startDate && $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate]);
            }
        }

        // Apply status filter
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Apply institution type filter
        if ($institutionType !== 'all') {
            $query->whereHas('institution', function ($q) use ($institutionType) {
                $q->where('type', $institutionType);
            });
        }

        // Apply level filter
        if ($level !== 'all') {
            $query->where('level', $level);
        }

        // Get counts
        $totalParticipants = (clone $query)->count();
        $activeParticipants = (clone $query)->where('status', 'active')->count();
        $completedParticipants = (clone $query)->where('status', 'completed')->count();
        $pendingParticipants = (clone $query)->where('status', 'pending')->count();

        return [
            Stat::make('Total Peserta', $totalParticipants)
                ->description('Semua peserta magang')
                ->descriptionIcon('heroicon-s-users')
                ->color('primary'),

            Stat::make('Peserta Aktif', $activeParticipants)
                ->description('Sedang menjalani magang')
                ->descriptionIcon('heroicon-s-check-circle')
                ->color('success'),

            Stat::make('Peserta Selesai', $completedParticipants)
                ->description('Telah menyelesaikan magang')
                ->descriptionIcon('heroicon-s-academic-cap')
                ->color('info'),

            Stat::make('Menunggu Persetujuan', $pendingParticipants)
                ->description('Status pending')
                ->descriptionIcon('heroicon-s-clock')
                ->color('warning'),
        ];
    }
}
