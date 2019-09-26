<?php
/**
 * 报表服务层
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1 0001
 * Time: 下午 4:25
 */

namespace App\Services;


use App\Lib\Util\QueryPager;
use App\Model\AliPay\AliPayClient;
use App\Model\AliPay\AliPayTransfer;
use App\Model\Bond;
use App\Model\BusinessContract;
use App\Model\CheckBuilding;
use App\Model\Config;
use App\Model\ContractChattel;
use App\Model\ContractService;
use App\Model\ContractTenement;
use App\Model\Driver;
use App\Model\DriverTakeOver;
use App\Model\EntireContract;
use App\Model\Inspect;
use App\Model\InspectChattel;
use App\Model\InspectCheck;
use App\Model\Level;
use App\Model\LookHouse;
use App\Model\OperatorRoom;
use App\Model\Order;
use App\Model\OtherRentApplication;
use App\Model\Passport;
use App\Model\PassportReward;
use App\Model\PassportStore;
use App\Model\Plant;
use App\Model\PlantOperateLog;
use App\Model\Region;
use App\Model\RentAdjust;
use App\Model\RentApplication;
use App\Model\RentArrears;
use App\Model\RentContract;
use App\Model\RentFee;
use App\Model\RentHouse;
use App\Model\RentPic;
use App\Model\RouteItems;
use App\Model\ScoreLog;
use App\Model\SeparateContract;
use App\Model\SignLog;
use App\Model\Survey;
use App\Model\SysSign;
use App\Model\Task;
use App\Model\Tenement;
use App\Model\TenementCertificate;
use App\Model\TenementNote;
use App\Model\TenementScore;
use App\Model\UserEvaluate;
use App\Model\UserEvaluateTag;
use App\Model\Verify;
use App\Model\VerifyLog;
use App\User;
use Carbon\Carbon;
use http\Env\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ReportService extends CommonService
{
    /**
     * @description:物品清单
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function chattelReport(array $input)
    {
        $where = function ($query) use($input){
            //搜索词查询
            if (@$input['tenement_name'] and @$input['tenement_name'] != '') {
                $tenement_name = @$input['tenement_name'];
                $query->where('ct.tenement_full_name','like', '%'.$tenement_name.'%');
            }
            if (@$input['District'] and @$input['District'] != '') {
                $District = @$input['District'];
                $query->where('h.District',$District);
            }
            if (@$input['TA'] and @$input['TA'] != '') {
                $TA = @$input['TA'];
                $query->where('h.TA',$TA);
            }
            if (@$input['Region'] and @$input['Region'] != '') {
                $Region = @$input['Region'];
                $query->where('h.Region',$Region);
            }
            if (@$input['contract_status'] and @$input['contract_status'] != ''){
                $query->where('c.contract_status',$input['contract_status']);
            }
            $query->where('c.user_id',$input['user_id']);

        };
        $count = DB::table('rent_contract as c')
            ->leftJoin('contract_tenement as ct','c.id','ct.contract_id')
            ->leftJoin('rent_house as h','h.id','c.house_id')
            ->where($where)->count();
        if($count < ($input['page']-1)*10){
            return $this->error('2','get contract list failed');
        }else{
            $res = DB::table('rent_contract as c')
                ->leftJoin('contract_tenement as ct','c.id','ct.contract_id')
                ->leftJoin('rent_house as h','h.id','c.house_id')
                ->where($where)->limit(10)->offset(($input['page']-1)*10)
                ->select('ct.tenement_full_name','ct.tenement_e_mail','tenement_mobile','h.property_name','c.house_id','c.contract_id','c.contract_type','c.contract_status','c.rent_start_date','c.rent_end_date','c.id')
                ->get();
            foreach ($res as $k => $v){
                $inspect_id = Inspect::where('rent_house_id',$v->house_id)->where('inspect_status',4)->orderByDesc('id')->pluck('id')->first();
                if($inspect_id){
                    $res[$k]->inspect_date = Inspect::where('rent_house_id',$v->house_id)->where('inspect_status',4)->pluck('inspect_completed_date')->first();
                    $res[$k]->total_chattel = InspectChattel::where('inspect_id',$inspect_id)->sum('chattel_num');
                    $res[$k]->inspector = Inspect::where('rent_house_id',$v->house_id)->where('inspect_status',4)->pluck('check_name')->first();
                }else{
                    $res[$k]->total_chattel = ContractChattel::where('contract_id',$v->id)->sum('chattel_num');
                    $res[$k]->inspect_date = '';
                    $res[$k]->inspector = '';
                }
            }
            }
            $data['contract_list'] = $res;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/10);
            return $this->success('get contract list success',$data);
    }


    /**
     * @description:物品清单详情
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function chattelDetail(array $input)
    {
        $contract_id = $input['contract_id'];
        $house_id = RentContract::where('id',$contract_id)->pluck('house_id')->first();
        $inspect_id = Inspect::where('rent_house_id',$house_id)->where('inspect_status',4)->orderByDesc('updated_at')->pluck('id')->first();
        if($inspect_id){
            $count =  InspectChattel::where('inspect_id',$inspect_id)->count();
            if($count < ($input['page']-1)*10){
                return $this->error('2','get contract list failed');
            }else{
                $res = InspectChattel::where('inspect_id',$inspect_id)->offset(($input['page']-1)*10)->limit(10)->get();
            }
        }else{
            $count = ContractChattel::where('contract_id',$contract_id)->count();
            if($count < ($input['page']-1)*10){
                return $this->error('2','get contract list failed');
            }else{
                $res = ContractChattel::where('contract_id',$contract_id)->offset(($input['page']-1)*10)->limit(10)->get();
            }
        }

        $data['contract_list'] = $res;
        $data['current_page'] = $input['page'];
        $data['total_page'] = ceil($count/10);
        return $this->success('get contract list success',$data);
    }

    /**
     * @description:租约到期
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentDeadLineReport(array $input)
    {
        $where = function ($query) use($input){
            //搜索词查询
            if (@$input['tenement_name'] and @$input['tenement_name'] != '') {
                $tenement_name = @$input['tenement_name'];
                $query->where('ct.tenement_full_name','like', '%'.$tenement_name.'%');
            }
            if (@$input['District'] and @$input['District'] != '') {
                $District = @$input['District'];
                $query->where('h.District',$District);
            }
            if (@$input['TA'] and @$input['TA'] != '') {
                $TA = @$input['TA'];
                $query->where('h.TA',$TA);
            }
            if (@$input['Region'] and @$input['Region'] != '') {
                $Region = @$input['Region'];
                $query->where('h.Region',$Region);
            }
            $query->where('c.user_id',$input['user_id']);
            $query->where('c.rent_end_date','<=',date('Y-m-d',strtotime('+30 days')));
        };
        $count = DB::table('rent_contract as c')
            ->leftJoin('contract_tenement as ct','c.id','ct.contract_id')
            ->leftJoin('rent_house as h','h.id','c.house_id')
            ->where($where)->count();
        if($count < ($input['page']-1)*10){
            return $this->error('2','get contract list failed');
        }else{
            $res = DB::table('rent_contract as c')
                ->leftJoin('contract_tenement as ct','c.id','ct.contract_id')
                ->leftJoin('rent_house as h','h.id','c.house_id')
                ->where($where)->limit(10)->offset(($input['page']-1)*10)
                ->select('ct.tenement_full_name','ct.tenement_e_mail','tenement_mobile','h.property_name','c.contract_id','c.contract_type','c.rent_start_date','c.rent_end_date','c.id')
                ->get();
            foreach ($res as $k => $v){
                if($v->contract_type == 1){
                    $res[$k]->rent_fee = EntireContract::where('contract_id',$v->id)->pluck('rent_per_week')->first();
                    $res[$k]->arrears = RentArrears::where('contract_id',$v->id)->sum('arrears_fee');
                    $res[$k]->rent = RentArrears::where('contract_id',$v->id)->where('arrears_type',2)->sum('arrears_fee');

                }elseif ($v->contract_type == 2 || $v->contract_type == 3){
                    $res[$k]->rent_fee = SeparateContract::where('contract_id',$v->id)->pluck('rent_per_week')->first();
                    $res[$k]->arrears = RentArrears::where('contract_id',$v->id)->sum('arrears_fee');
                    $res[$k]->rent = RentArrears::where('contract_id',$v->id)->where('arrears_type',2)->sum('arrears_fee');
                }else{
                    $res[$k]->rent_fee = BusinessContract::where('contract_id',$v->id)->pluck('month_rent')->first();
                    $res[$k]->arrears = RentArrears::where('contract_id',$v->id)->sum('arrears_fee');
                    $res[$k]->rent = RentArrears::where('contract_id',$v->id)->where('arrears_type',2)->sum('arrears_fee');
                }
            }
            $data['contract_list'] = $res;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/10);
            return $this->success('get contract list success',$data);
        }
    }



    /**
     * @description:涨租列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentIncrementReport(array $input)
    {
        $where = function ($query) use($input){
            //搜索词查询
            if (@$input['tenement_name'] and @$input['tenement_name'] != '') {
                $tenement_name = @$input['tenement_name'];
                $query->where('ct.tenement_full_name','like', '%'.$tenement_name.'%');
            }
            if (@$input['District'] and @$input['District'] != '') {
                $District = @$input['District'];
                $query->where('h.District',$District);
            }
            if (@$input['TA'] and @$input['TA'] != '') {
                $TA = @$input['TA'];
                $query->where('h.TA',$TA);
            }
            if (@$input['Region'] and @$input['Region'] != '') {
                $Region = @$input['Region'];
                $query->where('h.Region',$Region);
            }
            $query->where('c.user_id',$input['user_id']);
            $keywords= function ($querys) {
                $querys/*->orwhere('a.alarm_code ','like', '%'.$keyword.'%')*/
                    ->where('c.increment_date', '<=',  date('Y-m-d',strtotime('+30 days')))->orwhere('c.increment_date',  null);
                return $querys;
            };
            $query->where($keywords);
        };
        $count = DB::table('rent_contract as c')
            ->leftJoin('contract_tenement as ct','c.id','ct.contract_id')
            ->leftJoin('rent_house as h','h.id','c.house_id')
            ->where($where)->count();
        if($count < ($input['page']-1)*10){
            return $this->error('2','get contract list failed');
        }else{
            $res = DB::table('rent_contract as c')
                ->leftJoin('contract_tenement as ct','c.id','ct.contract_id')
                ->leftJoin('rent_house as h','h.id','c.house_id')
                ->where($where)->limit(10)->offset(($input['page']-1)*10)
                ->select('ct.tenement_full_name','ct.tenement_e_mail','tenement_mobile','h.property_name','c.contract_id','c.contract_type','c.rent_start_date','c.rent_end_date','c.id')
                ->get();
            foreach ($res as $k => $v){
                if($v->contract_type == 1){
                    $res[$k]->rent_fee = EntireContract::where('contract_id',$v->id)->pluck('rent_per_week')->first();
                    $res[$k]->arrears = RentArrears::where('contract_id',$v->id)->sum('arrears_fee');
                    $res[$k]->rent = RentArrears::where('contract_id',$v->id)->where('arrears_type',2)->sum('arrears_fee');

                }elseif ($v->contract_type == 2 || $v->contract_type == 3){
                    $res[$k]->rent_fee = SeparateContract::where('contract_id',$v->id)->pluck('rent_per_week')->first();
                    $res[$k]->arrears = RentArrears::where('contract_id',$v->id)->sum('arrears_fee');
                    $res[$k]->rent = RentArrears::where('contract_id',$v->id)->where('arrears_type',2)->sum('arrears_fee');
                }else{
                    $res[$k]->rent_fee = BusinessContract::where('contract_id',$v->id)->pluck('month_rent')->first();
                    $res[$k]->arrears = RentArrears::where('contract_id',$v->id)->sum('arrears_fee');
                    $res[$k]->rent = RentArrears::where('contract_id',$v->id)->where('arrears_type',2)->sum('arrears_fee');
                }
            }
            $data['contract_list'] = $res;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/10);
            return $this->success('get contract list success',$data);
        }
    }


    /**
     * @description:押金列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function bondReport(array $input)
    {
        $where = function ($query) use($input){
            //搜索词查询
            if (@$input['tenement_name'] and @$input['tenement_name'] != '') {
                $tenement_name = @$input['tenement_name'];
                $query->where('ct.tenement_full_name','like', '%'.$tenement_name.'%');
            }
            //房屋搜索
            if (@$input['rent_house_id'] and @$input['rent_house_id'] != '') {
                $rent_house_id = @$input['rent_house_id'];
                $query->where('h.id',$rent_house_id);
            }
            //状态
            if (@$input['bond_status'] and @$input['bond_status'] != '') {
                $bond_status = @$input['bond_status'];
                if($bond_status == 2){
                    $query->whereIn('r.bond_status',[1,2,3]);
                }elseif ($bond_status == 3){
                    $query->whereIn('r.bond_status',[3,4,5,6]);
                }elseif ($bond_status == 4){
                    $query->whereIn('r.bond_status',[3,7,8,9]);
                }

            }
            $query->where('c.user_id',$input['user_id']);
            $query->where('r.arrears_type',1);
        };
        $count = DB::table('rent_contract as c')
            ->leftJoin('contract_tenement as ct','c.id','ct.contract_id')
            ->leftJoin('rent_house as h','h.id','c.house_id')
            ->leftJoin('rent_arrears as r','r.contract_id','c.id')
            ->where($where)->count();
        if($count < ($input['page']-1)*10){
            return $this->error('2','get contract list failed');
        }else{
            $res = DB::table('rent_contract as c')
                ->leftJoin('contract_tenement as ct','c.id','ct.contract_id')
                ->leftJoin('rent_house as h','h.id','c.house_id')
                ->leftJoin('rent_arrears as r','r.contract_id','c.id')
                ->where($where)->limit(10)->offset(($input['page']-1)*10)
                ->select('ct.tenement_full_name','ct.tenement_e_mail','ct.tenement_mobile','h.property_name','c.contract_id','c.contract_type',
                    'c.rent_start_date','c.rent_end_date','c.id','r.pay_fee','r.bond_status')
                ->get();
            $rent_fee = 0;
            $arrears = 0;
            $rent = 0;
            foreach ($res as $k => $v){
                if($v->contract_type == 1){
                    $res[$k]->rent_fee = EntireContract::where('contract_id',$v->id)->pluck('rent_per_week')->first();
                    $res[$k]->arrears = RentArrears::where('contract_id',$v->id)->where('arrears_type','!=',4)->sum('arrears_fee');
                    $res[$k]->rent = RentArrears::where('contract_id',$v->id)->where('arrears_type','!=',4)->sum('need_pay_fee');
                    $rent_fee += $res[$k]->rent_fee;
                    $arrears +=  $res[$k]->arrears;
                    $rent += $res[$k]->rent;
                }elseif ($v->contract_type == 2 || $v->contract_type == 3){
                    $res[$k]->rent_fee = SeparateContract::where('contract_id',$v->id)->pluck('rent_per_week')->first();
                    $res[$k]->arrears = RentArrears::where('contract_id',$v->id)->where('arrears_type','!=',4)->sum('arrears_fee');
                    $res[$k]->rent = RentArrears::where('contract_id',$v->id)->where('arrears_type','!=',4)->sum('need_pay_fee');
                    $rent_fee += $res[$k]->rent_fee;
                    $arrears +=  $res[$k]->arrears;
                    $rent += $res[$k]->rent;
                }else{
                    $res[$k]->rent_fee = BusinessContract::where('contract_id',$v->id)->pluck('month_rent')->first();
                    $res[$k]->arrears = RentArrears::where('contract_id',$v->id)->where('arrears_type','!=',4)->sum('arrears_fee');
                    $res[$k]->rent = RentArrears::where('contract_id',$v->id)->where('arrears_type','!=',4)->sum('need_pay_fee');
                    $rent_fee += $res[$k]->rent_fee;
                    $arrears +=  $res[$k]->arrears;
                    $rent += $res[$k]->rent;
                }
            }
            $data['contract_list'] = $res;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/10);
            $data['rent_fee'] = $rent_fee;
            $data['rent'] = $rent;
            $data['arreares'] = $arrears;
            return $this->success('get contract list success',$data);
        }
    }


    /**
     * @description:欠款列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function arrearsReport(array $input)
    {
        $where = function ($query) use($input){
            //搜索词查询
            if (@$input['tenement_name'] and @$input['tenement_name'] != '') {
                $tenement_name = @$input['tenement_name'];
                $query->where('ct.tenement_full_name','like', '%'.$tenement_name.'%');
            }
            //房屋搜索
            if (@$input['rent_house_id'] and @$input['rent_house_id'] != '') {
                $rent_house_id = @$input['rent_house_id'];
                $query->where('h.id',$rent_house_id);
            }
            //状态
            if (@$input['arrears_type'] and @$input['arrears_type'] != '') {
                $arrears_type = @$input['arrears_type'];
                $query->where('r.arrears_type',$arrears_type);
            }
            // 时间
            if (@$input['start_time'] && @$input['end_time'] && @$input['start_time'] != '' && @$input['end_time'] != '') {
               $query->whereBetween('r.created_at',[$input['start_time'],$input['end_time']]);
            }
            $query->where('c.user_id',$input['user_id']);
        };
        $count = DB::table('rent_arrears as r')
            ->leftJoin('rent_contract as c','r.contract_id','c.id')
            ->leftJoin('contract_tenement as ct','c.id','ct.contract_id')
            ->leftJoin('rent_house as h','h.id','c.house_id')
            ->where($where)->count();
        if($count < ($input['page']-1)*10){
            return $this->error('2','get contract list failed');
        }else{
            $res = DB::table('rent_arrears as r')
                ->leftJoin('rent_contract as c','r.contract_id','c.id')
                ->leftJoin('contract_tenement as ct','c.id','ct.contract_id')
                ->leftJoin('rent_house as h','h.id','c.house_id')
                ->where($where)->limit(10)->offset(($input['page']-1)*10)
                ->select('ct.tenement_full_name','ct.tenement_e_mail','ct.tenement_mobile','h.property_name','c.contract_id','c.contract_type',
                    'c.rent_start_date','c.rent_end_date','r.pay_fee','r.need_pay_fee','r.arrears_fee','r.id')
                ->get();
            $total_arrears = 0;
            $total_pay_fee = 0;
            $total_need_pay_fee = 0;
            foreach ($res as $k => $v){
                $total_arrears += $v->arrears_fee;
                $total_need_pay_fee += $v->need_pay_fee;
                $total_pay_fee += $v->pay_fee;
            }
            $data['contract_list'] = $res;
            $data['total_arrears'] = $total_arrears;
            $data['total_pay_fee'] = $total_pay_fee;
            $data['total_need_pay_fee'] = $total_need_pay_fee;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/10);
            return $this->success('get contract list success',$data);
        }
    }


    /**
     * @description:租客欠款列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function tenementReport(array $input)
    {
        $where = function ($query) use($input){
            //搜索词查询
            if (@$input['tenement_name'] and @$input['tenement_name'] != '') {
                $tenement_name = @$input['tenement_name'];
                $query->where('ct.tenement_full_name','like', '%'.$tenement_name.'%');
            }
            //房屋搜索
            if (@$input['rent_house_id'] and @$input['rent_house_id'] != '') {
                $rent_house_id = @$input['rent_house_id'];
                $query->where('h.id',$rent_house_id);
            }
            //状态
            if (@$input['arrears_type'] and @$input['arrears_type'] != '') {
                $arrears_type = @$input['arrears_type'];
                $query->where('r.arrears_type',$arrears_type);
            }
            // 时间
            if (@$input['start_time'] && @$input['end_time'] && @$input['start_time'] != '' && @$input['end_time'] != '') {
                $query->whereBetween('r.created_at',[$input['start_time'],$input['end_time']]);
            }
            $query->where('r.arrears_type','!=',4);
            $query->where('c.user_id',$input['user_id']);
        };
        $count = DB::table('rent_arrears as r')
            ->leftJoin('rent_contract as c','r.contract_id','c.id')
            ->leftJoin('contract_tenement as ct','c.id','ct.contract_id')
            ->leftJoin('rent_house as h','h.id','c.house_id')
            ->where($where)->count();
        if($count < ($input['page']-1)*10){
            return $this->error('2','get contract list failed');
        }else{
            $res = DB::table('rent_arrears as r')
                ->leftJoin('rent_contract as c','r.contract_id','c.id')
                ->leftJoin('contract_tenement as ct','c.id','ct.contract_id')
                ->leftJoin('rent_house as h','h.id','c.house_id')
                ->where($where)->orderByDesc('r.need_pay_fee')->limit(10)->offset(($input['page']-1)*10)
                ->select('ct.tenement_full_name','ct.tenement_e_mail','ct.tenement_mobile','h.property_name','c.contract_id','c.contract_type',
                    'c.rent_start_date','c.rent_end_date','r.id','r.pay_fee','r.need_pay_fee','r.arrears_fee','r.id','r.arrears_type')
                ->get();
            $total_arrears = 0;
            $total_pay_fee = 0;
            $total_need_pay_fee = 0;
            foreach ($res as $k => $v){
                if($v->contract_type == 1){
                    $res[$k]->rent_fee = EntireContract::where('contract_id',$v->id)->pluck('rent_per_week')->first();
                }elseif ($v->contract_type == 2 || $v->contract_type == 3){
                    $res[$k]->rent_fee = SeparateContract::where('contract_id',$v->id)->pluck('rent_per_week')->first();
                }else{
                    $res[$k]->rent_fee = BusinessContract::where('contract_id',$v->id)->pluck('month_rent')->first();
                }
                $total_arrears += $v->arrears_fee;
                $total_need_pay_fee += $v->need_pay_fee;
                $total_pay_fee += $v->pay_fee;
            }
            $data['contract_list'] = $res;
            $data['total_arrears'] = $total_arrears;
            $data['total_pay_fee'] = $total_pay_fee;
            $data['total_need_pay_fee'] = $total_need_pay_fee;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/10);
            return $this->success('get contract list success',$data);
        }
    }


    /**
     * @description:租客行为记录详情
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function tenementReportDetail(array $input)
    {
        $arrears_id = $input['arrears_id'];
        $user_id = $input['user_id'];
        $tenement_id = RentArrears::where('id',$arrears_id)->pluck('tenement_id')->first();

        $tenement_res = TenementNote::where('user_id',$user_id)->where('tenement_id',$tenement_id)->count();
        if($tenement_res < ($input['page']-1)*10){
            return $this->error('2','get tenement note failed');
        }else{
            $tenement_note = TenementNote::where('user_id',$user_id)->where('tenement_id',$tenement_id)->offset(($input['page']-1)*10)->limit(10)->get();
            foreach ($tenement_note as $k => $v){
                $tenement_note[$k]->tenement_name = Tenement::where('id',$tenement_id)->pluck('first_name')->first();
            }
            $data['tenement_note'] = $tenement_note;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($tenement_res/10);
        }
        return $this->success('get tenement note success',$data);
    }



    /**
     * @description:租客账单列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function tenementArrearsReport(array $input)
    {
        $where = function ($query) use($input){
            //搜索词查询
            if (@$input['tenement_name'] and @$input['tenement_name'] != '') {
                $tenement_name = @$input['tenement_name'];
                $query->where('ct.tenement_full_name','like', '%'.$tenement_name.'%');
            }
            // 房屋筛选
            if (@$input['rent_house_id'] and @$input['rent_house_id'] != '') {
                $rent_house_id = @$input['rent_house_id'];
                $query->where('h.id',$rent_house_id);
            }
            //状态
            if (@$input['arrears_type'] and @$input['arrears_type'] != '') {
                $arrears_type = @$input['arrears_type'];
                $query->where('r.arrears_type',$arrears_type);
            }
            // 时间
            if (@$input['start_time'] && @$input['end_time'] && @$input['start_time'] != '' && @$input['end_time'] != '') {
                $query->whereBetween('r.created_at',[$input['start_time'],$input['end_time']]);
            }
            $query->where('r.arrears_type','!=',4);
            $query->where('c.user_id',$input['user_id']);
        };
        $count = DB::table('rent_arrears as r')
            ->leftJoin('rent_contract as c','r.contract_id','c.id')
            ->leftJoin('contract_tenement as ct','c.id','ct.contract_id')
            ->leftJoin('rent_house as h','h.id','c.house_id')
            ->where($where)->count();
        if($count < ($input['page']-1)*10){
            return $this->error('2','get contract list failed');
        }else{
            $res = DB::table('rent_arrears as r')
                ->leftJoin('rent_contract as c','r.contract_id','c.id')
                ->leftJoin('contract_tenement as ct','c.id','ct.contract_id')
                ->leftJoin('rent_house as h','h.id','c.house_id')
                ->where($where)->orderByDesc('r.need_pay_fee')->limit(10)->offset(($input['page']-1)*10)
                ->select('ct.tenement_full_name','ct.tenement_e_mail','ct.tenement_mobile','h.property_name','c.contract_id','c.contract_type',
                    'c.rent_start_date','c.rent_end_date','r.id','r.pay_fee','r.need_pay_fee','r.arrears_fee','r.id','r.arrears_type','r.created_at as invoice_date')
                ->get();
            $total_arrears = 0;
            $total_pay_fee = 0;
            $total_need_pay_fee = 0;
            foreach ($res as $k => $v){
                if($v->contract_type == 1){
                    $res[$k]->rent_fee = EntireContract::where('contract_id',$v->id)->pluck('rent_per_week')->first();
                }elseif ($v->contract_type == 2 || $v->contract_type == 3){
                    $res[$k]->rent_fee = SeparateContract::where('contract_id',$v->id)->pluck('rent_per_week')->first();
                }else{
                    $res[$k]->rent_fee = BusinessContract::where('contract_id',$v->id)->pluck('month_rent')->first();
                }
                $total_arrears += $v->arrears_fee;
                $total_need_pay_fee += $v->need_pay_fee;
                $total_pay_fee += $v->pay_fee;
            }
            $data['contract_list'] = $res;
            $data['total_arrears'] = $total_arrears;
            $data['total_pay_fee'] = $total_pay_fee;
            $data['total_need_pay_fee'] = $total_need_pay_fee;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/10);
            return $this->success('get contract list success',$data);
        }
    }


    /**
     * @description:商业费用单
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function businessArrearsReport(array $input)
    {
        $where = function ($query) use($input){
            //搜索词查询
            if (@$input['tenement_name'] and @$input['tenement_name'] != '') {
                $tenement_name = @$input['tenement_name'];
                $query->where('ct.tenement_full_name','like', '%'.$tenement_name.'%');
            }
            // 房屋筛选
            if (@$input['rent_house_id'] and @$input['rent_house_id'] != '') {
                $rent_house_id = @$input['rent_house_id'];
                $query->where('h.id',$rent_house_id);
            }
            //状态
            if (@$input['arrears_type'] and @$input['arrears_type'] != '') {
                $arrears_type = @$input['arrears_type'];
                $query->where('r.arrears_type',$arrears_type);
            }
            // 时间
            if (@$input['start_time'] && @$input['end_time'] && @$input['start_time'] != '' && @$input['end_time'] != '') {
                $query->whereBetween('r.created_at',[$input['start_time'],$input['end_time']]);
            }
            $query->where('r.arrears_type','!=',4);
            $query->where('contract_type',4);
            $query->where('c.user_id',$input['user_id']);
        };
        $count = DB::table('rent_arrears as r')
            ->leftJoin('rent_contract as c','r.contract_id','c.id')
            ->leftJoin('contract_tenement as ct','c.id','ct.contract_id')
            ->leftJoin('rent_house as h','h.id','c.house_id')
            ->where($where)->count();
        if($count < ($input['page']-1)*10){
            return $this->error('2','get contract list failed');
        }else{
            $res = DB::table('rent_arrears as r')
                ->leftJoin('rent_contract as c','r.contract_id','c.id')
                ->leftJoin('contract_tenement as ct','c.id','ct.contract_id')
                ->leftJoin('rent_house as h','h.id','c.house_id')
                ->where($where)->orderByDesc('r.need_pay_fee')->limit(10)->offset(($input['page']-1)*10)
                ->select('ct.tenement_full_name','ct.tenement_e_mail','ct.tenement_mobile','h.property_name','c.contract_id','c.contract_type',
                    'c.rent_start_date','c.rent_end_date','r.id','r.pay_fee','r.need_pay_fee','r.items_name','r.arrears_fee','r.rate','r.id','r.arrears_type','r.created_at as invoice_date')
                ->get();
            $total_arrears = 0;
            $total_pay_fee = 0;
            $total_need_pay_fee = 0;
            foreach ($res as $k => $v){
                if($v->contract_type == 1){
                    $res[$k]->rent_fee = EntireContract::where('contract_id',$v->id)->pluck('rent_per_week')->first();
                }elseif ($v->contract_type == 2 || $v->contract_type == 3){
                    $res[$k]->rent_fee = SeparateContract::where('contract_id',$v->id)->pluck('rent_per_week')->first();
                }else{
                    $res[$k]->rent_fee = BusinessContract::where('contract_id',$v->id)->pluck('month_rent')->first();
                }
                $total_arrears += $v->arrears_fee;
                $total_need_pay_fee += $v->need_pay_fee;
                $total_pay_fee += $v->pay_fee;
            }
            $data['contract_list'] = $res;
            $data['total_arrears'] = $total_arrears;
            $data['total_pay_fee'] = $total_pay_fee;
            $data['total_need_pay_fee'] = $total_need_pay_fee;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/10);
            return $this->success('get contract list success',$data);
        }
    }

    /**
     * @description:获取房屋列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHouseList(array $input)
    {
        $user_id = $input['user_id'];
        if(isset($input['operator_id'])){
            $operator_id = $input['operator_id'];
            $room_list = OperatorRoom::where('operator_id',$operator_id)->pluck('house_id');
            $res = RentHouse::whereIn('id',$room_list)->get();
        }else{
            $res = RentHouse::where('user_id',$user_id)->get();
        }
        foreach ($res as $k => $v){
            $data[$k]['rent_house_id'] = $v->id;
            $data[$k]['house_name'] = $v->property_name.$v->room_name;
        }
        return $this->success('get tenement note success',$data);
    }

}