<?php

namespace App\Filament\Resources\Users\Widgets;

use App\Models\Attendance;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;

class UserAttendanceStatistics extends StatsOverviewWidget
{
    public ?Model $record = null;

    protected function getStats(): array
    {
        return [
            Stat::make('Hadir',
                Attendance::where('user_id', $this->record->id)
                    ->where('status', 'present')
                    ->count()
            ),
            Stat::make('Sakit',
                Attendance::where('user_id', $this->record->id)
                    ->where('status', 'sick')
                    ->count()
            ),
            Stat::make('Izin',
                Attendance::where('user_id', $this->record->id)
                    ->where('status', 'permission')
                    ->count()
            ),
            Stat::make('Alfa',
                Attendance::where('user_id', $this->record->id)
                    ->where('status', 'alpha')
                    ->count()
            ),
        ];
    }
}
