<?php

namespace App\Filament\Pages;

use UnitEnum;
use BackedEnum;
use App\Models\User;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Actions\Action;
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

    protected static string | UnitEnum | null $navigationGroup = 'Manajemen Peserta';
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

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'active' => 'success',
                        'completed' => 'gray',
                        'cancelled' => 'danger',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'active' => 'Aktif',
                        'completed' => 'Lulus',
                        'cancelled' => 'Dibatalkan',
                    }),
            ])
            ->actions([
                Action::make('view')
                    ->label('Lihat Detail')
                    ->icon(Heroicon::OutlinedEye)
                    ->url(fn(User $record): string => UserResource::getUrl('view', ['record' => $record])),

                // Action::make('access_report')
                //     ->label('Laporan Akhir')
                //     ->icon(Heroicon::OutlinedDocumentText)
                //     ->color('info')
                //     ->visible(fn(User $record): bool => !is_null($record->report_file))
                //     ->modalHeading(fn($record) => 'Laporan Akhir: ' . $record->name)
                //     ->modalContent(fn($record) => view('filament.modals.report-viewer', [
                //         'record' => $record
                //     ]))
                //     ->modalSubmitAction(false)
                //     ->modalCancelActionLabel('Tutup')
                //     ->modalWidth('7xl')
                //     ->slideOver(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50]);
    }
}
