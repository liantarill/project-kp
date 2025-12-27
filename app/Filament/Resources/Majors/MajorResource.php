<?php

namespace App\Filament\Resources\Majors;

use App\Filament\Resources\Majors\Pages\CreateMajor;
use App\Filament\Resources\Majors\Pages\EditMajor;
use App\Filament\Resources\Majors\Pages\ListMajors;
use App\Filament\Resources\Majors\Schemas\MajorForm;
use App\Filament\Resources\Majors\Tables\MajorsTable;
use App\Models\Major;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MajorResource extends Resource
{
    protected static ?string $model = Major::class;

    public static function getLabel(): string
    {
        return 'Jurusan';
    }

    public static function getPluralLabel(): string
    {
        return 'Jurusan';
    }

    public static function getNavigationLabel(): string
    {
        return 'Jurusan';
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::AcademicCap;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return MajorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        // return MajorsTable::configure($table);
        return $table->columns([
            TextColumn::make('name')
                ->label('Nama Jurusan'),
            TextColumn::make('institution.name')
                ->label('Nama Instansi'),
            TextColumn::make('level')
                ->label('Jenjang'),
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
            'index' => ListMajors::route('/'),
            'create' => CreateMajor::route('/create'),
            'edit' => EditMajor::route('/{record}/edit'),
        ];
    }
}
