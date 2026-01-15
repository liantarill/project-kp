<?php

namespace App\Filament\Resources\Institutions;

use App\Filament\Resources\Institutions\Pages\CreateInstitution;
use App\Filament\Resources\Institutions\Pages\EditInstitution;
use App\Filament\Resources\Institutions\Pages\ListInstitutions;
use App\Filament\Resources\Institutions\Schemas\InstitutionForm;
use App\Filament\Resources\Institutions\Tables\InstitutionsTable;
use App\Models\Institution;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class InstitutionResource extends Resource
{
    protected static ?string $model = Institution::class;
    protected static string|UnitEnum|null $navigationGroup = 'Data Master';

    public static function getLabel(): string
    {
        return 'Instansi';
    }

    public static function getPluralLabel(): string
    {
        return 'Instansi';
    }

    public static function getNavigationLabel(): string
    {
        return 'Instansi';
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::AcademicCap;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return InstitutionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        // return InstitutionsTable::configure($table);
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Instansi')
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Jenis Instansi')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'SMA' => 'Sekolah Menengah Atas',
                        'SMK' => 'Sekolah Menengah Kejuruan',
                        'PERGURUAN_TINGGI' => 'Perguruan Tinggi'
                    }),
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
            'index' => ListInstitutions::route('/'),
            'create' => CreateInstitution::route('/create'),
            'edit' => EditInstitution::route('/{record}/edit'),
        ];
    }
}
