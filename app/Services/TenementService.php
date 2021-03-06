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
use App\Model\HouseScore;
use App\Model\Level;
use App\Model\Order;
use App\Model\Passport;
use App\Model\PassportReward;
use App\Model\PassportStore;
use App\Model\Plant;
use App\Model\PlantOperateLog;
use App\Model\RentArrears;
use App\Model\RentContract;
use App\Model\RentHouse;
use App\Model\RentPic;
use App\Model\RouteItems;
use App\Model\ScoreLog;
use App\Model\SignLog;
use App\Model\SysSign;
use App\Model\Tenement;
use App\Model\TenementCertificate;
use App\Model\UserEvaluate;
use App\Model\UserEvaluateTag;
use App\Model\Verify;
use App\Model\VerifyLog;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TenementService extends CommonService
{
    /**
     * @description:租户增加个人信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function addTenementInformation(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role <4){
            return $this->error('2','this account is not a tenement role');
        }else{
            $tenement_info = Tenement::where('user_id',$input['user_id'])->first();
            if($tenement_info){
                return $this->error('3','you already add the tenement information');
            }else{
                $tenement_data = [
                    'user_id'                   => $input['user_id'],
                    'tenement_id'               => tenementId(),
                    'headimg'                   => @$input['headimg'],
                    'first_name'                => $input['first_name'],
                    'middle_name'               => $input['middle_name'],
                    'last_name'                 => $input['last_name'],
                    'mobile'                    => $input['mobile'],
                    'phone'                     => $input['phone'],
                    'email'                     => $input['email'],
                    'birthday'                  => $input['birthday'],
                    'mail_address'              => $input['mail_address'],
                    'service_address'           => $input['service_address'],
                    'mail_code'                 => $input['mail_code'],
                    'zip_code'                  => $input['zip_code'],
                    'bank_no'                   => $input['bank_no'],
                    'contact_name'              => $input['contact_name'],
                    'contact_phone'             => $input['contact_phone'],
                    'contact_address'           => $input['contact_address'],
                    'company'                   => $input['company'],
                    'job_title'                 => $input['job_title'],
                    'instruction'               => $input['instruction'],
                    'subject_code'              => subjectCode(),
                    'created_at'                => date('Y-m-d H:i:s',time()),
                ];
                $model = new Tenement();
                $res = $model->insertGetId($tenement_data);
                if(!$res){
                    return $this->error('4','tenement information add failed');
                }else{
                    static $error = 0;
                    $certificate_model = new TenementCertificate();
                    foreach ($input['certificate_category'] as $k => $v){
                        $certificate_data = [
                            'tenement_id'   => $res,
                            'certificate_category'  => $v,
                            'certificate_no'        => $input['certificate_no'][$k],
                            'certificate_pic1'      => $input['certificate_pic1'][$k],
                            'certificate_pic2'      => $input['certificate_pic2'][$k],
                            'created_at'            => date('Y-m-d H:i:s',time()),
                        ];
                        $certificate_res = $certificate_model->insert($certificate_data);
                        if(!$certificate_res){
                            $error = $error+1;
                        }
                    }
                    if($error){
                        return $this->error('4','tenement information add failed');
                    }
                    return $this->success('tenement information add success',$res);
                }
            }
        }
    }


    /**
     * @description:租户修改个人信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function editTenementInformation(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role <4){
            return $this->error('2','this account is not a tenement role');
        }else{
            $tenement_info = Tenement::where('user_id',$input['user_id'])->first();
            if(!$tenement_info){
                return $this->error('3','you not  add the tenement information');
            }else{
                if($tenement_info['id'] != $input['tenement_id']){
                    return $this->error('4','update wrong tenement information');
                }
                $tenement_data = [
                    'headimg'                   => $input['headimg'],
                    'first_name'                => $input['first_name'],
                    'middle_name'               => $input['middle_name'],
                    'last_name'                 => $input['last_name'],
                    'mobile'                    => $input['mobile'],
                    'phone'                     => $input['phone'],
                    'email'                     => $input['email'],
                    'birthday'                  => $input['birthday'],
                    'mail_address'              => $input['mail_address'],
                    'service_address'           => $input['service_address'],
                    'mail_code'                 => $input['mail_code'],
                    'zip_code'                  => $input['zip_code'],
                    'bank_no'                   => $input['bank_no'],
                    'contact_name'              => $input['contact_name'],
                    'contact_phone'             => $input['contact_phone'],
                    'contact_address'           => $input['contact_address'],
                    'company'                   => $input['company'],
                    'job_title'                 => $input['job_title'],
                    'instruction'               => $input['instruction'],
                    'updated_at'                => date('Y-m-d H:i:s',time()),
                ];
                $model = new Tenement();
                $res = $model->where('user_id',$input['user_id'])->update($tenement_data);
                if(!$res){
                    return $this->error('5','tenement information add failed');
                }else{
                    static $error = 0;
                    $certificate_model = new TenementCertificate();
                    $certificate_model->where('tenement_id',$input['tenement_id'])->update(['deleted_at'=>date('Y-m-d H:i:s',time())]);
                    foreach ($input['certificate_category'] as $k => $v){
                        $certificate_data = [
                            'tenement_id'           => $input['tenement_id'],
                            'certificate_category'  => $v,
                            'certificate_no'        => $input['certificate_no'][$k],
                            'certificate_pic1'      => $input['certificate_pic1'][$k],
                            'certificate_pic2'      => $input['certificate_pic2'][$k],
                            'created_at'            => date('Y-m-d H:i:s',time()),
                        ];
                        $certificate_res = $certificate_model->insert($certificate_data);
                        if(!$certificate_res){
                            $error = $error+1;
                        }
                    }
                    if($error){
                        return $this->error('6','tenement information edit failed');
                    }
                    return $this->success('tenement information edit success');
                }
            }
        }
    }


    /**
     * @description:租户获得个人信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTenementSelfInformation(array $input)
    {
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role <4){
            return $this->error('2','this account is not a tenement role');
        }else{
            $tenement_info = Tenement::where('user_id',$input['user_id'])->first();
            if($tenement_info){
                $tenement_info = $tenement_info->toArray();
                $certificate_model = new TenementCertificate();
                $certificate_data = $certificate_model->where('tenement_id',$tenement_info['id'])->where('deleted_at',null)->get()->toArray();
                foreach ($certificate_data as $k => $v){
                    $tenement_info['certificate_category'][$k] = $v['certificate_category'];
                    $tenement_info['certificate_no'][$k] = $v['certificate_no'];
                    $tenement_info['certificate_pic1'][$k] = $v['certificate_pic1'];
                    $tenement_info['certificate_pic2'][$k] = $v['certificate_pic2'];
                }
                return $this->success('get tenement information success',$tenement_info);
            }else{
                return $this->error('3','get tenement information failed');
            }
        }
    }


    /**
     * @description:删除租户信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteTenementInformation(array $input)
    {
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role <4){
            return $this->error('2','this account is not a tenement role');
        }else{
            $res = Tenement::where('user_id',$input['user_id'])->update('deleted_at',date('Y-m-d H:i:s',time()));
            if($res){
                return $this->success('deleted tenement information success');
            }else{
                return $this->error('3','deleted tenement information failed');
            }
        }
    }



    /**
     * @description:房东查看租户信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function watchTenementInformation(array $input)
    {
        $tenement_info = Tenement::where('id',$input['tenement_id'])->first()->toArray();
        if($tenement_info){
            $certificate_model = new TenementCertificate();
            $certificate_data = $certificate_model->where('tenement_id',$tenement_info['id'])->get()->toArray();
            foreach ($certificate_data as $k => $v){
                $tenement_info['certificate_category'][$k] = $v['certificate_category'];
                $tenement_info['certificate_no'][$k] = $v['certificate_no'];
                $tenement_info['certificate_pic1'][$k] = $v['certificate_pic1'];
                $tenement_info['certificate_pic2'][$k] = $v['certificate_pic2'];
            }
            return $this->success('get tenement information success',$tenement_info);
        }else{
            return $this->error('3','get tenement information failed');
        }

    }


    /**
     * @description:房屋打分
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function houseScore(array $input)
    {
        $rent_house_id = RentContract::where('id',$input['contract_id'])->pluck('house_id')->first();
        $tenement_score_data = [
            'user_id'           => $input['user_id'],
            'pay_score'         => $input['pay_score'],
            'hygiene_score'     => $input['hygiene_score'],
            'facility_score'    => $input['facility_score'],
            'detail'            => $input['detail'],
            'contract_id'       => $input['contract_id'],
            'rent_house_id'     => $rent_house_id,
            'created_at'        => date('Y-m-d H:i:s',time()),
        ];
        $score_data = HouseScore::insert($tenement_score_data);
        if($score_data){
            RentContract::where('id',$input['contract_id'])->update(['contract_status'   => 7,'updated_at'   => date('Y-m-d H:i:s',time()),]);
            return $this->success('tenement score success');
        }else{
            return $this->error('2','tenement score failed');
        }
    }


    /**
     * @description:房屋账单列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArrearsHouseList(array $input)
    {
        $user_id = $input['user_id'];
        $tenement_id = Tenement::where('user_id',$user_id)->pluck('id')->first();
        $rent_house_id = RentArrears::where('tenement_id',$tenement_id)->first();
        $rent_house_ids = RentArrears::where('tenement_id',$tenement_id)->groupBy('rent_house_id')->select('rent_house_id')->get();
        if($rent_house_id){
            foreach ($rent_house_ids as $k => $v){
                $rent_house_info[$k]['rent_house_id'] = $v['rent_house_id'];
                $rent_house_info[$k]['rent_house_property_name'] = RentHouse::where('id',$v['rent_house_id'])->pluck('property_name')->first();
                $rent_house_info[$k]['rent_house_room_name'] = RentHouse::where('id',$v['rent_house_id'])->pluck('room_name')->first();
            }
            $data['rent_house_info'] = $rent_house_info;
            return $this->success('get arrears house list success',$data);
        } else{
            return $this->error('2','you not have arrears house');
        }
    }

    /**
     * @description:账单列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArrearsList(array $input)
    {
        $model = new RentArrears();
        $rent_house_id = $input['rent_house_id'];
        $model = $model->where('rent_house_id',$rent_house_id);
        $user_id = $input['user_id'];
        $tenement_id = Tenement::where('user_id',$user_id)->pluck('id')->first();
        $model = $model->where('tenement_id',$tenement_id);
        if(@$input['start_date'] && @$input['end_date']){
            $model = $model->whereBetween('created_at',[$input['start_date'],$input['end_date']]);
        }
        if(@$input['type']){
            $model = $model->where('arrears_type',$input['type']);
        }
        if($input['is_pay'] == 2){
            $model = $model->where('is_pay',2);
        }elseif ($input['is_pay'] == 3){
            $model = $model->whereIn('is_pay',[1,3]);
        }
        $model = $model->where('arrears_type','<',4);
        $total_arrears = 0;
        $need_pay_fee = 0;
        $pay_fee = 0;
        $count = $model->count();
        if($count <= ($input['page']-1)*10){
            return $this->error('2','no more arrears info');
        }
        $arrears_res = $model->offset(($input['page']-1)*10)->limit(10)->get()->toArray();
        foreach ($arrears_res as $k => $v){
            $total_arrears += $v['arrears_fee'];
            $need_pay_fee += $v['need_pay_fee'];
            $pay_fee += $v['pay_fee'];
        }
        $contract_id = $arrears_res[0]['contract_id'];
        $landlord_full_name = RentContract::where('id',$contract_id)->pluck('landlord_full_name')->first();
        $tenement_name = ContractTenement::where('tenement_id',$tenement_id)->where('contract_id',$contract_id)->pluck('tenement_full_name')->first();
        $property_address = RentHouse::where('id',$rent_house_id)->pluck('address')->first();
        $subject_code = Tenement::where('id',$tenement_id)->pluck('subject_code')->first();
        $landlord_id = RentContract::where('id',$contract_id)->pluck('user_id')->first();
        $rent_house_pic = RentPic::where('rent_house_id',$rent_house_id)->pluck('house_pic')->first();
        $landlord_headimg = \App\Model\User::where('id',$landlord_id)->pluck('head_img')->first();
        $data['im_id'] = 'user_'.$landlord_id;
        $data['arrears_list'] = $arrears_res;
        $data['landlodr_full_name'] = $landlord_full_name;
        $data['tenement_name'] = $tenement_name;
        $data['property_address'] = $property_address;
        $data['subject_code'] = $subject_code;
        $data['landlord_id'] = $landlord_id;
        $data['rent_house_pic'] = $rent_house_pic;
        $data['landlord_head_img'] = $landlord_headimg;
        $data['current_page'] = $input['page'];
        $data['total_page'] = ceil($count/10);
        $data['total_arrears'] = $total_arrears;
        $data['need_pay_fee'] = $need_pay_fee;
        $data['pay_fee'] = $pay_fee;
        return $this->success('get arrears list success',$data);
    }
}