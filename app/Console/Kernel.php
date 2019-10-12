<?php

namespace App\Console;

use App\Http\Controllers\Api\PhoneController;
use App\Models\CustomerInfo;
use App\Models\OrderProfit;
use App\Services\TaskService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\DB;

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
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();


        $schedule->call(function () {
            $task = new TaskService();
            $task->relet();
            $task->testTask();
            $task->checkInsurance();
            $task->increaseRate();
            $task->bondCheck();
            $task->bondLodged();
            $task->ticket();
            $task->arrearsNote();
            $task->landlordArrearsNote();
        })->daily();

        $schedule->call(function () {
            DB::table('tea')->insert(['id'=>1,'name'=>'test']);
        })->dailyAt('18:15');
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
