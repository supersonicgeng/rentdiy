<?php

namespace App\Http\Services;

use App\Models\Config;
use App\Models\Customer;
use App\Models\CustomerInCome;
use App\Models\CustomerInfo;
use App\Models\Order;
use App\Models\OrderProfit;
use App\Models\OrderReport;
use App\Models\Shop\Good;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

Class OrderService1
{
    /**
     * @param $order_sn
     * 分佣结算
     */
    public static function change($order_sn, $pay_time)
    {
        //报表参数
        $v2 = 0;
        $v3 = 0;
        $v4 = 0;
        $v5 = 0;
        $v1_p = 0;
        $v2_p = 0;
        $v3_p = 0;
        $v4_p = 0;
        $v5_p = 0;

        $order = Order::where('trade_id', $order_sn)->first();

        $config = Config::orderBy('created_at', 'desc')->first();
        //防止重复结算
        if ($order->order_status == 1) {
            return 1;
        }

        //开通类型
        $type = substr($order_sn, 0, 1);

        //月度
        if ($type == 'A') {
            $days = 30;
        } elseif ($type == 'B') {
            $days = 30 * 3;
        } elseif ($type == 'C') {
            $days = 365;
        }


        DB::beginTransaction();

        try {

            $buyer = Customer::where('id', $order->adzone_id)->first();
            //建立购买开通记录
            OrderProfit::create([
                'order_id' => $order->id,
                'f_id' => $buyer->id,
                'f_prof' => 0,
                'level' => 1,
                'status' => 1,
                'order_price' => $order->price,
                'buyer_id' => $buyer->id
            ]);
            $v1 = $buyer->id;

            //当前用户不是vip 和 超级vip 以及有上级才有分佣
            if ($buyer->member_type < 2 and $buyer->parent_id != 0) {
                //直属上级
                $parent = Customer::find($buyer->parent_id);
                $scale = self::commission($config, $parent->member_type, 2);
                self::profit($buyer->parent_id, $order, 2, self::commission($config, $scale, 2), $buyer->id);
                $v2 = $buyer->parent_id;
                $v2_p = $order->price * $scale;
            }


            //创立订单报表
            OrderReport::create([
                'order_id' => $order->id,
                'v1' => $v1, //购买本人
                'v1_p' => $v1_p,
                'v2' => $v2, //直属上级
                'v2_p' => $v2_p,
                'v3' => $v3, // 直属上上级
                'v3_p' => $v3_p,
                'v4' => $v4, //第一次出现
                'v4_p' => $v4_p,
                'v5' => $v5,
                'v5_p' => $v5_p,
                'platform_p' => $order->price - $v1_p - $v2_p - $v3_p - $v4_p - $v5_p
            ]);

            //修改订单状态
            $order->order_status = 1;
            $order->pay_time = $pay_time;
            $order->save();


            //非超级vip状态下开通会员
            if ($buyer->member_type < 3) {

                $buyer->member_type = 2;

                if ($buyer->member_start == '') {
                    $buyer->member_start = Timeformat(time());
                }

                if (strtotime($buyer->member_validity) > time()) {
                    $buyer->member_validity = Timeformat(strtotime($buyer->member_validity) + $days * 24 * 3600);
                } else {
                    $buyer->member_validity = Timeformat(time() + $days * 24 * 3600);
                }

            } else {
                //超级Vip下开通会员
                $buyer->vip_remain = $buyer->vip_remain + $days * 24 * 3600;//累加会员时间

            }

            //修改会员状态 增加会员资格
            $buyer->save();

        } catch (\Exception $e) {
            DB::rollBack();

            return 0;
        }


        DB::commit();
        //升级会员 给会员到期事件
        if ($buyer->member_type = 2) {

            $end_time = strtotime($buyer->member_validity) - time();

            Redis::Setex(json_encode(['name' => 'jhm_vip_die', 'id' => $buyer->id]), $end_time + 2, '会员到期');

        }

        return 1;

    }

    /***
     * 分层结算
     */
    protected static function profit($f_id, $order, $level, $scale, $buyer_id)
    {
        //建立直属上级分佣明细表
        OrderProfit::create([
            'order_id' => $order->id,
            'f_id' => $f_id,
            'f_prof' => $order->price * $scale,
            'status' => 1,
            'level' => $level,
            'order_price' => $order->price,
            'buyer_id' => $buyer_id
        ]);

        //建立每日收益表和增加余额
        $customerInfo = CustomerInfo::where('customer_id', $f_id)->first();
        $customerInfo->balance = $customerInfo->balance + $order->price * $scale;//余额
        $customerInfo->allowance = $customerInfo->allowance + $order->price * $scale;//津贴
        $customerInfo->income = $customerInfo->income + $order->price * $scale;//累计收益
        $customerInfo->confirmed_income = $customerInfo->confirmed_income + $order->price * $scale;//已确认收益
        $customerInfo->order_nums = $customerInfo->order_nums + 1;//增加订单数
        $customerInfo->total_price = $customerInfo->total_price + $order->price;//增加订单总额
        $customerInfo->save();

        $res = CustomerInCome::where('customer_id', $f_id)->first();

        //新增每日佣金表
        if ($res) {
            $res->income = $res->income + $order->price * $scale;
            $res->save();
        } else {
            CustomerInCome::create([
                'customer_id' => $f_id,
                'income' => $order->price * $scale
            ]);
        }

    }

    /***
     * @param $config  佣金配置
     * @param $member_type  用户等级
     * @param $level     所处津贴级别
     */
    private static function commission($config, $member_type, $level)
    {
        //VIP佣金比例
        if ($member_type == 2) {

            if ($level == 2) {
                $scale = $config->vip_allowance_one;
            } else {
                $scale = $config->vip_allowance_two;
            }

        }

        //超级VIP佣金比例
        if ($member_type == 3) {
            //第一市场
            if ($level == 2) {
                $scale = $config->parther_allowance_one;
            } elseif ($level == 3) {
                //第二市场
                $scale = $config->parther_allowance_two;
            } elseif ($level == 4) {
                $scale = $config->parther_allowance_third;
            }
        }

        return $scale;
    }


}
