<?php
/**
 * 充值服务层
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1 0001
 * Time: 下午 4:25
 */

namespace App\Services;


use App\Lib\Util\QueryPager;
use App\Model\Bond;
use App\Model\BondRefund;
use App\Model\BondTransfer;
use App\Model\ContractTenement;
use App\Model\OperatorRoom;
use App\Model\Region;
use App\Model\RentArrears;
use App\Model\RentContact;
use App\Model\RentContract;
use App\Model\RentHouse;
use App\Model\RentPic;
use App\Model\Verify;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ChargeService extends CommonService
{
    /**
     * @description:充值列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function chargeList(array $input)
    {
        $res = DB::table('charge')->get();

        return $this->success('get bond list success',$res);
    }


    /**
     * @description:充值列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function charge(array $input)
    {
        $charge_id = $input['charge_id'];
        $amount = DB::table('charge')->where('id',$charge_id)->pluck('charge_fee')->first();
        $free = DB::table('charge')->where('id',$charge_id)->pluck('free_balance')->first();
        $charge_sn = chargeSn();
        $charge_data = [
            'user_id'   => $input['user_id'],
            'charge_sn' => $charge_sn,
            'charge_fee'    => $amount,
            'free_fee'      => $free,
            'charge_type'   => 1,
            'charge_status' => 1,
        ];
        $res = DB::table('charge_list')->insert($charge_data);
        //生成订单操作


        $auth = base64_encode('SS64008062:pY9^KFwY9!U5b');

        $http = new \GuzzleHttp\Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . $auth
            ]
        ]);

        $response = $http->post('https://poliapi.apac.paywithpoli.com/api/v2/Transaction/Initiate', [
            'json' => [
                'Amount' => $amount,//金额
                'CurrencyCode' => 'NZD',
                'MerchantReference' => $charge_sn,//唯一订单号  前面生成
                'MerchantHomepageURL' => 'https://rent-diy.com/',
                'SuccessURL' => 'https://rent-diy.com/',//支付成功用户跳往地址
                'FailureURL' => '',//用户在银行支付失败跳往网站地址
                'CancellationURL' => '',//用户支付取消跳往地址
                'NotificationURL' => ''//异步回调地址
            ]
        ]);

        $result = json_decode($response->getBody());

        if ($result->Success == true) {

            return $this->success('success', $result->NavigateURL);//返回支付地址给前端
        }else{
            return $this->error('2','pay failed');
        }
    }

    /***
     * 异步回调操作（post路由）
     * @method POST路由
     */
    public function notify(array $input)
    {

        $token = $input['token'];
        $auth = base64_encode('SS64008062:pY9^KFwY9!U5b');

        $http = new \GuzzleHttp\Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . $auth
            ]
        ]);
        //查询账单
        $response = $http->get('https://poliapi.apac.paywithpoli.com/api/v2/Transaction/GetTransaction?token=' . $token);

        $result = json_decode($response->getBody());

        $charge_sn = $result->MerchantReference;//下单订单号
        $order_status = $result->TransactionStatusCode; // Completed已完成     其他状态看https://www.polipayments.com/TransactionStatus

        //更新订单状态 和 相关逻辑操作
        if($order_status == 'Completed'){
            $charge_type = DB::table('charge_list')->where('charge_sn',$charge_sn)->pluck('charge_type')->first();
            if($charge_type == 1){
                // 更改状态
                DB::table('charge_list')->where('charge_sn',$charge_sn)->update(['charge_status' => 2,'updated_at'=>date('Y-m-d H:i:s',time())]);
                // 添加余额
                $charge_fee = DB::table('charge_list')->where('charge_sn',$charge_sn)->pluck('charge_fee')->first();
                $user_id = DB::table('charge_list')->where('charge_sn',$charge_sn)->pluck('user_id')->first();
                $free_fee = DB::table('charge_list')->where('charge_sn',$charge_sn)->pluck('free_fee')->first();
                DB::table('user')->where('id',$user_id)->increment('balance',$charge_fee);
                DB::table('user')->where('id',$user_id)->increment('free_balance',$free_fee);
                // 充值列表

            }elseif ($charge_type == 2){
                // 更改状态
                DB::table('charge_list')->where('charge_sn',$charge_sn)->update(['charge_status' => 2,'updated_at'=>date('Y-m-d H:i:s',time())]);
                // 添加余额
                $user_id = DB::table('charge_list')->where('charge_sn',$charge_sn)->pluck('user_id')->first();
                $free_fee = DB::table('charge_list')->where('charge_sn',$charge_sn)->pluck('free_fee')->first();
                DB::table('user')->where('id',$user_id)->increment('free_balance',$free_fee);
                // 添加VIP
                // 查看上次此VIP时限
                $vip_res = DB::table('vip_list')->where('user_id',$user_id)->where('vip_type',1)->pluck('vip_end_date')->last();
                if(!$vip_res){ //没有vip记录
                    $vip_data = [
                        'user_id'           => $user_id,
                        'vip_type'          => 1,
                        'vip_start_date'    => date('Y-m-d',time()),
                        'vip_end_date'      => date('Y-m-d',strtotime('+1 years')-3600*24),
                        'created_at'        => date('Y-m-d H:i:s',time()),
                    ];
                }else{
                    $vip_data = [
                        'user_id'           => $user_id,
                        'vip_type'          => 1,
                        'vip_start_date'    => date('Y-m-d',strtotime($vip_res)+3600*24),
                        'vip_end_date'      => date('Y-m-d',strtotime($vip_res.'+1 years')),
                        'created_at'        => date('Y-m-d H:i:s',time()),
                    ];
                }
                // 添加VIP记录
                $vip_insert_res = DB::table('vip_list')->insert($vip_data);
            }elseif ($charge_type == 3){
                // 更改状态
                DB::table('charge_list')->where('charge_sn',$charge_sn)->update(['charge_status' => 2,'updated_at'=>date('Y-m-d H:i:s',time())]);
                // 添加余额
                $user_id = DB::table('charge_list')->where('charge_sn',$charge_sn)->pluck('user_id')->first();
                $free_fee = DB::table('charge_list')->where('charge_sn',$charge_sn)->pluck('free_fee')->first();
                DB::table('user')->where('id',$user_id)->increment('free_balance',$free_fee);
                // 添加VIP
                // 查看上次此VIP时限
                $vip_res = DB::table('vip_list')->where('user_id',$user_id)->where('vip_type',2)->pluck('vip_end_date')->last();
                if(!$vip_res){ //没有vip记录
                    $vip_data = [
                        'user_id'           => $user_id,
                        'vip_type'          => 2,
                        'vip_start_date'    => date('Y-m-d',time()),
                        'vip_end_date'      => date('Y-m-d',strtotime('+1 years')-3600*24),
                        'created_at'        => date('Y-m-d H:i:s',time()),
                    ];
                }else{
                    $vip_data = [
                        'user_id'           => $user_id,
                        'vip_type'          => 2,
                        'vip_start_date'    => date('Y-m-d',strtotime($vip_res)+3600*24),
                        'vip_end_date'      => date('Y-m-d',strtotime($vip_res.'+1 years')),
                        'created_at'        => date('Y-m-d H:i:s',time()),
                    ];
                }
                // 添加VIP记录
                $vip_insert_res = DB::table('vip_list')->insert($vip_data);
            }elseif ($charge_type == 4){
                // 更改状态
                DB::table('charge_list')->where('charge_sn',$charge_sn)->update(['charge_status' => 2,'updated_at'=>date('Y-m-d H:i:s',time())]);
                // 添加余额
                $user_id = DB::table('charge_list')->where('charge_sn',$charge_sn)->pluck('user_id')->first();
                $free_fee = DB::table('charge_list')->where('charge_sn',$charge_sn)->pluck('free_fee')->first();
                DB::table('user')->where('id',$user_id)->increment('free_balance',$free_fee);
                // 添加VIP
                // 查看上次此VIP时限
                $vip_res = DB::table('vip_list')->where('user_id',$user_id)->where('vip_type',3)->pluck('vip_end_date')->last();
                if(!$vip_res){ //没有vip记录
                    $vip_data = [
                        'user_id'           => $user_id,
                        'vip_type'          => 3,
                        'vip_start_date'    => date('Y-m-d',time()),
                        'vip_end_date'      => date('Y-m-d',strtotime('+1 years')-3600*24),
                        'created_at'        => date('Y-m-d H:i:s',time()),
                    ];
                }else{
                    $vip_data = [
                        'user_id'           => $user_id,
                        'vip_type'          => 3,
                        'vip_start_date'    => date('Y-m-d',strtotime($vip_res)+3600*24),
                        'vip_end_date'      => date('Y-m-d',strtotime($vip_res.'+1 years')),
                        'created_at'        => date('Y-m-d H:i:s',time()),
                    ];
                }
                // 添加VIP记录
                $vip_insert_res = DB::table('vip_list')->insert($vip_data);
            }elseif ($charge_type == 5){
                // 更改状态
                DB::table('charge_list')->where('charge_sn',$charge_sn)->update(['charge_status' => 2,'updated_at'=>date('Y-m-d H:i:s',time())]);
                // 添加余额
                $user_id = DB::table('charge_list')->where('charge_sn',$charge_sn)->pluck('user_id')->first();
                $free_fee = DB::table('charge_list')->where('charge_sn',$charge_sn)->pluck('free_fee')->first();
                DB::table('user')->where('id',$user_id)->increment('free_balance',$free_fee);
                // 添加VIP
                // 查看上次此VIP时限
                $vip_res = DB::table('vip_list')->where('user_id',$user_id)->where('vip_type',4)->pluck('vip_end_date')->last();
                if(!$vip_res){ //没有vip记录
                    $vip_data = [
                        'user_id'           => $user_id,
                        'vip_type'          => 4,
                        'vip_start_date'    => date('Y-m-d',time()),
                        'vip_end_date'      => date('Y-m-d',strtotime('+1 years')-3600*24),
                        'created_at'        => date('Y-m-d H:i:s',time()),
                    ];
                }else{
                    $vip_data = [
                        'user_id'           => $user_id,
                        'vip_type'          => 4,
                        'vip_start_date'    => date('Y-m-d',strtotime($vip_res)+3600*24),
                        'vip_end_date'      => date('Y-m-d',strtotime($vip_res.'+1 years')),
                        'created_at'        => date('Y-m-d H:i:s',time()),
                    ];
                }
                // 添加VIP记录
                $vip_insert_res = DB::table('vip_list')->insert($vip_data);
            }
        }
    }

}