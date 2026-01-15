<?php

namespace App\Filament\Resources\Users\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Users\UserResource;
use App\Filament\Resources\Users\Widgets\UserAttendanceStatistics;
use App\Filament\Resources\Users\RelationManagers\AttendancesRelationManager;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            UserAttendanceStatistics::class,
        ];
    }
    protected function getRelations(): array
    {
        return [
            AttendancesRelationManager::class,
        ];
    }
}
