<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\ActiveUsers;
use App\Filament\Pages\PendingUsers;
use App\Filament\Resources\Departments\DepartmentResource;
use App\Models\Department;
use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
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
            Stat::make('Peserta Aktif', $total)
                ->color('primary')
                ->url(ActiveUsers::getUrl())
                ->description('Lihat semua User Aktif')
                ->descriptionIcon(Heroicon::ArrowRight, IconPosition::Before)
                ->icon(Heroicon::CheckCircle),
            Stat::make('Peserta Menunggu Verifikasi', $total)
                ->icon(Heroicon::CheckCircle)
                ->url(PendingUsers::getUrl()),
            Stat::make('Divisi Penuh', $fullDepartmentsCount.' / '.$totalDepartments)
                ->url(DepartmentResource::getUrl('index'))
                ->description('Lihat semua department')
                ->icon(Heroicon::Squares2x2),
        ];
    }
}
