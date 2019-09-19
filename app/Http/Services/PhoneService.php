<?php

namespace App\Http\Services;


use App\Models\Customer;
use App\Models\CustomerInfo;
use App\Models\KaOrderProfit;
use App\Models\PhoneOrder;
use App\Models\PhoneOrderReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


Class PhoneService
{

    private $order_id;//订单总表id
    private $selfBuy_profit; //自购佣金基数
    private $customer_id_when_buy;//开通信用卡用户id
    private $tbProfit;
    private $first_rate;//全平台扣减比例

    //以下是报表所需参数
    private $v1 = 0;
    private $v2 = 0;
    private $v3 = 0;
    private $v4 = 0;

    private $v1_p = 0;
    private $v2_p = 0;
    private $v3_p = 0;
    private $v4_p = 0;


    /***
     * 进行预分佣
     */
    public function forecast($photoOrder)
    {

        //防止重复写入佣金
        $report = PhoneOrderReport::where('ka_order_id', $photoOrder->all_order_id)->first();

        if ($report) {
            //返回正常状态
            return;
        }

        $config = DB::table('sys_configs')->where('pri_key', 'shouji')->first();

        $user = Customer::find($photoOrder->uid);

        if (!$user) {
            return;
        }

        $this->tbProfit = $photoOrder->cmmamt;//佣金基数
        $this->first_rate = $config->del_ratio;
        $this->base_profit = round($this->tbProfit * $config->del_ratio, 2); //平台佣金基数
        $this->order_id = $photoOrder->all_order_id;//订单id
        $this->customer_id_when_buy = $photoOrder->uid;


        //如果购买者是未绑定用户 只有平台获得分佣
        if ($user->member_type == 4) {
            $this->createReport();
            return;

        }
        $this->selfBuy_profit = round($this->base_profit * $this->selfBuyLevel($user->member_type, $config), 2);//开通人佣金

        $this->creatProfit($this->customer_id_when_buy, $this->selfBuy_profit, 1, $this->selfBuyLevel($user->member_type, $config));

        //设置自购变量
        $this->v1 = $this->customer_id_when_buy;
        $v1_p = $this->selfBuy_profit;

        if ($v1_p >= 0.01) {
            $this->v1_p = $v1_p;
        }

        //购买用户有上级 自己本身不是超级vip和vip 才进行下一步分佣
        if ($user->parent_id != 0 and $user->member_type < 2) {
            //进行上级的分成
            $this->ceng($user->parent_id, $user->member_type, 2, $config);
        }

        $this->createReport();//创建分佣报表
    }


    /***
     * 获得自购等级佣金比例
     */
    private function selfBuyLevel($level, $config)
    {
        switch ($level) {
            case '1':
                return $config->buyer_self_ratio;
                break;
            case '2':
                return $config->vip_self_ratio;
                break;
            case '3':
                return $config->partner_self_ratio;
                break;
        }
    }


    /***
     * 获得直属佣金比例
     */
    private function OneBuyLevel($level, $config)
    {
        switch ($level) {

            case '1':
                return $config->buyer_one_ratio;

                break;

            case '2':
                return $config->vip_one_ratio;

                break;
            case '3':
                return $config->partner_one_ratio;

                break;
        }
    }

    /***
     * 获得直属下或下下级佣金比例
     */
    private function TwoBuyLevel($level, $config)
    {
        switch ($level) {

            case '2':
                return $config->vip_two_ratio;

                break;
            case '3':
                return $config->partner_two_ratio;

                break;
        }
    }

    /***
     * 创建订单数据报表
     */
    public function createReport()
    {
        PhoneOrderReport::create([
            'ka_order_id' => $this->order_id,
            'v1' => $this->v1,
            'v1_p' => $this->v1_p,
            'v2' => $this->v2,
            'v2_p' => $this->v2_p,
            'v3' => $this->v3,
            'v3_p' => $this->v3_p,
            'v4' => $this->v4,
            'v4_p' => $this->v4_p,
            'platform_p' => $this->tbProfit - $this->v1_p - $this->v2_p - $this->v3_p - $this->v4_p
        ]);
    }


    /***
     * 创建分佣订单
     */
    private function creatProfit($f_id, $f_prof, $level, $rate)
    {
        //只对大于0.01元 进行分佣


        KaOrderProfit::create([
            'order_id' => $this->order_id,
            'f_id' => $f_id,
            'f_prof' => round($f_prof, 2),
            'level' => $level,
            'status' => 0,
            'buyer_id' => $this->customer_id_when_buy,
            'rate' => $rate,
            'type' => 3  //手机回收
        ]);

        //个人明细表维护

        $customerInfo = CustomerInfo::where('customer_id', $f_id)->first();

//            $customerInfo->income = $customerInfo->income + $f_prof;//累计收益
//            $customerInfo->forecast = $customerInfo->forecast + $f_prof;//预估收益
        $customerInfo->order_nums = $customerInfo->order_nums + 1;//增加订单数
//            $customerInfo->total_price = $customerInfo->total_price + $this->alipay_total_price;//增加订单总额
        $customerInfo->save();


    }

    /**
     * 各层级的预分佣
     * $parent_id 父级ID
     * $son_level 购买人等级
     * $level 分成等级
     * $config 佣金配置
     */
    public function ceng($parent_id, $son_level, $level = 2, $config)
    {
        //查出父级信息
        $father = Customer::where('id', $parent_id)->first();

        switch ($level) {

            //直属上级
            case 2:
                $this->v2 = $father->id;
                //父级大于子集就有分成
                if ($father->member_type >= $son_level) {
                    $v2_p = $this->selfBuy_profit * $this->OneBuyLevel($father->member_type, $config);

//                    if ($v2_p >= 0.01) {
                    $this->creatProfit($father->id, $this->selfBuy_profit * $this->OneBuyLevel($father->member_type, $config), $level, $this->OneBuyLevel($father->member_type, $config));

                    $this->v2_p = round($v2_p, 2);
//                    }
                }
                break;

            //直属上上级
            case 3:

                $this->v3 = $father->id;

                //上上级 vip级 起步
                if ($father->member_type >= 2) {

                    $v3_p = $this->selfBuy_profit * $this->TwoBuyLevel($father->member_type, $config);


//                    if ($v3_p >= 0.01) {

                    $this->creatProfit($father->id, $this->selfBuy_profit * $this->TwoBuyLevel($father->member_type, $config), $level, $this->TwoBuyLevel($father->member_type, $config));

                    $this->v3_p = round($v3_p, 2);
//                    }


                }
                break;

            //超级vip
            case 4:

                $this->v4 = $father->id;
                $v4_p = $this->selfBuy_profit * $this->TwoBuyLevel($father->member_type, $config);

//                if ($v4_p >= 0.01) {
                $this->creatProfit($father->id, $this->selfBuy_profit * $this->TwoBuyLevel($father->member_type, $config), $level, $this->TwoBuyLevel($father->member_type, $config));

                $this->v4_p = round($v4_p, 2);
//                }

                break;

        }
        //当前父级不是超级vip 和 vip 并有上级
        if ($father->member_type < 2 and $father->parent_id != 0) {

            if ($level < 3) {
                $this->ceng($father->parent_id, $son_level, $level + 1, $config);

            } else {

                if ($father->last_super != 0) {
                    $this->ceng($father->last_super, $son_level, $level + 1, $config);

                }

            }

        }

    }

}
