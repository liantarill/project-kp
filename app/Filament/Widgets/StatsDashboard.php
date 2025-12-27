<?php

namespace App\Filament\Widgets;

use App\Models\Department;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsDashboard extends StatsOverviewWidget
{
    protected function getStats(): array
    {

        $total = User::activeParticipant();

        $totalDepartments = Department::count();
        $fullDepartmentsCount = Department::whereHas('users', function ($q) {
            $q->where('role', 'participant')
                ->where('status', 'active');
        }, '>=', DB::raw('quota'))
            ->count();

        return [
            Stat::make('Peserta Aktif', $total),
            Stat::make('Bounce rate', '21%'),
            Stat::make('Divisi Penuh', $fullDepartmentsCount.' / '.$totalDepartments),
        ];
    }
}
