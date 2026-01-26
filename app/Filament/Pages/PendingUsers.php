<?php

namespace App\Filament\Pages;

use UnitEnum;
use BackedEnum;
use App\Models\User;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\Users\UserResource;
use Filament\Tables\Concerns\InteractsWithTable;

class PendingUsers extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationLabel = 'Verifikasi Peserta';

    public function getTitle(): string
    {
        return 'Verifikasi Peserta';
    }

    protected string $view = 'filament.pages.pending-users';

    public function getBreadcrumbs(): array
    {
        return [
            UserResource::getUrl('index') => 'User',
            'Verifikasi Peserta',
        ];
    }

    // public static function shouldRegisterNavigation(): bool
    // {
    //     return false;
    // }

    protected static string | BackedEnum | null $navigationIcon = Heroicon::Clock;

    protected static string | UnitEnum | null $navigationGroup = 'Manajemen Pengguna';

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query()
                ->where('status', 'pending')
                ->where('role', 'participant'))
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('department.name')
                    ->label('Bagian')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('institution.name')
                    ->label('Asal Instansi')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('acceptance_proof')
                    ->label('Bukti Penerimaan')
                    ->state(fn() => 'Lihat Bukti')
                    ->badge('info')
                    ->size('md')
                    ->action(
                        Action::make('previewAcceptanceProof')
                            ->modalHeading('Bukti Penerimaan')
                            ->modalWidth('3xl')
                            ->modalSubmitAction(false) // tidak ada tombol submit
                            ->modalCancelActionLabel('Tutup') // ganti label tombol cancel
                            ->modalContent(fn($record) => view(
                                'filament.modals.acceptance-proof',
                                ['record' => $record]
                            ))
                    )
                    ->toggleable(),
                // TextColumn::make('status')
                //     ->label('Status')
                //     ->badge()
                //     ->color(fn(string $state): string => match ($state) {
                //         'pending' => 'warning',
                //         'active' => 'success',
                //         'completed' => 'gray',
                //         'cancelled' => 'danger',
                //     })
                //     ->formatStateUsing(fn(string $state): string => match ($state) {
                //         'pending' => 'Menunggu',
                //         'active' => 'Aktif',
                //         'completed' => 'Lulus',
                //         'cancelled' => 'Dibatalkan',
                //     }),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Terima')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('primary')
                    ->button()
                    ->action(function (User $record) {
                        $record->update(['status' => 'active']);
                        Notification::make()
                            ->title('Peserta Diterima')
                            ->body("{$record->name} berhasil diverifikasi dan status menjadi Aktif.")
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Terima Peserta')
                    ->modalDescription('Apakah Anda yakin ingin menerima peserta ini? Status akan berubah menjadi Aktif.'),

                Action::make('reject')
                    ->label('Tolak')
                    ->icon(Heroicon::OutlinedXCircle)
                    ->color('danger')
                    ->button()
                    ->action(function (User $record) {
                        $record->update(['status' => 'cancelled']);
                        Notification::make()
                            ->title('Peserta Ditolak')
                            ->body("{$record->name} telah ditolak.")
                            ->danger()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Peserta')
                    ->modalDescription('Apakah Anda yakin ingin menolak peserta ini? Status akan berubah menjadi Dibatalkan.'),
            ])
            ->bulkActions([
                BulkAction::make('approveSelected')
                    ->label('Terima Terpilih')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('success')
                    ->action(function (Collection $records) {
                        $records->each->update(['status' => 'active']);
                        Notification::make()
                            ->title('Berhasil')
                            ->body("{$records->count()} peserta berhasil diterima.")
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->deselectRecordsAfterCompletion(),

                BulkAction::make('rejectSelected')
                    ->label('Tolak Terpilih')
                    ->icon(Heroicon::OutlinedXCircle)
                    ->color('danger')
                    ->action(function (Collection $records) {
                        $records->each->update(['status' => 'cancelled']);
                        Notification::make()
                            ->title('Berhasil')
                            ->body("{$records->count()} peserta berhasil ditolak.")
                            ->danger()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->deselectRecordsAfterCompletion(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50]);
    }
}
