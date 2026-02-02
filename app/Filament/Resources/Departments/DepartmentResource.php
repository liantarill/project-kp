<?php

namespace App\Filament\Resources\Departments;

use App\Filament\Resources\Departments\Pages\CreateDepartment;
use App\Filament\Resources\Departments\Pages\EditDepartment;
use App\Filament\Resources\Departments\Pages\ListDepartments;
use App\Filament\Resources\Departments\Schemas\DepartmentForm;
use App\Filament\Resources\Departments\Tables\DepartmentsTable;
use App\Models\Department;
use BackedEnum;
use UnitEnum;
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
    protected static string|UnitEnum|null $navigationGroup = 'Data Master';

    public static function getLabel(): string
    {
        return 'Bagian';
    }

    public static function getPluralLabel(): string
    {
        return 'Bagian';
    }

    public static function getNavigationLabel(): string
    {
        return 'Bagian';
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Squares2x2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return DepartmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rank')
                    ->label('#')
                    ->rowIndex()
                    ->alignCenter()
                    ->weight('bold')
                    ->color('primary'),
                TextColumn::make('name'),
                TextColumn::make('quota')
                    ->label('Kuota'),
                TextColumn::make('users_count')
                    ->label('Jumlah Peserta')
                    ->counts([
                        'users' => fn($query) => $query->whereIn('status', ['active', 'pending'])
                    ])
                    ->badge()
                    ->size('md'),

                TextColumn::make('sisa_kuota')
                    ->label('Sisa Kuota')
                    ->getStateUsing(fn($record) => $record->quota - $record->users_count)
                    ->badge()
                    ->size('md')
                    ->color(
                        fn($state) => $state <= 0 ? 'danger' : 'success'
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
