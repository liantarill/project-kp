<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Console\Command;

class AutoAlphaAttendance extends Command
{
    // nama command yang dipanggil di artisan
    protected $signature = 'attendance:auto-alpha';

    protected $description = 'Menandai user alfa otomatis jika tidak absen';

    public function handle()
    {
        $today = now()->toDateString();
        $batasJam = '10:00:00';

        // cek apakah sudah lewat jam batas
        if (now()->format('H:i:s') < $batasJam) {
            $this->info('Belum jam 10:00 WIB, command tidak dijalankan.');

            return;
        }

        // ambil user yang belum absen hari ini
        $users = User::where('role', 'participant')->whereDoesntHave('attendances', function ($q) use ($today) {
            $q->where('date', $today);
        })->get();

        foreach ($users as $user) {
            Attendance::create([
                'user_id' => $user->id,
                'date' => $today,
                'status' => 'absent',
            ]);
            $this->info("User {$user->name} diberi status ALFA.");
        }

        $this->info('Proses alfa otomatis selesai.');
    }
}
