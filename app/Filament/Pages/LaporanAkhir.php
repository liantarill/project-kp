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
use Symfony\Component\Console\Color;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Concerns\InteractsWithTable;

class LaporanAkhir extends Page implements HasTable
{

    use InteractsWithTable;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::DocumentText;

    protected static string | UnitEnum | null $navigationGroup = 'Dokumen & Laporan';

    protected string $view = 'filament.pages.laporan-akhir';

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query()
                // ->where('status', 'active')
                ->where('role', 'participant')
                ->whereNotNull('report_file'))
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

                TextColumn::make('report_file')
                    ->label('Laporan')
                    ->state(fn() => 'Lihat Laporan')
                    ->badge('info')
                    ->size('md')
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
                    )
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'completed' => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'active' => 'Aktif',
                        'completed' => 'Lulus',
                    }),
            ])
            ->actions([
                // Action::make('graduate') removed to separate responsibilities
            ])
            ->bulkActions([
                // BulkAction::make('graduateSelected') removed
            ])

            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50]);
    }
}
