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
use App\Model\Inspect;
use App\Model\InspectRoom;
use App\Model\Landlord;
use App\Model\LandlordOrder;
use App\Model\LandlordOrderScore;
use App\Model\Level;
use App\Model\LookHouse;
use App\Model\Operator;
use App\Model\Order;
use App\Model\Passport;
use App\Model\PassportReward;
use App\Model\PassportStore;
use App\Model\Plant;
use App\Model\PlantOperateLog;
use App\Model\Providers;
use App\Model\ProvidersCompanyPic;
use App\Model\ProvidersCompanyPromoPic;
use App\Model\Region;
use App\Model\RentApplication;
use App\Model\RentHouse;
use App\Model\RentPic;
use App\Model\RouteItems;
use App\Model\ScoreLog;
use App\Model\ServiceIntroduce;
use App\Model\SignLog;
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

class ProvidersService extends CommonService
{
    /**
     * @description:房东增加房东信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function addProvidersInformation(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role != 2 && $user_info->user_role != 3 && $user_info->user_role != 6 && $user_info->user_role != 7  ){
            return $this->error('2','this account is not a provider role');
        }else{
            $provider_info = Providers::where('user_id',$input['user_id'])->count();
            if($provider_info >= 3){
                return $this->error('3','you only can add 3 provider information');
            }else{
                $provider_data = [
                    'user_id'       => $input['user_id'],
                    'providers_sn'  => providersId(),
                    'headimg'       => $input['headimg'],
                    'service_name'  => $input['service_name'],
                    'jobs'          => implode(',',$input['jobs']),
                    'first_name'    => $input['first_name'],
                    'middle_name'   => $input['middle_name'],
                    'last_name'     => $input['last_name'],
                    'tax_no'        => $input['tax_no'],
                    /*'mobile'        => $input['mobile'],*/
                    'phone'         => $input['phone'],
                    'email'         => $input['email'],
                    'address'       => $input['address'],
                    'mail_address'  => $input['mail_address'],
                    'mail_code'     => $input['mail_code'],
                    'bank_account'  => $input['bank_account'],
                    'license_no'    => $input['license_no'],
                    'logo'          => $input['logo'],
                    'about_us'      => $input['about_us'],
                    'created_at'    => date('Y-m-d H:i:s',time()),
                ];
                $model = new Providers();
                $res = $model->insertGetId($provider_data);
                if(!$res){
                    return $this->error('4','provider information add failed');
                }else{
                    static $error = 0;
                    $service_company_pic = $input['service_company_pic'];
                    foreach ($service_company_pic as $k => $v){
                        $service_company_pic_data = [
                            'service_id'    => $res,
                            'company_pic'   => $v,
                            'created_at'    => date('Y-m-d H:i:s',time()),
                        ];
                        $add_company_res = ProvidersCompanyPic::insert($service_company_pic_data);
                        if(!$add_company_res) {
                            $error += 1;
                        }
                    }
                    $service_company_promo_pic = $input['service_company_promo_pic'];
                    foreach ($service_company_promo_pic as $key => $value){
                        $service_company_promo_pic_data = [
                            'service_id'        => $res,
                            'company_promo_pic' => $value,
                            'created_at'        => date('Y-m-d H:i:s',time()),
                        ];
                        $add_company_promo_res = ProvidersCompanyPromoPic::insert($service_company_promo_pic_data);
                        if(!$add_company_promo_res){
                            $error += 1;
                        }
                    }
                    $service_introduce = $input['service_introduce'];
                    foreach ($service_introduce as $keys => $values){
                        $service_introduce_data = [
                            'service_id'    => $res,
                            'service_name'  => $values['service_name'],
                            'price'         => $values['price'],
                            'is_gts'        => $values['is_gts'],
                            'details'       => $values['details'],
                            'created_at'    => date('Y-m-d H:i:s',time()),
                        ];
                        $service_introduce_res = ServiceIntroduce::insert($service_introduce_data);
                        if(!$service_introduce_res){
                            $error += 1;
                        }
                    }
                    if(!$error){
                        return $this->success('provider information add success',$res);
                    }else{
                        return $this->error('5','pic save failed');
                    }
                }
            }
        }
    }


    /**
     * @description:房东编辑房东信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function editProvidersInformation(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role != 2 && $user_info->user_role != 3 && $user_info->user_role != 6 && $user_info->user_role != 7  ){
            return $this->error('2','this account is not a provider role');
        }else{
            $provider_info = Providers::where('user_id',$input['user_id'])->where('id',$input['service_id'])->first();
            if(!$provider_info){
                return $this->error('3','you can not edit this provider information');
            }else{
                $provider_data = [
                    'headimg'       => @$input['headimg']?$input['headimg']:$provider_info->headimg,
                    'service_name'  => @$input['service_name']?$input['service_name']:$provider_info->service_name,
                    'jobs'          => @$input['jobs']?implode(',',$input['jobs']):$provider_info->jobs,
                    'first_name'    => @$input['first_name']?$input['first_name']:$provider_info->first_name,
                    'middle_name'   => @$input['middle_name']?$input['middle_name']:$provider_info->middle_name,
                    'last_name'     => @$input['last_name']?$input['last_name']:$provider_info->last_name,
                    'tax_no'        => @$input['tax_no']?$input['tax_no']:$provider_info->tax_no,
                    /*'mobile'        => @$input['mobile']?$input['mobile']:$provider_info->mobile,*/
                    'phone'         => @$input['phone']?$input['phone']:$provider_info->phone,
                    'email'         => @$input['email']?$input['email']:$provider_info->email,
                    'address'       => @$input['address']?$input['address']:$provider_info->address,
                    'mail_address'  => @$input['mail_address']?$input['mail_address']:$provider_info->mail_address,
                    'mail_code'     => @$input['mail_code']?$input['mail_code']:$provider_info->mail_code,
                    'bank_account'  => @$input['bank_account']?$input['bank_account']:$provider_info->bank_account,
                    'license_no'    => @$input['license_no']?$input['license_no']:$provider_info->license_no,
                    'logo'          => @$input['logo']?$input['logo']:$provider_info->logo,
                    'about_us'      => @$input['about_us']?$input['about_us']:$provider_info->about_us,
                    'updated_at'    => date('Y-m-d H:i:s',time()),
                ];
                $model = new Providers();
                $res = $model->where('id',$input['service_id'])->update($provider_data);
                if(!$res){
                    return $this->error('4','provider information add failed');
                }else{
                    static $error = 0;
                    ProvidersCompanyPic::where('service_id',$input['service_id'])->update(['deleted_at'=>date('Y-m-d H:i:s',time())]);
                    ProvidersCompanyPromoPic::where('service_id',$input['service_id'])->update(['deleted_at'=>date('Y-m-d H:i:s',time())]);
                    ServiceIntroduce::where('service_id',$input['service_id'])->update(['deleted_at'=>date('Y-m-d H:i:s',time())]);
                    $service_company_pic = $input['service_company_pic'];
                    foreach ($service_company_pic as $k => $v){
                        $service_company_pic_data = [
                            'service_id'    => $input['service_id'],
                            'company_pic'   => $v,
                            'created_at'    => date('Y-m-d H:i:s',time()),
                        ];
                        $add_company_res = ProvidersCompanyPic::insert($service_company_pic_data);
                        if(!$add_company_res) {
                            $error += 1;
                        }
                    }
                    $service_company_promo_pic = $input['service_company_promo_pic'];
                    foreach ($service_company_promo_pic as $key => $value){
                        $service_company_promo_pic_data = [
                            'service_id'        => $input['service_id'],
                            'company_promo_pic' => $value,
                            'created_at'        => date('Y-m-d H:i:s',time()),
                        ];
                        $add_company_promo_res = ProvidersCompanyPromoPic::insert($service_company_promo_pic_data);
                        if(!$add_company_promo_res){
                            $error += 1;
                        }
                    }
                    $service_introduce = $input['service_introduce'];
                    foreach ($service_introduce as $keys => $values){
                        $service_introduce_data = [
                            'service_id'    => $input['service_id'],
                            'service_name'  => $values['service_name'],
                            'price'         => $values['price'],
                            'is_gts'        => $values['is_gts'],
                            'details'       => $values['details'],
                            'created_at'    => date('Y-m-d H:i:s',time()),
                        ];
                        $service_introduce_res = ServiceIntroduce::insert($service_introduce_data);
                        if(!$service_introduce_res){
                            $error += 1;
                        }
                    }
                    if(!$error){
                        return $this->success('provider information edit success');
                    }else{
                        return $this->error('5','pic edit failed');
                    }
                }
            }
        }
    }



    /**
     * @description:服务商获得自己的服务商列表信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProvidersSelfList(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role != 2 && $user_info->user_role != 3 && $user_info->user_role != 6 && $user_info->user_role != 7  ){
            return $this->error('2','this account is not a provider role');
        }else{
            $provider_list = Providers::where('user_id',$input['user_id'])->where('deleted_at',null)->get()->toArray();
            foreach ($provider_list as $k => $v){
                $provider_list[$k]['jobs'] = explode(',',$provider_list[$k]['jobs']);
                $provider_list[$k]['service_company_pic'] = ProvidersCompanyPic::where('service_id',$v['id'])->where('deleted_at',null)->pluck('company_pic')->toArray(); // 公司图片
                $provider_list[$k]['service_company_promo_pic'] = ProvidersCompanyPromoPic::where('service_id',$v['id'])->where('deleted_at',null)->pluck('company_promo_pic')->toArray(); // 公司图片
                $provider_list[$k]['service_introduce'] = ServiceIntroduce::where('service_id',$v['id'])->where('deleted_at',null)->get()->toArray(); // 公司图片
            }
            if(!$provider_list){
                return $this->error('3','you not add a  providers information');
            }else{
                $data['provider_list'] = $provider_list;
                return $this->success('get providers success',$data);
            }
        }
    }


    /**
     * @description:服务商获得服务商信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProvidersInformation(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role != 2 && $user_info->user_role != 3 && $user_info->user_role != 6 && $user_info->user_role != 7  ){
            return $this->error('2','this account is not a provider role');
        }else{
            $provider_info = Providers::where('user_id',$input['user_id'])->where('id',$input['service_id'])->first();
            if(!$provider_info){
                return $this->error('3','you not add a  providers information');
            }else{
                $provider_info['jobs'] = explode(',',$provider_info['jobs']);
                $provider_info['service_company_pic'] = ProvidersCompanyPic::where('service_id',$input['service_id'])->where('deleted_at',null)->pluck('company_pic')->toArray(); // 公司图片
                $provider_info['service_company_promo_pic'] = ProvidersCompanyPromoPic::where('service_id',$input['service_id'])->where('deleted_at',null)->pluck('company_promo_pic')->toArray(); // 公司宣传图片
                $provider_info['service_introduce'] = ServiceIntroduce::where('service_id',$input['service_id'])->where('deleted_at',null)->get()->toArray();
                return $this->success('get providers success',$provider_info);
            }
        }
    }


    /**
     * @description:删除服务商主体
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteProvidersInformation(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role != 2 && $user_info->user_role != 3 && $user_info->user_role != 6 && $user_info->user_role != 7  ){
            return $this->error('2','this account is not a provider role');
        }else{
            $res = Providers::where('user_id',$input['user_id'])->where('id',$input['service_id'])->update('deleted_at',date('Y-m-d H:i:s',time()));
            if(!$res){
                return $this->error('3','delete a  providers information failed');
            }else{
                ProvidersCompanyPic::where('service_id',$input['service_id'])->update('deleted_at',date('Y-m-d H:i:s',time())); // 删除公司图片
                ProvidersCompanyPromoPic::where('service_id',$input['service_id'])->update('deleted_at',date('Y-m-d H:i:s',time())); // 删除公司宣传图片
                return $this->success('deleted providers success');
            }
        }
    }


    /**
     * @description:服务商已接订单列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderList(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role != 2 && $user_info->user_role != 3 && $user_info->user_role != 6 && $user_info->user_role != 7  ){
            return $this->error('2','this account is not a provider role');
        }else{
            $service_ids = Providers::where('user_id',$input['user_id'])->pluck('id');
            $model = new LandlordOrder();
            $model = $model->whereIn('providers_id',$service_ids);
            $start_date = $input['start_date'];
            if($start_date){
                $model = $model->where('end_time','>',$start_date);
            }
            $end_date = $input['end_date'];
            if($end_date){
                $model = $model->where('end_time','<',$end_date);
            }
            $page = $input['page'];
            $count = $model->count();
            if($count < ($page-1)*10){
                return $this->error('3','no more order info');
            }
            $res = $model->offset(($page-1)*10)->limit(10)->get()->toArray();
            static $amount = 0;
            foreach($res as $k=>$v){
                $res[$k]['customer'] = Landlord::where('user_id',$v['user_id'])->pluck('landlord_name')->first();
                $res[$k]['room_name'] = RentHouse::where('id',$v['rent_house_id'])->pluck('room_name')->first();
                $amount += $v['budget'];
            }
            $data['order_list'] = $res;
            $data['total_amount'] = $amount;
            $data['total_page'] = ceil($count/10);
            $data['current_page'] = $page;
            if($res){
                return $this->success('get order list success',$data);
            }else{
                return $this->error('3','get order list failed');
            }
        }
    }



    /**
     * @description:服务商已接看房订单列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLookOrderList(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role != 2 && $user_info->user_role != 3 && $user_info->user_role != 6 && $user_info->user_role != 7  ){
            return $this->error('2','this account is not a provider role');
        }else{
            $service_ids = Providers::where('user_id',$input['user_id'])->pluck('id');
            $model = new LandlordOrder();
            $model = $model->whereIn('providers_id',$service_ids);
            $model = $model->where('order_type',1);
            $model = $model->where('order_status',2);
            $model = $model->groupBy('rent_house_id');
            $page = $input['page'];
            $count = $model->get()->toArray();
            $count = count($count);
            if($count < ($page-1)*5){
                return $this->error('3','no more order info');
            }
            $res = $model->offset(($page-1)*5)->limit(5)->select('rent_house_id')->get()->toArray();
            foreach($res as $k=>$v){
                $house_info[$k] = RentHouse::where('id',$v['rent_house_id'])->select('id','rent_category','property_name','property_type','address','available_time','rent_fee_pre_week','rent_least_fee','bedroom_no','bathroom_no','parking_no','garage_no','District','TA','Region','available_date','require_renter')->first()->toArray();
                $house_info[$k]['house_pic'] = RentPic::where('rent_house_id',$v['rent_house_id'])->where('deleted_at',null)->pluck('house_pic')->toArray();// 图片
                $house_info[$k]['full_address'] = $house_info[$k]['address'].','.Region::getName($house_info[$k]['District']).','.Region::getName($house_info[$k]['TA']).','.Region::getName($house_info[$k]['Region']); //地址
            }
            if(!@$house_info){
                return $this->error('4','no data');
            }
            $data['house_info'] = $house_info;
            $data['total_page'] = ceil($count/5);
            $data['current_page'] = $page;
            if($res){
                return $this->success('get house list success',$data);
            }else{
                return $this->error('3','get house list failed');
            }
        }
    }



    /**
     * @description:服务商已接租户调查订单列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTenementOrderList(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role != 2 && $user_info->user_role != 3 && $user_info->user_role != 6 && $user_info->user_role != 7  ){
            return $this->error('2','this account is not a provider role');
        }else{
            $service_ids = Providers::where('user_id',$input['user_id'])->pluck('id');
            $model = new LandlordOrder();
            $model = $model->whereIn('providers_id',$service_ids);
            $model = $model->where('order_type',2);
            $model = $model->where('order_status',2);
            $model = $model->groupBy('rent_house_id');
            $page = $input['page'];
            $count = $model->get()->toArray();
            $count = count($count);
            if($count < ($page-1)*5){
                return $this->error('3','no more order info');
            }
            $res = $model->offset(($page-1)*5)->limit(5)->select('rent_house_id')->get()->toArray();
            foreach($res as $k=>$v){
                $house_info[$k] = RentHouse::where('id',$v['rent_house_id'])->select('id','rent_category','property_name','property_type','address','available_time','rent_fee_pre_week','rent_least_fee','bedroom_no','bathroom_no','parking_no','garage_no','District','TA','Region','available_date','require_renter')->first()->toArray();
                $house_info[$k]['house_pic'] = RentPic::where('rent_house_id',$v['rent_house_id'])->where('deleted_at',null)->pluck('house_pic')->toArray();// 图片
                $house_info[$k]['full_address'] = $house_info[$k]['address'].','.Region::getName($house_info[$k]['District']).','.Region::getName($house_info[$k]['TA']).','.Region::getName($house_info[$k]['Region']); //地址
            }
            if(!@$house_info){
                return $this->error('4','no data');
            }
            $data['house_info'] = $house_info;
            $data['total_page'] = ceil($count/5);
            $data['current_page'] = $page;
            if($res){
                return $this->success('get house list success',$data);
            }else{
                return $this->error('3','get house list failed');
            }
        }
    }



    /**
     * @description:服务商已接房屋检查订单列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInspectOrderList(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role != 2 && $user_info->user_role != 3 && $user_info->user_role != 6 && $user_info->user_role != 7  ){
            return $this->error('2','this account is not a provider role');
        }else{
            $service_ids = Providers::where('user_id',$input['user_id'])->pluck('id');
            $model = new LandlordOrder();
            $model = $model->whereIn('providers_id',$service_ids);
            $model = $model->where('order_type',3);
            $model = $model->where('order_status',2);
            /*$model = $model->groupBy('rent_house_id');*/
            $page = $input['page'];
            $count = $model->get()->toArray();
            $count = count($count);
            if($count < ($page-1)*5){
                return $this->error('3','no more order info');
            }
            $res = $model->offset(($page-1)*5)->limit(5)->select('rent_house_id','inspect_id')->get()->toArray();
            foreach($res as $k=>$v){
                $house_info[$k] = RentHouse::where('id',$v['rent_house_id'])->select('id','rent_category','property_name','address','bedroom_no','bathroom_no','parking_no','garage_no','District','TA','Region','available_date','require_renter')->first()->toArray();
                $house_info[$k]['full_address'] = $house_info[$k]['address'].','.Region::getName($house_info[$k]['District']).','.Region::getName($house_info[$k]['TA']).','.Region::getName($house_info[$k]['Region']); //地址
                $house_info[$k]['inspect_info'] = Inspect::where('id',$v['inspect_id'])->first();
            }
            if(!@$house_info){
                return $this->error('4','no data');
            }
            $data['house_info'] = $house_info;
            $data['total_page'] = ceil($count/5);
            $data['current_page'] = $page;
            if($res){
                return $this->success('get house list success',$data);
            }else{
                return $this->error('3','get house list failed');
            }
        }
    }



    /**
     * @description:服务商已接维修订单列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRepairOrderList(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role != 2 && $user_info->user_role != 3 && $user_info->user_role != 6 && $user_info->user_role != 7  ){
            return $this->error('2','this account is not a provider role');
        }else{
            $service_ids = Providers::where('user_id',$input['user_id'])->pluck('id');
            $model = new LandlordOrder();
            $model = $model->whereIn('providers_id',$service_ids);
            $model = $model->where('order_type',4);
            $model = $model->where('order_status',2);
            /*$model = $model->groupBy('rent_house_id');*/
            $page = $input['page'];
            $count = $model->get()->toArray();
            $count = count($count);
            if($count < ($page-1)*5){
                return $this->error('3','no more order info');
            }
            $res = $model->offset(($page-1)*5)->limit(5)->select('rent_house_id','group_id')->get()->toArray();
            foreach($res as $k=>$v){
                $house_info[$k] = RentHouse::where('id',$v['rent_house_id'])->select('id','rent_category','property_name','property_type','address','available_time','rent_fee_pre_week','rent_least_fee','bedroom_no','bathroom_no','parking_no','garage_no','District','TA','Region','available_date','require_renter')->first()->toArray();
                $house_info[$k]['house_pic'] = RentPic::where('rent_house_id',$v['rent_house_id'])->where('deleted_at',null)->pluck('house_pic')->toArray();// 图片
                $house_info[$k]['full_address'] = $house_info[$k]['address'].','.Region::getName($house_info[$k]['District']).','.Region::getName($house_info[$k]['TA']).','.Region::getName($house_info[$k]['Region']); //地址
                $house_info[$k]['order_group_id'] = $v['group_id'];
            }
            if(!@$house_info){
                return $this->error('4','no data');
            }
            $data['house_info'] = $house_info;
            $data['total_page'] = ceil($count/5);
            $data['current_page'] = $page;
            if($res){
                return $this->success('get house list success',$data);
            }else{
                return $this->error('3','get house list failed');
            }
        }
    }



    /**
     * @description:服务商已接房屋诉讼订单列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLitigationOrderList(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role != 2 && $user_info->user_role != 3 && $user_info->user_role != 6 && $user_info->user_role != 7  ){
            return $this->error('2','this account is not a provider role');
        }else{
            $service_ids = Providers::where('user_id',$input['user_id'])->pluck('id');
            $model = new LandlordOrder();
            $model = $model->whereIn('providers_id',$service_ids);
            $model = $model->where('order_type',5);
            $model = $model->where('order_status',2);
            $model = $model->groupBy('rent_house_id');
            $page = $input['page'];
            $count = $model->get()->toArray();
            $count = count($count);
            if($count < ($page-1)*5){
                return $this->error('3','no more order info');
            }
            $res = $model->offset(($page-1)*5)->limit(5)->select('rent_house_id','group_id')->get()->toArray();
            foreach($res as $k=>$v){
                $house_info[$k] = RentHouse::where('id',$v['rent_house_id'])->select('id','rent_category','property_name','property_type','address','available_time','rent_fee_pre_week','rent_least_fee','bedroom_no','bathroom_no','parking_no','garage_no','District','TA','Region','available_date','require_renter')->first()->toArray();
                $house_info[$k]['house_pic'] = RentPic::where('rent_house_id',$v['rent_house_id'])->where('deleted_at',null)->pluck('house_pic')->toArray();// 图片
                $house_info[$k]['full_address'] = $house_info[$k]['address'].','.Region::getName($house_info[$k]['District']).','.Region::getName($house_info[$k]['TA']).','.Region::getName($house_info[$k]['Region']); //地址
                $house_info[$k]['order_group_id'] = $v['group_id'];
            }
            if(!@$house_info){
                return $this->error('4','no data');
            }
            $data['house_info'] = $house_info;
            $data['total_page'] = ceil($count/5);
            $data['current_page'] = $page;
            if($res){
                return $this->success('get house list success',$data);
            }else{
                return $this->error('3','get house list failed');
            }
        }
    }

    /**
     * @description:服务商已接房屋诉讼订单列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLookOrderDetail(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role != 2 && $user_info->user_role != 3 && $user_info->user_role != 6 && $user_info->user_role != 7  ){
            return $this->error('2','this account is not a provider role');
        }else{
            $service_ids = Providers::where('user_id',$input['user_id'])->select('id')->get();
            $model = new LandlordOrder();
            $model = $model->whereIn('providers_id',$service_ids);
            $model = $model->where('order_type',1);
            $model = $model->where('order_status',2);
            $model = $model->where('rent_house_id',$input['rent_house_id']);
            $res = $model->select('rent_application_id')->get()->toArray();
            $count = count($res);
            $page = $input['page'];
            if($count < ($page-1)*5){
                return $this->error('3','no more application');
            }
            foreach($res as $k=>$v){
                $appliction_data[$k] = RentApplication::where('id',$v['rent_application_id'])->first();
                $tenement_info = Tenement::where('id', $appliction_data[$k]['tenement_id'])->first();
                $appliction_data[$k]['tenement_name'] = $tenement_info['first_name'].' '.$tenement_info['middle_name'].' '.$tenement_info['last_name'];
                $appliction_data[$k]['tenement_headimg'] = $tenement_info['headimg'];
            }
            if(!@$appliction_data){
                return $this->error('4','no data');
            }
            $data['application_list'] = $appliction_data;
            $data['total_page'] = ceil($count/5);
            $data['current_page'] = $page;
            if($res){
                return $this->success('get application list success',$data);
            }else{
                return $this->error('4','get application list failed');
            }
        }
    }


    /**
     * @description:服务商已接房屋诉讼订单列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTenementOrderDetail(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role != 2 && $user_info->user_role != 3 && $user_info->user_role != 6 && $user_info->user_role != 7  ){
            return $this->error('2','this account is not a provider role');
        }else{
            $service_ids = Providers::where('user_id',$input['user_id'])->select('id')->get();
            $model = new LandlordOrder();
            $model = $model->whereIn('providers_id',$service_ids);
            $model = $model->where('order_type',2);
            $model = $model->where('order_status',2);
            $model = $model->where('rent_house_id',$input['rent_house_id']);
            $res = $model->select('rent_application_id')->get()->toArray();
            $count = count($res);
            $page = $input['page'];
            if($count < ($page-1)*5){
                return $this->error('3','no more application');
            }
            foreach($res as $k=>$v){
                $appliction_data[$k] = RentApplication::where('id',$v['rent_application_id'])->first();
                $tenement_info = Tenement::where('id', $appliction_data[$k]['tenement_id'])->first();
                $appliction_data[$k]['tenement_name'] = $tenement_info['first_name'].' '.$tenement_info['middle_name'].' '.$tenement_info['last_name'];
                $appliction_data[$k]['tenement_headimg'] = $tenement_info['headimg'];
            }
            if(!@$appliction_data){
                return $this->error('4','no data');
            }
            $data['application_list'] = $appliction_data;
            $data['total_page'] = ceil($count/5);
            $data['current_page'] = $page;
            if($res){
                return $this->success('get application list success',$data);
            }else{
                return $this->error('4','get application list failed');
            }
        }
    }


    /**
     * @description:服务商已接房屋诉讼订单列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRepairOrderDetail(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role != 2 && $user_info->user_role != 3 && $user_info->user_role != 6 && $user_info->user_role != 7  ){
            return $this->error('2','this account is not a provider role');
        }else{
            $service_ids = Providers::where('user_id',$input['user_id'])->select('id')->get();
            $model = new LandlordOrder();
            $model = $model->whereIn('providers_id',$service_ids);
            $model = $model->where('order_type',4);
            /*$model = $model->where('order_status',2);*/
            $model = $model->where('rent_house_id',$input['rent_house_id']);
            $model = $model->where('group_id',$input['order_group_id']);
            $res = $model->select('issue_id','group_id')->get()->toArray();
            foreach($res as $k=>$v){
                $issue_data[$k] = InspectRoom::where('id',$v['issue_id'])->first();
            }
            if(!@$issue_data){
                return $this->error('4','no data');
            }
            $data['repair_list'] = $issue_data;
            $data['order_info'] = $model->where('group_id',$res[0]['group_id'])->select('order_name','jobs','requirement')->first()->toArray();
            if($res){
                return $this->success('get repair list success',$data);
            }else{
                return $this->error('4','get repair list failed');
            }
        }
    }

    /**
     * @description:服务商已接房屋诉讼订单列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLitigationOrderDetail(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role != 2 && $user_info->user_role != 3 && $user_info->user_role != 6 && $user_info->user_role != 7  ){
            return $this->error('2','this account is not a provider role');
        }else{
            $service_ids = Providers::where('user_id',$input['user_id'])->select('id')->get();
            $model = new LandlordOrder();
            $model = $model->whereIn('providers_id',$service_ids);
            $model = $model->where('order_type',5);
            $model = $model->where('order_status',2);
            $model = $model->where('rent_house_id',$input['rent_house_id']);
            $res = $model->select('rent_contract_id')->get()->toArray();
            $count = count($res);
            $page = $input['page'];
            if($count < ($page-1)*5){
                return $this->error('3','no more application');
            }
            foreach($res as $k=>$v){
                $appliction_data[$k] = RentApplication::where('id',$v['rent_application_id'])->first();
                $tenement_info = Tenement::where('id', $appliction_data[$k]['tenement_id'])->first();
                $appliction_data[$k]['tenement_name'] = $tenement_info['first_name'].' '.$tenement_info['middle_name'].' '.$tenement_info['last_name'];
                $appliction_data[$k]['tenement_headimg'] = $tenement_info['headimg'];
            }
            if(!@$appliction_data) {
                return $this->error('4', 'no data');
            }
            $data['application_list'] = $appliction_data;
            $data['total_page'] = ceil($count/5);
            $data['current_page'] = $page;
            if($res){
                return $this->success('get application list success',$data);
            }else{
                return $this->error('4','get application list failed');
            }
        }
    }


    /**
     * @description:完成租客调查
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function tenementReview(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role != 2 && $user_info->user_role != 3 && $user_info->user_role != 6 && $user_info->user_role != 7  ){
            return $this->error('2','this account is not a provider role');
        }else{
            $application_status = $input['application_status'];
            $res = RentApplication::where('id',$input['rent_application_id'])->update(['application_status'=>$application_status,'updated_at'=>date('Y-m-d H:i:s',time())]);
            if($res){
                // 更改订单状态
                LandlordOrder::where('rent_application_id',$input['rent_application_id'])->where('order_type',2)->update(['order_status'=>3,'updated_at'=>date('Y-m-d H:i:s',time())]);
                return $this->success('tenement review success');
            }else{
                return $this->error('3','tenement review failed');
            }
        }
    }


    /**
     * @description:完成看房调查
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function lookOrder(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role != 2 && $user_info->user_role != 3 && $user_info->user_role != 6 && $user_info->user_role != 7  ){
            return $this->error('2','this account is not a provider role');
        }else{
            $operator_id = $input['operator_id'];
            if(!$operator_id){
                $check_name = Operator::where('id',$operator_id)->pluck('operator_name')->first();
            }else{
                $providers_id = LandlordOrder::where('rent_application_id',$input['rent_application_id'])->where('order_type',1)->pluck('providers_id')->first();
                $check_name = Providers::where('id',$providers_id)->pluck('first_name')->first();
            }
            $look_data = [
                'rent_application_id'   => $input['rent_application_id'],
                'recommendation_score'  => $input['recommendation_score'],
                'look_note'             => $input['look_note'],
                'upload_url'            => $input['upload_url'],
                'check_name'            => $check_name,
            ];
            $res = LookHouse::insert($look_data);
            if($res){
                // 更改订单状态
                LandlordOrder::where('rent_application_id',$input['rent_application_id'])->where('order_type',1)->update(['order_status'=>3,'updated_at'=>date('Y-m-d H:i:s',time())]);
                return $this->success('look house success');
            }else{
                return $this->error('3','look house failed');
            }
        }
    }

    /**
     * @description:服务商给房东打分
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function landlordScore(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role != 2 && $user_info->user_role != 3 && $user_info->user_role != 6 && $user_info->user_role != 7  ){
            return $this->error('2','this account is not a provider role');
        }else{
            $order_id = $input['order_id'];
            $order_res = LandlordOrderScore::where('order_id',$order_id)->first();
            if($order_res){
                return $this->error('3','this order already scored');
            }
            $score_data = [
                'order_id'          => $order_id,
                'landlord_user_id'  => LandlordOrder::where('id',$order_id)->pluck('user_id')->first(),
                'providers_id'      => LandlordOrder::where('id',$order_id)->pluck('providers_id')->first(),
                'community_score'   => $input['community_score'],
                'pay_score'         => $input['pay_score'],
                'score_note'        => $input['score_note'],
                'created_at'        => date('Y-m-d H:i:s',time()),
            ];
            $score_res = LandlordOrderScore::insert($score_data);
            if($score_res){
                return $this->success('score success');
            }else{
                return $this->error('4','score failed');
            }
        }
    }
}