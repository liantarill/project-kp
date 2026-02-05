<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminResource\Pages\CreateAdmin;
use App\Filament\Resources\AdminResource\Pages\EditAdmin;
use App\Filament\Resources\AdminResource\Pages\ListAdmins;
use App\Models\User;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class AdminResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen User';

    public static function getLabel(): string
    {
        return 'Admin';
    }

    public static function getPluralLabel(): string
    {
        return 'Admin';
    }

    public static function getNavigationLabel(): string
    {
        return 'Admin';
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserCircle;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required(fn($context) => $context === 'create')
                    ->dehydrated(fn($state) => filled($state))
                    ->minLength(8)
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(User::query()->where('role', 'admin'))
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email_verified_at')
                    ->label('Email Terverifikasi')
                    ->dateTime('d F Y, H:i')
                    ->sortable()
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'warning')
                    ->formatStateUsing(fn($state) => $state ? 'Terverifikasi' : 'Belum Terverifikasi'),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d F Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAdmins::route('/'),
            'create' => CreateAdmin::route('/create'),
            'edit' => EditAdmin::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', 'admin');
    }
}
