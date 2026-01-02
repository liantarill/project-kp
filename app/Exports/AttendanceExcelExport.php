<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExcelExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping
{
    protected string $userId;

    protected User $user;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
        $this->user = User::findOrFail($userId);
    }

    public function query()
    {
        return Attendance::query()
            ->where('user_id', $this->userId);
    }

    public function map($attendance): array
    {
        $status = '';
        switch ($attendance->status) {
            case 'present':
                $status = 'Hadir';
                break;
            case 'permission':
                $status = 'Izin';
                break;
            case 'sick':
                $status = 'Sakit';
                break;
            case 'absent':
                $status = 'Alfa';
                break;
            case 'late':
                $status = 'Terlambat';
                break;
            default:
                break;
        }

        return [
            $attendance->date?->locale('id')->translatedFormat('d F Y'),
            $attendance->check_in->format('H:i'),
            $status,
            $attendance->photo ?? '-',
        ];
    }

    public function headings(): array
    {
        return [
            ['Nama', $this->user->name],
            ['Asal Instansi', $this->user->institution?->name ?? '-'],
            ['Jurusan', $this->user->level.' '.$this->user->major ?? '-'],
            ['Divisi', $this->user->department->name],
            [
                'Periode',
                $this->user->start_date->locale('id')->translatedFormat('d F Y').' s/d '.
                $this->user->end_date->locale('id')->translatedFormat('d F Y'),
            ],
            [], // baris kosong
            ['Tanggal', 'Jam Masuk', 'Status', 'Bukti Kehadiran'],
        ];
    }
}
