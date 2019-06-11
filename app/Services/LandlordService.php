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
use App\Model\ContractTenement;
use App\Model\Driver;
use App\Model\DriverTakeOver;
use App\Model\Landlord;
use App\Model\LandlordOrder;
use App\Model\Level;
use App\Model\Order;
use App\Model\Passport;
use App\Model\PassportReward;
use App\Model\PassportStore;
use App\Model\Plant;
use App\Model\PlantOperateLog;
use App\Model\Providers;
use App\Model\ProvidersScore;
use App\Model\RentContract;
use App\Model\RentHouse;
use App\Model\RouteItems;
use App\Model\ScoreLog;
use App\Model\SignLog;
use App\Model\SysSign;
use App\Model\Tender;
use App\Model\Tenement;
use App\Model\TenementNote;
use App\Model\UserEvaluate;
use App\Model\UserEvaluateTag;
use App\Model\Verify;
use App\Model\VerifyLog;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LandlordService extends CommonService
{
    /**
     * @description:房东增加房东信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function addLandlordInformation(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if(!$user_info->user_role %2 ){
            return $this->error('2','this account is not a landlord role');
        }else{
            $landlord_info = Landlord::where('user_id',$input['user_id'])->where('deleted_at',null)->count();
            if($landlord_info >= 11){
                return $this->error('3','you only can add 11 landlord information');
            }else{
                $landlord_data = [
                    'user_id'           => $input['user_id'],
                    'landlord_name'     => $input['landlord_name'],
                    'landlord_sn'       => landlordId(),
                    'property_address'  => $input['property_address'],
                    'headimg'           => $input['headimg'],
                    'first_name'        => $input['first_name'],
                    'middle_name'       => $input['middle_name'],
                    'last_name'         => $input['last_name'],
                    'tax_no'            => $input['tax_no'],
                    'mobile'            => $input['mobile'],
                    'hm'                => $input['hm'],
                    'wk'                => $input['wk'],
                    'phone'             => $input['phone'],
                    'email'             => $input['email'],
                    'mail_address'      => $input['mail_address'],
                    'mail_code'         => $input['mail_code'],
                    'bank_account'      => $input['bank_account'],
                    'notice'            => $input['notice'],
                    'created_at'        => date('Y-m-d H:i:s',time()),
                ];
                $model = new Landlord();
                $res = $model->insertGetId($landlord_data);
                if(!$res){
                    return $this->error('4','landlord information add failed');
                }else{
                    return $this->success('landlord information add success',$res);
                }
            }
        }
    }


    /**
     * @description:房东修改房东信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function editLandlordInformation(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if(!$user_info->user_role %2 ){
            return $this->error('2','this account is not a landlord role');
        }else{
            $landlord_info = Landlord::where('user_id',$input['user_id'])->where('id',$input['landlord_id'])->first();
            if(!$landlord_info){
                return $this->error('3','you the landlord information is not ture');
            }else{
                $landlord_data = [
                    'landlord_name'     => @$input['landlord_name']?$input['landlord_name']:$landlord_info->landlord_name,
                    'property_address'  => @$input['property_address']?$input['property_address']:$landlord_info->property_address,
                    'headimg'           => @$input['headimg']?$input['headimg']:$landlord_info->headimg,
                    'first_name'        => @$input['first_name']?$input['first_name']:$landlord_info->first_name,
                    'middle_name'       => @$input['middle_name']?$input['middle_name']:$landlord_info->middle_name,
                    'last_name'         => @$input['last_name']?$input['last_name']:$landlord_info->last_name,
                    'tax_no'            => @$input['tax_no']?$input['tax_no']:$landlord_info->tax_no,
                    'mobile'            => @$input['mobile']?$input['mobile']:$landlord_info->mobile,
                    'phone'             => @$input['phone']?$input['phone']:$landlord_info->phone,
                    'hm'                => @$input['hm']?$input['hm']:$landlord_info->hm,
                    'wk'                => @$input['wk']?$input['wk']:$landlord_info->phone,
                    'email'             => @$input['email']?$input['email']:$landlord_info->email,
                    'mail_address'      => @$input['mail_address']?$input['mail_address']:$landlord_info->mail_address,
                    'mail_code'         => @$input['mail_code']?$input['mail_code']:$landlord_info->mail_code,
                    'bank_account'      => @$input['bank_account']?$input['bank_account']:$landlord_info->bank_account,
                    'notice'            => @$input['notice']?$input['notice']:$landlord_info->notice,
                    'updated_at'        => date('Y-m-d H:i:s',time()),
                ];
                $model = new Landlord();
                $res = $model->where('id',$input['landlord_id'])->update($landlord_data);
                if(!$res){
                    return $this->error('4','landlord information edit failed');
                }else{
                    return $this->success('landlord information edit success');
                }
            }
        }
    }

    /**
     * @description:房东获得房东列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLandlordList(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if(!$user_info->user_role %2 ){
            return $this->error('2','this account is not a landlord role');
        }else{
            $landlord_list = Landlord::where('user_id',$input['user_id'])->where('deleted_at',null)/*->select('id as landlord_id','landlord_name')*/->get()->toArray();
            if(!$landlord_list){
                return $this->error('3','you not add a landlord ');
            }else{
                $data['landlord_list'] = $landlord_list;
                return $this->success('get landlord success',$data);
            }
        }
    }


    /**
     * @description:房东获得房东列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLandlordInformation(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if(!$user_info->user_role %2 ){
            return $this->error('2','this account is not a landlord role');
        }else{
            $landlord_info = Landlord::where('user_id',$input['user_id'])->where('id',$input['landlord_id'])->first();
            if(!$landlord_info){
                return $this->error('3','you not add a land lord ');
            }else{
                return $this->success('get land lord success',$landlord_info);
            }
        }
    }


    /**
     * @description:房东获得房东列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteLandlordInformation(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if(!$user_info->user_role %2 ){
            return $this->error('2','this account is not a landlord role');
        }else{
            $res = Landlord::where('user_id',$input['user_id'])->where('id',$input['landlord_id'])->update('deleted_at',date('Y-m-d H:i:s',time()));
            if(!$res){
                return $this->error('3','deleted  land lord ');
            }else{
                return $this->success('delete land lord success');
            }
        }
    }


    /**
     * @description:房东报价列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderList(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if(!$user_info->user_role %2 ){
            return $this->error('2','this account is not a landlord role');
        }else{
            $model = new LandlordOrder();
            $page = $input['page'];
            $count = $model->where('user_id',$input['user_id'])->count();
            if($count < ($page-1)*10){
                return $this->error('2','no more order');
            }
            $sort_order = $input['sort_order'];
            if($sort_order == 2){
                $model = $model->orderBy('id','DESC');
            }
            $res = $model->where('user_id',$input['user_id'])->offset(($page-1)*10)->limit(10)->get()->toArray();
            foreach ($res as $k => $v){
                $res[$k]['property_name'] = RentHouse::where('id',$v['rent_house_id'])->pluck('property_name')->first();
            }
            $data['order_list'] = $res;
            $data['total_page'] = ceil($count/10);
            $data['current_page'] = $page;
            return $this->success('get order list success',$data);
        }
    }

    /**
     * @description:房东报价列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function tenderList(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if(!$user_info->user_role %2 ){
            return $this->error('2','this account is not a landlord role');
        }else{
            $model = new Tender();
            $model =  $model->where('order_id',$input['order_id']);
            if($input['sort_type'] == 1){
                $model = $model->orderBy('id','DESC');
            }elseif ($input['sort_type'] == 2){
                $model = $model->where('tender_status',3);
            }elseif ($input['sort_type'] == 3){
                $model = $model->where('tender_status',2);
            }elseif ($input['sort_type'] == 4){
                $model = $model->where('tender_status',1);
            }
            $count = $model->count();
            if($count < ($input['page']-1)*5){
                return $this->error('3','no more tender information');
            }
            $res = $model->offset(($input['page']-1)*5)->limit(5)->get()->toArray();
            foreach ($res as $k => $v){
                $res[$k]['providers_name']  = Providers::where('id',$v['service_id'])->pluck('service_name')->first();
                $res[$k]['quality_score']  = ProvidersScore::where('service_id',$v['service_id'])->avg('quality_score');
                $res[$k]['community_score']  = ProvidersScore::where('service_id',$v['service_id'])->avg('community_score');
                $res[$k]['money_score']  = ProvidersScore::where('service_id',$v['service_id'])->avg('money_score');
                $res[$k]['providers_name']  = Providers::where('id',$v['service_id'])->pluck('service_name')->first();
            }
            $data['tender_list'] = $res;
            $data['total_page'] = ceil($count/5);
            $data['current_page'] = $input['page'];
            return $this->success('get tender list success',$data);
        }
    }


    /**
     * @description:房东报价确认
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function tenderAccept(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if(!$user_info->user_role %2 ){
            return $this->error('2','this account is not a landlord role');
        }else{
            $order_id = $input['order_id'];
            $tender_id = $input['tender_id'];
            // 将所有的报价全部变成失效
            $res1 = Tender::where('order_id',$order_id)->update(['tender_status'=>2]);
            // 将报价的订单生效
            $res2 = Tender::where('id',$tender_id)->update(['tender_status'=>3]);
            // 将订单状态更改 增加服务商信息
            $order_change_data = [
                'order_status'  => 2,
                'providers_id'  => Tender::where('id',$tender_id)->pluck('service_id')->first(),
                'created_at'    => date('Y-m-d H:i:s',time()),
            ];
            $res3 = LandlordOrder::where('id',$order_id)->update($order_change_data);
            if($res1 && $res2 && $res3){
                return $this->success('order accept success');
            }else{
                return $this->error('3','order accept failed');
            }
        }
    }


    /**
     * @description:房东订单中止
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderStop(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if(!$user_info->user_role %2 ){
            return $this->error('2','this account is not a landlord role');
        }else{
            $order_id = $input['order_id'];
            // 修改订单状态
            $model = new LandlordOrder();
            $res = $model->where('id',$order_id)->update(['order_status'=>4,'updated_at'=>date('Y-m-d H:i:s',time())]);
            if($res){
                return $this->success('order stop success');
            }else{
                return $this->error('3','order stop failed');
            }
        }
    }


    /**
     * @description:获得租户列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTenementList(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if(!$user_info->user_role %2 ){
            return $this->error('2','this account is not a landlord role');
        }else{
            $contract_ids = RentContract::where('user_id',$input['user_id'])->where('contract_status','>','1')->pluck('id');
            dd($contract_ids);
            if(!$contract_ids){
                return $this->error('2','no tenement in contract');
            }
            $tenement_ids = ContractTenement::whereIn('contract_id',$contract_ids)->pluck('tenement_id');
            if(!$tenement_ids){
                return $this->error('2','no tenement in contract');
            }else{
                foreach ($tenement_ids as $k => $v){
                    $tenement_info[] = Tenement::where('id',$v)->select('id','headimg','first_name','middle_name','last_name','mobile','email','birthday','mail_address')->get();
                }
                $data['tenement_info'] = $tenement_info;
                return $this->success('get tenement list success',$data);
            }
        }
    }


    /**
     * @description:租户行为记录
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function tenementNote(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if(!$user_info->user_role %2 ){
            return $this->error('2','this account is not a landlord role');
        }else{
            $model = new TenementNote();
            $note_data = [
                'user_id'       => $input['user_id'],
                'tenement_id'   => $input['tenement_id'],
                'tenement_note' => $input['tenement_note'],
                'created_at'    => date('Y-m-d H:i:s',time()),
            ];
            $res = $model->insert($note_data);
            if($res){
                return $this->success('add tenement note success');
            }else{
                return $this->error('2','add tenement note failed');
            }
        }
    }
}