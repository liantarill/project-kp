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

        $totalActive = User::where('role', 'participant')
            ->where('status', 'active')
            ->count();

        $totalPending = User::where('role', 'participant')
            ->where('status', 'pending')
            ->count();

        $totalCompleted = User::where('role', 'participant')
            ->where('status', 'completed')
            ->count();

        $totalDepartments = Department::count();
        $fullDepartmentsCount = Department::whereHas('users', function ($q) {
            $q->where('role', 'participant')
                ->where('status', 'active');
        }, '>=', DB::raw('quota'))
            ->count();

        return [
            Stat::make('Peserta Aktif', $totalActive)
                ->color('success')
                ->url(ActiveUsers::getUrl())
                ->description('Lihat semua User Aktif')
                ->descriptionIcon(Heroicon::ArrowRightCircle, IconPosition::Before)
                ->icon(Heroicon::CheckCircle),

            Stat::make(' Menunggu Verifikasi', $totalPending)
                ->color('warning')
                ->icon(Heroicon::Clock)
                ->description('Perlu tindakan segera')
                ->descriptionIcon(Heroicon::ArrowRightCircle, IconPosition::Before)
                ->url(PendingUsers::getUrl()),

            Stat::make('Peserta Lulus', $totalCompleted)
                ->color('info')
                ->icon(Heroicon::AcademicCap)
                ->description('Total alumni magang'),

            Stat::make('Bagian Penuh', $fullDepartmentsCount . ' / ' . $totalDepartments)
                ->url(DepartmentResource::getUrl('index'))
                ->description('Lihat semua department')
                ->descriptionIcon(Heroicon::ArrowRightCircle, IconPosition::Before)
                ->icon(Heroicon::Squares2x2),
        ];
    }
}
