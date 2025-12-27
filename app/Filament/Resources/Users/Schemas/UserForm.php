<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->readOnly()
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->readOnly(),
                Select::make('institution_id')
                    ->label('Instansi')
                    ->relationship('institution', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('major')
                    ->label('Jurusan')
                    ->readOnly()
                    ->required(),
                Select::make('level')
                    ->label('Jenjang')
                    ->options([
                        'SMA' => 'SMA Sederajat',
                        'D1' => 'D1',
                        'D2' => 'D2',
                        'D3' => 'D3',
                        'D4' => 'D4',
                        'S1' => 'S1',
                    ])
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('department_id')
                    ->label('Divisi')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('acceptance_proof')
                    ->readOnly(),
                Select::make('status')
                    ->options([
                        'pending' => 'Menunggu',
                        'active' => 'Aktif',
                        'completed' => 'Lulus',
                        'cancelled' => 'Batal',
                    ])->searchable()
                    ->preload()
                    ->required()
                    ->default('pending'),
                DatePicker::make('start_date')
                    ->label('Tanggal Mulai'),
                DatePicker::make('end_date')
                    ->label('Tanggal Selesai'),
                DateTimePicker::make('email_verified_at')
                    ->disabled(),
            ]);
    }
}
