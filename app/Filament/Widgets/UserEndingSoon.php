<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class UserEndingSoon extends TableWidget
{
    protected static ?string $heading = 'Peserta Akan Berakhir';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => User::query()
                ->where('status', 'active')
                ->whereBetween('end_date', [
                    now(),
                    now()->addWeeks(2),
                ])
                ->orderBy('end_date')
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Lengkap'),
                TextColumn::make('department.name')
                    ->label('Divisi'),
                TextColumn::make('end_date'),

                TextColumn::make('remaining_days')
                    ->label('Hari Tersisa')
                    ->getStateUsing(fn ($record) => (int) ceil(now()->diffInDays($record->end_date, false)))
                    ->suffix(' hari')
                    ->badge()
                    ->color(fn ($state) => $state <= 3 ? 'danger' : 'warning'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
