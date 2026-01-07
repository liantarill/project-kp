<?php

namespace App\Filament\Resources\Departments;

use App\Filament\Resources\Departments\Pages\CreateDepartment;
use App\Filament\Resources\Departments\Pages\EditDepartment;
use App\Filament\Resources\Departments\Pages\ListDepartments;
use App\Filament\Resources\Departments\Schemas\DepartmentForm;
use App\Filament\Resources\Departments\Tables\DepartmentsTable;
use App\Models\Department;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    public static function getLabel(): string
    {
        return 'Divisi';
    }

    public static function getPluralLabel(): string
    {
        return 'Divisi';
    }

    public static function getNavigationLabel(): string
    {
        return 'Divisi';
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Squares2x2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return DepartmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        // return DepartmentsTable::configure($table);
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('quota')
                    ->label('Kuota'),
                TextColumn::make('users_count')
                    ->label('Jumlah Peserta')
                    ->counts('users')
                    ->badge()
                    ->size('md'),

                TextColumn::make('sisa_kuota')
                    ->label('Sisa Kuota')
                    ->getStateUsing(fn ($record) => $record->quota - $record->users_count)
                    ->badge()
                    ->size('md')
                    ->color(fn ($state) => $state <= 0 ? 'danger' : 'success'
                    ),
            ])
            ->recordActions([
                EditAction::make(),
                // ViewAction::make(),
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
            'index' => ListDepartments::route('/'),
            // 'create' => CreateDepartment::route('/create'),
            // 'edit' => EditDepartment::route('/{record}/edit'),
        ];
    }
}
