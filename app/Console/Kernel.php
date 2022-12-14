<?php

namespace App\Console;

use App\Console\Commands\deletefollowleadafterday;
use App\Models\followLead;
use App\Models\Order;
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

    protected $commands = [
        deletefollowleadafterday::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('follow lead:create')->everyMinute();
        $schedule->call(function () {
            $posts = followLead::where('created_at', '<', now()->subDays(2)->toDateTimeString())->get();
            $posts->each->forceDelete();
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
