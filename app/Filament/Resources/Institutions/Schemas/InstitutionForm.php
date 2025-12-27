<?php

namespace App\Filament\Resources\Institutions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InstitutionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),

                Select::make('type')
                    ->options([
                        'SMA' => 'Sekolah Menengah Atas (SMA)',
                        'SMK' => 'Sekolah Menengah Kejuruan (SMK)',
                        'PERGURUAN_TINGGI' => 'Perguruan Tinggi',
                    ]),
            ]);
    }
}
