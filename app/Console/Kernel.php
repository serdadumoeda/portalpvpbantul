<?php

namespace App\Console;

use App\Jobs\QueueHeartbeatJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Heartbeat untuk memastikan scheduler dan worker queue aktif
        $schedule->job(new QueueHeartbeatJob())->everyFiveMinutes();
        // Reminder course (tugas, sesi, grading, survey pasca kelas, rekaman)
        $schedule->command('course:reminders')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
}
