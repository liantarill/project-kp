<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\ViewUser;
use App\Filament\Resources\Users\RelationManagers\AttendancesRelationManager;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Filament\Resources\Users\Widgets\UserAttendanceStatistics;
use App\Models\User;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Peserta';

    public static function getLabel(): string
    {
        return 'Peserta';
    }

    public static function getPluralLabel(): string
    {
        return 'Peserta';
    }

    public static function getNavigationLabel(): string
    {
        return 'Peserta';
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Users;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        // return UsersTable::configure($table);
        return $table
            ->filters([
                SelectFilter::make('institution_id')
                    ->label('Asal Instansi')
                    ->relationship('institution', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu',
                        'active' => 'Aktif',
                        'completed' => 'Lulus',
                        'cancelled' => 'Dibatalkan',
                    ]),
            ])
            ->query(User::query()->where('role', 'participant'))
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->searchable(),
                // TextColumn::make('email'),
                // TextColumn::make('role')
                //     ->formatStateUsing(fn ($state) => match ($state) {
                //         'admin' => 'Admin',
                //         'participant' => 'Peserta',
                //         default => $state,
                //     }),
                TextColumn::make('department.name')
                    ->label('Bagian'),
                TextColumn::make('institution.name')
                    ->label('Asal Instansi'),
                TextColumn::make('status')
                    ->sortable()
                    ->badge()
                    ->size('md')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'pending' => 'Menunggu',
                        'active' => 'Aktif',
                        'completed' => 'Lulus',
                        'cancelled' => 'Dibatalkan',
                    })
                    ->icon(fn(string $state): Heroicon => match ($state) {
                        'pending' => Heroicon::OutlinedClock,
                        'active' => Heroicon::OutlinedCheckCircle,
                        'completed' => Heroicon::OutlinedAcademicCap,
                        'cancelled' => Heroicon::OutlinedXCircle,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'active' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    }),

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
            AttendancesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            // 'create' => CreateUser::route('/create'),
            // 'edit' => EditUser::route('/{record}/edit'),
            'view' => ViewUser::route('/{record}/view'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getWidgets(): array
    {
        return [
            UserAttendanceStatistics::class,
        ];
    }
}
