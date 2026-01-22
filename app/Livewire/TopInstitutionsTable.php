<?php

namespace App\Livewire;

use Filament\Tables\Table;
use App\Models\Institution;
use App\Services\ReportFilterService;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class TopInstitutionsTable extends TableWidget
{
    protected static ?string $heading = 'Top 10 Institusi dengan Peserta Terbanyak';
    protected static ?int $sort = 7;
    protected int | string | array $columnSpan = 'full';

    protected $listeners = ['filterUpdated' => 'updateFilters'];

    public array $filters = [];

    public function mount(): void
    {
        $this->filters = ReportFilterService::getDefaults();
    }

    public function updateFilters(array $filters): void
    {
        $this->filters = ReportFilterService::sanitize($filters);
        $this->dispatch('$refresh');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getOptimizedQuery())
            ->columns($this->getColumns())
            ->defaultSort('total_participants', 'desc')
            ->paginated(false)
            ->poll('30s')
            ->striped()
            ->emptyStateHeading('Tidak ada data institusi')
            ->emptyStateDescription('Belum ada institusi dengan peserta yang sesuai dengan filter.')
            ->emptyStateIcon('heroicon-o-building-office-2');
    }

    protected function getOptimizedQuery(): Builder
    {
        $filters = $this->filters;

        return Institution::query()
            ->select([
                'institutions.id',
                'institutions.name',
                'institutions.type',
                DB::raw('COUNT(DISTINCT users.id) as total_participants'),
                DB::raw("SUM(CASE WHEN users.status = 'active' THEN 1 ELSE 0 END) as active"),
                DB::raw("SUM(CASE WHEN users.status = 'completed' THEN 1 ELSE 0 END) as completed"),
                DB::raw("SUM(CASE WHEN users.status = 'pending' THEN 1 ELSE 0 END) as pending"),
                DB::raw("SUM(CASE WHEN users.status = 'cancelled' THEN 1 ELSE 0 END) as cancelled"),
            ])
            ->leftJoin('users', function ($join) use ($filters) {
                $join->on('institutions.id', '=', 'users.institution_id')
                    ->where('users.role', 'participant');

                $this->applyJoinFilters($join, $filters);
            })
            ->when($filters['institution_type'] !== 'all', function (Builder $q) use ($filters) {
                return $q->where('institutions.type', $filters['institution_type']);
            })
            ->groupBy('institutions.id', 'institutions.name', 'institutions.type')
            ->havingRaw('COUNT(DISTINCT users.id) > 0')
            ->orderByRaw('COUNT(DISTINCT users.id) DESC')
            ->orderBy('institutions.name')
            ->limit(10);
    }

    /**
     * Apply filters to join clause
     */
    protected function applyJoinFilters($join, array $filters): void
    {
        $period = $filters['period'] ?? 'all';

        // Date filter
        switch ($period) {
            case 'month':
                if (!empty($filters['month']) && !empty($filters['year'])) {
                    $join->whereMonth('users.created_at', $filters['month'])
                        ->whereYear('users.created_at', $filters['year']);
                }
                break;

            case 'year':
                if (!empty($filters['year'])) {
                    $join->whereYear('users.created_at', $filters['year']);
                }
                break;

            case 'custom':
                if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
                    $join->whereBetween('users.created_at', [
                        $filters['start_date'] . ' 00:00:00',
                        $filters['end_date'] . ' 23:59:59'
                    ]);
                }
                break;
        }

        // Status filter
        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $join->where('users.status', $filters['status']);
        }

        // Level filter
        if (!empty($filters['level']) && $filters['level'] !== 'all') {
            $join->where('users.level', $filters['level']);
        }
    }

    protected function getColumns(): array
    {
        return [
            TextColumn::make('rank')
                ->label('#')
                ->rowIndex()
                ->alignCenter()
                ->weight('bold')
                ->color('primary'),

            TextColumn::make('name')
                ->label('Nama Institusi')
                ->searchable()
                ->sortable()
                ->wrap()
                ->weight('semibold')
                ->description(
                    fn($record) => $record->type
                        ? str_replace('_', ' ', ucwords(strtolower($record->type)))
                        : null
                ),

            // TextColumn::make('type')
            //     ->label('Tipe')
            //     ->badge()
            //     ->color(fn(?string $state): string => match ($state) {
            //         'PERGURUAN_TINGGI' => 'success',
            //         'SMK' => 'warning',
            //         'SMA' => 'info',
            //         default => 'gray',
            //     })
            //     ->formatStateUsing(fn(?string $state): string => match ($state) {
            //         'PERGURUAN_TINGGI' => 'Perguruan Tinggi',
            //         'SMK' => 'SMK',
            //         'SMA' => 'SMA',
            //         default => '-',
            //     })
            //     ->alignCenter(),

            TextColumn::make('total_participants')
                ->label('Total')
                ->numeric()
                ->sortable()
                ->alignCenter()
                ->weight('bold')
                ->color('primary')
                ->default(0),

            TextColumn::make('active')
                ->label('Aktif')
                ->numeric()
                ->badge()
                ->color('success')
                ->alignCenter()
                ->default(0),

            TextColumn::make('completed')
                ->label('Selesai')
                ->numeric()
                ->badge()
                ->color('info')
                ->alignCenter()
                ->default(0),

            TextColumn::make('pending')
                ->label('Pending')
                ->numeric()
                ->badge()
                ->color('warning')
                ->alignCenter()
                ->default(0),

            TextColumn::make('cancelled')
                ->label('Dibatalkan')
                ->numeric()
                ->badge()
                ->color('danger')
                ->alignCenter()
                ->default(0)
                ->toggleable()
                ->toggledHiddenByDefault(),
        ];
    }
}
