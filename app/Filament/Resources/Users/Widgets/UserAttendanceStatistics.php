<?php

namespace App\Filament\Resources\Users\Widgets;

use App\Models\Attendance;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;

class UserAttendanceStatistics extends StatsOverviewWidget
{
    public ?Model $record = null;

    public function getColumns(): int|array
    {
        return [
            'md' => 5,
            'default' => 2,
        ];
    }

    protected function getStats(): array
    {
        if (! $this->record) {
            return [];
        }

        $counts = Attendance::where('user_id', $this->record->id)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return [

            Stat::make('Hadir', $counts['present'] ?? 0)
                ->icon(Heroicon::CheckCircle)
                ->color('danger'),
            Stat::make('Sakit', $counts['sick'] ?? 0)
                ->icon(Heroicon::InformationCircle)
                ->color('info'),

            Stat::make('Izin', $counts['permission'] ?? 0)
                ->icon(Heroicon::DocumentText)
                ->color('warning'),

            Stat::make('Alfa', $counts['absent'] ?? 0)
                ->icon(Heroicon::XCircle)
                ->color('danger'),

            Stat::make('Terlambat', $counts['late'] ?? 0)
                ->icon(Heroicon::Clock)
                ->color('warning'),
        ];
    }
}
