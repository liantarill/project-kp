<?php

namespace App\Filament\Resources\Users\Widgets;

use App\Filament\Pages\ActiveUsers;
use App\Filament\Pages\PendingUsers;
use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatusOverview extends StatsOverviewWidget
{
    public function getColumns(): int|array
    {
        return [
            'md' => 4,
            'default' => 2,
        ];
    }

    protected function getStats(): array
    {
        $counts = User::where('role', 'participant')
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            Stat::make('Menunggu Verifikasi', $counts['pending'] ?? 0)->color('primary')
                ->color('warning')
                ->url(PendingUsers::getUrl())
                ->description('Lihat semua')
                ->descriptionIcon(Heroicon::ArrowRightCircle, IconPosition::Before)
                ->icon(Heroicon::Clock),
            Stat::make('Aktif', $counts['active'] ?? 0)
                ->color('primary')
                ->url(ActiveUsers::getUrl())
                ->description('Lihat semua')
                ->descriptionIcon(Heroicon::ArrowRightCircle, IconPosition::Before)
                ->icon(Heroicon::CheckCircle),
            Stat::make('Lulus', $counts['completed'] ?? 0)
                ->color('success')
                // ->url(PendingUsers::getUrl())
                ->description('Lihat semua')
                ->descriptionIcon(Heroicon::ArrowRightCircle, IconPosition::Before)
                ->icon(Heroicon::Clipboard),
            Stat::make('Batal', $counts['cancelled'] ?? 0)
                ->color('danger')
                // ->url(PendingUsers::getUrl())
                ->description('Lihat semua')
                ->descriptionIcon(Heroicon::ArrowRightCircle, IconPosition::Before)
                ->icon(Heroicon::XCircle),
        ];
    }
}
