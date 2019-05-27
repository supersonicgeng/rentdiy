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
use App\Model\RentApplication;
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
use App\Model\Tenement;
use App\Model\TenementCertificate;
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

class RentService extends CommonService
{
    /**
     * @description:租户申请租房
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentApplication(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role <4){
            return $this->error('2','this account is not a tenement role');
        }else{
            $tenement_info = Tenement::where('user_id',$input['user_id'])->first()->toArray();
            if(!$tenement_info){
                return $this->error('3','you must update your tenement info');
            }else{
                $application_data = [
                    'tenement_id'       => $tenement_info['id'],
                    'rent_house_id'     => $input['rent_house_id'],
                    'adult'             => $input['adult'],
                    'children'          => $input['children'],
                    'tenement_people'   => $input['adult']+$input['children'],
                    'income'            => $input['income'],
                    'income_cycle'      => $input['income_cycle'],
                    'rent_time'         => $input['rent_time'],
                    'rent_time_cycle'   => $input['rent_time_cycle'],
                    'start_rent_time'   => $input['check_in_time'],
                    'end_rent_time'     => $input['departure_time'],
                    'created_at'        => date('Y-m-d H:i:s',time()),
                ];
                $model = new RentApplication();
                $res = $model->insert($application_data);
                if($res){
                    return $this->success('application success');
                }else{
                    return $this->error('4','application failed');
                }
            }
        }
    }

    /**
     * @description:租户租房申请（非本平台）
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function outRentApplicationAdd(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role <4){
            return $this->error('2','this account is not a tenement role');
        }else{
            $application_data = [
                'tenement_id'           => $input['tenement_id'],
                'apply_house_address'   => $input['apply_house_address'],
                'apply_start_time'      => $input['apply_start_time'],
                'apply_end_time'        => $input['apply_end_time'],
                'tenement_address'      => $input['tenement_address'],
                'tenement_name'         => $input['tenement_name'],
                'birthday'              => $input['birthday'],
                'phone'                 => $input['phone'],
                'mobile'                => $input['mobile'],
                'email'                 => $input['email'],
                'welfare_no'            => $input['welfare_no'],
                'have_pets'             => $input['have_pets'],
                'pets'                  => @$input['pets'],
                'current_address'       => $input['current_address'],
                'current_rent_fee'      => $input['current_rent_fee'],
                'rent_times'            => $input['rent_times'],
                'rent_way'              => $input['rent_way'],
                'live_method'           => $input['live_method'],
                'other_method'          => @$input['other_method'],
                'leave_reason'          => $input['leave_reason'],
                'current_landlord_name' => $input['current_landlord_name'],
                'landlord_phone'        => $input['landlord_phone'],
                'landlord_email'        => $input['landlord_email'],
                'property_manager_name' => $input['property_manager_name'],
                'manager_phone'         => $input['manager_phone'],
                'manager_email'         => $input['manager_email'],
                'inform_landlord'       => $input['inform_landlord'],
                'driving_license'       => @$input['driving_license'],
                'version_num'           => @$input['version_num'],
                'passport'              => @$input['passport'],
                'vehicle'               => @$input['vehicle'],
                'alternative'           => @$input['alternative'],
                'model'                 => @$input['model'],
                'work_situation'        => $input['work_situation'],
                'company_name'          => $input['company_name'],
                'job_title'             => $input['job_title'],
                'employer_name'         => $input['employer_name'],
                'company_address'       => $input['company_address'],
                'company_phone'         => $input['company_phone'],
                'company_email'         => $input['company_email'],
                'inform_company'        => $input['inform_company'],
                'income'                => $input['income'],
                'contact_name'          => $input['contact_name'],
                'contact_address'       => $input['contact_address'],
                'contact_phone'         => $input['contact_phone'],
                'contact_mobile'        => $input['contact_mobile'],
                'contact_email'         => $input['contact_email'],
                'contact_relation'      => $input['contact_relation'],
                'recommend_name1'       => $input['recommend_name1'],
                'recommend_email1'      => $input['recommend_email1'],
                'recommend_tel1'        => $input['recommend_tel1'],
                'recommend_relation1'   => $input['recommend_relation1'],
                'recommend_name2'       => @$input['recommend_name2'],
                'recommend_email2'      => @$input['recommend_email2'],
                'recommend_tel2'        => @$input['recommend_tel2'],
                'recommend_relation2'   => @$input['recommend_relation2'],
                'sign'                  => $input['sign'],
                'created_at'            => date('Y-m-d H:i:s',time())
            ];
            $model = new OtherRentApplication();
            $res = $model->insert($application_data);
            if($res){
                return $this->success('application add success');
            } else{
                return $this->error('3','application add failed');
            }
        }
    }


    /**
     * @description:租户租房申请（非本平台）
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function outRentApplicationInformation(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role <4){
            return $this->error('2','this account is not a tenement role');
        }else{
            $model = new OtherRentApplication();
            $res = $model->where('id',$input['out_rent_application_id'])->first();
            if($res){
                return $this->success('get application success',$res);
            } else{
                return $this->error('3','get application failed');
            }
        }
    }


    /**
     * @description:租户租房申请（非本平台）
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentApplicationOutList(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role <4){
            return $this->error('2','this account is not a tenement role');
        }else{
            $model = new OtherRentApplication();
            $res = $model->where('tenement_id',$input['tenement_id'])->where('deleted_at',null)->select('id','apply_house_address')->get();
            if($res){
                return $this->success('get application success',$res);
            } else{
                return $this->error('3','get application failed');
            }
        }
    }


    /**
     * @description:租户租房申请（非本平台）编辑
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentApplicationOutEdit(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role <4){
            return $this->error('2','this account is not a tenement role');
        }else{
            $model = new OtherRentApplication();
            $application_info = $model->where('id',$input['out_rent_application_id'])->first();
            $application_data = [
                'apply_house_address'   => @$input['apply_house_address']?$input['apply_house_address']:$application_info->apply_house_address,
                'apply_start_time'      => @$input['apply_start_time']?$input['apply_start_time']:$application_info->apply_start_time,
                'apply_end_time'        => @$input['apply_end_time']?$input['apply_end_time']:$application_info->apply_end_time,
                'tenement_address'      => @$input['tenement_address']?$input['tenement_address']:$application_info->tenement_address,
                'tenement_name'         => @$input['tenement_name']?$input['tenement_name']:$application_info->tenement_name,
                'birthday'              => @$input['birthday']?$input['birthday']:$application_info->birthday,
                'phone'                 => @$input['phone']?$input['phone']:$application_info->phone,
                'mobile'                => @$input['mobile']?$input['mobile']:$application_info->mobile,
                'email'                 => @$input['email']?$input['email']:$application_info->email,
                'welfare_no'            => @$input['welfare_no']?$input['welfare_no']:$application_info->welfare_no,
                'have_pets'             => @$input['have_pets']?$input['have_pets']:$application_info->have_pets,
                'pets'                  => @$input['pets']?$input['pets']:$application_info->pets,
                'current_address'       => @$input['current_address']?$input['current_address']:$application_info->current_address,
                'current_rent_fee'      => @$input['current_rent_fee']?$input['current_rent_fee']:$application_info->current_rent_fee,
                'rent_times'            => @$input['rent_times']?$input['rent_times']:$application_info->rent_times,
                'rent_way'              => @$input['rent_way']?$input['rent_way']:$application_info->rent_way,
                'live_method'           => @$input['live_method']?$input['live_method']:$application_info->live_method,
                'other_method'          => @$input['other_method']?$input['other_method']:$application_info->other_method,
                'leave_reason'          => @$input['leave_reason']?$input['leave_reason']:$application_info->leave_reason,
                'current_landlord_name' => @$input['current_landlord_name']?$input['current_landlord_name']:$application_info->current_landlord_name,
                'landlord_phone'        => @$input['landlord_phone']?$input['landlord_phone']:$application_info->landlord_phone,
                'landlord_email'        => @$input['landlord_email']?$input['landlord_email']:$application_info->landlord_email,
                'property_manager_name' => @$input['property_manager_name']?$input['property_manager_name']:$application_info->property_manager_name,
                'manager_phone'         => @$input['manager_phone']?$input['manager_phone']:$application_info->manager_phone,
                'manager_email'         => @$input['manager_email']?$input['manager_email']:$application_info->manager_email,
                'inform_landlord'       => @$input['inform_landlord']?$input['inform_landlord']:$application_info->inform_landlord,
                'driving_license'       => @$input['driving_license']?$input['driving_license']:$application_info->driving_license,
                'version_num'           => @$input['version_num']?$input['version_num']:$application_info->version_num,
                'passport'              => @$input['passport']?$input['passport']:$application_info->passport,
                'vehicle'               => @$input['vehicle']?$input['vehicle']:$application_info->vehicle,
                'others'                => @$input['others']?$input['others']:$application_info->others,
                'work_situation'        => @$input['work_situation']?$input['work_situation']:$application_info->work_situation,
                'company_name'          => @$input['company_name']?$input['company_name']:$application_info->company_name,
                'job_title'             => @$input['job_title']?$input['job_title']:$application_info->job_title,
                'employer_name'         => @$input['employer_name']?$input['employer_name']:$application_info->employer_name,
                'company_address'       => @$input['company_address']?$input['company_address']:$application_info->company_address,
                'company_phone'         => @$input['company_phone']?$input['company_phone']:$application_info->company_phone,
                'company_email'         => @$input['company_email']?$input['company_email']:$application_info->company_email,
                'inform_company'        => @$input['inform_company']?$input['inform_company']:$application_info->inform_company,
                'income'                => @$input['income']?$input['income']:$application_info->income,
                'contact_name'          => @$input['contact_name']?$input['contact_name']:$application_info->contact_name,
                'contact_address'       => @$input['contact_address']?$input['contact_address']:$application_info->contact_address,
                'contact_phone'         => @$input['contact_phone']?$input['contact_phone']:$application_info->contact_phone,
                'contact_mobile'        => @$input['contact_mobile']?$input['contact_mobile']:$application_info->contact_mobile,
                'contact_email'         => @$input['contact_email']?$input['contact_email']:$application_info->contact_email,
                'contact_relation'      => @$input['contact_relation']?$input['contact_relation']:$application_info->contact_relation,
                'recommend_name1'       => @$input['recommend_name1']?$input['recommend_name1']:$application_info->recommend_name1,
                'recommend_email1'      => @$input['recommend_email1']?$input['recommend_email1']:$application_info->recommend_email1,
                'recommend_tel1'        => @$input['recommend_tel1']?$input['recommend_tel1']:$application_info->recommend_tel1,
                'recommend_relation1'   => @$input['recommend_relation1']?$input['recommend_relation1']:$application_info->recommend_relation1,
                'recommend_name2'       => @$input['recommend_name2']?$input['recommend_name2']:$application_info->recommend_name2,
                'recommend_email2'      => @$input['recommend_email2']?$input['recommend_email2']:$application_info->recommend_email2,
                'recommend_tel2'        => @$input['recommend_tel2']?$input['recommend_tel2']:$application_info->recommend_tel2,
                'recommend_relation2'   => @$input['recommend_relation2']?$input['recommend_relation2']:$application_info->recommend_relation2,
                'sign'                  => @$input['sign']?$input['sign']:$application_info->sign,
                'updated_at'            => date('Y-m-d H:i:s',time())
            ];
            $res = $model->where('id',$input['out_rent_application_id'])->save($application_data);
            if($res){
                return $this->success('update application success');
            }else{
                return $this->error('3','update application success');
            }
        }
    }



    /**
     * @description:租户租房申请（非本平台）删除
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentApplicationOutDelete(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role <4){
            return $this->error('2','this account is not a tenement role');
        }else{
            $model = new OtherRentApplication();
            $res = $model->where('id',$input['out_rent_application_id'])->update('deleted_at', date('Y-m-d H:i:s',time()));
            if($res){
                return $this->success('delete application success');
            } else{
                return $this->error('3','delete application failed');
            }
        }
    }

    /**
 * @description:租户租房申请（房东查看）
 * @author: syg <13971394623@163.com>
 * @param $code
 * @param $message
 * @param array|null $data
 * @return \Illuminate\Http\JsonResponse
 */
    public function rentHouseApplicationList(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role % 2){
            $model = new RentApplication();
            $application_start_date = @$input['application_start_time'];
            if($application_start_date){
                $model = $model->where('created_at','>',$application_start_date);
            }
            $application_end_date = @$input['application_end_time'];
            if($application_end_date){
                $model = $model->where('created_at','<',$application_end_date);
            }
            $application_status = @$input['application_status'];
            if($application_status){
                $model = $model->where('application_status',$application_status);
            }
            $tenement_people = @$input['tenement_people'];
            if($tenement_people){
                $model = $model->where('tenement_people',$tenement_people);
            }
            $page = $input['page'];
            $count = $model->where('rent_house_id',$input['rent_house_id'])->where('deleted_at',null)->count();
            if($count < ($page-1)*5){
                return $this->error('4','page number wrong');
            }
            $res = $model->where('rent_house_id',$input['rent_house_id'])->where('deleted_at',null)->offset(($page-1)*5)->limit(5)->get()->toArray();
            if($res){
                foreach ($res as $k => $v){
                    $tenement_info = Tenement::where('id',$v['tenement_id'])->first()->toArray();
                    $res[$k]['tenement_name'] = $tenement_info['first_name'].'&nbsp'.$tenement_info['middle_name'].'&nbsp'.$tenement_info['last_name'];
                    $res[$k]['tenement_headimg'] = $tenement_info['headimg'];
                    $res[$k]['look_house'] = LookHouse::where('rent_application_id',$v['id'])->first();
                    /*$res[$k]['survey_score'] = Survey::where('application_id',$v['id'])->pluck('survey_score')->first();
                    $res[$k]['survey_people'] = Survey::where('application_id',$v['id'])->pluck('survey_people')->first();
                    $res[$k]['survey_date'] = Survey::where('application_id',$v['id'])->pluck('survey_date')->first();*/
                }
                $data['application_list'] = $res;
                $data['total_page'] = ceil($count/5);
                $data['current_page'] = $page;
                return $this->success('get application success',$data);
            } else{
                return $this->error('3','get application failed');
            }
        }else{
            return $this->error('2','this account is not a landlord role');
        }
    }


    /**
     * @description:租户租房申请列表（租户查看）
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentTenementApplicationList(array $input)
    {
        //dd($input);
        $page = $input['page'];
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role < 4){
            return $this->error('2','this account is not a tenement role');
        }else{
            $model = new RentApplication();
            $application_start_date = @$input['application_start_time'];
            if($application_start_date){
                $model = $model->where('created_at','>',$application_start_date);
            }
            $application_end_date = @$input['application_end_time'];
            if($application_end_date){
                $model = $model->where('created_at','<',$application_end_date);
            }
            $application_status = @$input['application_status'];
            if($application_status){
                $model = $model->where('status',$application_status);
            }
            $sort_order = $input['sort_order'];
            $count = $model->where('tenement_id',$input['tenement_id'])->count();
            if($count<($page-1)*9){
                return $this->error('3','no data');
            }
            $total_page = ceil($count/9);
            if($sort_order == 1){
                $res = $model->where('tenement_id',$input['tenement_id'])->orderBy('id','DESC')->offset(($page-1)*9)->limit(9)->get()->toArray();
            }else{
                $res = $model->where('tenement_id',$input['tenement_id'])->offset(($page-1)*9)->limit(9)->get()->toArray();
            }
            if($res){
                foreach ($res as $k => $v){
                    $house_info = RentHouse::where('id',$v['rent_house_id'])->select('id','rent_category','property_name','property_type','address','available_time','rent_fee_pre_week','rent_least_fee','bedroom_no','bathroom_no','parking_no','garage_no','District','TA','Region','available_date')->first()->toArray();;
                    $application_res[$k] = $house_info;
                    $application_res[$k]['house_pic'] = RentPic::where('rent_house_id',$v['id'])->where('deleted_at',null)->pluck('house_pic')->toArray();// 图片
                    $application_res[$k]['full_address'] = $house_info['address'].','.Region::getName($house_info['District']).','.Region::getName($house_info['TA']).','.Region::getName($house_info['Region']);
                    $application_res[$k]['application_id'] = $v['id'];
                }
                $data['house_list'] = $application_res;
                $data['total_page'] = $total_page;
                $data['current_page'] = $input['page'];
                return $this->success('get application success',$data);
            } else{
                return $this->error('4','get application failed');
            }
        }
    }

    /**
     * @description:租户租房申请详情（租户查看）
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentTenementApplicationDetail(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role < 4){
            return $this->error('2','this account is not a tenement role');
        }else{
            $model = new RentApplication();
            $res = $model->where('id',$input['application'])->first()->toArray();
            if($res){
                return $this->success('get application success',$res);
            } else{
                return $this->error('3','get application failed');
            }
        }
    }


    /**
     * @description:添加租约
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentContactAdd(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id', $input['user_id'])->first();
        if (!$user_info->user_role % 2) {
            return $this->error('2', 'this account is not a landlord role');
        } else {
            $model = new RentContract();
            $contract_data = [
                'contract_id'                   => contractId(),
                'house_id'                      => $input['house_id'],
                'user_id'                       => $input['user_id'],
                'landlord_id'                   => $input['landlord_id'],
                'landlord_full_name'            => $input['landlord_full_name'],
                'landlord_e_mail'               => $input['landlord_e_mail'],
                'house_address'                 => $input['house_address'],
                'landlord_mobile_phone'         => $input['landlord_mobile_phone'],
                'landlord_telephone'            => $input['landlord_telephone'],
                'landlord_hm'                   => $input['landlord_hm'],
                'landlord_wk'                   => $input['landlord_wk'],
                'landlord_other_address'        => $input['landlord_other_address'],
                'landlord_additional_address'   => $input['landlord_additional_address'],
                'landlord_wish'                 => $input['landlord_wish'],
                'contract_type'                 => $input['contract_type'],
                'created_at'                    => date('Y-m-d H:i:s', time())
            ];
            if ($input['contract_type'] == 1) {
                $contract_res = $model->insertGetId($contract_data);
                if ($contract_res) {
                    $contract_tenement_model = new ContractTenement();
                    foreach ($input['tenement_info'] as $k => $v){
                        if(!@$v['tenement_id']){
                            //房东自己添加的时候添加租户列表
                            $tenement_res = Tenement::where('email',$v['tenement_e_mail'])->pluck('id');
                            if($tenement_res){ // 当这个email 在租户表中有时 默认存为那个用户表
                                $v['tenement_id'] = $tenement_res;
                            }else{ // 没有在租户表中新建一个租户信息
                                $tenement_data = [
                                    'tenement_id'               => tenementId(),
                                    'mobile'                    => $v['tenement_mobile'],
                                    'phone'                     => $v['tenement_phone'],
                                    'email'                     => $v['tenement_e_mail'],
                                    'mail_address'              => $input['mail_address'],
                                    'service_address'           => $v['service_physical_address'],
                                    'mail_code'                 => $v['tenement_post_code'],
                                    'created_at'                => date('Y-m-d H:i:s',time()),
                                ];
                                $v['tenement_id'] = Tenement::insertGetId($tenement_data);
                            }
                        }
                        $contract_tenement_data = [
                            'contract_id'               => $contract_res,
                            'tenement_id'               => @$v['tenement_id'],
                            'tenement_full_name'        => $v['tenement_full_name'],
                            'identification_no'         => $v['identification_no'],
                            'identification_type'       => $v['identification_type'],
                            'service_physical_address'  => $v['service_physical_address'],
                            'tenement_e_mail'           => $v['tenement_e_mail'],
                            'tenement_phone'            => $v['tenement_phone'],
                            'tenement_mobile'           => $v['tenement_mobile'],
                            'tenement_hm'               => $v['tenement_hm'],
                            'tenement_wk'               => $v['tenement_wk'],
                            'tenement_post_address'     => $v['tenement_post_address'],
                            'tenement_post_code'        => $v['tenement_post_code'],
                            'tenement_service_address'  => $v['tenement_service_address'],
                            'other_contact_address'     => $v['other_contact_address'],
                            'additional_address'        => $v['additional_address'],
                            'guarantor_name'            => @$v['guarantor_name'],
                            'occupation'                => @$v['occupation'],
                            'home_address'              => @$v['home_address'],
                            'guarantor_phone'           => @$v['guarantor_phone'],
                            'guarantor_e_mail'          => @$v['guarantor_e_mail'],
                            'is_child'                  => $v['is_child'],
                            'created_at'                => date('Y-m-d H:i:s', time()),
                        ];
                        $contract_tenement_res = $contract_tenement_model->insert($contract_tenement_data);
                    }
                    foreach ($input['chattel_info'] as $k => $v){
                        $contract_chattel_data = [
                            'contract_id'   => $contract_res,
                            'rent_house_id' => $input['house_id'],
                            'chattel'       => $v['chattel'],
                            'chattel_num'   => $v['chattel_num'],
                            'note'          => $v['note'],
                            'created_at'    => date('Y-m-d H:i:s', time()),
                        ];
                        $contract_chattel_res = ContractChattel::insert($contract_chattel_data);
                    }
                    $entire_model = new EntireContract();
                    $entire_data = [
                        'contract_id'                               => $contract_res,
                        'tenancy_address'                           => $input['tenancy_address'],
                        'rent_per_week'                             => $input['rent_per_week'],
                        'pay_method'                                => $input['pay_method'],
                        'bond_amount'                               => $input['bond_amount'],
                        'rent_to_be_paid_at'                        => $input['rent_to_be_paid_at'],
                        'bank_account'                              => $input['bank_account'],
                        'account_name'                              => $input['account_name'],
                        'bank'                                      => $input['bank'],
                        'branch'                                    => $input['branch'],
                        'effective_date'                            => $input['effective_date'],
                        'can_periodic_tenancy'                      => $input['can_periodic_tenancy'],
                        'end_date'                                  => $input['end_date'],
                        'rule'                                      => $input['rule'],
                        'rule_upload_url'                           => $input['rule_upload_url'],
                        'meter_readings'                            => $input['meter_readings'],
                        'is_ceiling_insulation'                     => $input['is_ceiling_insulation'],
                        'ceiling_insulation_detail'                 => $input['ceiling_insulation_detail'],
                        'is_insulation_underfloor_insulation'       => $input['is_insulation_underfloor_insulation'],
                        'insulation_underfloor_insulation_detail'   => $input['insulation_underfloor_insulation_detail'],
                        'location_ceiling_insulation'               => $input['location_ceiling_insulation'],
                        'location_ceiling_insulation_detail'        => $input['location_ceiling_insulation_detail'],
                        'ceiling_insulation_type'                   => $input['ceiling_insulation_type'],
                        'ceiling_insulation_type_detail'            => $input['ceiling_insulation_type_detail'],
                        'R_value'                                   => $input['R_value'],
                        'minimum_thickness'                         => $input['minimum_thickness'],
                        'ceiling_insulation_age'                    => $input['ceiling_insulation_age'],
                        'ceiling_insulation_condition'              => $input['ceiling_insulation_condition'],
                        'ceiling_insulation_condition_reason'       => $input['ceiling_insulation_condition_reason'],
                        'location_underfloor_insulation'            => $input['location_underfloor_insulation'],
                        'location_underfloor_insulation_detail'     => $input['location_underfloor_insulation_detail'],
                        'underfloor_insulation_type'                => $input['underfloor_insulation_type'],
                        'underfloor_insulation_type_detail'         => $input['underfloor_insulation_type_detail'],
                        'underfloor_R_value'                        => $input['underfloor_R_value'],
                        'underfloor_minimum_thickness'              => $input['underfloor_minimum_thickness'],
                        'condition'                                 => $input['condition'],
                        'condition_detail'                          => $input['condition_detail'],
                        'wall_insulation'                           => $input['wall_insulation'],
                        'wall_insulation_detail'                    => $input['wall_insulation_detail'],
                        'supplementary_information'                 => $input['supplementary_information'],
                        'install_insulation'                        => $input['install_insulation'],
                        'install_insulation_detail'                 => $input['install_insulation_detail'],
                        'underfloor_insulation'                     => $input['underfloor_insulation'],
                        'underfloor_insulation_detail'              => $input['underfloor_insulation_detail'],
                        'last_upgraded'                             => $input['last_upgraded'],
                        'professionally_assessed'                   => $input['professionally_assessed'],
                        'plan'                                      => $input['plan'],
                        'landlord_state'                            => $input['landlord_state'],
                        'landlord_signature'                        => $input['landlord_signature'],
                        'tenement_signature'                        => $input['tenement_signature'],
                        'rent_end_date'                             => $input['rent_end_date'],
                        'rent_fee'                                  => $input['rent_fee'],
                        'created_at'                                => date('Y-m-d H:i:s', time()),
                    ];
                    $entire_res = $entire_model->insert($entire_data);
                    if ($contract_tenement_res && $entire_res && $contract_chattel_res) {
                        return $this->success('contract add success');
                    } else {
                        return $this->error('3', 'add contract failed');
                    }
                } else {
                    return $this->error('3', 'add contract failed');
                }
            } elseif ($input['contract_type'] == 2 || $input['contract_type'] == 3) {
                $contract_res = $model->insertGetId($contract_data);
                if ($contract_res) {
                    $contract_tenement_model = new ContractTenement();
                    foreach ($input['tenement_info'] as $k => $v){
                        if(!@$v['tenement_id']){
                            //房东自己添加的时候添加租户列表
                            $tenement_res = Tenement::where('email',$v['tenement_e_mail'])->pluck('id');
                            if($tenement_res){ // 当这个email 在租户表中有时 默认存为那个用户表
                                $v['tenement_id'] = $tenement_res;
                            }else{ // 没有在租户表中新建一个租户信息
                                $tenement_data = [
                                    'tenement_id'               => tenementId(),
                                    'mobile'                    => $v['tenement_mobile'],
                                    'phone'                     => $v['tenement_phone'],
                                    'email'                     => $v['tenement_e_mail'],
                                    'mail_address'              => $input['mail_address'],
                                    'service_address'           => $v['service_physical_address'],
                                    'mail_code'                 => $v['tenement_post_code'],
                                    'created_at'                => date('Y-m-d H:i:s',time()),
                                ];
                                $v['tenement_id'] = Tenement::insertGetId($tenement_data);
                            }
                        }
                        $contract_tenement_data = [
                            'contract_id'               => $contract_res,
                            'tenement_id'               => @$v['tenement_id'],
                            'tenement_full_name'        => $v['tenement_full_name'],
                            'identification_no'         => $v['identification_no'],
                            'identification_type'       => $v['identification_type'],
                            'service_physical_address'  => $v['service_physical_address'],
                            'tenement_e_mail'           => $v['tenement_e_mail'],
                            'tenement_phone'            => $v['tenement_phone'],
                            'tenement_mobile'           => $v['tenement_mobile'],
                            'tenement_hm'               => $v['tenement_hm'],
                            'tenement_wk'               => $v['tenement_wk'],
                            'tenement_post_address'     => $v['tenement_post_address'],
                            'tenement_post_code'        => $v['tenement_post_code'],
                            'tenement_service_address'  => $v['tenement_service_address'],
                            'other_contact_address'     => $v['other_contact_address'],
                            'additional_address'        => $v['additional_address'],
                            'guarantor_name'            => @$v['guarantor_name'],
                            'occupation'                => @$v['occupation'],
                            'home_address'              => @$v['home_address'],
                            'guarantor_phone'           => @$v['guarantor_phone'],
                            'guarantor_e_mail'          => @$v['guarantor_e_mail'],
                            'is_child'                  => $v['is_child'],
                            'created_at'                => date('Y-m-d H:i:s', time()),
                        ];
                        $contract_tenement_res = $contract_tenement_model->insert($contract_tenement_data);
                    }
                    foreach ($input['chattel_info'] as $k => $v){
                        $contract_chattel_data = [
                            'contract_id'   => $contract_res,
                            'rent_house_id' => $input['house_id'],
                            'chattel'       => $v['chattel'],
                            'chattel_num'   => $v['chattel_num'],
                            'note'          => $v['note'],
                            'created_at'    => date('Y-m-d H:i:s', time()),
                        ];
                        $contract_chattel_res = ContractChattel::insert($contract_chattel_data);
                    }
                    foreach ($input['service_fee_info'] as $k =>  $v){
                        $service_fee_data = [
                            'contract_id'   => $contract_res,
                            'service_name'  => $v['service_name'],
                            'service_price' => $v['service_price'],
                            'created_at'    => date('Y-m-d H:i:s', time()),
                        ];
                        $service_fee_res = ContractService::insert($service_fee_data);
                    }
                    $separate_model = new SeparateContract();
                    $separate_data = [
                        'contract_id'                               => $contract_res,
                        'agent_name'                                => $input['agent_name'],
                        'agent_address'                             => $input['agent_address'],
                        'agent_e_mail'                              => $input['agent_e_mail'],
                        'agent_phone'                               => $input['agent_phone'],
                        'agent_mobile'                              => $input['agent_mobile'],
                        'agent_hm'                                  => $input['agent_hm'],
                        'agent_wk'                                  => $input['agent_wk'],
                        'agent_other_address'                       => $input['agent_other_address'],
                        'agent_additional_address'                  => $input['agent_additional_address'],
                        'tenancy_address'                           => $input['tenancy_address'],
                        'rent_per_week'                             => $input['rent_per_week'],
                        'is_house_rule'                             => $input['is_house_rule'],
                        'is_fire'                                   => $input['is_fire'],
                        'is_body'                                   => $input['is_body'],
                        'pay_method'                                => $input['pay_method'],
                        'bond_amount'                               => $input['bond_amount'],
                        'to_be_paid'                                => $input['to_be_paid'],
                        'rent_to_be_paid_at'                        => $input['rent_to_be_paid_at'],
                        'bank_account'                              => $input['bank_account'],
                        'account_name'                              => $input['account_name'],
                        'bank'                                      => $input['bank'],
                        'branch'                                    => $input['branch'],
                        'agree_date'                                => $input['agree_date'],
                        'intended'                                  => $input['intended'],
                        'is_joint_tenancy'                          => $input['is_joint_tenancy'],
                        'is_joint_tenancy_detail'                   => $input['is_joint_tenancy_detail'],
                        'is_not_share'                              => $input['is_not_share'],
                        'is_share_people'                           => $input['is_share_people'],
                        'allow_service'                             => $input['allow_service'],
                        'is_ceiling_insulation'                     => $input['is_ceiling_insulation'],
                        'ceiling_insulation_detail'                 => $input['ceiling_insulation_detail'],
                        'is_insulation_underfloor_insulation'       => $input['is_insulation_underfloor_insulation'],
                        'insulation_underfloor_insulation_detail'   => $input['insulation_underfloor_insulation_detail'],
                        'location_ceiling_insulation'               => $input['location_ceiling_insulation'],
                        'location_ceiling_insulation_detail'        => $input['location_ceiling_insulation_detail'],
                        'ceiling_insulation_type'                   => $input['ceiling_insulation_type'],
                        'ceiling_insulation_type_detail'            => $input['ceiling_insulation_type_detail'],
                        'R_value'                                   => $input['R_value'],
                        'minimum_thickness'                         => $input['minimum_thickness'],
                        'ceiling_insulation_age'                    => $input['ceiling_insulation_age'],
                        'ceiling_insulation_condition'              => $input['ceiling_insulation_condition'],
                        'ceiling_insulation_condition_reason'       => $input['ceiling_insulation_condition_reason'],
                        'location_underfloor_insulation'            => $input['location_underfloor_insulation'],
                        'location_underfloor_insulation_detail'     => $input['location_underfloor_insulation_detail'],
                        'underfloor_insulation_type'                => $input['underfloor_insulation_type'],
                        'underfloor_insulation_type_detail'         => $input['underfloor_insulation_type_detail'],
                        'underfloor_R_value'                        => $input['underfloor_R_value'],
                        'underfloor_minimum_thickness'              => $input['underfloor_minimum_thickness'],
                        'condition'                                 => $input['condition'],
                        'condition_detail'                          => $input['condition_detail'],
                        'wall_insulation'                           => $input['wall_insulation'],
                        'wall_insulation_detail'                    => $input['wall_insulation_detail'],
                        'supplementary_information'                 => $input['supplementary_information'],
                        'install_insulation'                        => $input['install_insulation'],
                        'install_insulation_detail'                 => $input['install_insulation_detail'],
                        'underfloor_insulation'                     => $input['underfloor_insulation'],
                        'underfloor_insulation_detail'              => $input['underfloor_insulation_detail'],
                        'last_upgraded'                             => $input['last_upgraded'],
                        'professionally_assessed'                   => $input['professionally_assessed'],
                        'plan'                                      => $input['plan'],
                        'landlord_state'                            => $input['landlord_state'],
                        'landlord_signature'                        => $input['landlord_signature'],
                        'tenement_signature'                        => $input['tenement_signature'],
                        'rent_end_date'                             => $input['rent_end_date'],
                        'rent_fee'                                  => $input['rent_fee'],
                        'created_at'                                => date('Y-m-d H:i:s', time()),
                    ];
                    $separate_res = $separate_model->insert($separate_data);
                    if ($contract_tenement_res && $separate_res && $contract_chattel_res && $service_fee_res) {
                        return $this->success('contract add success');
                    } else {
                        return $this->error('3', 'add contract failed');
                    }
                } else {
                    return $this->error('3', 'add contract failed');
                }
            } elseif ($input['contract_type'] == 4 ) {
                $contract_res = $model->insertGetId($contract_data);
                if ($contract_res) {
                    $contract_tenement_model = new ContractTenement();
                    foreach ($input['tenement_info'] as $k => $v){
                        if(!@$v['tenement_id']){
                            //房东自己添加的时候添加租户列表
                            $tenement_res = Tenement::where('email',$v['tenement_e_mail'])->pluck('id');
                            if($tenement_res){ // 当这个email 在租户表中有时 默认存为那个用户表
                                $v['tenement_id'] = $tenement_res;
                            }else{ // 没有在租户表中新建一个租户信息
                                $tenement_data = [
                                    'tenement_id'               => tenementId(),
                                    'mobile'                    => $v['tenement_mobile'],
                                    'phone'                     => $v['tenement_phone'],
                                    'email'                     => $v['tenement_e_mail'],
                                    'mail_address'              => $input['mail_address'],
                                    'service_address'           => $v['service_physical_address'],
                                    'mail_code'                 => $v['tenement_post_code'],
                                    'created_at'                => date('Y-m-d H:i:s',time()),
                                ];
                                $v['tenement_id'] = Tenement::insertGetId($tenement_data);
                            }
                        }
                        $contract_tenement_data = [
                            'contract_id'               => $contract_res,
                            'tenement_id'               => @$v['tenement_id'],
                            'tenement_full_name'        => $v['tenement_full_name'],
                            'identification_no'         => $v['identification_no'],
                            'identification_type'       => $v['identification_type'],
                            'service_physical_address'  => $v['service_physical_address'],
                            'tenement_e_mail'           => $v['tenement_e_mail'],
                            'tenement_phone'            => $v['tenement_phone'],
                            'tenement_mobile'           => $v['tenement_mobile'],
                            'tenement_hm'               => $v['tenement_hm'],
                            'tenement_wk'               => $v['tenement_wk'],
                            'tenement_post_address'     => $v['tenement_post_address'],
                            'tenement_post_code'        => $v['tenement_post_code'],
                            'tenement_service_address'  => $v['tenement_service_address'],
                            'other_contact_address'     => $v['other_contact_address'],
                            'additional_address'        => $v['additional_address'],
                            'guarantor_name'            => $v['guarantor_name'],
                            'occupation'                => $v['occupation'],
                            'home_address'              => $v['home_address'],
                            'guarantor_phone'           => $v['guarantor_phone'],
                            'guarantor_e_mail'          => $v['guarantor_e_mail'],
                            'is_child'                  => $v['is_child'],
                            'created_at'                => date('Y-m-d H:i:s', time()),
                        ];
                        $contract_tenement_res = $contract_tenement_model->insert($contract_tenement_data);
                    }
                    $business_model = new BusinessContract();
                    $business_data = [
                        'contract_id'                           => $contract_res,
                        'premises'                              => $input['premises'],
                        'car_parks'                             => $input['car_parks'],
                        'lease_term'                            => $input['lease_term'],
                        'term_method'                           => $input['term_method'],
                        'commencement_date'                     => $input['commencement_date'],
                        'final_expiry_date'                     => $input['final_expiry_date'],
                        'renewal_right'                         => $input['renewal_right'],
                        'renewal_time'                          => $input['renewal_time'],
                        'annual_rent'                           => $input['annual_rent'],
                        'premises_pro'                          => $input['premises_pro'],
                        'premises_gst'                          => $input['premises_gst'],
                        'car_parks_pro'                         => $input['car_parks_pro'],
                        'car_gst'                               => $input['car_gst'],
                        'total'                                 => $input['total'],
                        'total_gst'                             => $input['total_gst'],
                        'month_rent'                            => $input['month_rent'],
                        'rent_payment_date'                     => $input['rent_payment_date'],
                        'day_each_month'                        => $input['day_each_month'],
                        'market_rent_assessment_date'           => $input['market_rent_assessment_date'],
                        'cpi_date'                              => $input['cpi_date'],
                        'outgoing'                              => $input['outgoing'],
                        'default_interest_rate'                 => $input['default_interest_rate'],
                        'commercial_use'                        => $input['commercial_use'],
                        'business_use'                          => $input['business_use'],
                        'insurance'                             => $input['insurance'],
                        'no_access_period'                      => $input['no_access_period'],
                        'further_term'                          => $input['further_term'],
                        'tax_apy_local'                         => $input['tax_apy_local'],
                        'tax_apy_local_detail'                  => $input['tax_apy_local_detail'],
                        'hydroelectric'                         => $input['hydroelectric'],
                        'hydroelectric_detail'                  => $input['hydroelectric_detail'],
                        'garbage_collection'                    => $input['garbage_collection'],
                        'garbage_collection_detail'             => $input['garbage_collection_detail'],
                        'fire_service'                          => $input['fire_service'],
                        'fire_service_detail'                   => $input['fire_service_detail'],
                        'insurance_excess'                      => $input['insurance_excess'],
                        'insurance_excess_detail'               => $input['insurance_excess_detail'],
                        'air_conditioning'                      => $input['air_conditioning'],
                        'air_conditioning_detail'               => $input['air_conditioning_detail'],
                        'provide_toilets'                       => $input['provide_toilets'],
                        'provide_toilets_detail'                => $input['provide_toilets_detail'],
                        'maintenance_cost_for_garden'           => $input['maintenance_cost_for_garden'],
                        'maintenance_cost_for_garden_detail'    => $input['maintenance_cost_for_garden_detail'],
                        'maintenance_cost_for_parks'            => $input['maintenance_cost_for_parks'],
                        'maintenance_cost_for_parks_detail'     => $input['maintenance_cost_for_parks_detail'],
                        'management_cost'                       => $input['management_cost'],
                        'management_cost_detail'                => $input['management_cost_detail'],
                        'incurred_cost'                         => $input['incurred_cost'],
                        'incurred_cost_detail'                  => $input['incurred_cost_detail'],
                        'fixtures_fittings'                     => $input['fixtures_fittings'],
                        'fixtures_fittings_upload_url'          => $input['fixtures_fittings_upload_url'],
                        'premises_condition'                    => $input['premises_condition'],
                        'premises_condition_upload_url'         => $input['premises_condition_upload_url'],
                        'notes'                                 => $input['notes'],
                        'notes_upload_url'                      => $input['notes_upload_url'],
                        'landlord_signature'                    => $input['landlord_signature'],
                        'tenement_signature'                    => $input['tenement_signature'],
                        'rent_end_date'                         => $input['rent_end_date'],
                        'rent_fee'                              => $input['rent_fee'],
                        'created_at'                            => date('Y-m-d H:i:s', time()),
                    ];
                    $business_res = $business_model->insert($business_data);
                    if ($contract_tenement_res && $business_res) {
                        return $this->success('contract add success');
                    } else {
                        return $this->error('3', 'add contract failed');
                    }
                } else {
                    return $this->error('3', 'add contract failed');
                }
            }
        }
    }

    /**
     * @description:租约列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentContactList(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if(!$user_info->user_role % 2){
            return $this->error('2','this account is not a landlord role');
        }else{
            $model = new RentContract();
            $contact_status = @$input['contact_status'];
            if($contact_status){
                $model = $model->where('status',$contact_status);
            }
            $model = $model->where('house_id',$input['rent_house_id']);
            $model = $model->where('user_id',$input['user_id']);
            $count = $model->count();
            $page = $input['page'];
            if($count < ($page-1)*10){
                return $this->error('3','no contact');
            }else{
                $res = $model->offset(($page-1)*10)->limit(10)->get()->toArray();
                foreach($res as $key => $value){
                    $res[$key]['property_name'] = RentHouse::where('id',$value['house_id'])->pluck('property_name')->first();
                    $res[$key]['tenement_name'] = ContractTenement::where('contract_id',$value['id'])->pluck('tenement_full_name')->first();
                    $tenement_id = ContractTenement::where('contract_id',$value['id'])->pluck('tenement_id')->first();
                    if($tenement_id){
                        $res[$key]['tenement_sn'] = Tenement::where('id',$tenement_id)->pluck('tenement_id')->first();
                        $res[$key]['tenement_id'] = $tenement_id;
                        $res[$key]['tenement_headimg'] = Tenement::where('id',$tenement_id)->pluck('headimg')->first();
                    }else{
                        $res[$key]['tenement_sn'] = '';
                        $res[$key]['tenement_id'] = '';
                        $res[$key]['tenement_headimg'] = '';
                    }
                }
                $data['contract_list'] = $res;
                $data['total_page'] = ceil($count/10);
                $data['current_page'] = $page;
                return $this->success('get rent contract list success',$data);
            }
        }
    }

    /**
     * @description:租约详情
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentContactDetail(array $input)
    {
        //dd($input);
        $model = new RentContract();
        $res = $model->where('id',$input['contract_id'])->first();
        if($res->contract_contract_type == 1){
            $res = $model->where('id',$input['contract_id'])->leftjoin('contract_tenement','contract_tenement.contract_id','=','id')
                ->leftjoin('entire_contract','entire_contract.contract_id','=','id')->first();
            if($res){
                return $this->success('get contact success',$res);
            }else{
                return $this->error('2','get contact failed');
            }
        }elseif($res->contract_contract_type == 2 || $res->contract_contract_type == 3){
            $res = $model->where('id',$input['contract_id'])->leftjoin('contract_tenement','contract_tenement.contract_id','=','id')
                ->leftjoin('separate_contract','separate_contract.contract_id','=','id')->first();
            if($res){
                return $this->success('get contact success',$res);
            }else{
                return $this->error('2','get contact failed');
            }
        }elseif($res->contract_contract_type == 4){
            $res = $model->where('id',$input['contract_id'])->leftjoin('contract_tenement','contract_tenement.contract_id','=','id')
                ->leftjoin('business_contract','business_contract.contract_id','=','id')->first();
            if($res){
                return $this->success('get contact success',$res);
            }else{
                return $this->error('2','get contact failed');
            }
        }

    }




    /**
     * @description:修改租约
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentContactEdit(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id', $input['user_id'])->first();
        if (!$user_info->user_role % 2) {
            return $this->error('2', 'this account is not a landlord role');
        } else {
            $model = new RentContract();
            $contract_data = [
                'contract_id'                   => contractId(),
                'house_id'                      => $input['house_id'],
                'landlord_id'                   => $input['landlord_id'],
                'landlord_full_name'            => $input['landlord_full_name'],
                'landlord_e_mail'               => $input['landlord_e_mail'],
                'house_address'                 => $input['house_address'],
                'landlord_mobile_phone'         => $input['landlord_mobile_phone'],
                'landlord_telephone'            => $input['landlord_telephone'],
                'landlord_hm'                   => $input['landlord_hm'],
                'landlord_wk'                   => $input['landlord_wk'],
                'landlord_other_address'        => $input['landlord_other_address'],
                'landlord_additional_address'   => $input['landlord_additional_address'],
                'landlord_wish'                 => $input['landlord_wish'],
                'contract_type'                 => $input['contract_type'],
                'created_at'                    => date('Y-m-d H:i:s', time())
            ];
            if ($input['contract_type'] == 1) {
                $contract_res = $model->insertGetId($contract_data);
                if ($contract_res) {
                    $contract_tenement_model = new ContractTenement();
                    foreach ($input['tenement_info'] as $k => $v){
                        $contract_tenement_data = [
                            'contract_id'               => $contract_res,
                            'tenement_id'               => @$v['tenement_id'],
                            'tenement_full_name'        => $v['tenement_full_name'],
                            'identification_no'         => $v['identification_no'],
                            'identification_type'       => $v['identification_type'],
                            'service_physical_address'  => $v['service_physical_address'],
                            'tenement_e_mail'           => $v['tenement_e_mail'],
                            'tenement_phone'            => $v['tenement_phone'],
                            'tenement_mobile'           => $v['tenement_mobile'],
                            'tenement_hm'               => $v['tenement_hm'],
                            'tenement_wk'               => $v['tenement_wk'],
                            'tenement_post_address'     => $v['tenement_post_address'],
                            'tenement_post_code'        => $v['tenement_post_code'],
                            'tenement_service_address'  => $v['tenement_service_address'],
                            'other_contact_address'     => $v['other_contact_address'],
                            'additional_address'        => $v['additional_address'],
                            'guarantor_name'            => $v['guarantor_name'],
                            'occupation'                => $v['occupation'],
                            'home_address'              => $v['home_address'],
                            'guarantor_phone'           => $v['guarantor_phone'],
                            'guarantor_e_mail'          => $v['guarantor_e_mail'],
                            'is_child'                  => $v['is_child'],
                            'created_at'                => date('Y-m-d H:i:s', time()),
                        ];
                        $contract_tenement_res = $contract_tenement_model->insert($contract_tenement_data);
                    }
                    $entire_model = new EntireContract();
                    $entire_data = [
                        'contract_id'                               => $contract_res,
                        'tenancy_address'                           => $input['tenancy_address'],
                        'rent_per_week'                             => $input['rent_per_week'],
                        'pay_method'                                => $input['pay_method'],
                        'bond_amount'                               => $input['bond_amount'],
                        'rent_to_be_paid_at'                        => $input['rent_to_be_paid_at'],
                        'bank_account'                              => $input['bank_account'],
                        'account_name'                              => $input['account_name'],
                        'bank'                                      => $input['bank'],
                        'branch'                                    => $input['branch'],
                        'effective_date'                            => $input['effective_date'],
                        'can_periodic_tenancy'                      => $input['can_periodic_tenancy'],
                        'end_date'                                  => $input['end_date'],
                        'rule'                                      => $input['rule'],
                        'rule_upload_url'                           => $input['rule_upload_url'],
                        'meter_readings'                            => $input['meter_readings'],
                        'is_ceiling_insulation'                     => $input['is_ceiling_insulation'],
                        'ceiling_insulation_detail'                 => $input['ceiling_insulation_detail'],
                        'is_insulation_underfloor_insulation'       => $input['is_insulation_underfloor_insulation'],
                        'insulation_underfloor_insulation_detail'   => $input['insulation_underfloor_insulation_detail'],
                        'location_ceiling_insulation'               => $input['location_ceiling_insulation'],
                        'location_ceiling_insulation_detail'        => $input['location_ceiling_insulation_detail'],
                        'ceiling_insulation_type'                   => $input['ceiling_insulation_type'],
                        'ceiling_insulation_type_detail'            => $input['ceiling_insulation_type_detail'],
                        'R_value'                                   => $input['R_value'],
                        'minimum_thickness'                         => $input['minimum_thickness'],
                        'ceiling_insulation_age'                    => $input['ceiling_insulation_age'],
                        'ceiling_insulation_condition'              => $input['ceiling_insulation_condition'],
                        'ceiling_insulation_condition_reason'       => $input['ceiling_insulation_condition_reason'],
                        'location_underfloor_insulation'            => $input['location_underfloor_insulation'],
                        'location_underfloor_insulation_detail'     => $input['location_underfloor_insulation_detail'],
                        'underfloor_insulation_type'                => $input['underfloor_insulation_type'],
                        'underfloor_insulation_type_detail'         => $input['underfloor_insulation_type_detail'],
                        'underfloor_R_value'                        => $input['underfloor_R_value'],
                        'underfloor_minimum_thickness'              => $input['underfloor_minimum_thickness'],
                        'condition'                                 => $input['condition'],
                        'condition_detail'                          => $input['condition_detail'],
                        'wall_insulation'                           => $input['wall_insulation'],
                        'wall_insulation_detail'                    => $input['wall_insulation_detail'],
                        'supplementary_information'                 => $input['supplementary_information'],
                        'install_insulation'                        => $input['install_insulation'],
                        'install_insulation_detail'                 => $input['install_insulation_detail'],
                        'underfloor_insulation'                     => $input['underfloor_insulation'],
                        'underfloor_insulation_detail'              => $input['underfloor_insulation_detail'],
                        'last_upgraded'                             => $input['last_upgraded'],
                        'professionally_assessed'                   => $input['professionally_assessed'],
                        'plan'                                      => $input['plan'],
                        'landlord_state'                            => $input['landlord_state'],
                        'landlord_signature'                        => $input['landlord_signature'],
                        'tenement_signature'                        => $input['tenement_signature'],
                        'rent_end_date'                             => $input['rent_end_date'],
                        'rent_fee'                                  => $input['rent_fee'],
                        'created_at'                                => date('Y-m-d H:i:s', time()),
                    ];
                    $entire_res = $entire_model->insert($entire_data);
                    if ($contract_tenement_res && $entire_res) {
                        return $this->success('contract add success');
                    } else {
                        return $this->error('3', 'add contract failed');
                    }
                } else {
                    return $this->error('3', 'add contract failed');
                }
            } elseif ($input['contract_type'] == 2 || $input['contract_type'] == 3) {
                $contract_res = $model->insertGetId($contract_data);
                if ($contract_res) {
                    $contract_tenement_model = new ContractTenement();
                    foreach ($input['tenement_info'] as $k => $v){
                        $contract_tenement_data = [
                            'contract_id'               => $contract_res,
                            'tenement_id'               => @$v['tenement_id'],
                            'tenement_full_name'        => $v['tenement_full_name'],
                            'identification_no'         => $v['identification_no'],
                            'identification_type'       => $v['identification_type'],
                            'service_physical_address'  => $v['service_physical_address'],
                            'tenement_e_mail'           => $v['tenement_e_mail'],
                            'tenement_phone'            => $v['tenement_phone'],
                            'tenement_mobile'           => $v['tenement_mobile'],
                            'tenement_hm'               => $v['tenement_hm'],
                            'tenement_wk'               => $v['tenement_wk'],
                            'tenement_post_address'     => $v['tenement_post_address'],
                            'tenement_post_code'        => $v['tenement_post_code'],
                            'tenement_service_address'  => $v['tenement_service_address'],
                            'other_contact_address'     => $v['other_contact_address'],
                            'additional_address'        => $v['additional_address'],
                            'guarantor_name'            => $v['guarantor_name'],
                            'occupation'                => $v['occupation'],
                            'home_address'              => $v['home_address'],
                            'guarantor_phone'           => $v['guarantor_phone'],
                            'guarantor_e_mail'          => $v['guarantor_e_mail'],
                            'is_child'                  => $v['is_child'],
                            'created_at'                => date('Y-m-d H:i:s', time()),
                        ];
                        $contract_tenement_res = $contract_tenement_model->insert($contract_tenement_data);
                    }
                    $separate_model = new SeparateContract();
                    $separate_data = [
                        'contract_id'                               => $contract_res,
                        'agent_name'                                => $input['agent_name'],
                        'agent_address'                             => $input['agent_address'],
                        'agent_e_mail'                              => $input['agent_e_mail'],
                        'agent_phone'                               => $input['agent_phone'],
                        'agent_mobile'                              => $input['agent_mobile'],
                        'agent_hm'                                  => $input['agent_hm'],
                        'agent_wk'                                  => $input['agent_wk'],
                        'agent_other_address'                       => $input['agent_other_address'],
                        'agent_additional_address'                  => $input['agent_additional_address'],
                        'tenancy_address'                           => $input['tenancy_address'],
                        'rent_per_week'                             => $input['rent_per_week'],
                        'is_house_rule'                             => $input['is_house_rule'],
                        'is_fire'                                   => $input['is_fire'],
                        'is_body'                                   => $input['is_body'],
                        'pay_method'                                => $input['pay_method'],
                        'bond_amount'                               => $input['bond_amount'],
                        'to_be_paid'                                => $input['to_be_paid'],
                        'rent_to_be_paid_at'                        => $input['rent_to_be_paid_at'],
                        'bank_account'                              => $input['bank_account'],
                        'account_name'                              => $input['account_name'],
                        'bank'                                      => $input['bank'],
                        'branch'                                    => $input['branch'],
                        'agree_date'                                => $input['agree_date'],
                        'intended'                                  => $input['intended'],
                        'is_joint_tenancy'                          => $input['is_joint_tenancy'],
                        'is_joint_tenancy_detail'                   => $input['is_joint_tenancy_detail'],
                        'is_not_share'                              => $input['is_not_share'],
                        'is_share_people'                           => $input['is_share_people'],
                        'allow_service'                             => $input['allow_service'],
                        'is_ceiling_insulation'                     => $input['is_ceiling_insulation'],
                        'ceiling_insulation_detail'                 => $input['ceiling_insulation_detail'],
                        'is_insulation_underfloor_insulation'       => $input['is_insulation_underfloor_insulation'],
                        'insulation_underfloor_insulation_detail'   => $input['insulation_underfloor_insulation_detail'],
                        'location_ceiling_insulation'               => $input['location_ceiling_insulation'],
                        'location_ceiling_insulation_detail'        => $input['location_ceiling_insulation_detail'],
                        'ceiling_insulation_type'                   => $input['ceiling_insulation_type'],
                        'ceiling_insulation_type_detail'            => $input['ceiling_insulation_type_detail'],
                        'R_value'                                   => $input['R_value'],
                        'minimum_thickness'                         => $input['minimum_thickness'],
                        'ceiling_insulation_age'                    => $input['ceiling_insulation_age'],
                        'ceiling_insulation_condition'              => $input['ceiling_insulation_condition'],
                        'ceiling_insulation_condition_reason'       => $input['ceiling_insulation_condition_reason'],
                        'location_underfloor_insulation'            => $input['location_underfloor_insulation'],
                        'location_underfloor_insulation_detail'     => $input['location_underfloor_insulation_detail'],
                        'underfloor_insulation_type'                => $input['underfloor_insulation_type'],
                        'underfloor_insulation_type_detail'         => $input['underfloor_insulation_type_detail'],
                        'underfloor_R_value'                        => $input['underfloor_R_value'],
                        'underfloor_minimum_thickness'              => $input['underfloor_minimum_thickness'],
                        'condition'                                 => $input['condition'],
                        'condition_detail'                          => $input['condition_detail'],
                        'wall_insulation'                           => $input['wall_insulation'],
                        'wall_insulation_detail'                    => $input['wall_insulation_detail'],
                        'supplementary_information'                 => $input['supplementary_information'],
                        'install_insulation'                        => $input['install_insulation'],
                        'install_insulation_detail'                 => $input['install_insulation_detail'],
                        'underfloor_insulation'                     => $input['underfloor_insulation'],
                        'underfloor_insulation_detail'              => $input['underfloor_insulation_detail'],
                        'last_upgraded'                             => $input['last_upgraded'],
                        'professionally_assessed'                   => $input['professionally_assessed'],
                        'plan'                                      => $input['plan'],
                        'landlord_state'                            => $input['landlord_state'],
                        'landlord_signature'                        => $input['landlord_signature'],
                        'tenement_signature'                        => $input['tenement_signature'],
                        'rent_end_date'                             => $input['rent_end_date'],
                        'rent_fee'                                  => $input['rent_fee'],
                        'created_at'                                => date('Y-m-d H:i:s', time()),
                    ];
                    $separate_res = $separate_model->insert($separate_data);
                    if ($contract_tenement_res && $separate_res) {
                        return $this->success('contract add success');
                    } else {
                        return $this->error('3', 'add contract failed');
                    }
                } else {
                    return $this->error('3', 'add contract failed');
                }
            }
            elseif ($input['contract_type'] == 4 ) {
                $contract_res = $model->insertGetId($contract_data);
                if ($contract_res) {
                    $contract_tenement_model = new ContractTenement();
                    foreach ($input['tenement_info'] as $k => $v){
                        $contract_tenement_data = [
                            'contract_id'               => $contract_res,
                            'tenement_id'               => @$v['tenement_id'],
                            'tenement_full_name'        => $v['tenement_full_name'],
                            'identification_no'         => $v['identification_no'],
                            'identification_type'       => $v['identification_type'],
                            'service_physical_address'  => $v['service_physical_address'],
                            'tenement_e_mail'           => $v['tenement_e_mail'],
                            'tenement_phone'            => $v['tenement_phone'],
                            'tenement_mobile'           => $v['tenement_mobile'],
                            'tenement_hm'               => $v['tenement_hm'],
                            'tenement_wk'               => $v['tenement_wk'],
                            'tenement_post_address'     => $v['tenement_post_address'],
                            'tenement_post_code'        => $v['tenement_post_code'],
                            'tenement_service_address'  => $v['tenement_service_address'],
                            'other_contact_address'     => $v['other_contact_address'],
                            'additional_address'        => $v['additional_address'],
                            'guarantor_name'            => $v['guarantor_name'],
                            'occupation'                => $v['occupation'],
                            'home_address'              => $v['home_address'],
                            'guarantor_phone'           => $v['guarantor_phone'],
                            'guarantor_e_mail'          => $v['guarantor_e_mail'],
                            'is_child'                  => $v['is_child'],
                            'created_at'                => date('Y-m-d H:i:s', time()),
                        ];
                        $contract_tenement_res = $contract_tenement_model->insert($contract_tenement_data);
                    }
                    $contract_tenement_res = $contract_tenement_model->insert($contract_tenement_data);
                    $business_model = new BusinessContract();
                    $business_data = [
                        'contract_id'                           => $contract_res,
                        'premises'                              => $input['premises'],
                        'car_parks'                             => $input['car_parks'],
                        'lease_term'                            => $input['lease_term'],
                        'term_method'                           => $input['term_method'],
                        'commencement_date'                     => $input['commencement_date'],
                        'final_expiry_date'                     => $input['final_expiry_date'],
                        'renewal_time'                          => $input['renewal_time'],
                        'annual_rent'                           => $input['annual_rent'],
                        'premises_pro'                          => $input['premises_pro'],
                        'premises_gst'                          => $input['premises_gst'],
                        'car_parks_pro'                         => $input['car_parks_pro'],
                        'car_gst'                               => $input['car_gst'],
                        'total'                                 => $input['total'],
                        'total_gst'                             => $input['total_gst'],
                        'month_rent'                            => $input['month_rent'],
                        'rent_payment_date'                     => $input['rent_payment_date'],
                        'day_each_month'                        => $input['day_each_month'],
                        'market_rent_assessment_date'           => $input['market_rent_assessment_date'],
                        'cpi_date'                              => $input['cpi_date'],
                        'outgoing'                              => $input['outgoing'],
                        'default_interest_rate'                 => $input['default_interest_rate'],
                        'commercial_use'                        => $input['commercial_use'],
                        'business_use'                          => $input['business_use'],
                        'insurance'                             => $input['insurance'],
                        'no_access_period'                      => $input['no_access_period'],
                        'further_term'                          => $input['further_term'],
                        'tax_apy_local'                         => $input['tax_apy_local'],
                        'tax_apy_local_detail'                  => $input['tax_apy_local_detail'],
                        'hydroelectric'                         => $input['hydroelectric'],
                        'hydroelectric_detail'                  => $input['hydroelectric_detail'],
                        'garbage_collection'                    => $input['garbage_collection'],
                        'garbage_collection_detail'             => $input['garbage_collection_detail'],
                        'fire_service'                          => $input['fire_service'],
                        'fire_service_detail'                   => $input['fire_service_detail'],
                        'insurance_excess'                      => $input['insurance_excess'],
                        'insurance_excess_detail'               => $input['insurance_excess_detail'],
                        'air_conditioning'                      => $input['air_conditioning'],
                        'air_conditioning_detail'               => $input['air_conditioning_detail'],
                        'provide_toilets'                       => $input['provide_toilets'],
                        'provide_toilets_detail'                => $input['provide_toilets_detail'],
                        'maintenance_cost_for_garden'           => $input['maintenance_cost_for_garden'],
                        'maintenance_cost_for_garden_detail'    => $input['maintenance_cost_for_garden_detail'],
                        'maintenance_cost_for_parks'            => $input['maintenance_cost_for_parks'],
                        'maintenance_cost_for_parks_detail'     => $input['maintenance_cost_for_parks_detail'],
                        'management_cost'                       => $input['management_cost'],
                        'management_cost_detail'                => $input['management_cost_detail'],
                        'incurred_cost'                         => $input['incurred_cost'],
                        'incurred_cost_detail'                  => $input['incurred_cost_detail'],
                        'fixtures_fittings'                     => $input['fixtures_fittings'],
                        'fixtures_fittings_upload_url'          => $input['fixtures_fittings_upload_url'],
                        'premises_condition'                    => $input['premises_condition'],
                        'premises_condition_upload_url'         => $input['premises_condition_upload_url'],
                        'notes'                                 => $input['notes'],
                        'notes_upload_url'                      => $input['notes_upload_url'],
                        'landlord_signature'                    => $input['landlord_signature'],
                        'tenement_signature'                    => $input['tenement_signature'],
                        'rent_end_date'                         => $input['rent_end_date'],
                        'rent_fee'                              => $input['rent_fee'],
                        'created_at'                            => date('Y-m-d H:i:s', time()),
                    ];
                    $business_res = $business_model->insert($business_data);
                    if ($contract_tenement_res && $business_res) {
                        return $this->success('contract add success');
                    } else {
                        return $this->error('3', 'add contract failed');
                    }
                } else {
                    return $this->error('3', 'add contract failed');
                }
            }
        }
    }

    /**
     * @description:租约生效
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentContactEffect(array $input)
    {
        //dd($input);
        $model = new RentContract();
        $effect_data = [
            'contract_status'   => 2,
            'rent_start_date'   => $input['rent_start_date'],
            'rent_end_date'     => $input['rent_end_date'],
        ];
        $res = $model->where('id',$input['contract_id'])->update($effect_data);
        if($res){
            $contract_data = $model->where('id',$input['contract_id'])->first();
            if($contract_data->contract_type == 1){
                // 生成押金记录
                $contract_tenement_data = ContractTenement::where('contract_id',$input['contract_id'])->first();
                $rent_house_info = RentHouse::where('id',$contract_data->house_id)->first();
                $entire_data = EntireContract::where('contract_id',$input['contract_id'])->first();
                $bond_data = [
                    'contract_id'       => $input['contract_id'],
                    'contract_sn'       => $contract_data->contract_id,
                    'user_id'           => $input['user_id'],
                    'tenement_name'     => $contract_tenement_data->tenement_full_name,
                    'property_name'     => $rent_house_info->property_name,
                    'tenement_phone'    => $contract_tenement_data->tenement_phone,
                    'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                    'total_bond'        => $entire_data->bond_amount,
                    'created_at'        => date('Y-m-d H:i:s',time()),
                ];
                $bond_res = Bond::insert($bond_data);
                // 生成预付记录
                if(0>strtotime($input['rent_start_date'])-time()&&strtotime($input['rent_start_date'])-time()>-3600*24*60){
                    if($entire_data->pay_method == 2){
                        $cycle = ceil((time()-strtotime($input['rent_start_date']))/3600/24/7);
                        for($i=0;$i<$cycle;$i++){
                            $arrears_data = [
                                'contract_id'       => $input['contract_id'],
                                'contract_sn'       => $contract_data->contract_id,
                                'user_id'           => $input['user_id'],
                                'rent_house_id'     => $contract_data->house_id,
                                'tenement_id'       => $contract_tenement_data->tenement_id,
                                'tenement_name'     => $contract_tenement_data->tenement_full_name,
                                'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                                'effect_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*7*$i),
                                'rent_fee'          => $entire_data->rent_per_week,
                                'arrears'           => $entire_data->rent_per_week,
                                'created_at'        => date('Y-m-d H:i:s',time()),
                            ];
                            RentFee::insert($arrears_data);
                        }
                    }elseif ($entire_data->pay_method == 3){
                        $cycle = ceil((time()-strtotime($input['rent_start_date']))/3600/24/14);
                        for($i=0;$i<$cycle;$i++){
                            $arrears_data = [
                                'contract_id'       => $input['contract_id'],
                                'contract_sn'       => $contract_data->contract_id,
                                'user_id'           => $input['user_id'],
                                'rent_house_id'     => $contract_data->house_id,
                                'tenement_id'       => $contract_tenement_data->tenement_id,
                                'tenement_name'     => $contract_tenement_data->tenement_full_name,
                                'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                                'effect_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*14*$i),
                                'rent_fee'          => $entire_data->rent_per_week,
                                'arrears'           => $entire_data->rent_per_week,
                                'created_at'        => date('Y-m-d H:i:s',time()),
                            ];
                            RentFee::insert($arrears_data);
                        }
                    }elseif ($entire_data->pay_method == 4){
                        $cycle = ceil((time()-strtotime($input['rent_start_date']))/3600/24/30);
                        for($i=0;$i<$cycle;$i++){
                            $arrears_data = [
                                'contract_id'       => $input['contract_id'],
                                'contract_sn'       => $contract_data->contract_id,
                                'user_id'           => $input['user_id'],
                                'rent_house_id'     => $contract_data->house_id,
                                'tenement_id'       => $contract_tenement_data->tenement_id,
                                'tenement_name'     => $contract_tenement_data->tenement_full_name,
                                'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                                'effect_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*30*$i),
                                'rent_fee'          => $entire_data->rent_per_week,
                                'arrears'           => $entire_data->rent_per_week,
                                'created_at'        => date('Y-m-d H:i:s',time()),
                            ];
                            RentFee::insert($arrears_data);
                        }
                    }
                }else if(0<strtotime($input['rent_start_date'])-time()&&strtotime($input['rent_start_date'])-time()<3600*24*60){
                    if($entire_data->pay_method == 2){
                        $cycle = ceil((time()-strtotime($input['rent_start_date']))/3600/24/7);
                        for($i=0;$i<$cycle;$i++){
                            $arrears_data = [
                                'contract_id'       => $input['contract_id'],
                                'contract_sn'       => $contract_data->contract_id,
                                'user_id'           => $input['user_id'],
                                'rent_house_id'     => $contract_data->house_id,
                                'tenement_id'       => $contract_tenement_data->tenement_id,
                                'tenement_name'     => $contract_tenement_data->tenement_full_name,
                                'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                                'effect_date'       => $input['rent_start_date'],
                                'rent_fee'          => $entire_data->rent_per_week,
                                'arrears'           => $entire_data->rent_per_week,
                                'created_at'        => date('Y-m-d H:i:s',time()),
                            ];
                            RentFee::insert($arrears_data);
                        }
                    }elseif ($entire_data->pay_method == 3){
                        $cycle = ceil((time()-strtotime($input['rent_start_date']))/3600/24/14);
                        for($i=0;$i<$cycle;$i++){
                            $arrears_data = [
                                'contract_id'       => $input['contract_id'],
                                'contract_sn'       => $contract_data->contract_id,
                                'user_id'           => $input['user_id'],
                                'rent_house_id'     => $contract_data->house_id,
                                'tenement_id'       => $contract_tenement_data->tenement_id,
                                'tenement_name'     => $contract_tenement_data->tenement_full_name,
                                'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                                'effect_date'       => $input['rent_start_date'],
                                'rent_fee'          => $entire_data->rent_per_week,
                                'arrears'           => $entire_data->rent_per_week,
                                'created_at'        => date('Y-m-d H:i:s',time()),
                            ];
                            RentFee::insert($arrears_data);
                        }
                    }elseif ($entire_data->pay_method == 4){
                        $cycle = ceil((time()-strtotime($input['rent_start_date']))/3600/24/30);
                        for($i=0;$i<$cycle;$i++){
                            $arrears_data = [
                                'contract_id'       => $input['contract_id'],
                                'contract_sn'       => $contract_data->contract_id,
                                'user_id'           => $input['user_id'],
                                'rent_house_id'     => $contract_data->house_id,
                                'tenement_id'       => $contract_tenement_data->tenement_id,
                                'tenement_name'     => $contract_tenement_data->tenement_full_name,
                                'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                                'effect_date'       => $input['rent_start_date'],
                                'rent_fee'          => $entire_data->rent_per_week,
                                'arrears'           => $entire_data->rent_per_week,
                                'created_at'        => date('Y-m-d H:i:s',time()),
                            ];
                            RentFee::insert($arrears_data);
                        }
                    }
                }
            }elseif ($contract_data->contract_type == 2 || $contract_data->contract_type == 3){
                // 生成押金记录
                $contract_tenement_data = ContractTenement::where('contract_id',$input['contract_id'])->first();
                $rent_house_info = RentHouse::where('id',$contract_data->house_id)->first();
                $separate_data = SeparateContract::where('contract_id',$input['contract_id'])->first();
                $bond_data = [
                    'contract_id'       => $input['contract_id'],
                    'contract_sn'       => $contract_data->contract_id,
                    'user_id'           => $input['user_id'],
                    'tenement_name'     => $contract_tenement_data->tenement_full_name,
                    'property_name'     => $rent_house_info->property_name,
                    'tenement_phone'    => $contract_tenement_data->tenement_phone,
                    'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                    'total_bond'        => $separate_data->bond_amount,
                    'created_at'        => date('Y-m-d H:i:s',time()),
                ];
                $bond_res = Bond::insert($bond_data);
                // 生成预付记录
                if(0>strtotime($input['rent_start_date'])-time()&&strtotime($input['rent_start_date'])-time()>-3600*24*60){
                    if($separate_data->pay_method == 2){
                        $cycle = ceil((time()-strtotime($input['rent_start_date']))/3600/24/7);
                        for($i=0;$i<$cycle;$i++){
                            $arrears_data = [
                                'contract_id'       => $input['contract_id'],
                                'contract_sn'       => $contract_data->contract_id,
                                'user_id'           => $input['user_id'],
                                'rent_house_id'     => $contract_data->house_id,
                                'tenement_id'       => $contract_tenement_data->tenement_id,
                                'tenement_name'     => $contract_tenement_data->tenement_full_name,
                                'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                                'effect_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*7*$i),
                                'rent_fee'          => $separate_data->rent_per_week,
                                'arrears'           => $separate_data->rent_per_week,
                                'created_at'        => date('Y-m-d H:i:s',time()),
                            ];
                            RentFee::insert($arrears_data);
                        }
                    }elseif ($separate_data->pay_method == 3){
                        $cycle = ceil((time()-strtotime($input['rent_start_date']))/3600/24/14);
                        for($i=0;$i<$cycle;$i++){
                            $arrears_data = [
                                'contract_id'       => $input['contract_id'],
                                'contract_sn'       => $contract_data->contract_id,
                                'user_id'           => $input['user_id'],
                                'rent_house_id'     => $contract_data->house_id,
                                'tenement_id'       => $contract_tenement_data->tenement_id,
                                'tenement_name'     => $contract_tenement_data->tenement_full_name,
                                'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                                'effect_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*14*$i),
                                'rent_fee'          => $separate_data->rent_per_week,
                                'arrears'           => $separate_data->rent_per_week,
                                'created_at'        => date('Y-m-d H:i:s',time()),
                            ];
                            RentFee::insert($arrears_data);
                        }
                    }elseif ($separate_data->pay_method == 4){
                        $cycle = ceil((time()-strtotime($input['rent_start_date']))/3600/24/30);
                        for($i=0;$i<$cycle;$i++){
                            $arrears_data = [
                                'contract_id'       => $input['contract_id'],
                                'contract_sn'       => $contract_data->contract_id,
                                'user_id'           => $input['user_id'],
                                'rent_house_id'     => $contract_data->house_id,
                                'tenement_id'       => $contract_tenement_data->tenement_id,
                                'tenement_name'     => $contract_tenement_data->tenement_full_name,
                                'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                                'effect_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*30*$i),
                                'rent_fee'          => $separate_data->rent_per_week,
                                'arrears'           => $separate_data->rent_per_week,
                                'created_at'        => date('Y-m-d H:i:s',time()),
                            ];
                            RentFee::insert($arrears_data);
                        }
                    }
                }else if(0<strtotime($input['rent_start_date'])-time()&&strtotime($input['rent_start_date'])-time()<3600*24*60){
                    if($separate_data->pay_method == 2){
                        $cycle = ceil((time()-strtotime($input['rent_start_date']))/3600/24/7);
                        for($i=0;$i<$cycle;$i++){
                            $arrears_data = [
                                'contract_id'       => $input['contract_id'],
                                'contract_sn'       => $contract_data->contract_id,
                                'user_id'           => $input['user_id'],
                                'rent_house_id'     => $contract_data->house_id,
                                'tenement_id'       => $contract_tenement_data->tenement_id,
                                'tenement_name'     => $contract_tenement_data->tenement_full_name,
                                'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                                'effect_date'       => $input['rent_start_date'],
                                'rent_fee'          => $separate_data->rent_per_week,
                                'arrears'           => $separate_data->rent_per_week,
                                'created_at'        => date('Y-m-d H:i:s',time()),
                            ];
                            RentFee::insert($arrears_data);
                        }
                    }elseif ($separate_data->pay_method == 3){
                        $cycle = ceil((time()-strtotime($input['rent_start_date']))/3600/24/14);
                        for($i=0;$i<$cycle;$i++){
                            $arrears_data = [
                                'contract_id'       => $input['contract_id'],
                                'contract_sn'       => $contract_data->contract_id,
                                'user_id'           => $input['user_id'],
                                'rent_house_id'     => $contract_data->house_id,
                                'tenement_id'       => $contract_tenement_data->tenement_id,
                                'tenement_name'     => $contract_tenement_data->tenement_full_name,
                                'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                                'effect_date'       => $input['rent_start_date'],
                                'rent_fee'          => $separate_data->rent_per_week,
                                'arrears'           => $separate_data->rent_per_week,
                                'created_at'        => date('Y-m-d H:i:s',time()),
                            ];
                            RentFee::insert($arrears_data);
                        }
                    }elseif ($separate_data->pay_method == 4){
                        $cycle = ceil((time()-strtotime($input['rent_start_date']))/3600/24/30);
                        for($i=0;$i<$cycle;$i++){
                            $arrears_data = [
                                'contract_id'       => $input['contract_id'],
                                'contract_sn'       => $contract_data->contract_id,
                                'user_id'           => $input['user_id'],
                                'rent_house_id'     => $contract_data->house_id,
                                'tenement_id'       => $contract_tenement_data->tenement_id,
                                'tenement_name'     => $contract_tenement_data->tenement_full_name,
                                'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                                'effect_date'       => $input['rent_start_date'],
                                'rent_fee'          => $separate_data->rent_per_week,
                                'arrears'           => $separate_data->rent_per_week,
                                'created_at'        => date('Y-m-d H:i:s',time()),
                            ];
                            RentFee::insert($arrears_data);
                        }
                    }
                }
            }elseif ($contract_data->contract_type == 4){
                // 生成押金记录
                $contract_tenement_data = ContractTenement::where('contract_id',$input['contract_id'])->first();
                $rent_house_info = RentHouse::where('id',$contract_data->house_id)->first();
                $business_data = BusinessContract::where('contract_id',$input['contract_id'])->first();
                // 生成预付记录
                if(0>strtotime($input['rent_start_date'])-time()&&strtotime($input['rent_start_date'])-time()>-3600*24*60){
                    $cycle = ceil((time()-strtotime($input['rent_start_date']))/3600/24/30);
                    for($i=0;$i<$cycle;$i++){
                        $arrears_data = [
                            'contract_id'       => $input['contract_id'],
                            'contract_sn'       => $contract_data->contract_id,
                            'user_id'           => $input['user_id'],
                            'rent_house_id'     => $contract_data->house_id,
                            'tenement_id'       => $contract_tenement_data->tenement_id,
                            'tenement_name'     => $contract_tenement_data->tenement_full_name,
                            'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                            'effect_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*30*$i),
                            'rent_fee'          => $business_data->month_rent,
                            'arrears'           => $business_data->month_rent,
                            'created_at'        => date('Y-m-d H:i:s',time()),
                        ];
                        RentFee::insert($arrears_data);
                    }
                }else if(0<strtotime($input['rent_start_date'])-time()&&strtotime($input['rent_start_date'])-time()<3600*24*60){
                    $cycle = ceil((time()-strtotime($input['rent_start_date']))/3600/24/30);
                    for($i=0;$i<$cycle;$i++){
                        $arrears_data = [
                            'contract_id'       => $input['contract_id'],
                            'contract_sn'       => $contract_data->contract_id,
                            'user_id'           => $input['user_id'],
                            'rent_house_id'     => $contract_data->house_id,
                            'tenement_id'       => $contract_tenement_data->tenement_id,
                            'tenement_name'     => $contract_tenement_data->tenement_full_name,
                            'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                            'effect_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*30*$i),
                            'rent_fee'          => $business_data->month_rent,
                            'arrears'           => $business_data->month_rent,
                            'created_at'        => date('Y-m-d H:i:s',time()),
                        ];
                        RentFee::insert($arrears_data);
                    }
                }
            }
            return $this->success('contract effect success');
        }else{
            return $this->error('2','contact effect failed');
        }
    }


    /**
     * @description:查看证件
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewTenementInfo(array $input)
    {
        //dd($input);
        $model = new ContractTenement();
        $res = $model->where('contract_id',$input['contract_id'])->pluck('tenement_id')->first();
        if($res){
            $identification = TenementCertificate::where('tenement_id',$res)->select('certificate_category','certificate_no','certificate_pic1','certificate_pic2')->get();
            return $this->success('get identification success',$identification);
        }else{
            $data[0] = $model->where('contract_id',$input['contract_id'])->select('identification_no as certificate_no','identification_type as certificate_category')->first();
            $data[0]['certificate_pic1'] = '';
            $data[0]['certificate_pic2'] = '';
            return $this->success('get identification success',$data);
        }
    }
}