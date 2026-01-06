<?php

namespace App\Filament\Resources\Users\RelationManagers;

use App\Exports\AttendanceExcelExport;
use App\Filament\Exports\AttendanceExporter;
use App\Models\Attendance;
use Filament\Actions\Action;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class AttendancesRelationManager extends RelationManager
{
    protected static string $relationship = 'attendances';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                DatePicker::make('date')
                    ->required(),
                TimePicker::make('check_in')
                    ->required(),
                TimePicker::make('check_out'),
                TextInput::make('status')
                    ->required(),
                TextInput::make('note'),
                TextInput::make('photo'),
                TextInput::make('latitude')
                    ->numeric(),
                TextInput::make('longitude')
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table

            ->recordTitleAttribute('date')
            ->columns([
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d F Y')
                    ->sortable(),
                TextColumn::make('check_in')
                    ->label('Waktu Hadir')
                    ->time('H:i'),
                // TextColumn::make('check_out')
                //     ->label('Waktu Pulang')
                //     ->time(),
                TextColumn::make('status')
                    ->badge()
                    ->size('md')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'present' => 'Hadir',
                        'sick' => 'Sakit',
                        'late' => 'Terlambat',
                        'permission' => 'Izin',
                        'absent' => 'Alfa',
                    })
                    ->color(fn (string $state) => match ($state) {
                        'present' => 'success',
                        'sick' => 'info',
                        'permission' => 'gray',
                        'late' => 'warning',
                        'absent' => 'danger',
                        default => 'gray',
                    })
                    ->sortable()
                    ->searchable(),
                TextColumn::make('photo')
                    ->label('Foto')
                    ->size('md')
                    ->badge()
                    ->icon(Heroicon::OutlinedUser)
                    ->iconColor('info')
                    ->formatStateUsing(fn () => 'Lihat Foto')
                    ->url(fn ($record) => $record->photo
                            ? 'https://drive.google.com/file/d/'.$record->photo.'/view'
                            : null
                    )
                    ->openUrlInNewTab()
                    ->disabled(fn ($record) => is_null($record->photo)),

                // ImageColumn::make('photo')
                //     ->label('Foto Absensi')
                //     ->getStateUsing(function ($record) {
                //         return Storage::url($record->photo);
                //     }),

                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y H:i'),

                TextColumn::make('note')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('latitude')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('longitude')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                // ExportAction::make()
                //     ->exporter(AttendanceExporter::class)
                //     ->formats([
                //         ExportFormat::Xlsx,
                //     ]),

                Action::make('exportAttendance')
                    ->label('Export Attendance')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn () => Excel::download(
                        new AttendanceExcelExport($this->ownerRecord->id),
                        'Rekap Absensi-'.$this->ownerRecord->name.'.xlsx'
                    )),

                Action::make('lihatSemuaFoto')
                    ->label('Foto Absensi')
                    ->icon(Heroicon::Eye)
                    ->color('gray')
                    ->modalHeading(fn () => 'Foto Absensi: '.$this->ownerRecord->name)
                    ->modalDescription('Galeri foto absensi berdasarkan tanggal kehadiran')
                    ->modalAlignment(Alignment::Center)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalCancelAction(fn ($action) => $action->color('gray')->outlined())
                    ->modalContent(function () {
                        return view('filament.partials.attendance-photos', [
                            'user' => $this->ownerRecord,
                            'attendances' => Attendance::where('user_id', $this->ownerRecord->id)->get(),
                        ]);
                    }),

            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('detail')
                    ->label('Detail')
                    ->icon(Heroicon::Eye)
                    ->modalHeading(fn ($record) => 'Detail Kehadiran: '.$record->date->locale('id')->isoFormat('D MMMM Y'))
                    ->modalContent(fn ($record) => view(
                        'filament.partials.attendance-detail',
                        ['record' => $record]
                    ))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),
            ]);
        // ->headerActions([
        //     CreateAction::make(),
        //     AssociateAction::make(),
        // ])
        // ->recordActions([
        //     EditAction::make(),
        //     DissociateAction::make(),
        //     DeleteAction::make(),
        // ])
        // ->toolbarActions([
        //     BulkActionGroup::make([
        //         DissociateBulkAction::make(),
        //         DeleteBulkAction::make(),
        //     ]),
        // ]);
    }
}
