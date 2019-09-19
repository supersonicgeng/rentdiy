<?php

namespace App\Console;

use App\Http\Controllers\Api\PhoneController;
use App\Models\CustomerInfo;
use App\Models\OrderProfit;
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

        //真实分佣
        $schedule->call(function () {
            $a = new OrderController();
            $a->after();
        })->monthlyOn(22, '00:05');

        $schedule->call(function () {
            $a = new OrderController();
            $a->after();
        })->monthlyOn(22, '01:05');


        //定时拉取订单
        $schedule->call(function () {
            $phoneOrder = new PhoneController();
            $phoneOrder->getOrderList();
            $phoneOrder->updateOrder();
        })->everyMinute();

        //月初清空 当月新增可提现金额
        $schedule->call(function () {
            CustomerInfo::query()->update(['withdraw_month' => 1]);
        })->monthlyOn(1, '00:00');

        //更新当月新增可提现金额
        $schedule->call(function () {

            $month_start = Carbon::now()->subMonth()->firstOfMonth();
            $month_end = Carbon::now()->subMonth()->lastOfMonth();
            $customer_infos = CustomerInfo::all();
            //更新每个用户当月新增可提现金额
            foreach ($customer_infos as $customer_info) {
                $total = OrderProfit::where('f_id', $customer_info->customer_id)
                    ->where('status', 1)->where('updated_at', [$month_start, $month_end])->sum('f_prof');
                $customer_info->withdraw_month = $total;
                $customer_info->save();
            }


        })->monthlyOn(25, '00:10');
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
