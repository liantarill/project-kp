<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Facades\Filament;
use Filament\View\PanelsIconAlias;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\Action;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Contracts\Support\Htmlable;

// Report Imports
use App\Services\ReportFilterService;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use App\Livewire\EducationLevelChart;
use App\Livewire\AttendanceTrendChart;
use App\Livewire\TopInstitutionsTable;
use App\Livewire\AttendanceStatsOverview;
use App\Livewire\GenderDistributionChart;
use App\Livewire\InternshipDurationStats;
use App\Livewire\InternshipStatsOverview;
use App\Livewire\ParticipantsByDepartmentChart;
use App\Livewire\ParticipantsByInstitutionChart;

// Dashboard specific widget imports
use App\Filament\Widgets\StatsDashboard;
use App\Filament\Widgets\UserEndingSoon;
use App\Filament\Widgets\AttendanceTodayWidget;

class Dashboard extends BaseDashboard implements HasForms
{
    use InteractsWithForms;

    protected static ?string $title = 'Dashboard Absensi';
    protected static ?string $navigationLabel = 'Dashboard';
    
    // Setting view to report view to include the filter form
    protected  string $view = 'filament.pages.report';

    public static function getNavigationIcon(): string | BackedEnum | Htmlable | null
    {
        return static::$navigationIcon
            ?? FilamentIcon::resolve(PanelsIconAlias::PAGES_DASHBOARD_NAVIGATION_ITEM)
            ?? (Filament::hasTopNavigation() ? Heroicon::Home : Heroicon::Home);
    }

    // Filter Properties from Report.php
    public string $period = 'all';
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

        $this->period = $defaults['period'];
        $this->year = $defaults['year'];
        $this->month = $defaults['month'];
        $this->start_date = $defaults['start_date'];
        $this->end_date = $defaults['end_date'];
        $this->status = $defaults['status'];
        $this->institution_type = $defaults['institution_type'];
        $this->level = $defaults['level'];

        $this->form->fill();
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatsDashboard::class,
            AttendanceTodayWidget::class,
            UserEndingSoon::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            InternshipStatsOverview::class,
            ParticipantsByInstitutionChart::class,
            ParticipantsByDepartmentChart::class,
            GenderDistributionChart::class,
            EducationLevelChart::class,
            AttendanceTrendChart::class,
            TopInstitutionsTable::class,
            AttendanceStatsOverview::class,
            InternshipDurationStats::class
        ];
    }

    // Overriding getWidgets to avoid duplication if BaseDashboard behaves unexpectedly
    // usually empty if we use getHeader/FooterWidgets, but BaseDashboard might define checks.
    public function getWidgets(): array
    {
        return [];
    }

    protected function getFormSchema(): array
    {
        $currentYear = now()->year;
        $yearOptions = collect(range($currentYear, $currentYear - 5))
            ->mapWithKeys(fn($y) => [$y => $y])
            ->toArray();

        return [
            Section::make('Filter Report')
                ->description('Pilih periode dan kriteria untuk melihat statistik')
                ->schema([
                    Grid::make(4)->schema([
                        Select::make('period')
                            ->label('Periode')
                            ->options([
                                'all' => 'Semua Waktu',
                                'year' => 'Per Tahun',
                                'month' => 'Per Bulan',
                                'custom' => 'Custom Range',
                            ])
                            ->default('all')
                            ->selectablePlaceholder(false)
                            ->reactive()
                            ->afterStateUpdated(fn() => $this->updateFilters()),

                        Select::make('year')
                            ->label('Tahun')
                            ->options($yearOptions)
                            ->default(now()->year)
                            ->selectablePlaceholder(false)
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
                            ->selectablePlaceholder(false)
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

                    Grid::make(3)->schema([
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
                            ->selectablePlaceholder(false)
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
                            ->selectablePlaceholder(false)
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
                            ->selectablePlaceholder(false)
                            ->reactive()
                            ->afterStateUpdated(fn() => $this->updateFilters()),
                    ]),
                ])
                ->collapsible()
                ->columns(1),
        ];
    }

    public function updateFilters(): void
    {
        $filters = ReportFilterService::sanitize($this->getFilters());
        $this->dispatch('filterUpdated', filters: $filters);
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
        $defaults = ReportFilterService::getDefaults();
        $this->form->fill($defaults);
        $this->dispatch('filterUpdated', filters: $defaults);
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
