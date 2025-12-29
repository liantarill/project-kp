<?php

namespace App\Filament\Exports;

use App\Models\Attendance;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Filament\Notifications\Notification;
use Illuminate\Support\Number;

class AttendanceExporter extends Exporter
{
    protected static ?string $model = Attendance::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('date'),
            ExportColumn::make('check_in'),
            ExportColumn::make('check_out'),
            // ExportColumn::make('user_id')
            //     ->enabledByDefault(false),
            ExportColumn::make('status'),
            ExportColumn::make('note'),
            ExportColumn::make('photo'),
            // ExportColumn::make('latitude'),
            // ExportColumn::make('longitude'),
            // ExportColumn::make('created_at')
            //     ->enabledByDefault(false),
            // ExportColumn::make('updated_at')
            //     ->enabledByDefault(false),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your attendance export has completed and '.Number::format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }

    // public function sendCompletedNotification(Export $export): void
    // {
    //     $recipient = $export->user;

    //     if (! $recipient) {
    //         return;
    //     }

    //     Notification::make()
    //         ->success()
    //         ->title('Export selesai')
    //         ->body(static::getCompletedNotificationBody($export))
    //         ->sendToDatabase($recipient); // ← Langsung ke database
    // }
}
