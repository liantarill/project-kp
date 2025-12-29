<?php

namespace App\Filament\Resources\Users\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttendancesRelationManager extends RelationManager
{
    protected static string $relationship = 'attendances';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')
                    ->required(),
                TimePicker::make('check_in')
                    ->required(),
                TimePicker::make('check_out'),
                TextInput::make('status')
                    ->required(),
                TextInput::make('note'),
                TextInput::make('photo'),
                TextInput::make('latitude')
                    ->numeric(),
                TextInput::make('longitude')
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('date')
            ->columns([
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                TextColumn::make('check_in')
                    ->label('Waktu Hadir')
                    ->time(),
                TextColumn::make('check_out')
                    ->label('Waktu Pulang')
                    ->time(),
                TextColumn::make('status')
                    ->badge()
                    ->size('md')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'present' => 'Hadir',
                        'sick' => 'Sakit',
                        'late' => 'Terlambat',
                        'permission' => 'Izin',
                        'absent' => 'Alfa',
                    })
                    ->color(fn (string $state) => match ($state) {
                        'present' => 'success',
                        'sick' => 'info',
                        'permission' => 'gray',
                        'late' => 'warning',
                        'absent' => 'danger',
                        default => 'gray',
                    })
                    ->sortable()
                    ->searchable(),
                TextColumn::make('photo')
                    ->url(fn ($record) => 'https://drive.google.com/'.$record->photo.'/view')
                    ->openUrlInNewTab(),
                TextColumn::make('note')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('latitude')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('longitude')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ]);
        // ->headerActions([
        //     CreateAction::make(),
        //     AssociateAction::make(),
        // ])
        // ->recordActions([
        //     EditAction::make(),
        //     DissociateAction::make(),
        //     DeleteAction::make(),
        // ])
        // ->toolbarActions([
        //     BulkActionGroup::make([
        //         DissociateBulkAction::make(),
        //         DeleteBulkAction::make(),
        //     ]),
        // ]);
    }
}
