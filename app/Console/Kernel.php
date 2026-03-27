<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('members:mark-inactive')->dailyAt('00:00');
        $schedule->command('loans:advance-payment-dates')->dailyAt('00:05');
        $schedule->command('loans:auto-deduct-overdue')->dailyAt('06:00');
        $schedule->command('loans:monitor')->dailyAt('08:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
