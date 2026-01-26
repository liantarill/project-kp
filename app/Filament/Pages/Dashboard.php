<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Facades\Filament;
use Filament\View\PanelsIconAlias;
use Filament\Support\Icons\Heroicon;
use App\Filament\Widgets\StatsDashboard;
use App\Filament\Widgets\UserEndingSoon;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\AttendanceTodayWidget;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard Absensi';

    protected static ?string $navigationLabel = 'Dashboard';

    // protected static ?string $navigationIcon = Heroicon::AcademicCap;

    public static function getNavigationIcon(): string | BackedEnum | Htmlable | null
    {
        return static::$navigationIcon
            ?? FilamentIcon::resolve(PanelsIconAlias::PAGES_DASHBOARD_NAVIGATION_ITEM)
            ?? (Filament::hasTopNavigation() ? Heroicon::Home : Heroicon::Home);
    }

    public function getWidgets(): array
    {
        return [
            StatsDashboard::class,
            AttendanceTodayWidget::class,
            UserEndingSoon::class
        ];
    }
}
