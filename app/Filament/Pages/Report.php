<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Livewire\InstitutionChart;
use Filament\Support\Icons\Heroicon;
use App\Livewire\EducationLevelChart;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use App\Livewire\AttendanceTrendChart;
use App\Livewire\TopInstitutionsTable;
use Filament\Forms\Contracts\HasForms;
use App\Livewire\AttendanceStatusChart;
use App\Filament\Widgets\StatsDashboard;
use Filament\Schemas\Components\Section;
use App\Livewire\AttendanceStatsOverview;
use App\Livewire\GenderDistributionChart;
use App\Livewire\InternshipDurationStats;
use App\Livewire\InternshipStatsOverview;
use Filament\Forms\Components\DatePicker;
use App\Livewire\ParticipantsByDepartmentChart;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Livewire\ParticipantsByInstitutionChart;

class Report extends Page implements HasForms
{
    use InteractsWithForms;

    // protected string $view = 'filament.pages.report';

    /** FILTER STATE */
    // public ?int $year = null;

    // public function mount(): void
    // {
    //     $this->year = now()->year;
    // }
    protected string $view = 'filament.pages.report';

    protected function getFooterWidgets(): array
    {
        return [
            // AttendanceStatusChart::class,
            InternshipStatsOverview::class,
            ParticipantsByInstitutionChart::class,
            ParticipantsByDepartmentChart::class,
            // GenderDistributionChart::class
            EducationLevelChart::class,
            AttendanceTrendChart::class,
            TopInstitutionsTable::class,
            AttendanceStatsOverview::class,
            InternshipDurationStats::class
        ];
    }


    // protected function getFormSchema(): array
    // {
    //     return [
    //         Select::make('year')
    //             ->label('Tahun')
    //             ->options(
    //                 collect(range(now()->year, now()->year - 5))
    //                     ->mapWithKeys(fn($y) => [$y => $y])
    //             )
    //             ->reactive(),
    //     ];
    // }
    // ✅ Filter properties
    public $period = 'all';
    public $year;
    public $month;
    public $start_date;
    public $end_date;
    public $status = 'all';
    public $institution_type = 'all';
    public $level = 'all';

    public function mount(): void
    {
        // Set default values
        $this->year = now()->year;
        $this->month = now()->month;
        $this->start_date = now()->startOfMonth()->format('Y-m-d');
        $this->end_date = now()->format('Y-m-d');

        $this->form->fill();
    }

    // ✅ FILAMENT V2 METHOD
    protected function getFormSchema(): array
    {
        return [
            Section::make('Filter Report')
                ->description('Pilih periode dan kriteria untuk melihat statistik')
                ->schema([
                    Grid::make(4)
                        ->schema([
                            Select::make('period')
                                ->label('Periode')
                                ->options([
                                    'all' => 'Semua Waktu',
                                    'year' => 'Per Tahun',
                                    'month' => 'Per Bulan',
                                    'custom' => 'Custom Range',
                                ])
                                ->default('all')
                                ->reactive()
                                ->afterStateUpdated(fn() => $this->updateFilters()),

                            Select::make('year')
                                ->label('Tahun')
                                ->options(function () {
                                    $currentYear = now()->year;
                                    $years = [];
                                    for ($i = $currentYear; $i >= $currentYear - 5; $i--) {
                                        $years[$i] = $i;
                                    }
                                    return $years;
                                })
                                ->default(now()->year)
                                ->visible(fn($get) => in_array($get('period'), ['year', 'month']))
                                ->reactive()
                                ->afterStateUpdated(fn() => $this->updateFilters()),

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
                                ->visible(fn($get) => $get('period') === 'month')
                                ->reactive()
                                ->afterStateUpdated(fn() => $this->updateFilters()),
                        ]),

                    Grid::make(2)
                        ->schema([
                            DatePicker::make('start_date')
                                ->label('Tanggal Mulai')
                                ->default(now()->startOfMonth())
                                ->reactive()
                                ->afterStateUpdated(fn() => $this->updateFilters()),

                            DatePicker::make('end_date')
                                ->label('Tanggal Akhir')
                                ->default(now())
                                ->reactive()
                                ->afterStateUpdated(fn() => $this->updateFilters()),
                        ])
                        ->visible(fn($get) => $get('period') === 'custom'),

                    Grid::make(3)
                        ->schema([
                            Select::make('status')
                                ->label('Status Peserta')
                                ->options([
                                    'all' => 'Semua Status',
                                    'pending' => 'Pending',
                                    'active' => 'Aktif',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan',
                                ])
                                ->default('all')
                                ->reactive()
                                ->afterStateUpdated(fn() => $this->updateFilters()),

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
                                ->afterStateUpdated(fn() => $this->updateFilters()),

                            Select::make('level')
                                ->label('Jenjang Pendidikan')
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
                                ->afterStateUpdated(fn() => $this->updateFilters()),
                        ]),
                ])
                ->collapsible()
                ->columns(1),
        ];
    }

    // Method untuk trigger update widgets
    public function updateFilters(): void
    {
        // Emit event ke semua widgets untuk refresh
        $this->dispatch('filterUpdated', $this->getFilters());
    }
    protected function getHeaderActions(): array
    {
        return [
            Action::make('resetFilter')
                ->label('Reset Filter')
                ->icon(Heroicon::ArrowPath)
                ->color('secondary')
                ->action(fn() => $this->resetFilters()),
        ];
    }
    public function resetFilters(): void
    {
        // reset semua field ke default
        $this->form->fill([
            'period' => 'all',
            'year' => now()->year,
            'month' => now()->month,
            'start_date' => now()->startOfMonth()->format('Y-m-d'),
            'end_date' => now()->format('Y-m-d'),
            'status' => 'all',
            'institution_type' => 'all',
            'level' => 'all',
        ]);

        // update semua widget
        $this->dispatch('filterUpdated', $this->getFilters());
    }


    // Method untuk get semua filters sebagai array
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
