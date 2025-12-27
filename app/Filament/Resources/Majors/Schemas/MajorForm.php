<?php

namespace App\Filament\Resources\Majors\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MajorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('institution_id')
                    ->required()
                    ->relationship('institution', 'name')
                    ->preload()
                    ->searchable(),
                Select::make('level')
                    ->label('Jenjang')
                    ->options([
                        'D3' => 'D3',
                        'D4' => 'D4',
                        'S1' => 'S1',
                    ])
                    ->searchable()
                    ->required(),
            ]);
    }
}
