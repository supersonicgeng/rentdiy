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
use App\Model\InspectCheck;
use App\Model\Level;
use App\Model\LookHouse;
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
            if (@$input['property_name'] and @$input['property_name'] != '') {
                $property_name = @$input['property_name'];
                $query->where('h.property_name','like', '%'.$property_name.'%');
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
            foreach ($res as $k => $v){
                if($v->contract_type == 1){
                    $res[$k]->rent_fee = EntireContract::where('contract_id',$v->id)->pluck('rent_per_week')->first();
                    $res[$k]->arrears = RentArrears::where('contract_id',$v->id)->where('arrears_type','!=',4)->sum('arrears_fee');
                    $res[$k]->rent = RentArrears::where('contract_id',$v->id)->where('arrears_type','!=',4)->sum('need_pay_fee');
                }elseif ($v->contract_type == 2 || $v->contract_type == 3){
                    $res[$k]->rent_fee = SeparateContract::where('contract_id',$v->id)->pluck('rent_per_week')->first();
                    $res[$k]->arrears = RentArrears::where('contract_id',$v->id)->where('arrears_type','!=',4)->sum('arrears_fee');
                    $res[$k]->rent = RentArrears::where('contract_id',$v->id)->where('arrears_type','!=',4)->sum('need_pay_fee');
                }else{
                    $res[$k]->rent_fee = BusinessContract::where('contract_id',$v->id)->pluck('month_rent')->first();
                    $res[$k]->arrears = RentArrears::where('contract_id',$v->id)->where('arrears_type','!=',4)->sum('arrears_fee');
                    $res[$k]->rent = RentArrears::where('contract_id',$v->id)->where('arrears_type','!=',4)->sum('need_pay_fee');
                }
            }
            $data['contract_list'] = $res;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/10);
            return $this->success('get contract list success',$data);
        }
    }
}