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
                    ->label('Nama')
                    ->required(),

                Select::make('type')
                    ->label('Jenis Intansi')
                    ->options([
                        'SMA' => 'Sekolah Menengah Atas (SMA)',
                        'SMK' => 'Sekolah Menengah Kejuruan (SMK)',
                        'PERGURUAN_TINGGI' => 'Perguruan Tinggi',
                    ]),
            ]);
    }
}
