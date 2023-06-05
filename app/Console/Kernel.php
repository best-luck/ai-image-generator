<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('subscriptions:renew-free')->everyMinute();
        $schedule->command('subscriptions:expiring-reminder')->everyMinute();
        $schedule->command('subscriptions:expired-reminder')->everyMinute();
        $schedule->command('subscriptions:expired-delete')->everyHour();
        $schedule->command('transactions:unpaid-delete')->everyHour();
        $schedule->command('images:delete-expired')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
