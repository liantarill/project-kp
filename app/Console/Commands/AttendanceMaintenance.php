<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AttendanceMaintenance extends Command
{
    // nama command yang dipanggil di artisan
    protected $signature = 'attendance:maintenance';

    protected $description = 'Menandai user alfa otomatis jika tidak absen & menghapus foto lama';

    public function handle()
    {
        Log::info('Command attendance:maintenance started.');

        try {
            DB::beginTransaction();
            
            $this->processAutoAlpha();
            $this->cleanOldPhotos();
            
            DB::commit();
            Log::info('Command attendance:maintenance finished successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in attendance:maintenance: ' . $e->getMessage());
            $this->error('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function processAutoAlpha()
    {
        $today = now()->toDateString();
        $batasJam = '10:00:00';

        // cek apakah sudah lewat jam batas
        if (now()->format('H:i:s') < $batasJam) {
            $msg = 'Belum jam 10:00 WIB, proses auto-alpha dilewati.';
            $this->info($msg);
            Log::info($msg);
            return;
        }

        // ambil user yang belum absen hari ini
        $users = User::where('role', 'participant')->whereDoesntHave('attendances', function ($q) use ($today) {
            $q->where('date', $today);
        })->get();

        $count = 0;
        foreach ($users as $user) {
            Attendance::create([
                'user_id' => $user->id,
                'date' => $today,
                'status' => 'absent',
            ]);
            $count++;
        }

        if ($count > 0) {
            $this->info("$count user diberi status ALFA.");
            Log::info("$count user records created with status absent.");
        } else {
            $this->info("Tidak ada user yang perlu ditandai ALFA.");
        }
    }

    private function cleanOldPhotos() 
    {
        // Hapus foto yang lebih lama dari 30 hari
        $cutoffDate = now()->subDays(30);
        
        $attendances = Attendance::whereNotNull('photo')
            ->whereDate('date', '<', $cutoffDate)
            ->get();

        $deletedCount = 0;

        foreach ($attendances as $attendance) {
            if ($attendance->photo && Storage::disk('public')->exists($attendance->photo)) {
                Storage::disk('public')->delete($attendance->photo);
                $deletedCount++;
            }
            
            // Update record agar tidak menunjuk ke file yang sudah dihapus
            $attendance->update(['photo' => null]);
        }

        if ($deletedCount > 0) {
            $this->info("$deletedCount foto lama berhasil dihapus.");
            Log::info("$deletedCount old attendance photos deleted.");
        } else {
            $this->info("Tidak ada foto lama yang perlu dihapus.");
        }
    }
}
