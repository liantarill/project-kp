<?php

namespace App\Filament\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Pages\Page;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

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
                    ->label('Instansi')
                    ->sortable()
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
