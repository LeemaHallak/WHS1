<?php

namespace App\Console;

use App\Models\OrderList;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\DeleteList;
use App\Console\Commands\MakeFinancialReport;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */

    protected $commands = [
        DeleteList::class,
        MakeFinancialReport::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('remove:list')
        ->everyMinute()
        ->runInBackground();
        $schedule->command('app:make-financial-report')->everyMinute();
        //->monthlyOn(19, '00:53');
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
