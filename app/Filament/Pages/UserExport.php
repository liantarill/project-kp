<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables\Table;
use App\Services\ReportFilterService;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Schemas\Components\Section;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Action;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Support\Icons\Heroicon;
use App\Models\User;
use Filament\Tables\Actions\HeaderAction;
use BackedEnum;

class UserExport extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::ArrowDownTray;
    protected static ?string $navigationLabel = 'Export Peserta';

    protected static ?string $title = 'Export Data Peserta';
    protected static ?string $slug = 'export-peserta';

    protected string $view = 'filament.pages.user-export';

    // Filter properties
    public string $period = 'year'; // Default to year as requested
    public ?int $year = null;
    public ?int $month = null;
    public ?string $start_date = null;
    public ?string $end_date = null;
    public string $status = 'all';
    public string $institution_type = 'all';
    public string $level = 'all';

    public function mount(): void
    {
        $defaults = ReportFilterService::getDefaults();

        // Override default period to 'year' as requested
        $this->period = 'year';
        $this->year = $defaults['year'];
        $this->month = $defaults['month'];

        $this->form->fill([
            'period' => $this->period,
            'year' => $this->year,
            'month' => $this->month,
            'status' => $defaults['status'],
            'institution_type' => $defaults['institution_type'],
            'level' => $defaults['level'],
            'start_date' => $defaults['start_date'],
            'end_date' => $defaults['end_date'],
        ]);
    }

    protected function getFormSchema(): array
    {
        $currentYear = now()->year;
        $yearOptions = collect(range($currentYear, $currentYear - 5))
            ->mapWithKeys(fn($y) => [$y => $y])
            ->toArray();

        return [
            Section::make('Filter Data')
                ->schema([
                    Grid::make(4)->schema([
                        Select::make('period')
                            ->label('Periode')
                            ->options([
                                'all' => 'Semua Waktu',
                                'year' => 'Per Tahun',
                                'month' => 'Per Bulan',
                                'custom' => 'Range Tanggal',
                            ])
                            ->default('year')
                            ->selectablePlaceholder(false)
                            ->reactive()
                            ->afterStateUpdated(function ($state) {
                                $this->period = $state;
                                $this->resetTable(); // Refresh table when filter changes
                            }),

                        Select::make('year')
                            ->label('Tahun')
                            ->options($yearOptions)
                            ->default(now()->year)
                            ->selectablePlaceholder(false)
                            ->visible(fn($get) => in_array($get('period'), ['year', 'month']))
                            ->reactive()
                            ->afterStateUpdated(function ($state) {
                                $this->year = $state;
                                $this->resetTable();
                            }),

                        Select::make('month')
                            ->label('Bulan')
                            ->options([
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember',
                            ])
                            ->default(now()->month)
                            ->selectablePlaceholder(false)
                            ->visible(fn($get) => $get('period') === 'month')
                            ->reactive()
                            ->afterStateUpdated(function ($state) {
                                $this->month = $state;
                                $this->resetTable();
                            }),
                    ]),

                    Grid::make(2)
                        ->schema([
                            DatePicker::make('start_date')
                                ->label('Tanggal Mulai')
                                ->default(now()->startOfMonth())
                                ->reactive()
                                ->afterStateUpdated(function ($state) {
                                    $this->start_date = $state;
                                    $this->resetTable();
                                }),

                            DatePicker::make('end_date')
                                ->label('Tanggal Akhir')
                                ->default(now())
                                ->reactive()
                                ->afterStateUpdated(function ($state) {
                                    $this->end_date = $state;
                                    $this->resetTable();
                                }),
                        ])
                        ->visible(fn($get) => $get('period') === 'custom'),

                    Grid::make(3)->schema([
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'all' => 'Semua Status',
                                'pending' => 'Pending',
                                'active' => 'Aktif',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->default('all')
                            ->reactive()
                            ->afterStateUpdated(function ($state) {
                                $this->status = $state;
                                $this->resetTable();
                            }),

                        Select::make('institution_type')
                            ->label('Tipe Institusi')
                            ->options([
                                'all' => 'Semua Tipe',
                                'PERGURUAN_TINGGI' => 'Perguruan Tinggi',
                                'SMK' => 'SMK',
                                'SMA' => 'SMA',
                            ])
                            ->default('all')
                            ->reactive()
                            ->afterStateUpdated(function ($state) {
                                $this->institution_type = $state;
                                $this->resetTable();
                            }),

                        Select::make('level')
                            ->label('Jenjang')
                            ->options([
                                'all' => 'Semua Jenjang',
                                'SMA' => 'SMA',
                                'D1' => 'D1',
                                'D2' => 'D2',
                                'D3' => 'D3',
                                'D4' => 'D4',
                                'S1' => 'S1',
                            ])
                            ->default('all')
                            ->reactive()
                            ->afterStateUpdated(function ($state) {
                                $this->level = $state;
                                $this->resetTable();
                            }),
                    ]),
                ])
                ->collapsible(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                $query = User::query()
                    ->where('role', 'participant');

                return ReportFilterService::applyParticipantFilters($query, $this->getFilters());
            })
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('institution.name')
                    ->label('Institusi')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'completed' => 'info',
                        'cancelled' => 'danger',
                        'pending' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('start_date')
                    ->label('Mulai')
                    ->date(),
                TextColumn::make('end_date')
                    ->label('Selesai')
                    ->date(),
            ])
            ->headerActions([
                Action::make('export')
                    ->label('Export Excel')
                    ->icon(Heroicon::ArrowDownTray)
                    ->action(fn() => $this->export())
            ]);
    }

    public function export()
    {
        return Excel::download(new UsersExport($this->getFilters()), 'peserta-' . now()->timestamp . '.xlsx');
    }

    public function resetTable(): void
    {
        $this->resetPage();
    }

    public function getFilters(): array
    {
        return [
            'period' => $this->period,
            'year' => $this->year,
            'month' => $this->month,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'institution_type' => $this->institution_type,
            'level' => $this->level,
        ];
    }
}
