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
use App\Model\Inspect;
use App\Model\InspectCheck;
use App\Model\LandlordOrder;
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
use Mpdf\Mpdf;
use setasign\Fpdi\PdfParser\StreamReader;

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
            $tenement_name = $tenement_info['first_name'];
            $property_name = RentHouse::where('id',$input['rent_house_id'])->pluck('property_name')->first();
            $room_name = RentHouse::where('id',$input['rent_house_id'])->pluck('room_name')->first();
            $user_id = RentHouse::where('id',$input['rent_house_id'])->pluck('user_id')->first();
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
                $task_data = [
                    'user_id'           => $user_id,
                    'task_type'         => 1,
                    'task_start_time'   => date('Y-m-d H:i:s',time()),
                    'task_status'       => 0,
                    'task_title'        => 'RENTAL APPLICATION',
                    'task_content'      => "RENTAL APPLICATION
Property: $property_name $room_name
Applicant name: $tenement_name
You have received a new tenancy application from above, please deal with it in time.",
                    'rent_house_id'     => $input['rent_house_id'],
                    'task_role'         => 1,
                    'created_at'        => date('Y-m-d H:i:s',time()),
                ];
                $task_res = Task::insert($task_data);
                $model = new RentApplication();
                $res = $model->insert($application_data);
                // 更新房屋状态为2
                RentHouse::where('id',$input['rent_house_id'])->update(['rent_status'=>2,'updated_at'=>date('Y-m-d H:i:s',time())]);
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
                'adult'                 => @$input['adult'],
                'children'              => @$input['children'],
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
            $res = $model->insertGetId($application_data);
            if($res){
                return $this->success('application add success',$res);
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
                    $tenement_info = Tenement::where('id',$v['tenement_id'])->first();
                    if($tenement_info){
                        $tenement_info = $tenement_info->toArray();
                        $res[$k]['tenement_name'] = $tenement_info['first_name'].' '.$tenement_info['middle_name'].' '.$tenement_info['last_name'];
                        $res[$k]['tenement_headimg'] = $tenement_info['headimg'];
                        $res[$k]['look_house'] = LookHouse::where('rent_application_id',$v['id'])->first();
                    }else{
                        $res[$k]['tenement_name'] = '';
                        $res[$k]['tenement_headimg'] = '';
                        $res[$k]['look_house'] = LookHouse::where('rent_application_id',$v['id'])->first();
                    }

                    /*$res[$k]['survey_score'] = Survey::where('application_id',$v['id'])->pluck('survey_score')->first();
                    $res[$k]['survey_people'] = Survey::where('application_id',$v['id'])->pluck('survey_people')->first();
                    $res[$k]['survey_date'] = Survey::where('application_id',$v['id'])->pluck('survey_date')->first();*/
                }
                $data['application_list'] = $res;
                $data['total_page'] = ceil($count/5);
                $data['current_page'] = $page;
                return $this->success('get application success',$data);
            } else{
                return $this->error('3','You have no recevied any tenancy application yet.');
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
            $application_end_date = @$input['application_end_time'];
            if($application_start_date && $application_end_date){
                $model = $model->whereBetween('created_at',[$application_start_date,$application_end_date]);
            }
            $application_status = @$input['application_status'];
            if($application_status){
                $model = $model->where('application_status',$application_status);
            }
            $sort_order = $input['sort_order'];
            $count = $model->where('tenement_id',$input['tenement_id'])->groupBy('rent_house_id')->count();
            if($count<($page-1)*9){
                return $this->error('3','no data');
            }
            $total_page = ceil($count/9);
            if($sort_order == 1){
                $res = $model->where('tenement_id',$input['tenement_id'])->groupBy('rent_house_id')->orderBy('id','DESC')->offset(($page-1)*9)->limit(9)->get()->toArray();
            }else{
                $res = $model->where('tenement_id',$input['tenement_id'])->groupBy('rent_house_id')->offset(($page-1)*9)->limit(9)->get()->toArray();
            }
            if($res){
                foreach ($res as $k => $v){
                    $house_info = RentHouse::where('id',$v['rent_house_id'])->select('id','rent_category','property_name','room_name','property_type','address','available_time','rent_fee_pre_week','rent_least_fee','bedroom_no','bathroom_no','parking_no','garage_no','District','TA','Region','available_date')->first()->toArray();;
                    $application_res[$k] = $house_info;
                    $application_res[$k]['house_pic'] = RentPic::where('rent_house_id',$v['rent_house_id'])->where('deleted_at',null)->pluck('house_pic')->toArray();// 图片
                    $application_res[$k]['full_address'] = $house_info['address'].','.Region::getName($house_info['District']).','.Region::getName($house_info['TA']).','.Region::getName($house_info['Region']);
                    $application_res[$k]['application_id'] = $v['id'];
                    $application_res[$k]['application_status'] = $v['application_status'];
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
     * @description:同意申请
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentTenementApplicationAgree(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if(!$user_info->user_role % 2){
            return $this->error('2','this account is not a tenement role');
        }else{
            $model = new RentApplication();
            $rent_house_id = $model->where('id',$input['application_id'])->pluck('rent_house_id')->first();
            $model->where('rent_house_id',$rent_house_id)->update(['application_status'=>7,'updated_at'=>date('Y:m:d h:i:s',time())]);
            $res = $model->where('id',$input['application_id'])->update(['application_status'=>8,'updated_at'=>date('Y:m:d h:i:s',time())]);
            RentHouse::where('id',$rent_house_id)->update(['rent_status'=>3,'updated_at'=>date('Y-m-d H:i:s',time())]);
            if($res){
                // 房屋操作节点
                $house_log_data = [
                    'user_id'   => $input['user_id'],
                    'rent_house_id' => $rent_house_id,
                    'log_type'      => 2,
                    'created_at'    => date('Y-m-d H:i:s',time()),
                ];
                DB::table('house_log')->insert($house_log_data);
                return $this->success('application agree success',$res);
            } else{
                return $this->error('3','application agree failed');
            }
        }
    }




    /**
     * @description:备选申请
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentTenementApplicationBackup(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if(!$user_info->user_role % 2){
            return $this->error('2','this account is not a tenement role');
        }else{
            $model = new RentApplication();
            $res = $model->where('id',$input['application_id'])->update(['application_status'=>5,'updated_at'=>date('Y:m:d h:i:s',time())]);
            if($res){
                return $this->success('application backup success',$res);
            } else{
                return $this->error('3','application backup failed');
            }
        }
    }





    /**
     * @description:拒绝申请
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentTenementApplicationReject(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if(!$user_info->user_role % 2){
            return $this->error('2','this account is not a tenement role');
        }else{
            $model = new RentApplication();
            $res = $model->where('id',$input['application_id'])->update(['application_status'=>7,'updated_at'=>date('Y:m:d h:i:s',time())]);
            if($res){
                return $this->success('application reject success',$res);
            } else{
                return $this->error('3','gapplication reject failed');
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
                            $tenement_res = Tenement::where('email',$v['tenement_e_mail'])->pluck('id')->first();
                            if($tenement_res){ // 当这个email 在租户表中有时 默认存为那个用户表
                                $v['tenement_id'] = $tenement_res;
                            }else{ // 没有在租户表中新建一个租户信息
                                $tenement_data = [
                                    'tenement_id'               => tenementId(),
                                    'mobile'                    => $v['tenement_mobile'],
                                    'first_name'                => $v['tenement_full_name'],
                                    'phone'                     => $v['tenement_phone'],
                                    'email'                     => $v['tenement_e_mail'],
                                    'mail_address'              => $v['tenement_post_address'],
                                    'service_address'           => $v['tenement_service_address'],
                                    'mail_code'                 => $v['tenement_post_code'],
                                    'subject_code'              => subjectCode(),
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
                        'sign_date'                                 => $input['sign_date'],
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
                            $tenement_res = Tenement::where('email',$v['tenement_e_mail'])->pluck('id')->first();
                            if($tenement_res){ // 当这个email 在租户表中有时 默认存为那个用户表
                                $v['tenement_id'] = $tenement_res;
                            }else{ // 没有在租户表中新建一个租户信息
                                $tenement_data = [
                                    'tenement_id'               => tenementId(),
                                    'mobile'                    => $v['tenement_mobile'],
                                    'phone'                     => $v['tenement_phone'],
                                    'email'                     => $v['tenement_e_mail'],
                                    'mail_address'              => $v['tenement_post_address'],
                                    'service_address'           => $v['tenement_service_address'],
                                    'mail_code'                 => $v['tenement_post_code'],
                                    'created_at'                => date('Y-m-d H:i:s',time()),
                                    'subject_code'              => subjectCode(),
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
                        'house_rule_url'                            => $input['house_rule_url'],
                        'is_fire'                                   => $input['is_fire'],
                        'fire_url'                                  => $input['fire_url'],
                        'is_body'                                   => $input['is_body'],
                        'body_url'                                  => $input['body_url'],
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
                        'rule'                                      => $input['rule'],
                        /*'rule_upload_url'                           => $input['rule_upload_url'],*/
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
                        'sign_date'                                 => $input['sign_date'],
                        'rent_end_date'                             => $input['rent_end_date'],
                        'rent_fee'                                  => $input['rent_fee'],
                        'created_at'                                => date('Y-m-d H:i:s', time()),
                    ];
                    $separate_res = $separate_model->insert($separate_data);
                    if ($contract_tenement_res && $separate_res && $contract_chattel_res /*&& $service_fee_res*/) {
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
                            $tenement_res = Tenement::where('email',$v['tenement_e_mail'])->pluck('id')->first();
                            if($tenement_res){ // 当这个email 在租户表中有时 默认存为那个用户表
                                $v['tenement_id'] = $tenement_res;
                            }else{ // 没有在租户表中新建一个租户信息
                                $tenement_data = [
                                    'tenement_id'               => tenementId(),
                                    'mobile'                    => $v['tenement_mobile'],
                                    'phone'                     => $v['tenement_phone'],
                                    'email'                     => $v['tenement_e_mail'],
                                    'mail_address'              => $v['tenement_post_address'],
                                    'service_address'           => $v['tenement_service_address'],
                                    'mail_code'                 => $v['tenement_post_code'],
                                    'subject_code'              => subjectCode(),
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
                        'sign_date'                             => $input['sign_date'],
                        /*'rent_end_date'                         => $input['rent_end_date'],*/
                        /*'rent_fee'                              => $input['rent_fee'],*/
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
            $model = $model->whereIn('contract_status',[1,2,3,4]);
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
     * @description:租户查看租约列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentTenementContractList(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if($user_info->user_role < 4){
            return $this->error('2','this account is not a tenement role');
        }else{
            $model = new RentContract();
            $contract_ids = ContractTenement::where('tenement_id',$input['tenement_id'])->pluck('contract_id')->toArray();
            $contract_status = @$input['contract_status'];
            if($contract_status){
                $model = $model->where('contract_status',$contract_status);
            }
            $model = $model->whereIn('id',$contract_ids);
            $count = $model->count();
            $page = $input['page'];
            if($count < ($page-1)*5){
                return $this->error('3','no contact');
            }else{
                $res = $model->offset(($page-1)*5)->limit(5)->get()->toArray();
                if($res){
                    foreach ($res as $k => $v){
                        $house_info = RentHouse::where('id',$v['house_id'])->select('id','rent_category','property_name','room_name','property_type','address','available_time','rent_fee_pre_week','rent_least_fee','bedroom_no','bathroom_no','parking_no','garage_no','District','TA','Region','available_date')->first()->toArray();;
                        $application_res[$k] = $house_info;
                        $application_res[$k]['house_pic'] = RentPic::where('rent_house_id',$v['house_id'])->where('deleted_at',null)->pluck('house_pic')->toArray();// 图片
                        $application_res[$k]['full_address'] = $house_info['address'].','.Region::getName($house_info['District']).','.Region::getName($house_info['TA']).','.Region::getName($house_info['Region']);
                        $application_res[$k]['contract_id'] = $v['id'];
                        $application_res[$k]['contract_status'] = $v['contract_status'];
                        $application_res[$k]['contract_sn'] = $v['contract_id'];
                        $application_res[$k]['rent_start_date'] = $v['rent_start_date'];
                        $application_res[$k]['rent_end_date'] = $v['rent_end_date'];
                        $application_res[$k]['contract_type'] = $v['contract_type'];
                    }
                    $data['house_list'] = $application_res;
                    $data['total_page'] = ceil($count/5);
                    $data['current_page'] = $input['page'];
                    return $this->success('get rent contract list success',$data);
                }else{
                    return $this->error('4','get contract list failed');
                }

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
    public function rentContractDetail(array $input)
    {
        //dd($input);
        $model = new RentContract();
        $res = $model->where('id',$input['contract_id'])->first();
        if($res->contract_type == 1){
            $res = $model->where('rent_contract.id',$input['contract_id'])->leftjoin('rent_entire_contract','rent_entire_contract.contract_id','=','rent_contract.id')->first()->toArray();
            if($res){
                $res['tenement_info'] = ContractTenement::where('contract_id',$input['contract_id'])->get()->toArray();
                $res['chattel_info'] = ContractChattel::where('contract_id',$input['contract_id'])->get()->toArray();
                $res['contract_sn'] = RentContract::where('id',$input['contract_id'])->pluck('contract_id')->first();
                return $this->success('get contact success',$res);
            }else{
                return $this->error('2','get contact failed');
            }
        }elseif($res->contract_type == 2 || $res->contract_type == 3){
            $res = $model->where('rent_contract.id',$input['contract_id'])->leftjoin('rent_separate_contract','rent_separate_contract.contract_id','=','rent_contract.id')->first()->toArray();
            if($res){
                $res['tenement_info'] = ContractTenement::where('contract_id',$input['contract_id'])->get()->toArray();
                $res['chattel'] = ContractChattel::where('contract_id',$input['contract_id'])->get()->toArray();
                $res['contract_sn'] = RentContract::where('id',$input['contract_id'])->pluck('contract_id')->first();
                $res['service_fee_info'] = ContractService::where('contract_id',$input['contract_id'])->get()->toArray();
                return $this->success('get contact success',$res);
            }else{
                return $this->error('2','get contact failed');
            }
        }elseif($res->contract_type == 4){
            $res = $model->where('rent_contract.id',$input['contract_id'])->leftjoin('rent_business_contract','rent_business_contract.contract_id','=','rent_contract.id')->first()->toArray();
            if($res){
                $res['tenement_info'] = ContractTenement::where('contract_id',$input['contract_id'])->get()->toArray();
                $res['contract_sn'] = RentContract::where('id',$input['contract_id'])->pluck('contract_id')->first();
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
                        'to_be_paid'                                => $input['to_be_paid'],
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
                        'house_rule_url'                            => $input['house_rule_url'],
                        'is_fire'                                   => $input['is_fire'],
                        'fire_url'                                  => $input['fire_url'],
                        'is_body'                                   => $input['is_body'],
                        'body_url'                                  => $input['body_url'],
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
        $contract_data = $model->where('id',$input['contract_id'])->first();
        // 其他租约全部变成失效
        $model->where('house_id',$contract_data->house_id)->update(['contract_status'   => 5,'updated_at'   => date('Y-m-d H:i:s',time()),]);
        // 租约变成生效
        $effect_data = [
            'contract_status'   => 2,
            'rent_start_date'   => $input['rent_start_date'],
            'rent_end_date'     => $input['rent_end_date'],
            'updated_at'        => date('Y-m-d H:i:s',time()),
        ];
        $res = $model->where('id',$input['contract_id'])->update($effect_data);
        if($res){
            if($contract_data->contract_type == 1){
                // 生成押金记录
                $contract_tenement_data = ContractTenement::where('contract_id',$input['contract_id'])->first();
                $rent_house_info = RentHouse::where('id',$contract_data->house_id)->first();
                $entire_data = EntireContract::where('contract_id',$input['contract_id'])->first();
                $bond_data = [
                    'contract_id'       => $input['contract_id'],
                    'contract_sn'       => $contract_data->contract_id,
                    'user_id'           => $input['user_id'],
                    'rent_house_id'     => $contract_data->house_id,
                    'arrears_type'      => 1,
                    'tenement_id'       => $contract_tenement_data->tenement_id,
                    'tenement_name'     => $contract_tenement_data->tenement_full_name,
                    'property_name'     => $rent_house_info->property_name,
                    'tenement_phone'    => $contract_tenement_data->tenement_phone,
                    'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                    'arrears_fee'       => $entire_data->bond_amount,
                    'is_pay'            => 1,
                    'pay_fee'           => 0,
                    'need_pay_fee'      => $entire_data->bond_amount,
                    'expire_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*7),
                    'items_name'        => 'bond fee',
                    'District'          => $rent_house_info->District,
                    'TA'                => $rent_house_info->TA,
                    'Region'            => $rent_house_info->Region,
                    'subject_code'      => Tenement::where('id',$contract_tenement_data->tenement_id)->pluck('subject_code')->first(),
                    'created_at'        => date('Y-m-d H:i:s',time()),
                ];
                $bond_res = RentArrears::insert($bond_data);
                // 生成预付记录
                if(0>strtotime($input['rent_start_date'])-time()&&strtotime($input['rent_start_date'])-time()>-3600*24*60){
                    if($entire_data->pay_method == 1){
                        $cycle = ceil((time()-strtotime($input['rent_start_date']))/3600/24/7);
                        for($i=0;$i<$cycle;$i++){
                            $arrears_data = [
                                'contract_id'       => $input['contract_id'],
                                'contract_sn'       => $contract_data->contract_id,
                                'user_id'           => $input['user_id'],
                                'rent_house_id'     => $contract_data->house_id,
                                'arrears_type'      => 2,
                                'tenement_id'       => $contract_tenement_data->tenement_id,
                                'tenement_name'     => $contract_tenement_data->tenement_full_name,
                                'property_name'     => $rent_house_info->property_name,
                                'tenement_phone'    => $contract_tenement_data->tenement_phone,
                                'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                                'effect_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*7*$i),
                                'arrears_fee'       => $entire_data->rent_per_week,
                                'is_pay'            => 1,
                                'pay_fee'           => 0,
                                'need_pay_fee'      => $entire_data->rent_per_week,
                                'rent_circle'       => 1,
                                'rent_times'        => $i+1,
                                'expire_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*7*$i+3600*24*7),
                                'items_name'        => 'rent fee',
                                'District'          => $rent_house_info->District,
                                'TA'                => $rent_house_info->TA,
                                'Region'            => $rent_house_info->Region,
                                'subject_code'      => Tenement::where('id',$contract_tenement_data->tenement_id)->pluck('subject_code')->first(),
                                'created_at'        => date('Y-m-d H:i:s',time()),
                            ];
                            RentArrears::insert($arrears_data);
                        }
                    }elseif ($entire_data->pay_method == 2){
                        $cycle = ceil((time()-strtotime($input['rent_start_date']))/3600/24/14);
                        for($i=0;$i<$cycle;$i++){
                            $arrears_data = [
                                'contract_id'       => $input['contract_id'],
                                'contract_sn'       => $contract_data->contract_id,
                                'user_id'           => $input['user_id'],
                                'rent_house_id'     => $contract_data->house_id,
                                'arrears_type'      => 2,
                                'tenement_id'       => $contract_tenement_data->tenement_id,
                                'tenement_name'     => $contract_tenement_data->tenement_full_name,
                                'property_name'     => $rent_house_info->property_name,
                                'tenement_phone'    => $contract_tenement_data->tenement_phone,
                                'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                                'effect_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*14*$i),
                                'arrears_fee'       => $entire_data->rent_per_week*2,
                                'is_pay'            => 1,
                                'pay_fee'           => 0,
                                'need_pay_fee'      => $entire_data->rent_per_week*2,
                                'rent_circle'       => 2,
                                'rent_times'        => $i+1,
                                'expire_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*14*$i+3600*24*7),
                                'items_name'        => 'rent fee',
                                'District'          => $rent_house_info->District,
                                'TA'                => $rent_house_info->TA,
                                'Region'            => $rent_house_info->Region,
                                'subject_code'      => Tenement::where('id',$contract_tenement_data->tenement_id)->pluck('subject_code')->first(),
                                'created_at'        => date('Y-m-d H:i:s',time()),
                            ];
                            RentArrears::insert($arrears_data);
                        }
                    }elseif ($entire_data->pay_method == 3){
                        $cycle = ceil((time()-strtotime($input['rent_start_date']))/3600/24/30);
                        for($i=0;$i<$cycle;$i++){
                            $arrears_data = [
                                'contract_id'       => $input['contract_id'],
                                'contract_sn'       => $contract_data->contract_id,
                                'user_id'           => $input['user_id'],
                                'rent_house_id'     => $contract_data->house_id,
                                'arrears_type'      => 2,
                                'tenement_id'       => $contract_tenement_data->tenement_id,
                                'tenement_name'     => $contract_tenement_data->tenement_full_name,
                                'property_name'     => $rent_house_info->property_name,
                                'tenement_phone'    => $contract_tenement_data->tenement_phone,
                                'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                                'effect_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*30*$i),
                                'arrears_fee'       => $entire_data->rent_per_week*30/7,
                                'is_pay'            => 1,
                                'pay_fee'           => 0,
                                'need_pay_fee'      => $entire_data->rent_per_week*30/7,
                                'rent_circle'       => 3,
                                'rent_times'        => $i+1,
                                'expire_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*30*$i+3600*24*7),
                                'items_name'        => 'rent fee',
                                'District'          => $rent_house_info->District,
                                'TA'                => $rent_house_info->TA,
                                'Region'            => $rent_house_info->Region,
                                'subject_code'      => Tenement::where('id',$contract_tenement_data->tenement_id)->pluck('subject_code')->first(),
                                'created_at'        => date('Y-m-d H:i:s',time()),
                            ];
                            RentArrears::insert($arrears_data);
                        }
                    }
                }else if(0<strtotime($input['rent_start_date'])-time()&&strtotime($input['rent_start_date'])-time()<3600*24*60){
                    if($entire_data->pay_method == 1){
                        $cycle = ceil((time()-strtotime($input['rent_start_date']))/3600/24/7);
                        for($i=0;$i<$cycle;$i++){
                            $arrears_data = [
                                'contract_id'       => $input['contract_id'],
                                'contract_sn'       => $contract_data->contract_id,
                                'user_id'           => $input['user_id'],
                                'rent_house_id'     => $contract_data->house_id,
                                'arrears_type'      => 2,
                                'tenement_id'       => $contract_tenement_data->tenement_id,
                                'tenement_name'     => $contract_tenement_data->tenement_full_name,
                                'property_name'     => $rent_house_info->property_name,
                                'tenement_phone'    => $contract_tenement_data->tenement_phone,
                                'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                                'effect_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*7*$i),
                                'arrears_fee'       => $entire_data->rent_per_week,
                                'is_pay'            => 1,
                                'pay_fee'           => 0,
                                'need_pay_fee'      => $entire_data->rent_per_week,
                                'rent_circle'       => 1,
                                'rent_times'        => $i+1,
                                'expire_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*7*$i+3600*24*7),
                                'items_name'        => 'rent fee',
                                'District'          => $rent_house_info->District,
                                'TA'                => $rent_house_info->TA,
                                'Region'            => $rent_house_info->Region,
                                'subject_code'      => Tenement::where('id',$contract_tenement_data->tenement_id)->pluck('subject_code')->first(),
                                'created_at'        => date('Y-m-d H:i:s',time()),
                            ];
                            RentArrears::insert($arrears_data);
                        }
                    }elseif ($entire_data->pay_method == 2){
                        $cycle = ceil((time()-strtotime($input['rent_start_date']))/3600/24/14);
                        for($i=0;$i<$cycle;$i++){
                            $arrears_data = [
                                'contract_id'       => $input['contract_id'],
                                'contract_sn'       => $contract_data->contract_id,
                                'user_id'           => $input['user_id'],
                                'rent_house_id'     => $contract_data->house_id,
                                'arrears_type'      => 2,
                                'tenement_id'       => $contract_tenement_data->tenement_id,
                                'tenement_name'     => $contract_tenement_data->tenement_full_name,
                                'property_name'     => $rent_house_info->property_name,
                                'tenement_phone'    => $contract_tenement_data->tenement_phone,
                                'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                                'effect_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*14*$i),
                                'arrears_fee'       => $entire_data->rent_per_week*2,
                                'is_pay'            => 1,
                                'pay_fee'           => 0,
                                'need_pay_fee'      => $entire_data->rent_per_week*2,
                                'rent_circle'       => 2,
                                'rent_times'        => $i+1,
                                'expire_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*14*$i+3600*24*7),
                                'items_name'        => 'rent fee',
                                'District'          => $rent_house_info->District,
                                'TA'                => $rent_house_info->TA,
                                'Region'            => $rent_house_info->Region,
                                'subject_code'      => Tenement::where('id',$contract_tenement_data->tenement_id)->pluck('subject_code')->first(),
                                'created_at'        => date('Y-m-d H:i:s',time()),
                            ];
                            RentArrears::insert($arrears_data);
                        }
                    }elseif ($entire_data->pay_method == 3){
                        $cycle = ceil((time()-strtotime($input['rent_start_date']))/3600/24/30);
                        for($i=0;$i<$cycle;$i++){
                            $arrears_data = [
                                'contract_id'       => $input['contract_id'],
                                'contract_sn'       => $contract_data->contract_id,
                                'user_id'           => $input['user_id'],
                                'rent_house_id'     => $contract_data->house_id,
                                'arrears_type'      => 2,
                                'tenement_id'       => $contract_tenement_data->tenement_id,
                                'tenement_name'     => $contract_tenement_data->tenement_full_name,
                                'property_name'     => $rent_house_info->property_name,
                                'tenement_phone'    => $contract_tenement_data->tenement_phone,
                                'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                                'effect_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*30*$i),
                                'arrears_fee'       => $entire_data->rent_per_week*30/7,
                                'is_pay'            => 1,
                                'pay_fee'           => 0,
                                'need_pay_fee'      => $entire_data->rent_per_week*30/7,
                                'rent_circle'       => 3,
                                'rent_times'        => $i+1,
                                'expire_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*30*$i+3600*24*7),
                                'items_name'        => 'rent fee',
                                'District'          => $rent_house_info->District,
                                'TA'                => $rent_house_info->TA,
                                'Region'            => $rent_house_info->Region,
                                'subject_code'      => Tenement::where('id',$contract_tenement_data->tenement_id)->pluck('subject_code')->first(),
                                'created_at'        => date('Y-m-d H:i:s',time()),
                            ];
                            RentArrears::insert($arrears_data);
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
                    'rent_house_id'     => $contract_data->house_id,
                    'arrears_type'      => 1,
                    'tenement_id'       => $contract_tenement_data->tenement_id,
                    'tenement_name'     => $contract_tenement_data->tenement_full_name,
                    'property_name'     => $rent_house_info->property_name,
                    'tenement_phone'    => $contract_tenement_data->tenement_phone,
                    'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                    'arrears_fee'       => $separate_data->bond_amount,
                    'is_pay'            => 1,
                    'pay_fee'           => 0,
                    'need_pay_fee'      => $separate_data->bond_amount,
                    'expire_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*7),
                    'items_name'        => 'bond fee',
                    'District'          => $rent_house_info->District,
                    'TA'                => $rent_house_info->TA,
                    'Region'            => $rent_house_info->Region,
                    'subject_code'      => Tenement::where('id',$contract_tenement_data->tenement_id)->pluck('subject_code')->first(),
                    'created_at'        => date('Y-m-d H:i:s',time()),
                ];
                $bond_res = RentArrears::insert($bond_data);
                // 生成预付记录
                if(0>strtotime($input['rent_start_date'])-time()&&strtotime($input['rent_start_date'])-time()>-3600*24*60){
                    if($separate_data->pay_method == 1){
                        $cycle = ceil((time()-strtotime($input['rent_start_date']))/3600/24/7);
                        for($i=0;$i<$cycle;$i++){
                            $arrears_data = [
                                'contract_id'       => $input['contract_id'],
                                'contract_sn'       => $contract_data->contract_id,
                                'user_id'           => $input['user_id'],
                                'rent_house_id'     => $contract_data->house_id,
                                'arrears_type'      => 2,
                                'tenement_id'       => $contract_tenement_data->tenement_id,
                                'tenement_name'     => $contract_tenement_data->tenement_full_name,
                                'property_name'     => $rent_house_info->property_name,
                                'tenement_phone'    => $contract_tenement_data->tenement_phone,
                                'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                                'effect_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*7*$i),
                                'arrears_fee'       => $separate_data->rent_per_week,
                                'is_pay'            => 1,
                                'pay_fee'           => 0,
                                'need_pay_fee'      => $separate_data->rent_per_week,
                                'rent_circle'       => 1,
                                'rent_times'        => $i+1,
                                'expire_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*7*$i+3600*24*7),
                                'items_name'        => 'rent fee',
                                'District'          => $rent_house_info->District,
                                'TA'                => $rent_house_info->TA,
                                'Region'            => $rent_house_info->Region,
                                'subject_code'      => Tenement::where('id',$contract_tenement_data->tenement_id)->pluck('subject_code')->first(),
                                'created_at'        => date('Y-m-d H:i:s',time()),
                            ];
                            RentArrears::insert($arrears_data);
                        }
                    }elseif ($separate_data->pay_method == 2){
                        $cycle = ceil((time()-strtotime($input['rent_start_date']))/3600/24/14);
                        for($i=0;$i<$cycle;$i++){
                            $arrears_data = [
                                'contract_id'       => $input['contract_id'],
                                'contract_sn'       => $contract_data->contract_id,
                                'user_id'           => $input['user_id'],
                                'rent_house_id'     => $contract_data->house_id,
                                'arrears_type'      => 2,
                                'tenement_id'       => $contract_tenement_data->tenement_id,
                                'tenement_name'     => $contract_tenement_data->tenement_full_name,
                                'property_name'     => $rent_house_info->property_name,
                                'tenement_phone'    => $contract_tenement_data->tenement_phone,
                                'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                                'effect_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*14*$i),
                                'arrears_fee'       => $separate_data->rent_per_week*2,
                                'is_pay'            => 1,
                                'pay_fee'           => 0,
                                'need_pay_fee'      => $separate_data->rent_per_week*2,
                                'rent_circle'       => 2,
                                'rent_times'        => $i+1,
                                'expire_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*14*$i+3600*24*7),
                                'items_name'        => 'rent fee',
                                'District'          => $rent_house_info->District,
                                'TA'                => $rent_house_info->TA,
                                'Region'            => $rent_house_info->Region,
                                'subject_code'      => Tenement::where('id',$contract_tenement_data->tenement_id)->pluck('subject_code')->first(),
                                'created_at'        => date('Y-m-d H:i:s',time()),
                            ];
                            RentArrears::insert($arrears_data);
                        }
                    }elseif ($separate_data->pay_method == 3){
                        $cycle = ceil((time()-strtotime($input['rent_start_date']))/3600/24/30);
                        for($i=0;$i<$cycle;$i++){
                            $arrears_data = [
                                'contract_id'       => $input['contract_id'],
                                'contract_sn'       => $contract_data->contract_id,
                                'user_id'           => $input['user_id'],
                                'rent_house_id'     => $contract_data->house_id,
                                'arrears_type'      => 2,
                                'tenement_id'       => $contract_tenement_data->tenement_id,
                                'tenement_name'     => $contract_tenement_data->tenement_full_name,
                                'property_name'     => $rent_house_info->property_name,
                                'tenement_phone'    => $contract_tenement_data->tenement_phone,
                                'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                                'effect_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*30*$i),
                                'arrears_fee'       => $separate_data->rent_per_week*30/7,
                                'is_pay'            => 1,
                                'pay_fee'           => 0,
                                'need_pay_fee'      => $separate_data->rent_per_week*30/7,
                                'rent_circle'       => 3,
                                'rent_times'        => $i+1,
                                'expire_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*30*$i+3600*24*7),
                                'items_name'        => 'rent fee',
                                'District'          => $rent_house_info->District,
                                'TA'                => $rent_house_info->TA,
                                'Region'            => $rent_house_info->Region,
                                'subject_code'      => Tenement::where('id',$contract_tenement_data->tenement_id)->pluck('subject_code')->first(),
                                'created_at'        => date('Y-m-d H:i:s',time()),
                            ];
                            RentArrears::insert($arrears_data);
                        }
                    }
                }else if(0<strtotime($input['rent_start_date'])-time()&&strtotime($input['rent_start_date'])-time()<3600*24*60){
                    if($separate_data->pay_method == 1){
                        $cycle = ceil((time()-strtotime($input['rent_start_date']))/3600/24/7);
                        for($i=0;$i<$cycle;$i++){
                            $arrears_data = [
                                'contract_id'       => $input['contract_id'],
                                'contract_sn'       => $contract_data->contract_id,
                                'user_id'           => $input['user_id'],
                                'rent_house_id'     => $contract_data->house_id,
                                'arrears_type'      => 2,
                                'tenement_id'       => $contract_tenement_data->tenement_id,
                                'tenement_name'     => $contract_tenement_data->tenement_full_name,
                                'property_name'     => $rent_house_info->property_name,
                                'tenement_phone'    => $contract_tenement_data->tenement_phone,
                                'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                                'effect_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*7*$i),
                                'arrears_fee'       => $separate_data->rent_per_week,
                                'is_pay'            => 1,
                                'pay_fee'           => 0,
                                'need_pay_fee'      => $separate_data->rent_per_week,
                                'rent_circle'       => 1,
                                'rent_times'        => $i+1,
                                'expire_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*7*$i+3600*24*7),
                                'items_name'        => 'rent fee',
                                'District'          => $rent_house_info->District,
                                'TA'                => $rent_house_info->TA,
                                'Region'            => $rent_house_info->Region,
                                'subject_code'      => Tenement::where('id',$contract_tenement_data->tenement_id)->pluck('subject_code')->first(),
                                'created_at'        => date('Y-m-d H:i:s',time()),
                            ];
                            RentArrears::insert($arrears_data);
                        }
                    }elseif ($separate_data->pay_method == 2){
                        $cycle = ceil((time()-strtotime($input['rent_start_date']))/3600/24/14);
                        for($i=0;$i<$cycle;$i++){
                            $arrears_data = [
                                'contract_id'       => $input['contract_id'],
                                'contract_sn'       => $contract_data->contract_id,
                                'user_id'           => $input['user_id'],
                                'rent_house_id'     => $contract_data->house_id,
                                'arrears_type'      => 2,
                                'tenement_id'       => $contract_tenement_data->tenement_id,
                                'tenement_name'     => $contract_tenement_data->tenement_full_name,
                                'property_name'     => $rent_house_info->property_name,
                                'tenement_phone'    => $contract_tenement_data->tenement_phone,
                                'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                                'effect_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*14*$i),
                                'arrears_fee'       => $separate_data->rent_per_week*2,
                                'is_pay'            => 1,
                                'pay_fee'           => 0,
                                'need_pay_fee'      => $separate_data->rent_per_week*2,
                                'rent_circle'       => 2,
                                'rent_times'        => $i+1,
                                'expire_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*14*$i+3600*24*7),
                                'items_name'        => 'rent fee',
                                'District'          => $rent_house_info->District,
                                'TA'                => $rent_house_info->TA,
                                'Region'            => $rent_house_info->Region,
                                'subject_code'      => Tenement::where('id',$contract_tenement_data->tenement_id)->pluck('subject_code')->first(),
                                'created_at'        => date('Y-m-d H:i:s',time()),
                            ];
                            RentArrears::insert($arrears_data);
                        }
                    }elseif ($separate_data->pay_method == 3){
                        $cycle = ceil((time()-strtotime($input['rent_start_date']))/3600/24/30);
                        for($i=0;$i<$cycle;$i++){
                            $arrears_data = [
                                'contract_id'       => $input['contract_id'],
                                'contract_sn'       => $contract_data->contract_id,
                                'user_id'           => $input['user_id'],
                                'rent_house_id'     => $contract_data->house_id,
                                'arrears_type'      => 2,
                                'tenement_id'       => $contract_tenement_data->tenement_id,
                                'tenement_name'     => $contract_tenement_data->tenement_full_name,
                                'property_name'     => $rent_house_info->property_name,
                                'tenement_phone'    => $contract_tenement_data->tenement_phone,
                                'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                                'effect_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*30*$i),
                                'arrears_fee'       => $separate_data->rent_per_week*30/7,
                                'is_pay'            => 1,
                                'pay_fee'           => 0,
                                'need_pay_fee'      => $separate_data->rent_per_week*30/7,
                                'rent_circle'       => 3,
                                'rent_times'        => $i+1,
                                'expire_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*30*$i+3600*24*7),
                                'items_name'        => 'rent fee',
                                'District'          => $rent_house_info->District,
                                'TA'                => $rent_house_info->TA,
                                'Region'            => $rent_house_info->Region,
                                'subject_code'      => Tenement::where('id',$contract_tenement_data->tenement_id)->pluck('subject_code')->first(),
                                'created_at'        => date('Y-m-d H:i:s',time()),
                            ];
                            RentArrears::insert($arrears_data);
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
                            'arrears_type'      => 2,
                            'tenement_id'       => $contract_tenement_data->tenement_id,
                            'tenement_name'     => $contract_tenement_data->tenement_full_name,
                            'property_name'     => $rent_house_info->property_name,
                            'tenement_phone'    => $contract_tenement_data->tenement_phone,
                            'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                            'effect_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*30*$i),
                            'arrears_fee'       => $business_data->rent_per_week*30/7,
                            'is_pay'            => 1,
                            'pay_fee'           => 0,
                            'need_pay_fee'      => $business_data->rent_per_week*30/7,
                            'rent_circle'       => 3,
                            'rent_times'        => $i+1,
                            'expire_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*30*$i+3600*24*7),
                            'items_name'        => 'rent fee',
                            'District'          => $rent_house_info->District,
                            'TA'                => $rent_house_info->TA,
                            'Region'            => $rent_house_info->Region,
                            'subject_code'      => Tenement::where('id',$contract_tenement_data->tenement_id)->pluck('subject_code')->first(),
                            'created_at'        => date('Y-m-d H:i:s',time()),
                        ];
                        RentArrears::insert($arrears_data);
                    }
                }else if(0<strtotime($input['rent_start_date'])-time()&&strtotime($input['rent_start_date'])-time()<3600*24*60){
                    $cycle = ceil((time()-strtotime($input['rent_start_date']))/3600/24/30);
                    for($i=0;$i<$cycle;$i++){
                        $arrears_data = [
                            'contract_id'       => $input['contract_id'],
                            'contract_sn'       => $contract_data->contract_id,
                            'user_id'           => $input['user_id'],
                            'rent_house_id'     => $contract_data->house_id,
                            'arrears_type'      => 2,
                            'tenement_id'       => $contract_tenement_data->tenement_id,
                            'tenement_name'     => $contract_tenement_data->tenement_full_name,
                            'property_name'     => $rent_house_info->property_name,
                            'tenement_phone'    => $contract_tenement_data->tenement_phone,
                            'tenement_email'    => $contract_tenement_data->tenement_e_mail,
                            'effect_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*30*$i),
                            'arrears_fee'       => $business_data->rent_per_week*30/7,
                            'is_pay'            => 1,
                            'pay_fee'           => 0,
                            'need_pay_fee'      => $business_data->rent_per_week*30/7,
                            'rent_circle'       => 3,
                            'rent_times'        => $i+1,
                            'expire_date'       => date('Y-m-d',strtotime($input['rent_start_date'])+3600*24*30*$i+3600*24*7),
                            'items_name'        => 'rent fee',
                            'District'          => $rent_house_info->District,
                            'TA'                => $rent_house_info->TA,
                            'Region'            => $rent_house_info->Region,
                            'subject_code'      => Tenement::where('id',$contract_tenement_data->tenement_id)->pluck('subject_code')->first(),
                            'created_at'        => date('Y-m-d H:i:s',time()),
                        ];
                        RentArrears::insert($arrears_data);
                    }
                }
            }
            //房屋下架
            RentHouse::where('id',$contract_data->house_id)->update([
                'is_put'        => 1,
                'rent_status'   => 4,
                'updated_at'    => date('Y-m-d H:i:s',time()),
            ]);
            $property_name = $rent_house_info->property_name;
            $room_name = $rent_house_info->room_name;
            $property_address = $rent_house_info->address;
            $tenement_full_name = $contract_tenement_data->tenement_full_name;
            // 生成任务
            $task_data = [
                'user_id'           => $input['user_id'],
                'task_type'         => 3,
                'task_start_time'   => date('Y-m-d H:i:s',time()),
                'task_status'       => 0,
                'task_title'        => 'NEW PROPERTY INSPECTION',
                'task_content'      => "NEW PROPERTY INSPECTION
Property: $property_name $room_name $property_address
Tenant name: $tenement_full_name
Your property required the first inspection for the tenancy record. Please to create an new inspection in the system.",
                'rent_house_id'     => $contract_data->house_id,
                'task_role'         => 1,
                'created_at'        => date('Y-m-d H:i:s',time()),
            ];
            $task_res = Task::insert($task_data);
            // 用户操作节点
            if(DB::table('user_opeart_log')->where('user_id',$input['user_id'])->first()){
                $log_data = [
                    'opeartor_method'   => 2,
                    'updated_at'        => date('Y-m-d H:i:s',time()),
                ];
                DB::table('user_opeart_log')->where('user_id',$input['user_id'])->update($log_data);
            }else{
                $log_data = [
                    'user_id'           => $input['user_id'],
                    'opeartor_method'   => 2,
                    'created_at'        => date('Y-m-d H:i:s',time()),
                ];
                DB::table('user_opeart_log')->insert($log_data);
            }
            DB::table('user_opeart_log')->insert($log_data);
            // 房屋操作节点
            $house_log_data = [
                'user_id'   => $input['user_id'],
                'rent_house_id' => $contract_data->house_id,
                'log_type'      => 3,
                'created_at'    => date('Y-m-d H:i:s',time()),
            ];
            DB::table('house_log')->insert($house_log_data);
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
        $identification_res = TenementCertificate::where('tenement_id',$res)->first();
        if($identification_res){
            $identification = TenementCertificate::where('tenement_id',$res)->select('certificate_category','certificate_no','certificate_pic1','certificate_pic2')->get();
            return $this->success('get identification success',$identification);
        }else{
            $data[0] = $model->where('contract_id',$input['contract_id'])->select('identification_no as certificate_no','identification_type as certificate_category')->first();
            $data[0]['certificate_pic1'] = '';
            $data[0]['certificate_pic2'] = '';
            return $this->success('get identification success',$data);
        }
    }


    /**
     * @description:租户得分
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function tenementScore(array $input)
    {
        //dd($input);
        $model = new ContractTenement();
        $res = $model->where('contract_id',$input['contract_id'])->pluck('tenement_id')->first();
        $rent_house_id = RentContract::where('id',$input['contract_id'])->pluck('house_id')->first();
        $birthday = Tenement::where('id',$res)->pluck('birthday')->first();
        $tenement_score_data = [
            'tenement_id'       => $res,
            'tenement_name'     => Tenement::where('id',$res)->pluck('first_name')->first(),
            'user_id'           => $input['user_id'],
            'pay_score'         => $input['pay_score'],
            'hygiene_score'     => $input['hygiene_score'],
            'facility_score'    => $input['facility_score'],
            'detail'            => $input['detail'],
            'contract_id'       => $input['contract_id'],
            'accept_again'      => $input['accept_again'],
            'birthday'          => $birthday,
            'rent_house_id'     => $rent_house_id,
            'created_at'        => date('Y-m-d H:i:s',time()),
        ];
        $score_data = TenementScore::insert($tenement_score_data);
        if($score_data){
            RentContract::where('id',$input['contract_id'])->update(['contract_status'   => 6,'updated_at'   => date('Y-m-d H:i:s',time()),]);
            return $this->success('tenement score success');
        }else{
            return $this->error('2','tenement score failed');
        }
    }


    /**
     * @description:租金调整
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeRentFee(array $input)
    {
        //dd($input);
        $model = new RentAdjust();
        $adjust_data = [
            'contract_id'       => $input['contract_id'],
            'rent_fee_method'   => $input['rent_fee_method'],
            'rent_price'        => $input['rent_price'],
            'effective_date'    => $input['effective_date'],
            'created_at'        => date('Y-m-d H:i:s',time()),
        ];
        $adjust_res = $model->insert($adjust_data);
        if($adjust_res){
            return $this->success('change rent fee success');
        }else{
            return $this->error('2','change rent fee failed');
        }
    }

    /**
     * @description:租约中止
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentSuspend(array $input)
    {
        $suspend_type = $input['suspend_type'];
        if($suspend_type <4){
            // 仅通知
            // 生成任务
            $contact_id = $input['contract_id'];
            $rent_house_id = RentContract::where('id',$input['contract_id'])->pluck('house_id')->first();
            $property_name = RentHouse::where('id',$rent_house_id)->pluck('property_name')->first();
            $room_name = RentHouse::where('id',$rent_house_id)->pluck('room_name')->first();
            $property_address = RentHouse::where('id',$rent_house_id)->pluck('address')->first();
            $tenement_full_name = ContractTenement::where('contract_id',$contact_id)->pluck('tenement_full_name')->first();
            if($suspend_type == 1){
                $task_start_time = date('Y-m-d H:i:s',time()+3600*24*6);
                $task_data = [
                    'user_id'           => $input['user_id'],
                    'task_type'         => 10,
                    'task_start_time'   => $task_start_time,
                    'task_status'       => 0,
                    'task_title'        => 'ENDING TENANCY – 90 DAYS NOTICE',
                    'task_content'      => "ENDING TENANCY – 90 DAYS NOTICE
Property: $property_address
Tenant name: $tenement_full_name
Your tenancy at above property is end today. You will need to arrange the final inspection and also end this tenancy in the system.",
                    'contract_id'       => $input['contract_id'],
                    'task_role'         => 1,
                    'created_at'        => date('Y-m-d H:i:s',time()),
                ];
            }elseif ($suspend_type == 2){
                $task_start_time = date('Y-m-d H:i:s',time()+3600*24*94);
                $task_data = [
                    'user_id'           => $input['user_id'],
                    'task_type'         => 8,
                    'task_start_time'   => $task_start_time,
                    'task_status'       => 0,
                    'task_title'        => 'ENDING TENANCY – 28 DAYS NOTICE',
                    'task_content'      => "ENDING TENANCY – 28 DAYS NOTICE
Property: $property_address
Tenant name: $tenement_full_name
Your tenancy at above property is end today. You will need to arrange the final inspection and also end this tenancy in the system.",
                    'contract_id'       => $input['contract_id'],
                    'task_role'         => 1,
                    'created_at'        => date('Y-m-d H:i:s',time()),
                ];
            }elseif ($suspend_type == 3){
                $task_start_time = date('Y-m-d H:i:s',time()+3600*24*32);
                $task_data = [
                    'user_id'           => $input['user_id'],
                    'task_type'         => 9,
                    'task_start_time'   => $task_start_time,
                    'task_status'       => 0,
                    'task_title'        => 'ENDING TENANCY – 48 HOURS NOTICE',
                    'task_content'      => "ENDING TENANCY – 48 HOURS NOTICE
Property: $property_address
Tenant name: $tenement_full_name
Your tenancy at above property is end today. You will need to arrange the final inspection and also end this tenancy in the system.",
                    'contract_id'       => $input['contract_id'],
                    'task_role'         => 1,
                    'created_at'        => date('Y-m-d H:i:s',time()),
                ];
            }

            $task_res = Task::insert($task_data);
            if($task_res){
                return $this->success('rent suspend task add success');
            }else{
                return $this->error('2','rent suspend task add failed');
            }
        }else{
            $res = RentContract::where('id',$input['contract_id'])->update([
                'rent_end_date'     => $input['rent_end_date'],
                'contract_status'   => 3,
                'updated_at'        => date('Y-m-d H:i:s',time()),
            ]);
            $rent_house_id = RentContract::where('id',$input['contract_id'])->pluck('house_id')->first();
            RentHouse::where('id',$rent_house_id)->update(['rent_status'=>1,'is_put'=>2,'updated_at'=>date('Y-m-d H:i:s',time())]);
            if($res){
                // 增加最后一个租金单
                // 获取最后一次的租金详情

                /*$last_rent_fee_date = RentArrears::where('contract_id',$input['contract_id'])->where('arrears_type',2)->orderBy('created_at','DESC')->first();
                if($last_rent_fee_date['rent_circle'] ==1 ){
                    $fee_date = strtotime($last_rent_fee_date['effect_date'])+3600*24*8-1;
                }elseif ($last_rent_fee_date['rent_circle'] ==2){
                    $fee_date = strtotime($last_rent_fee_date['effect_date'])+3600*24*15-1;
                }elseif ($last_rent_fee_date['rent_circle'] ==3){
                    $fee_date = strtotime($last_rent_fee_date['effect_date'])+3600*24*31-1;
                }*/
                $contact_id = $input['contract_id'];
                $rent_house_id = RentContract::where('id',$input['contract_id'])->pluck('house_id')->first();
                $property_name = RentHouse::where('id',$rent_house_id)->pluck('property_name')->first();
                $room_name = RentHouse::where('id',$rent_house_id)->pluck('room_name')->first();
                $property_address = RentHouse::where('id',$rent_house_id)->pluck('address')->first();
                $tenement_full_name = ContractTenement::where('contract_id',$contact_id)->pluck('tenement_full_name')->first();

                $task_data = [
                    'user_id'           => $input['user_id'],
                    'task_type'         => 15,
                    'task_start_time'   => date('Y-m-d H:i:s',time()),
                    'task_status'       => 0,
                    'task_title'        => 'BOND REFUND',
                    'task_content'      => "BOND REFUND
Property: $room_name  $property_address
Tenant name: $tenement_full_name
This tenant’s tenancy is end. Please arrange the bond refund with the tenant.
Before the bond refund, a final inspection needs to be done at above property address and a final rent statement need to agree by both party. The tenant need to pay for any rent arrears, damages and other bills they may have to the landlord or the reasonable amount shall be deducted from the bond.",
                    'contract_id'       => $input['contract_id'],
                    'task_role'         => 1,
                    'created_at'        => date('Y-m-d H:i:s',time()),
                ];
                $task_res = Task::insert($task_data);
                $task_data1 = [
                    'user_id'           => $input['user_id'],
                    'task_type'         => 20,
                    'task_start_time'   => date('Y-m-d H:i:s',time()),
                    'task_status'       => 0,
                    'task_title'        => 'FINAL INSPECTION',
                    'task_content'      => "FINAL INSPECTION
Property: $room_name $property_address
Tenant name: $tenement_full_name
Tenancy ending date:
The above tenancy shall end soon. If you have not arranged an final inspection with the tenant, please do it as soon as possible. You will need,
l To do a final inspection for the above property.
l To discuss with the tenant if they have any rent arrears, damages and any other money they need to you.
l To arrange the key to be returned after tenancy end.
The bond can only refund if you satisfied with above or agree the amount with the tenant. If both parties cannot reach the agreement, you may wish to submit a tenancy tribunal application to resolve the matter.",
                    'contract_id'       => $input['contract_id'],
                    'task_role'         => 1,
                    'created_at'        => date('Y-m-d H:i:s',time()),
                ];
                $task_res = Task::insert($task_data1);
                // 用户操作节点
                if(DB::table('user_opeart_log')->where('user_id',$input['user_id'])->first()){
                    $log_data = [
                        'opeartor_method'   => 7,
                        'updated_at'        => date('Y-m-d H:i:s',time()),
                    ];
                    DB::table('user_opeart_log')->where('user_id',$input['user_id'])->update($log_data);
                }else{
                    $log_data = [
                        'user_id'           => $input['user_id'],
                        'opeartor_method'   => 7,
                        'created_at'        => date('Y-m-d H:i:s',time()),
                    ];
                    DB::table('user_opeart_log')->insert($log_data);
                }
                // 房屋操作节点
                $house_log_data = [
                    'user_id'   => $input['user_id'],
                    'rent_house_id' => $rent_house_id,
                    'log_type'      => 9,
                    'created_at'    => date('Y-m-d H:i:s',time()),
                ];
                DB::table('house_log')->insert($house_log_data);
                return $this->success('suspend contract success');
            }else{
                return $this->error('2','suspend contract failed');
            }
        }
    }


    /**
     * @description:租金中止确认
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentSuspendSure(array $input)
    {
        $contract_id = $input['contract_id'];
        $tenement_res = ContractTenement::where('contract_id',$contract_id)->first();
        $contract_info = RentContract::where('id',$contract_id)->first();
        $property_address = RentHouse::where('id',$contract_info->house_id)->pluck('address')->first();
        $check_date = Inspect::where('rent_house_id',$contract_info->house_id)->orderBy('id','DESC')->pluck('inspect_completed_date')->first();
        $arrears_data = RentArrears::where('contract_id',$contract_id)->whereIn('is_pay',[1,3])->where('arrears_type','<',4)->get();
        $bond = 0;
        $rent = 0;
        $other = 0;
        if($arrears_data){
            $arrears_data = $arrears_data->toArray();
            foreach ($arrears_data as $k => $v){
                if($v['arrears_type'] == 1){
                    $bond += $v['need_pay_fee'];
                }elseif($v['arrears_type'] == 2){
                    $rent += $v['need_pay_fee'];
                }else{
                    $other += $v['need_pay_fee'];
                }
            }
        }
        $data['tenement_res'] = $tenement_res;
        $data['contract_info'] = $contract_info->toArray();
        $data['property_address'] = $property_address;
        $data['check_date'] = $check_date;
        $data['arrears_data'] = $arrears_data;
        $data['bond'] = $bond;
        $data['rent'] = $rent;
        $data['other'] = $other;
        return $this->success('get contract info success',$data);
    }

    /**
     * @description:租约诉讼
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentLitigation(array $input)
    {
        $contract_id = $input['contract_id'];
        $landlord_name = RentContract::where('id',$contract_id)->pluck('landlord_full_name')->first();
        $property_addres = RentContract::where('id',$contract_id)->pluck('house_address')->first();
        $contract_sn = RentContract::where('id',$contract_id)->pluck('contract_id')->first();
        $tenement_name = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_full_name')->first();
        $tenement_phone = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_phone')->first();
        $arrears_res = RentArrears::where('contract_id',$contract_id)->where('arrears_type','!=',4)->where('is_pay','!=',2)->get();
        $total_arrears = RentArrears::where('contract_id',$contract_id)->where('arrears_type','!=',4)->sum('need_pay_fee');
        $total_rent_fee = RentArrears::where('contract_id',$contract_id)->where('arrears_type',2)->sum('need_pay_fee');
        $first_arrears_date = RentArrears::where('contract_id',$contract_id)->where('arrears_type','!=',4)->where('is_pay','!=',2)->pluck('created_at')->first();
        $arrears_date = ceil((time()-strtotime($first_arrears_date))/3600/24);
        $data['landlord_name'] = $landlord_name;
        $data['property_address'] = $property_addres;
        $data['contract_sn'] = $contract_sn;
        $data['tenement_name'] = $tenement_name;
        $data['tenement_phone'] = $tenement_phone;
        $data['arrears_res'] = $arrears_res;
        $data['total_arrears'] = $total_arrears;
        $data['total_rent_fee'] = $total_rent_fee;
        $data['arrears_date'] = $arrears_date;
        return $this->success('get contract info success',$data);
    }

    /**
     * @description:租约打印
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function contractPrint(array $input)
    {
        $contract_id = $input['contract_id'];
        $model = new RentContract();
        $contract_type = $model->where('id',$contract_id)->pluck('contract_type')->first();
        if($contract_type == 1){
            $contract_res = $model->where('id',$contract_id)->first();
            $tenement_res = ContractTenement::where('contract_id',$contract_id)->first();
            $entire_res = EntireContract::where('contract_id',$contract_id)->first();
            $rent_house_res = RentHouse::where('id',$contract_res->house_id)->first();
            // PDF
            $ip = "{$_SERVER['SERVER_NAME']}";
            $dashboard_pdf_file = "http://".$ip."/pdf/entirecontract-unlocked.pdf";
            $fileContent = file_get_contents($dashboard_pdf_file,'rb');
            $mpdf = new Mpdf();
            $pagecount = $mpdf->setSourceFile(StreamReader::createByString($fileContent));
            for($i=1; $i<=$pagecount;$i++){
                $import_page = $mpdf->importPage($i);
                $mpdf->useTemplate($import_page);
                if($i == 7){
                    $mpdf->WriteText(28,41,(string)$contract_res->landlord_full_name);
                    $mpdf->WriteText(55,57,(string)$contract_res->house_address);
                    $mpdf->WriteText(23,63,(string)$contract_res->landlord_e_mail);
                    $mpdf->WriteText(25,73,(string)$contract_res->landlord_telephone);
                    $mpdf->WriteText(70,73,(string)$contract_res->landlord_mobile_phone);
                    $mpdf->WriteText(110,73,(string)$contract_res->landlord_hm);
                    $mpdf->WriteText(155,73,(string)$contract_res->landlord_wk);
                    $mpdf->WriteText(52,79,(string)$contract_res->landlord_other_address);
                    $mpdf->WriteText(15,91,(string)$contract_res->landlord_additional_address);
                    $mpdf->WriteText(28,119,(string)$tenement_res->tenement_full_name);
                    if($tenement_res->identification_type == 1){
                        $mpdf->WriteText(46,124,(string)'√');
                    }elseif($tenement_res->identification_type == 2){
                        $mpdf->WriteText(79,124,(string)'√');
                    }elseif($tenement_res->identification_type == 3){
                        $mpdf->WriteText(106,124,(string)'√');
                    }
                    $mpdf->WriteText(144,125,(string)$tenement_res->identification_no);
                    $mpdf->WriteText(55,142,(string)$tenement_res->service_physical_address);
                    $mpdf->WriteText(23,148,(string)$tenement_res->tenement_e_mail);
                    $mpdf->WriteText(25,158,(string)$tenement_res->tenement_phone);
                    $mpdf->WriteText(70,158,(string)$tenement_res->tenement_mobile);
                    $mpdf->WriteText(110,158,(string)$tenement_res->tenement_hm);
                    $mpdf->WriteText(155,158,(string)$tenement_res->tenement_wk);
                    $mpdf->WriteText(52,164,(string)$tenement_res->other_contact_address);
                    $mpdf->WriteText(15,177,(string)$tenement_res->additional_address);
                    if($tenement_res->is_child == 1){
                        $mpdf->WriteText(28,189,(string)'∨');
                    }else{
                        $mpdf->WriteText(15,189,(string)'∨');
                    }
                    $mpdf->WriteText(15,213,(string)$entire_res->tenancy_address);
                    $mpdf->WriteText(38,226,(string)$entire_res->rent_per_week);
                    if($entire_res->to_be_paid == 0){
                        $mpdf->WriteText(78,225,(string)'∨');
                    }
                    if($entire_res->pay_method == 2){
                        $mpdf->WriteText(133,225,(string)'∨');
                    }else{
                        $mpdf->WriteText(152,225,(string)'∨');
                    }
                    $mpdf->WriteText(38,226,(string)$entire_res->rent_per_week);
                    $mpdf->WriteText(38,232,(string)$entire_res->bond_amount);
                    $mpdf->WriteText(42,239,(string)$entire_res->rent_to_be_paid_at);
                    $bank_count = str_split($entire_res->bank_account);
                    foreach ($bank_count as $k => $v){
                        if($k < 2){
                            $mpdf->WriteText(60+$k*4,246,(string)$v);
                        }elseif($k <9){
                            $mpdf->WriteText(60+$k*5,246,(string)$v);
                        }else{
                            $mpdf->WriteText(59+$k*5,246,(string)$v);
                        }
                    }
                    $mpdf->WriteText(36,255,(string)$entire_res->account_name);
                    $mpdf->WriteText(24,262,(string)$entire_res->bank);
                    $mpdf->WriteText(70,262,(string)$entire_res->branch);
                }
                if($i == 8){
                    $day = $entire_res->effective_date;
                    $day = explode('-',$day);
                    $mpdf->WriteText(70,38,(string)$day[2]);
                    $mpdf->WriteText(110,38,(string)$day[1]);
                    $mpdf->WriteText(150,38,(string)substr($day[0],2));
                    if($entire_res->can_periodic_tenancy == 2){
                        $end_day = $entire_res->end_date;
                        $end_day = explode('-',$end_day);
                        $mpdf->WriteText(85,62,(string)$end_day[2]);
                        $mpdf->WriteText(125,62,(string)$end_day[1]);
                        $mpdf->WriteText(165,62,(string)substr($end_day[0],2));
                    }
                    $mpdf->WriteText(26,113,(string)$entire_res->rule);
                    $mpdf->WriteText(80,190,(string)$contract_res->landlord_full_name);
                    $mpdf->WriteText(80,210,(string)$tenement_res->tenement_full_name);
                    $mpdf->Image($entire_res->landlord_signature, 160, 180, 20, 20, 'png', '', true, true);
                    $mpdf->Image($entire_res->tenement_signature, 160, 200, 20, 20, 'png', '', true, false);
                }
                if($i == 9){
                    if($entire_res->is_ceiling_insulation == 1){
                        $mpdf->WriteText(26,55,(string)'∨');
                    }else{
                        $mpdf->WriteText(43,55,(string)'∨');
                    }
                    $mpdf->WriteText(26,73,(string)$entire_res->ceiling_insulation_detail);
                    if($entire_res->is_insulation_underfloor_insulation == 1){
                        $mpdf->WriteText(26,90,(string)'∨');
                    }else{
                        $mpdf->WriteText(43,90,(string)'∨');
                    }
                    $mpdf->WriteText(26,108,(string)$entire_res->insulation_underfloor_insulation_detail);
                    if($entire_res->location_ceiling_insulation == 1){
                        $mpdf->WriteText(49,129,(string)'∨');
                    }elseif ($entire_res->location_ceiling_insulation == 2){
                        $mpdf->WriteText(49,134,(string)'∨');
                        $mpdf->WriteText(54,136,(string)$entire_res->location_ceiling_insulation_detail);
                    }elseif ($entire_res->location_ceiling_insulation == 3){
                        $mpdf->WriteText(49,142,(string)'∨');
                    }elseif ($entire_res->location_ceiling_insulation == 4){
                        $mpdf->WriteText(49,147,(string)'∨');
                        $mpdf->WriteText(54,151,(string)$entire_res->location_ceiling_insulation_detail);
                    }
                    if($entire_res->ceiling_insulation_type == 1){
                        $mpdf->WriteText(49,156,(string)'∨');
                    }elseif ($entire_res->ceiling_insulation_type == 2){
                        $mpdf->WriteText(49,161,(string)'∨');
                    }elseif ($entire_res->ceiling_insulation_type == 3){
                        $mpdf->WriteText(49,166,(string)'∨');
                        $mpdf->WriteText(60,166,(string)$entire_res->ceiling_insulation_type_detail);
                    }elseif ($entire_res->ceiling_insulation_type == 4){
                        $mpdf->WriteText(49,171,(string)'∨');
                    }
                    $mpdf->WriteText(87,177,(string)$entire_res->R_value);
                    $mpdf->WriteText(161,177,(string)$entire_res->minimum_thickness);
                    $mpdf->WriteText(95,183,(string)$entire_res->ceiling_insulation_age);
                    if($entire_res->ceiling_insulation_condition == 1){
                        $mpdf->WriteText(49,190,(string)'∨');
                        $mpdf->WriteText(60,195,(string)$entire_res->ceiling_insulation_condition_reason);
                    }elseif ($entire_res->ceiling_insulation_condition == 2){
                        $mpdf->WriteText(49,200,(string)'∨');
                    }elseif ($entire_res->ceiling_insulation_condition == 3){
                        $mpdf->WriteText(49,208,(string)'∨');
                    }
                    if($entire_res->location_underfloor_insulation == 1){
                        $mpdf->WriteText(49,219,(string)'∨');
                    }elseif ($entire_res->location_underfloor_insulation == 2){
                        $mpdf->WriteText(49,224,(string)'∨');
                        $mpdf->WriteText(54,226,(string)$entire_res->location_underfloor_insulation_detail);
                    }elseif ($entire_res->location_underfloor_insulation == 3){
                        $mpdf->WriteText(49,232,(string)'∨');
                    }elseif ($entire_res->location_underfloor_insulation == 4){
                        $mpdf->WriteText(49,237,(string)'∨');
                        $mpdf->WriteText(54,246,(string)$entire_res->location_underfloor_insulation_detail);
                    }
                    if($entire_res->underfloor_insulation_type == 1){
                        $mpdf->WriteText(49,253,(string)'∨');
                    }elseif ($entire_res->underfloor_insulation_type == 2){
                        $mpdf->WriteText(49,258,(string)'∨');
                    }elseif ($entire_res->underfloor_insulation_type == 3){
                        $mpdf->WriteText(49,263,(string)'∨');
                    }elseif ($entire_res->underfloor_insulation_type == 4){
                        $mpdf->WriteText(49,268,(string)'∨');
                    }elseif ($entire_res->underfloor_insulation_type == 5){
                        $mpdf->WriteText(49,273,(string)'∨');
                        $mpdf->WriteText(74,273,(string)$entire_res->underfloor_insulation_type_detail);
                    }
                }
                if($i == 10){
                    if($entire_res->underfloor_insulation_type == 6){
                        $mpdf->WriteText(49,33,(string)'∨');
                    }
                    $mpdf->WriteText(87,39,(string)$entire_res->underfloor_R_value);
                    $mpdf->WriteText(161,39,(string)$entire_res->underfloor_minimum_thickness);
                    if($entire_res->condition == 1){
                        $mpdf->WriteText(49,53,(string)'∨');
                        $mpdf->WriteText(54,58,(string)$entire_res->condition_detail);
                    }elseif ($entire_res->condition == 2){
                        $mpdf->WriteText(49,63,(string)'∨');
                    }elseif ($entire_res->condition == 3){
                        $mpdf->WriteText(49,68,(string)'∨');
                    }
                    if($entire_res->condition == 1){
                        $mpdf->WriteText(49,53,(string)'∨');
                        $mpdf->WriteText(54,58,(string)$entire_res->condition_detail);
                    }elseif ($entire_res->condition == 2){
                        $mpdf->WriteText(49,63,(string)'∨');
                    }elseif ($entire_res->condition == 3){
                        $mpdf->WriteText(49,68,(string)'∨');
                    }
                    if($entire_res->wall_insulation == 1){
                        $mpdf->WriteText(49,80,(string)'∨');
                    }elseif ($entire_res->wall_insulation == 2){
                        $mpdf->WriteText(49,85,(string)'∨');
                        $mpdf->WriteText(54,82,(string)$entire_res->wall_insulation_detail);
                    }elseif ($entire_res->wall_insulation == 3){
                        $mpdf->WriteText(49,93,(string)'∨');
                    }elseif ($entire_res->wall_insulation == 4){
                        $mpdf->WriteText(49,98,(string)'∨');
                    }
                    $mpdf->WriteText(49,112,(string)$entire_res->supplementary_information);
                    if($entire_res->install_insulation == 1){
                        $mpdf->WriteText(26,138,(string)'∨');
                    }elseif ($entire_res->install_insulation == 2){
                        $mpdf->WriteText(43,138,(string)'∨');
                        $mpdf->WriteText(26,148,(string)$entire_res->install_insulation_detail);
                    }
                    if($entire_res->underfloor_insulation == 1){
                        $mpdf->WriteText(26,176,(string)'∨');
                    }elseif ($entire_res->underfloor_insulation == 2){
                        $mpdf->WriteText(43,176,(string)'∨');
                        $mpdf->WriteText(26,186,(string)$entire_res->install_insulation_detail);
                    }
                    $mpdf->WriteText(95,208,(string)$entire_res->last_upgraded);
                    $mpdf->WriteText(95,216,(string)$entire_res->professionally_assessed);
                    $mpdf->WriteText(26,230,(string)$entire_res->plan);
                    $mpdf->WriteText(26,254,(string)$entire_res->landlord_state);
                    $mpdf->Image($entire_res->landlord_signature, 160, 266, 15, 15, 'png', '', true, true);
                }
                if($i == 12){
                    $mpdf->WriteText(125,195,(string)$contract_res->landlord_full_name);
                    $mpdf->WriteText(125,215,(string)$tenement_res->tenement_full_name);
                    $mpdf->Image($entire_res->landlord_signature, 180, 180, 20, 20, 'png', '', true, true);
                    $mpdf->Image($entire_res->tenement_signature, 180, 200, 20, 20, 'png', '', true, false);
                    $mpdf->WriteText(152,245,(string)$entire_res->bond_amount);
                }
                if($i < $pagecount){
                    $mpdf->AddPage();
                }
                //

            }
            return $this->success('get pdf success',$mpdf->Output());
        }elseif ($contract_type == 2){
            $contract_res = $model->where('id',$contract_id)->first();
            $tenement_res = ContractTenement::where('contract_id',$contract_id)->first();
            $separate_res = SeparateContract::where('contract_id',$contract_id)->first();
            $rent_house_res = RentHouse::where('id',$contract_res->house_id)->first();
            $service_res = ContractService::where('contract_id',$contract_id)->get();
            // PDF
            $ip = "{$_SERVER['SERVER_NAME']}";
            $dashboard_pdf_file = "http://".$ip."/pdf/separatecontract-unlocked.pdf";
            $fileContent = file_get_contents($dashboard_pdf_file,'rb');
            $mpdf = new Mpdf();
            $pagecount = $mpdf->setSourceFile(StreamReader::createByString($fileContent));
            for($i=1; $i<=$pagecount;$i++){
                $import_page = $mpdf->importPage($i);
                $mpdf->useTemplate($import_page);
                if($i == 2){
                    $mpdf->WriteText(31,31,(string)$contract_res->landlord_full_name);
                    $mpdf->WriteText(23,52,(string)$contract_res->house_address);
                    $mpdf->WriteText(27,59,(string)$contract_res->landlord_e_mail);
                    $mpdf->WriteText(28,69,(string)$contract_res->landlord_telephone);
                    $mpdf->WriteText(73,69,(string)$contract_res->landlord_mobile_phone);
                    $mpdf->WriteText(113,69,(string)$contract_res->landlord_hm);
                    $mpdf->WriteText(158,69,(string)$contract_res->landlord_wk);
                    $mpdf->WriteText(23,82,(string)$contract_res->landlord_other_address);
                    $mpdf->WriteText(23,93,(string)$contract_res->landlord_additional_address);
                    $mpdf->WriteText(31,115,(string)$separate_res->agent_name);
                    $mpdf->WriteText(23,136,(string)$separate_res->agent_address);
                    $mpdf->WriteText(27,142,(string)$separate_res->agent_e_mail);
                    $mpdf->WriteText(27,152,(string)$separate_res->agent_phone);
                    $mpdf->WriteText(72,152,(string)$separate_res->agent_mobile);
                    $mpdf->WriteText(112,152,(string)$separate_res->agent_hm);
                    $mpdf->WriteText(157,152,(string)$separate_res->agent_wk);
                    $mpdf->WriteText(23,164,(string)$separate_res->agent_other_address);
                    $mpdf->WriteText(23,176,(string)$separate_res->agent_additional_address);
                    $mpdf->WriteText(29,205,(string)$tenement_res->tenement_full_name);
                    if($tenement_res->identification_type == 1){
                        $mpdf->WriteText(45,218,(string)'√');
                    }elseif($tenement_res->identification_type == 2){
                        $mpdf->WriteText(78,218,(string)'√');
                    }elseif($tenement_res->identification_type == 3){
                        $mpdf->WriteText(105,218,(string)'√');
                    }
                    $mpdf->WriteText(147,219,(string)$tenement_res->identification_no);
                    $mpdf->WriteText(57,235,(string)$tenement_res->service_physical_address);
                    $mpdf->WriteText(32,248,(string)$tenement_res->tenement_e_mail);
                    $mpdf->WriteText(27,258,(string)$tenement_res->tenement_phone);
                    $mpdf->WriteText(71,258,(string)$tenement_res->tenement_mobile);
                    $mpdf->WriteText(110,258,(string)$tenement_res->tenement_hm);
                    $mpdf->WriteText(155,258,(string)$tenement_res->tenement_wk);
                    $mpdf->WriteText(56,264,(string)$tenement_res->other_contact_address);
                }
                if($i == 3){
                    $mpdf->WriteText(17,32,(string)$tenement_res->additional_address);
                    if($tenement_res->is_child == 0){
                        $mpdf->WriteText(30,42,(string)'∨');
                    }else{
                        $mpdf->WriteText(17,42,(string)'∨');
                    }
                    $mpdf->WriteText(17,68,(string)$separate_res->tenancy_address);
                    if($separate_res->is_house_rule == 1){
                        $mpdf->WriteText(18,83,(string)'∨');
                    }
                    if($separate_res->is_fire == 1){
                        $mpdf->WriteText(18,89,(string)'∨');
                    }
                    if($separate_res->is_body == 1){
                        $mpdf->WriteText(18,95,(string)'∨');
                    }
                    if($separate_res->to_be_paid == 1){
                        $mpdf->WriteText(93,103,(string)'∨');
                    }elseif($separate_res->pay_method == 2){
                        $mpdf->WriteText(123,103,(string)'∨');
                    }elseif ($separate_res->pay_method == 3){
                        $mpdf->WriteText(148,103,(string)'∨');
                    }
                    $mpdf->WriteText(40,104,(string)$separate_res->rent_per_week);
                    $mpdf->WriteText(40,110,(string)$separate_res->bond_amount);
                    $mpdf->WriteText(45,116,(string)$separate_res->rent_to_be_paid_at);
                    $bank_count = str_split($separate_res->bank_account);
                    foreach ($bank_count as $k => $v){
                        if($k < 2){
                            $mpdf->WriteText(62+$k*4,125,(string)$v);
                        }elseif($k <6){
                            $mpdf->WriteText(62+$k*5,125,(string)$v);
                        }elseif($k <9){
                            $mpdf->WriteText(63+$k*5,125,(string)$v);
                        }elseif($k <13){
                            $mpdf->WriteText(72+$k*4,125,(string)$v);
                        }else{
                            $mpdf->WriteText(61+$k*5,125,(string)$v);
                        }
                    }
                    $mpdf->WriteText(38,132,(string)$separate_res->account_name);
                    $mpdf->WriteText(26,140,(string)$separate_res->bank);
                    $mpdf->WriteText(72,140,(string)$separate_res->branch);
                    $day = $separate_res->agree_date;
                    $day = explode('-',$day);
                    $mpdf->WriteText(90,156,(string)$day[2]);
                    $mpdf->WriteText(130,156,(string)$day[1]);
                    $mpdf->WriteText(170,156,(string)substr($day[0],2));
                    if($separate_res->intended == 1){
                        $mpdf->WriteText(23,164,(string)'∨');
                    }elseif ($separate_res->intended == 1){
                        $mpdf->WriteText(23,172,(string)'∨');
                    }
                    if($separate_res->is_joint_tenancy == 1){
                        $mpdf->WriteText(23,182,(string)'∨');
                        $mpdf->WriteText(100,182,(string)$separate_res->is_joint_tenancy_detail);
                    }elseif ($separate_res->is_joint_tenancy == 2){
                        $mpdf->WriteText(23,188,(string)'∨');
                    }
                    if($separate_res->is_not_share == 1){
                        $mpdf->WriteText(23,198,(string)'∨');
                    }elseif ($separate_res->is_not_share == 2){
                        $mpdf->WriteText(23,204,(string)'∨');
                        $mpdf->WriteText(50,210,(string)$separate_res->is_share_people);
                    }
                    foreach ($service_res as $k => $v){
                        if($k < 2){
                            $mpdf->WriteText(25,228+$k*7,(string)$v->service_name);
                            $mpdf->WriteText(130,228+$k*7,(string)$v->service_price);
                        }
                    }
                    $mpdf->WriteText(25,260,(string)$separate_res->allow_service);
                }
                if($i == 5){
                    $mpdf->WriteText(70,120,(string)$contract_res->landlord_full_name);
                    $mpdf->Image($separate_res->landlord_signature, 170, 105, 20, 20, 'png', '', true, true);
                    $mpdf->WriteText(70,165,(string)$contract_res->landlord_full_name);
                    $mpdf->Image($separate_res->landlord_signature, 170, 150, 20, 20, 'png', '', true, true);
                    $mpdf->WriteText(70,188,(string)$tenement_res->tenement_full_name);
                    $mpdf->Image($separate_res->tenement_signature, 180, 173, 20, 20, 'png', '', true, false);
                }
                if($i == 6){
                    $mpdf->WriteText(70,230,(string)$contract_res->landlord_full_name);
                    $mpdf->Image($separate_res->landlord_signature, 170, 215, 20, 20, 'png', '', true, true);
                    $mpdf->WriteText(70,250,(string)$tenement_res->tenement_full_name);
                    $mpdf->Image($separate_res->tenement_signature, 180, 235, 20, 20, 'png', '', true, false);
                }
                if($i < $pagecount){
                    $mpdf->AddPage();
                }
                //

            }
            return $this->success('get pdf success',$mpdf->Output());
        }elseif ($contract_type == 3){
            $contract_res = $model->where('id',$contract_id)->first();
            $tenement_res = ContractTenement::where('contract_id',$contract_id)->first();
            $separate_res = SeparateContract::where('contract_id',$contract_id)->first();
            $rent_house_res = RentHouse::where('id',$contract_res->house_id)->first();
            $service_res = ContractService::where('contract_id',$contract_id)->get();
            // PDF
            $ip = "{$_SERVER['SERVER_NAME']}";
            $dashboard_pdf_file = "http://".$ip."/pdf/flatcontract.pdf";
            $fileContent = file_get_contents($dashboard_pdf_file,'rb');
            $mpdf = new Mpdf();
            $pagecount = $mpdf->setSourceFile(StreamReader::createByString($fileContent));
            for($i=1; $i<=$pagecount;$i++){
                $import_page = $mpdf->importPage($i);
                $mpdf->useTemplate($import_page);
                if($i == 1){
                    $mpdf->WriteText(45,72,(string)$contract_res->landlord_full_name);
                    $mpdf->WriteText(145,72,(string)$tenement_res->tenement_full_name);
                    $mpdf->WriteText(30,85,(string)$contract_res->landlord_additional_address);
                    $mpdf->WriteText(120,85,(string)$tenement_res->other_contact_address);
                    $mpdf->WriteText(35,118,(string)$contract_res->landlord_mobile_phone);
                    $mpdf->WriteText(120,118,(string)$tenement_res->tenement_mobile);
                    $mpdf->WriteText(35,125,(string)$contract_res->landlord_e_mail);
                    $mpdf->WriteText(120,125,(string)$tenement_res->tenement_e_mail);
                    $mpdf->WriteText(52,176,(string)$separate_res->rent_per_week);
                    $mpdf->WriteText(50,186,(string)$separate_res->bond_amount);
                    $day = $contract_res->rent_start_date;
                    $day = explode('-',$day);
                    $mpdf->WriteText(80,169,(string)$day[2]);
                    $mpdf->WriteText(87,169,(string)$day[1]);
                    $mpdf->WriteText(95,169,(string)substr($day[0],2));
                    $mpdf->WriteText(35,140,(string)$rent_house_res->address);
                    $district = Region::getName($rent_house_res->District);
                    $city = Region::getName($rent_house_res->TA);
                    $mpdf->WriteText(120,140,(string)$district);
                    $mpdf->WriteText(35,148,(string)$city);
                }
                if($i == 3){
                    $mpdf->WriteText(70,100,(string)$contract_res->landlord_full_name);
                    $mpdf->Image($separate_res->landlord_signature, 170, 85, 20, 20, 'png', '', true, true);
                    $mpdf->WriteText(70,120,(string)$tenement_res->tenement_full_name);
                    $mpdf->Image($separate_res->tenement_signature, 180, 105, 20, 20, 'png', '', true, false);
                    $mpdf->WriteText(70,165,(string)$contract_res->landlord_full_name);
                    $mpdf->Image($separate_res->landlord_signature, 170, 150, 20, 20, 'png', '', true, true);
                    $mpdf->WriteText(70,185,(string)$tenement_res->tenement_full_name);
                    $mpdf->Image($separate_res->tenement_signature, 180, 170, 20, 20, 'png', '', true, false);
                }
                if($i < $pagecount){
                    $mpdf->AddPage();
                }
            }
            return $this->success('get pdf success',$mpdf->Output());
        }else{
            $contract_res = $model->where('id',$contract_id)->first();
            $tenement_res = ContractTenement::where('contract_id',$contract_id)->first();
            $business_res = BusinessContract::where('contract_id',$contract_id)->first();
            $rent_house_res = RentHouse::where('id',$contract_res->house_id)->first();
            $service_res = ContractService::where('contract_id',$contract_id)->get();
            // PDF
            $ip = "{$_SERVER['SERVER_NAME']}";
            $dashboard_pdf_file = "http://".$ip."/pdf/businesscontract.pdf";
            $fileContent = file_get_contents($dashboard_pdf_file,'rb');
            $mpdf = new Mpdf();
            $pagecount = $mpdf->setSourceFile(StreamReader::createByString($fileContent));
            for($i=1; $i<=$pagecount;$i++){
                $import_page = $mpdf->importPage($i);
                $mpdf->useTemplate($import_page);
                if($i == 1){
                    $mpdf->WriteText(75,30,(string)$contract_res->rent_start_date);
                    $mpdf->WriteText(25,50,(string)$contract_res->landlord_full_name);
                    $mpdf->WriteText(25,70,(string)$tenement_res->tenement_full_name);
                    $mpdf->WriteText(25,90,(string)$tenement_res->guarantor_name);
                    $mpdf->Image($business_res->landlord_signature, 120, 165, 10, 10, 'png', '', true, true);
                }
                if($i == 2){
                    $mpdf->Image($business_res->tenement_signature, 120, 25, 10, 10, 'png', '', true, true);
                }
                if($i == 3){
                    $mpdf->WriteText(85,24,(string)$business_res->premises);
                    $mpdf->WriteText(85,36,(string)$business_res->car_parks);
                    $mpdf->WriteText(85,49,(string)$business_res->lease_term);
                    $mpdf->WriteText(85,57,(string)$business_res->commencement_date);
                    if($business_res->renewal_right == 1){
                        $mpdf->WriteText(85,65,(string)'yes');
                    }else{
                        $mpdf->WriteText(85,65,(string)'no');
                    }
                    $mpdf->WriteText(85,73,(string)$business_res->renewal_time);
                    $mpdf->WriteText(85,81,(string)$business_res->final_expiry_date);
                    $mpdf->WriteText(115,106,(string)$business_res->premises_pro);
                    $mpdf->WriteText(175,106,(string)$business_res->premises_gst);
                    $mpdf->WriteText(115,112,(string)$business_res->car_parks_pro);
                    $mpdf->WriteText(175,112,(string)$business_res->car_gst);
                    $mpdf->WriteText(115,118,(string)$business_res->total);
                    $mpdf->WriteText(175,118,(string)$business_res->total_gst);
                    $mpdf->WriteText(95,143,(string)$business_res->month_rent);
                    $mpdf->WriteText(100,151,(string)$business_res->rent_payment_date);
                    $mpdf->WriteText(170,151,(string)$business_res->day_each_month);
                    $mpdf->WriteText(95,185,(string)$business_res->market_rent_assessment_date);
                    $mpdf->WriteText(95,203,(string)$business_res->cpi_date);
                    $mpdf->WriteText(150,226,(string)$business_res->outgoing);
                    $mpdf->WriteText(150,238,(string)$business_res->default_interest_rate);
                    $mpdf->WriteText(23,260,(string)$business_res->business_use);
                }
                if($i == 4){
                    $mpdf->WriteText(23,35,(string)$business_res->insurance);
                    $mpdf->WriteText(23,138,(string)$business_res->no_access_period);
                    $mpdf->WriteText(23,175,(string)$business_res->further_term);
                }
                if($i == 5){
                    $mpdf->WriteText(28,35,(string)$business_res->tax_apy_local_detail);
                    $mpdf->WriteText(28,48,(string)$business_res->hydroelectric_detail);
                    $mpdf->WriteText(28,63,(string)$business_res->garbage_collection_detail);
                    $mpdf->WriteText(28,76,(string)$business_res->fire_service_detail);
                    $mpdf->WriteText(28,92,(string)$business_res->insurance_excess_detail);
                    $mpdf->WriteText(28,105,(string)$business_res->air_conditioning_detail);
                    $mpdf->WriteText(28,145,(string)$business_res->provide_toilets_detail);
                    $mpdf->WriteText(28,158,(string)$business_res->maintenance_cost_for_garden_detail);
                    $mpdf->WriteText(28,174,(string)$business_res->maintenance_cost_for_parks_detail);
                    $mpdf->WriteText(28,203,(string)$business_res->management_cost_detail);
                    $mpdf->WriteText(28,223,(string)$business_res->incurred_cost_detail);
                }
                if($i == 17){
                    $mpdf->WriteText(28,45,(string)$business_res->fixtures_fittings);
                }
                if($i == 18){
                    $mpdf->WriteText(28,45,(string)$business_res->premises_condition);
                }
                if($i == 19){
                    $mpdf->Image($business_res->landlord_signature, 120, 25, 30, 30, 'png', '', true, true);
                    $mpdf->Image($business_res->tenement_signature, 120, 65, 30, 30, 'png', '', true, true);
                }
                if($i < $pagecount){
                    $mpdf->AddPage();
                }
                //

            }
            return $this->success('get pdf success',$mpdf->Output());
        }

    }

    /**
     * @description:租约打印
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function marketRentFee(array $input)
    {
        $District = $input['District'];
        $property_type = $input['property_type'];
        $time = date('Y-m',strtotime('-24 month'));
        $token = 'c2839b211876bdd05c6511edfd198eeb';
        $header = [
            'headers' => [
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ]
        ];
        $url = "https://api.business.govt.nz/services/v1/tenancy-services/market-rent/statistics?period-ending=$time&num-months=24&area-definition=AU2016&include-aggregates=false&statistics=nLodged%2Cmed%2Clq%2Cuq%2Cbrr&dwelling-type=$property_type&num-bedrooms=1%2C2%2C3%2C4%2C5%2B&area-codes=$District";
        $http = new \GuzzleHttp\Client();
        $response = $http->request('get',$url,$header);
        $response = json_decode($response->getBody());
        if(isset($response->status) ){
            $data = [];
            return $this->error('2','get market rent information failed');
        }else{
            $data = $response->items;
            return $this->success('get market rent information success',$data);
        }

    }

    /**
     * @description:租户租房申请（非本平台）打印
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentApplicationOutPrint(array $input)
    {
        //dd($input);
        $outApplication = $input['outApplication_id'];
        $res = OtherRentApplication::where('id', $outApplication)->first();
        // PDF
        $ip = "{$_SERVER['SERVER_NAME']}";
        $dashboard_pdf_file = "http://" . $ip . "/pdf/PreTenancyApplicaitonForm.pdf";
        $fileContent = file_get_contents($dashboard_pdf_file, 'rb');
        $mpdf = new Mpdf();
        $pagecount = $mpdf->setSourceFile(StreamReader::createByString($fileContent));
        for ($i = 1; $i <= $pagecount; $i++) {
            $import_page = $mpdf->importPage($i);
            $mpdf->useTemplate($import_page);
            if ($i == 1) {
                $mpdf->WriteText(43, 63, (string)$res->apply_house_address);
                $mpdf->WriteText(66, 72, (string)$res->apply_start_time);
                $mpdf->WriteText(112, 72, (string)$res->apply_end_time);
                $mpdf->WriteText(32, 95, (string)$res->tenement_name);
                $mpdf->WriteText(125, 95, (string)$res->birthday);
                $mpdf->WriteText(40, 102, (string)$res->phone);
                $mpdf->WriteText(127, 102, (string)$res->mobile);
                $mpdf->WriteText(24, 109, (string)$res->email);
                $mpdf->WriteText(137, 109, (string)$res->welfare_no);
                if($res->have_pets == 1){
                    $mpdf->WriteText(13, 122, (string)'√');
                    $mpdf->WriteText(38, 122, (string)$res->pets);
                }else{
                    $mpdf->WriteText(68, 122, (string)'√');
                }
                $mpdf->WriteText(40, 140, (string)$res->current_address);
                $mpdf->WriteText(125, 140, (string)$res->current_rent_fee);
                if($res->rent_way == 1){
                    $mpdf->WriteText(113, 147, (string)$res->rent_times);
                }elseif ($res->rent_way == 2){
                    $mpdf->WriteText(113, 147, (string)$res->rent_times);
                }
                if($res->live_method == 1){
                    $mpdf->WriteText(12, 153, (string)'√');
                }elseif ($res->live_method == 2){
                    $mpdf->WriteText(64, 153, (string)'√');
                }elseif ($res->live_method == 3){
                    $mpdf->WriteText(104, 153, (string)'√');
                }elseif ($res->live_method == 4){
                    $mpdf->WriteText(144, 153, (string)'√');
                }else{
                    $mpdf->WriteText(180, 153, (string)'√');
                    $mpdf->WriteText(192, 153, (string)$res->other_method);
                }
                $mpdf->WriteText(77, 172, (string)$res->leave_reason);
                $mpdf->WriteText(27, 192, (string)$res->current_landlord_name);
                $mpdf->WriteText(152, 192, (string)$res->property_manager_name);
                $mpdf->WriteText(25, 199, (string)$res->landlord_phone);
                $mpdf->WriteText(117, 199, (string)$res->manager_phone);
                if($res->inform_landlord == 1){
                    $mpdf->WriteText(13, 206, (string)'√');
                }
                $mpdf->WriteText(52, 227, (string)$res->driving_license);
                $mpdf->WriteText(148, 227, (string)$res->version_num);
                $mpdf->WriteText(28, 244, (string)$res->passport);
                $mpdf->WriteText(64, 251, (string)$res->vehicle);
                $mpdf->WriteText(134, 251, (string)$res->model);
                $mpdf->WriteText(40, 257, (string)$res->model);
                $mpdf->WriteText(140, 257, (string)$res->alternative);
            }
            if ($i == 2){
                if($res->work_situation == 1){
                    $mpdf->WriteText(38, 29, (string)'√');
                } elseif ($res->work_situation == 2){
                    $mpdf->WriteText(61, 29, (string)'√');
                } elseif ($res->work_situation == 3){
                    $mpdf->WriteText(87, 29, (string)'√');
                }elseif ($res->work_situation == 4){
                    $mpdf->WriteText(105, 29, (string)'√');
                }elseif ($res->work_situation == 5){
                    $mpdf->WriteText(133, 29, (string)'√');
                }elseif ($res->work_situation == 6){
                    $mpdf->WriteText(174, 29, (string)'√');
                }
                $mpdf->WriteText(40, 44, (string)$res->company_name);
                $mpdf->WriteText(125, 44, (string)$res->job_title);
                $mpdf->WriteText(41, 50, (string)$res->employer_name);
                $mpdf->WriteText(118, 50, (string)$res->company_address);
                $mpdf->WriteText(26, 58, (string)$res->company_phone);
                $mpdf->WriteText(116, 58, (string)$res->company_email);
                if($res->inform_company == 1){
                    $mpdf->WriteText(13, 64, (string)'√');
                }
                $mpdf->WriteText(49, 72, (string)$res->income);
                $mpdf->WriteText(24, 94, (string)$res->contact_name);
                $mpdf->WriteText(119, 94, (string)$res->contact_address);
                $mpdf->WriteText(38, 101, (string)$res->contact_phone);
                $mpdf->WriteText(127, 101, (string)$res->contact_mobile);
                $mpdf->WriteText(25, 108, (string)$res->contact_email);
                $mpdf->WriteText(125, 108, (string)$res->contact_relation);
                $mpdf->WriteText(40, 173, (string)$res->recommend_name1);
                $mpdf->WriteText(26, 181, (string)$res->recommend_tel1);
                $mpdf->WriteText(25, 188, (string)$res->recommend_email1);
                $mpdf->WriteText(48, 194, (string)$res->recommend_relation1);
                $mpdf->WriteText(136, 173, (string)$res->recommend_name2);
                $mpdf->WriteText(122, 181, (string)$res->recommend_tel2);
                $mpdf->WriteText(121, 188, (string)$res->recommend_email2);
                $mpdf->WriteText(144, 194, (string)$res->recommend_relation2);
            }
            if ($i == 3){
                $imageName = "25220_".date("His",time())."_".rand(1111,9999).'.png';
                if (strstr($res->sign,",")){
                    $image = explode(',',$res->sign);
                    $image = $image[1];
                }
                $path = "./".date("Ymd",time());
                if (!is_dir($path)){ //判断目录是否存在 不存在就创建
                    mkdir($path,0777,true);
                }
                $imageSrc= $path."/". $imageName; //图片名字
                /*dd($imageSrc);*/
               /* $r = file_put_contents($imageSrc, base64_decode($image));//返回的是字节数
                $mpdf->Image($imageSrc, 0, 0, 210, 297, 'png', '', true, false);*/
            }
            if ($i < $pagecount) {
                $mpdf->AddPage();
            }
        }
        return $this->success('get pdf success',$mpdf->Output());
    }

    /**
     * @description:租约打印
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function marketRentFeeAdjust(array $input)
    {
        $contract_id = $input['contract_id'];
        $rent_house_id = RentContract::where('id',$contract_id)->pluck('house_id')->first();
        $rent_house_info = DB::table('rent_house')->where('id',$rent_house_id)->first();
        $District = $rent_house_info->District;
        if($rent_house_info->property_type == 1){
            $property_type = 'Apartment';
        }elseif ($rent_house_info->property_type == 2){
            $property_type = 'House';
        }elseif ($rent_house_info->property_type == 3){
            $property_type = 'NA';
        } elseif ($rent_house_info->property_type == 4){
            $property_type = 'Flat';
        } elseif ($rent_house_info->property_type == 5){
            $property_type = 'Room';
        }elseif ($rent_house_info->property_type == 6){
            $property_type = 'Boarding House';
        }
        $time = date('Y-m',strtotime('-24 month'));
        $token = 'c2839b211876bdd05c6511edfd198eeb';
        $header = [
            'headers' => [
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ]
        ];
        $url = "https://api.business.govt.nz/services/v1/tenancy-services/market-rent/statistics?period-ending=$time&num-months=24&area-definition=AU2016&include-aggregates=false&statistics=nLodged%2Cmed%2Clq%2Cuq%2Cbrr&dwelling-type=$property_type&num-bedrooms=1%2C2%2C3%2C4%2C5%2B&area-codes=$District";
        $http = new \GuzzleHttp\Client();
        $response = $http->request('get',$url,$header);
        $response = json_decode($response->getBody());
        if(isset($response->status) ){
            $data = [];
            return $this->error('2','get market rent information failed');
        }else{
            $data = $response->items;
            return $this->success('get market rent information success',$data);
        }

    }

    /**
     * @description:租约打印
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function litigationStart(array $input)
    {
        $contract_id = $input['contract_id'];
        $user_id= $input['user_id'];
        if($input['is_negotiation'] == 1){// 发服务市场
            $rent_house_id = RentContract::where('id',$contract_id)->pluck('house_id')->first();
            $room_info = RentHouse::where('id',$rent_house_id)->first();
            $model = new LandlordOrder();
            $group_id = $model->max('group_id'); // 获得目前存入的最大group_id
            $order_sn = orderId();
            $order_data = [
                'rent_contract_id'      => @$input['contract_id'],
                'issue_id'              => @$input['issue_id'],
                'group_id'              => $group_id+1,
                'user_id'               => $user_id,
                'tenement_id'           => @$input['tenement_id'],
                'order_sn'              => $order_sn,
                'rent_house_id'         => $rent_house_id,
                'District'              => $room_info->District,
                'TA'                    => $room_info->TA,
                'Region'                => $room_info->Region,
                'order_type'            => 5,
                'start_time'            => $input['start_time'],
                'end_time'              => $input['end_time'],
                'requirement'           => $input['requirement'],
                'budget'                => $input['budget'],
                'created_at'            => date('Y-m-d H:i:s',time()),
            ];
            $res = $model->insert($order_data);
            // 修改租约状态
            RentContract::where('id',$contract_id)->update(['contract_status'=>4,'updated_at'=>date('Y-m-d H:i:s',time())]);
        }else{
            // 修改租约状态
            RentContract::where('id',$contract_id)->update(['contract_status'=>4,'updated_at'=>date('Y-m-d H:i:s',time())]);
        }
        return $this->success('litigation success');
    }


    /**
     * @description:租约打印
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function litigationList(array $input)
    {
        $where = function ($query) use($input){
            //搜索词查询
            if (@$input['tenement_id'] and @$input['tenement_id'] != '') {
                $tenement_id = @$input['tenement_id'];
                $query->where('t.tenement_id','like', '%'.$tenement_id.'%');
            }
            //房屋搜索
            if (@$input['property_name'] and @$input['property_name'] != '') {
                $property_name = @$input['property_name'];
                $query->where('h.property_name','like','%'.$property_name.'%');
            }

            $query->where('r.user_id',$input['user_id'])->where('r.contract_status',4);
        };
        $page = $input['page'];
        $count = DB::table('rent_contract as r')
            ->leftJoin('rent_house as h','r.house_id','h.id')
            ->leftJoin('contract_tenement as ct','ct.contract_id','r.id')
            ->leftJoin('tenement_information as t','t.id','ct.tenement_id')
            ->where($where)->count();
        if($count < ($page-1)*10){
            return $this->error('2','no more data');
        }
        $litigation_contract_res = DB::table('rent_contract as r')
            ->leftJoin('rent_house as h','r.house_id','h.id')
            ->leftJoin('contract_tenement as ct','ct.contract_id','r.id')
            ->leftJoin('tenement_information as t','t.id','ct.tenement_id')
            ->where($where)->limit(10)->offset(($page-1)*10)
            ->select('r.id','t.tenement_id','h.property_name','ct.tenement_phone','ct.tenement_e_mail','r.rent_start_date','r.rent_end_date')->get();
        $data['res'] = $litigation_contract_res;
        $data['current_page'] = $page;
        $data['total_page'] = ceil($count/10);
        return $this->success('get litigation list success',$data);
    }
}