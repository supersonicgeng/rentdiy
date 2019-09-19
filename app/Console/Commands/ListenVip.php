<?php

namespace App\Console\Commands;


use App\Models\AppVersion;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use App\Models\Customer;

class ListenVip extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:listenOrder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'redis空间失效监听';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Redis::psubscribe(["__keyevent@0__:expired"], function ($message, $channel) {

            $res = json_decode($message, true);

            //监听活动
            file_put_contents('msg.txt', [$res['name'], $res['id']]);


            switch ($res['name']) {

                case 'jhm_vip_die':
                    //Vip到期事件
                    $customer = Customer::find($res['id']);
                    //判断当前是否是vip角色
                    if ($customer->member_type == 2) {
                        if (strtotime($customer->member_validity) < time()) {
                            $customer->member_type = 1;
                            $customer->member_start = null;
                            $customer->member_validity = null;
                            $customer->save();

                            $customers = Customer::where('tree_id', $customer->tree_id)->where('tree', '>', $customer->tree)->get();

                            //找出当前用户下级到第一个超级Vip 和 vip出现
                            $sons = son($customers, $customer->id);

                            if (count($sons) > 0) {
                                //当前用户上面没有超级vip
                                if ($customer->parent_id != 0) {

                                    $parent = Customer::find($customer->parent_id);
                                    //普通用户
                                    if ($parent->member_type == 1) {
                                        $last_super = $parent->last_super;
                                    }
                                    //vip用户
                                    if ($parent->member_type == 2) {
                                        $last_super = 0;
                                        $customer->last_super = 0;
                                    }
                                    //超级vip
                                    if ($parent->member_type == 3) {
                                        $last_super = $parent->id;
                                        $customer->last_super = $parent->id;
                                    }

                                } else {
                                    $last_super = 0;

                                }

                                Customer::whereIn('id', $sons)->update(['last_super' => $last_super]);//去除vip
                            }
                        }
                    }


                    break;

                case 'jhm_Svip_die':
                    //超级vip到期事件
                    $customer = Customer::find($res['id']);
                    if ($customer->member_type == 3 and strtotime($customer->member_validity) < time()) {
                        $customer->member_start = null;
                        $customer->member_validity = null;
                        $customer->member_type = 1;
                        //如果之前有vip冻结时间,还原vip身份
                        if ($customer->vip_remain > 0) {
                            $customer->member_start = date('Y-m-d H:i:s', time());
                            $customer->member_validity = date('Y-m-d H:i:s', time() + $customer->vip_remain);
                            $customer->member_type = 2;
                            $customer->vip_remain = 0;
                        }

                        $customer->save();

                        $customers = Customer::where('tree_id', $customer->tree_id)->where('tree', '>', $customer->tree)->get();

                        //找出当前用户下级到第一个超级Vip 和 vip出现
                        $sons = son($customers, $customer->id);

                        if (count($sons) > 0) {

                            //当前用户上面没有超级vip
                            if ($customer->parent_id != 0) {

                                $parent = Customer::find($customer->parent_id);
                                //普通用户
                                if ($parent->member_type == 1) {
                                    $last_super = $parent->last_super;
                                }
                                //vip用户
                                if ($parent->member_type == 2) {
                                    $last_super = 0;
                                    $customer->last_super = 0;
                                }
                                //超级vip
                                if ($parent->member_type == 3) {
                                    $last_super = $parent->id;
                                    $customer->last_super = $parent->id;
                                }

                            } else {
                                $last_super = 0;

                            }

                            $customer->save();

                            Customer::whereIn('id', $sons)->update(['last_super' => $last_super]);//去除vip
                        }

                    }

                    break;

                case 'jhm_version_die':
                    //维护到期事件
                    $app = AppVersion::find($res['id']);
                    if ($app->is_maintain == 1) {
                        if (strtotime($app->main_end) < time()) {
                            $app->is_maintain = 0;
                            $app->save();
                        }
                    }
                    break;
            }

        });
    }

//    /***
//     * 创建系统通知
//     * @param $customer_id
//     * @param $content
//     */
//    public function notice($customer_id, $content)
//    {
//        Notice::create([
//            'receiver' => $customer_id,
//            'content' => $content,
//            'is_read' => 0,
//            'type' => 1,
//            'created_at' => time()
//        ]);
//    }
}
