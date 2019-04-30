<?php
/**
 * 用户服务层
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1 0001
 * Time: 下午 4:25
 */

namespace App\Services;


use App\Lib\Util\QueryPager;
use App\Model\AliPay\AliPayClient;
use App\Model\AliPay\AliPayTransfer;
use App\Model\CheckBuilding;
use App\Model\Config;
use App\Model\Driver;
use App\Model\DriverTakeOver;
use App\Model\LandlordOrder;
use App\Model\Level;
use App\Model\Order;
use App\Model\OtherRentApplication;
use App\Model\Passport;
use App\Model\PassportReward;
use App\Model\PassportStore;
use App\Model\Plant;
use App\Model\PlantOperateLog;
use App\Model\Region;
use App\Model\RentApplication;
use App\Model\RentHouse;
use App\Model\RentPic;
use App\Model\RouteItems;
use App\Model\ScoreLog;
use App\Model\SignLog;
use App\Model\Survey;
use App\Model\SysSign;
use App\Model\Tenement;
use App\Model\UserEvaluate;
use App\Model\UserEvaluateTag;
use App\Model\Verify;
use App\Model\VerifyLog;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProvidersMarketService extends CommonService
{
    /**
     * @description:添加订单
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function landlordOrderAdd(array $input)
    {
        //dd($input);
        $user_id= $input['user_id'];
        $rent_house_id = $input['rent_house_id'];
        $room_info = RentHouse::where('id',$rent_house_id)->first();
        $model = new LandlordOrder();
        $order_sn = orderId();
        $order_data = [
            'rent_application_id'   => @$input['rent_application_id'],
            'rent_contract_id'      => @$input['rent_contract_id'],
            'user_id'               => $user_id,
            'tenement_id'           => @$input['tenement_id'],
            'order_sn'              => $order_sn,
            'rent_house_id'         => $rent_house_id,
            'District'              => $room_info->District,
            'TA'                    => $room_info->TA,
            'Region'                => $room_info->Region,
            'order_type'            => $input['order_type'],
            'start_time'            => $input['start_time'],
            'end_time'              => $input['end_time'],
            'requirement'           => $input['requirement'],
            'budget'                => $input['budget'],
            'created_at'            => date('Y-m-d H:i:s',time()),
        ];
        $res = $model->insert($order_data);
        if($res){
            return $this->success('send order to service market success');
        }else{
            return $this->error('3','send order to service market failed');
        }
    }




    /**
     * @description:服务商获得订单列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderList(array $input)
    {
        //dd($input);
        $model = new LandlordOrder();
        $Desc = @$input['District'];
        if($Desc){
            $model = $model->where('District',$Desc);
        }
        $TA = @$input['TA'];
        if($TA){
            $model = $model->where('TA',$TA);
        }
        $Region = @$input['Region'];
        if($Region){
            $model = $model->where('Region',$Region);
        }
        $start_date = @$input['start_date'];
        if($start_date){
            $model = $model->where('end_time','>',$start_date);
        }
        $end_date = @$input['end_date'];
        if($end_date){
            $model = $model->where('end_time','<',$end_date);
        }
        $order_type = @$input['order_type'];
        if($order_type){
            $model = $model->where('order_type',$order_type);
        }
        $model = $model->where('end_time','>=',date('Y-m-d',time()));
        $page = $input['page'];
        $count = $model->where('order_status',1)->count();
        if($count <= ($page-1)*5){
            return $this->error('2','no more information');
        }
        $res = $model->where('order_status',1)->offset(($page-1)*5)->limit(5)->get()->toArray();
        foreach ($res as $k => $v){
            $order_res[$k] = RentHouse::where('id',$v['rent_house_id'])->select('property_name','property_type','address','available_time','rent_fee_pre_week','rent_least_fee','bedroom_no','bathroom_no','parking_no','garage_no','District','TA','Region','available_date')->first()->toArray();
            $order_res[$k]['house_pic'] = RentPic::where('rent_house_id',$v['rent_house_id'])->where('deleted_at',null)->pluck('house_pic')->toArray();// 图片
            $order_res[$k]['full_address'] = $order_res[$k]['address'].','.Region::getName($order_res[$k]['District']).','.Region::getName($order_res[$k]['TA']).','.Region::getName($order_res[$k]['Region']); //地址
            $order_res[$k]['order_id'] = $v['id'];
            $order_res[$k]['order_type'] = $v['type'];
            $order_res[$k]['budget'] = $v['budget'];
            $order_res[$k]['created_at'] = $v['created_at'];
            $order_res[$k]['rent_house_id'] = $v['rent_house_id'];
        }
        $data['order_list'] = $order_res;
        $data['total_page'] = ceil($count/5);
        $data['current_page'] = $page;
        return $this->success('get order list success',$data);
    }




    /**
     * @description:获得订单列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderDetail(array $input)
    {
        //dd($input);
        $model = new LandlordOrder();
        $order_id = $input['order_id'];
        $res = $model->where('id', $order_id)->first()->toArray();
        if($res){
            return $this->success('get order info success'.$res);
        }else{
            return $this->error('2','get order info failed');
        }
    }
}