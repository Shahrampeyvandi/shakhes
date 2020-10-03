<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Schedules\ApiScheduler;
use App\Http\Schedules\DailyReportScheduler;
use App\Http\Schedules\FastScheduler;
use App\Http\Schedules\InformationScheduler;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->call(new InformationScheduler)->dailyAt('20:56');
        //$schedule->call(new InformationScheduler)->everyMinute();


        $schedule->call(new DailyReportScheduler)->daily();
        //$schedule->call(new DailyReportScheduler)->everyMinute();
        //$schedule->call(new DailyReportScheduler)->dailyAt('11:55');

        $schedule->call(new ApiScheduler)->everyMinute();
        
        // $schedule->call(new FastScheduler)->everyMinute();




        // $schedule->command('inspire')
        //          ->hourly();
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
