<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->disabled(),
                TextInput::make('username')
                    ->required()
                    ->disabled(),
                TextInput::make('role')
                    ->required()
                    ->default('participant'),
                TextInput::make('major_id')
                    ->numeric(),
                TextInput::make('department_id')
                    ->numeric(),
                TextInput::make('acceptance_proof'),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                DatePicker::make('start_date'),
                DatePicker::make('end_date'),
                DateTimePicker::make('email_verified_at'),

            ]);
    }
}
