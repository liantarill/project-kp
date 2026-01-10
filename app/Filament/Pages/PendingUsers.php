<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\SelectColumn;
use App\Filament\Resources\Users\UserResource;
use Filament\Tables\Concerns\InteractsWithTable;

class PendingUsers extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationLabel = 'User Menunggu Verifikasi';

    public function getTitle(): string
    {
        return 'User Menunggu Verifikasi';
    }

    protected string $view = 'filament.pages.pending-users';

    public function getBreadcrumbs(): array
    {
        return [
            UserResource::getUrl('index') => 'User',
            'User Menunggu Verifikasi',
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

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
                    ->label('Divisi')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('institution.name')
                    ->label('Asal Instansi')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('acceptance_proof')
                    ->label('Bukti Penerimaan')
                    ->state(fn() => 'Lihat Bukti') // teks yang ditampilkan
                    ->action(
                        Action::make('previewAcceptanceProof')
                            ->modalHeading('Bukti Penerimaan')
                            ->modalSubmitAction(false) // tidak ada tombol submit
                            ->modalCancelActionLabel('Tutup') // ganti label tombol cancel
                            ->modalWidth('md') // sm, md, lg, xl, full
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
