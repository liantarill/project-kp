<?php

namespace App\Livewire;

use Filament\Tables\Table;
use App\Models\Institution;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\DB;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class TopInstitutionsTable extends TableWidget
{
    protected static ?string $heading = 'Top 10 Institusi dengan Peserta Terbanyak';
    protected static ?int $sort = 7;
    protected int | string | array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        $period = request('period', 'all');
        $month = request('month', now()->month);
        $year = request('year', now()->year);

        return $table
            ->query(
                Institution::query()
                    ->selectRaw('
                institutions.id,
                institutions.name,
                institutions.type,
                COUNT(users.id) as total_participants,
                SUM(CASE WHEN users.status = \'active\' THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN users.status = \'completed\' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN users.status = \'pending\' THEN 1 ELSE 0 END) as pending
            ')
                    ->leftJoin('users', function ($join) {
                        $join->on('institutions.id', '=', 'users.institution_id')
                            ->where('users.role', 'participant');
                    })
                    ->groupBy(
                        'institutions.id',
                        'institutions.name',
                        'institutions.type'
                    )
                    ->orderByDesc('total_participants')
                    ->orderBy('institutions.id')
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Institusi')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'PERGURUAN_TINGGI' => 'success',
                        'SMK' => 'warning',
                        'SMA' => 'info',
                    }),

                TextColumn::make('total_participants')
                    ->label('Total Peserta')
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('active')
                    ->label('Aktif')
                    ->badge()
                    ->color('success')
                    ->alignCenter(),

                TextColumn::make('completed')
                    ->label('Selesai')
                    ->badge()
                    ->color('info')
                    ->alignCenter(),

                TextColumn::make('pending')
                    ->label('Pending')
                    ->badge()
                    ->color('warning')
                    ->alignCenter(),
            ]);
    }
}
