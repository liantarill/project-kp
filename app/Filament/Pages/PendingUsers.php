<?php

namespace App\Filament\Pages;

use UnitEnum;
use BackedEnum;
use App\Models\User;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\SelectColumn;
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
                SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu',
                        'active' => 'Aktif',
                        'completed' => 'Lulus',
                        'cancelled' => 'Batal',
                    ])
                    ->sortable()
                    ->rules(['required'])
                    ->selectablePlaceholder(false),

            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50]);
    }
}
