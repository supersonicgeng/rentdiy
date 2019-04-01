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
        $user_info = User::where('id',$input['user_id'])->first();
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
        $user_info = User::where('id',$input['user_id'])->first();
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
                'property_manager_name' => $input['property_manager_name'],
                'manager_phone'         => $input['manager_phone'],
                'manager_mobile'        => $input['manager_mobile'],
                'inform_landlord'       => $input['inform_landlord'],
                'driving_license'       => @$input['driving_license'],
                'version_num'           => @$input['version_num'],
                'passport'              => @$input['passport'],
                'vehicle'               => @$input['vehicle'],
                'others'                => @$input['others'],
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
        $user_info = User::where('id',$input['user_id'])->first();
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
        $user_info = User::where('id',$input['user_id'])->first();
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
        $user_info = User::where('id',$input['user_id'])->first();
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
                'property_manager_name' => @$input['property_manager_name']?$input['property_manager_name']:$application_info->property_manager_name,
                'manager_phone'         => @$input['manager_phone']?$input['manager_phone']:$application_info->manager_phone,
                'manager_mobile'        => @$input['manager_mobile']?$input['manager_mobile']:$application_info->manager_mobile,
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
        $user_info = User::where('id',$input['user_id'])->first();
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
        $user_info = User::where('id',$input['user_id'])->first();
        if($user_info->user_role % 2){
            $model = new RentApplication();
            $application_start_date = $input['application_start_time'];
            if($application_start_date){
                $model = $model->where('created_at','>',$application_start_date);
            }
            $application_end_date = $input['application_end_time'];
            if($application_end_date){
                $model = $model->where('created_at','<',$application_end_date);
            }
            $application_status = $input['application_status'];
            if($application_status){
                $model = $model->where('status',$application_status);
            }
            $tenement_people = $input['tenement_people'];
            if($tenement_people){
                $model = $model->where('tenement_people',$tenement_people);
            }
            $res = $model->where('house_id',$input['house_id'])->where('deleted_at',null)->get()->toArray();
            if($res){
                foreach ($res as $k => $v){
                    $tenement_info = Tenement::where('id',$v['tenement_id'])->first()->toArray();
                    $res[$k]['tenement_name'] = $tenement_info['first_name'].'&nbsp'.$tenement_info['middle_name'].'&nbsp'.$tenement_info['last_name'];
                    $res[$k]['tenement_headimg'] = $tenement_info['headimg'];
                    $res[$k]['survey_score'] = Survey::where('application_id',$v['id'])->pluck('survey_score')->first();
                    $res[$k]['survey_people'] = Survey::where('application_id',$v['id'])->pluck('survey_people')->first();
                    $res[$k]['survey_date'] = Survey::where('application_id',$v['id'])->pluck('survey_date')->first();
                }
                return $this->success('get application success',$res);
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
        $user_info = User::where('id',$input['user_id'])->first();
        if($user_info->user_role < 4){
            return $this->error('2','this account is not a tenement role');
        }else{
            $model = new RentApplication();
            $application_start_date = $input['application_start_time'];
            if($application_start_date){
                $model = $model->where('created_at','>',$application_start_date);
            }
            $application_end_date = $input['application_end_time'];
            if($application_end_date){
                $model = $model->where('created_at','<',$application_end_date);
            }
            $application_status = $input['application_status'];
            if($application_status){
                $model = $model->where('status',$application_status);
            }
            $count = $model->where('tenement_id',$input['tenement_id'])->count();
            if($count<($page-1)*9){
                return $this->error('3','no data');
            }
            $total_page = ceil($count/9);
            $res = $model->where('tenement_id',$input['tenement_id'])->offset(($page-1)*9)->get()->toArray();
            if($res){
                foreach ($res as $k => $v){
                    $house_info = RentHouse::where('id',$v['house_id'])->select('id','property_name','property_type','address','available_time','rent_fee_pre_week','rent_least_fee','bedroom_no','bathroom_no','parking_no','garage_no','Desc','TA','Region','available_date')->get()->toArray();;
                    $application_res[$k] = $house_info;
                    $application_res[$k]['house_pic'] = RentPic::where('rent_house_id',$v['id'])->where('deleted_At',null)->pluck('house_pic')->toArray();// 图片
                    $application_res[$k]['full_address'] = $house_info['address'].','.Region::getName($house_info['Desc']).','.Region::getName($house_info['TA']).','.Region::getName($house_info['Region']);
                    $application_res[$k]['application_id'] = $v['id'];
                }
                $application_res['total_page'] = $total_page;
                $application_res['current_page'] = $input['page'];
                return $this->success('get application success',$application_res);
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
        $user_info = User::where('id',$input['user_id'])->first();
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
}