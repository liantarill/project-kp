<?php

namespace App\Filament\Pages;

use UnitEnum;
use BackedEnum;
use App\Models\User;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\SelectColumn;
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
                TextColumn::make('report_file')
                    ->label('Laporan')
                    ->state(fn() => 'Lihat Laporan')
                    ->badge('info')
                    ->size('md')


            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50]);
    }
}
