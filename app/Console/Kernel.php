<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\AttendanceMaintenance::class,
    ];

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Auto absen ALFA setiap hari jam 10:01 WIB
        $schedule->command('attendance:maintenance')
            ->weekdays()
            ->dailyAt('10:01')
            ->withoutOverlapping()
            ->onOneServer()
            ->runInBackground();

        // $schedule->command('queue:work --stop-when-empty --max-jobs=50')
        //     ->everyMinute()
        //     ->withoutOverlapping();
    }
}
