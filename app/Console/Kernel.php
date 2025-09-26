<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Akşam 5'te günlük fiş sıfırlama
        $schedule->command('receipts:daily-reset')
                ->dailyAt('17:00')
                ->timezone('Europe/Istanbul')
                ->appendOutputTo(storage_path('logs/daily-reset.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
} 