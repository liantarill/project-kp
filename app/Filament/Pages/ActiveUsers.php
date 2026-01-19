<?php

namespace App\Filament\Pages;

use UnitEnum;
use BackedEnum;
use App\Models\User;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\View\PanelsIconAlias;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\SelectColumn;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\Users\UserResource;
use Filament\Tables\Concerns\InteractsWithTable;

class ActiveUsers extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationLabel = 'Peserta Aktif';

    public function getTitle(): string
    {
        return 'Peserta Aktif';
    }

    protected string $view = 'filament.pages.active-users';

    public function getBreadcrumbs(): array
    {
        return [
            UserResource::getUrl('index') => 'User',
            'Peserta Aktif',
        ];
    }


    // public static function shouldRegisterNavigation(): bool
    // {
    //     return false;
    // }


    protected static string | BackedEnum | null $navigationIcon = Heroicon::CheckBadge;

    protected static string | UnitEnum | null $navigationGroup = 'Manajemen Pengguna';
    // }
    public function table(Table $table): Table
    {
        return $table
            ->query(User::query()->where('status', 'active')->where('role', 'participant'))
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
