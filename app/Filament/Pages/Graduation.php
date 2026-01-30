<?php

namespace App\Filament\Pages;

use App\Exports\AttendanceExcelExport;
use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;
use BackedEnum;
use UnitEnum;

class Graduation extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationLabel = 'Verifikasi Kelulusan';

    protected static ?string $title = 'Verifikasi Kelulusan Peserta';

    // protected static ?string $navigationIcon = Heroicon::AcademicCap;
    public function getBreadcrumbs(): array
    {
        return [
            UserResource::getUrl('index') => 'User',
            'Verifikasi Kelulusan',
        ];
    }


    protected static string | BackedEnum | null $navigationIcon = Heroicon::AcademicCap;

    protected static string | UnitEnum | null $navigationGroup = 'Manajemen Peserta';

    // protected static ?string $navigationGroup = 'Manajemen Peserta';

    protected string $view = 'filament.pages.graduation';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                    ->where('role', 'participant')
                    ->where('status', 'active')
                    ->whereDate('end_date', '<=', now())
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('institution.name')
                    ->label('Asal Instansi')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('start_date')
                    ->label('Mulai')
                    ->date('d F Y')
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label('Selesai')
                    ->date('d F Y')
                    ->sortable(),

                TextColumn::make('report_file')
                    ->label('Laporan')
                    ->badge()
                    ->state(fn($record) => $record->report_file ? 'Lihat Laporan' : 'Belum Ada')
                    ->color(fn($record) => $record->report_file ? 'success' : 'danger')
                    ->icon(fn($record) => $record->report_file ? Heroicon::OutlinedCheckCircle : Heroicon::OutlinedXCircle)
                    ->action(
                        Action::make('previewReport')
                            ->modalHeading('Laporan Akhir')
                            ->modalWidth('3xl')
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Tutup')
                            ->modalContent(fn($record) => view(
                                'filament.modals.report-viewer',
                                ['record' => $record]
                            ))
                            ->disabled(fn($record) => is_null($record->report_file))
                    ),

            ])
            ->actions([
                Action::make('export_rekap')
                    ->label('Export Rekap')
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->color('gray')
                    ->action(fn(User $record) => Excel::download(new AttendanceExcelExport($record->id), 'Laporan_Kehadiran_' . $record->name . '.xlsx')),

                Action::make('luluskan')
                    ->label('Luluskan')
                    ->icon(Heroicon::OutlinedAcademicCap)
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Kelulusan')
                    ->modalDescription('Apakah Anda yakin ingin meluluskan peserta ini? Peserta akan ditandai sebagai "Lulus".')
                    ->modalSubmitActionLabel('Ya, Luluskan')
                    ->modalCancelActionLabel('Batal')
                    ->action(function (User $record) {
                        $record->update(['status' => 'completed']);
                        Notification::make()
                            ->title('Peserta berhasil diluluskan')
                            ->success()
                            ->send()
                            ->sendToDatabase(auth()->user());
                    })
                    ->extraModalFooterActions(fn(User $record): array => [
                        Action::make('confirm_lulus_print')
                            ->label('Ya & Cetak')
                            ->color('success')
                            ->action(
                                function (Action $action, User $record) {
                                    $record->update(['status' => 'completed']);

                                    Notification::make()
                                        ->title('Peserta berhasil diluluskan')
                                        ->success()
                                        ->send()
                                        ->sendToDatabase(auth()->user());

                                    $action->close();

                                    return Excel::download(
                                        new AttendanceExcelExport($record->id),
                                        'Laporan_Kehadiran_' . $record->name . '.xlsx'
                                    );
                                }
                            ),
                    ]),
            ]);
    }
}
