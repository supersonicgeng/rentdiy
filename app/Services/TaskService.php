<?php
/**
 * 任务服务层
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1 0001
 * Time: 下午 4:25
 */

namespace App\Services;


use App\Lib\Util\QueryPager;
use App\Model\CheckBuilding;
use App\Model\ContractTenement;
use App\Model\Driver;
use App\Model\DriverTakeOver;
use App\Model\LandlordOrder;
use App\Model\Level;
use App\Model\OrderArrears;
use App\Model\Plant;
use App\Model\PlantOperateLog;
use App\Model\Providers;
use App\Model\RentAdjust;
use App\Model\RentArrears;
use App\Model\RentContract;
use App\Model\RentHouse;
use App\Model\RouteItems;
use App\Model\ScoreLog;
use App\Model\SignLog;
use App\Model\SysSign;
use App\Model\Task;
use App\Model\Tenement;
use App\Model\UserEvaluate;
use App\Model\UserEvaluateTag;
use App\Model\Verify;
use App\Model\VerifyLog;
use App\User;
use Carbon\Carbon;
use function GuzzleHttp\Psr7\str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TaskService extends CommonService
{
    /**
     * 保险提醒 任务
     */
    public function checkInsurance()
    {
        // 保险到期 房屋
        $out_time_house_id = RentHouse::where('insurance_end_time',date('Y-m-d','+90 day'))->groupBy('group_id')->select('id','user_id')->get();
        foreach ($out_time_house_id as $k => $value){
            $task_data = [
                'user_id'           => $value['user_id'],
                'task_type'         => 2,
                'task_start_time'   => date('Y-m-d H:i:s',time()),
                'task_status'       => 0,
                'task_title'        => 'INSURANCE',
                'task_content'      => 'INSURANCE
Your property insurance will be expired after 90 days. Please be prepared if you need to renew or obtain a quotation from a new insurance company.',
                'rent_house_id'     => $value['id'],
                'task_role'         => 1,
                'created_at'        => date('Y-m-d H:i:s',time()),
            ];
            $task_res = Task::insert($task_data);
        }
    }

    /**
     * 续租 任务
     */
    public function relet()
    {
        // 待续约整租租约
        $out_time_house_id = RentContract::where('rent_end_date',date('Y-m-d','+60 day'))->where('contract_type',1)->select('id','user_id','house_id')->get();
        foreach ($out_time_house_id as $k => $value){
            $rent_house_id = $value['house_id'];
            $property_name = RentHouse::where('id',$rent_house_id)->pluck('property_name')->first();
            $room_name = RentHouse::where('id',$rent_house_id)->pluck('room_name')->first();
            $property_address = RentHouse::where('id',$rent_house_id)->pluck('address')->first();
            $tenement_full_name = ContractTenement::where('contract_id',$value['id'])->pluck('tenement_full_name')->first();
            $task_data = [
                'user_id'           => $value['user_id'],
                'task_type'         => 4,
                'task_start_time'   => date('Y-m-d H:i:s',time()),
                'task_status'       => 0,
                'task_title'        => 'ENDING / RENEW FIXED TERM TENANCY',
                'task_content'      => "ENDING / RENEW FIXED TERM TENANCY
Property: $property_name $room_name $property_address
Tenant name: $tenement_full_name
Your fixed term tenancy will be expired after 60days.
If you wish not to continue this tenancy, a notice must be given to the tenant between 90 and 21 days before the end of the fixed term.
If neither party gives notice the tenancy will automatically become a periodic tenancy once the fixed term ends.",
                'contract_id'       => $value['id'],
                'task_role'         => 1,
                'created_at'        => date('Y-m-d H:i:s',time()),
            ];
            $task_res = Task::insert($task_data);
        }


        // 待续约商业租约
        $out_time_house_id = RentContract::where('rent_end_date',date('Y-m-d','+90 day'))->where('contract_type',4)->select('id','user_id','house_id')->get();
        foreach ($out_time_house_id as $k => $value){
            $rent_house_id = $value['house_id'];
            $property_name = RentHouse::where('id',$rent_house_id)->pluck('property_name')->first();
            $room_name = RentHouse::where('id',$rent_house_id)->pluck('room_name')->first();
            $property_address = RentHouse::where('id',$rent_house_id)->pluck('address')->first();
            $tenement_full_name = ContractTenement::where('contract_id',$value['id'])->pluck('tenement_full_name')->first();
            $task_data = [
                'user_id'           => $value['user_id'],
                'task_type'         => 5,
                'task_start_time'   => date('Y-m-d H:i:s',time()),
                'task_status'       => 0,
                'task_title'        => 'ENDING / RENEW COMMERCIAL PROPERTY LEASE',
                'task_content'      => "ENDING / RENEW COMMERCIAL PROPERTY LEASE
Property: $property_address
Tenant name: $tenement_full_name
Your lease will be expired after 60days.
Please negotiate with tenant to renew this lease or to issue a notice to end the lease if you do not wish to continue this leasing.",
                'contract_id'       => $value['id'],
                'task_role'         => 1,
                'created_at'        => date('Y-m-d H:i:s',time()),
            ];
            $task_res = Task::insert($task_data);
        }
        // 7天提醒
        $out_time_house_id = RentContract::where('rent_end_date',date('Y-m-d','+7 day'))->select('id','user_id')->get();
        foreach ($out_time_house_id as $k => $value){
            $rent_house_id = $value['house_id'];
            $property_name = RentHouse::where('id',$rent_house_id)->pluck('property_name')->first();
            $room_name = RentHouse::where('id',$rent_house_id)->pluck('room_name')->first();
            $property_address = RentHouse::where('id',$rent_house_id)->pluck('address')->first();
            $tenement_full_name = ContractTenement::where('contract_id',$value['id'])->pluck('tenement_full_name')->first();
            $task_data = [
                'user_id'           => $value['user_id'],
                'task_type'         => 21,
                'task_start_time'   => date('Y-m-d H:i:s',time()),
                'task_status'       => 0,
                'task_title'        => 'ENDING / RENEW TENANCY',
                'task_content'      => "ENDING / RENEW TENANCY
Property: $property_address
Tenant name: $tenement_full_name
Your lease will be expired after 7 days.
Please negotiate with tenant to renew this tenancy.",
                'contract_id'       => $value['id'],
                'task_role'         => 1,
                'created_at'        => date('Y-m-d H:i:s',time()),
            ];
            $task_res = Task::insert($task_data);
        }
    }


    /**
     * 涨租金 任务
     */
    public function increaseRate()
    {
        // 整租租约
        $out_time_house_id = RentContract::where('rent_start_date',date('Y-m-d','-120 day'))->select('id','user_id','house_id')->get();
        foreach ($out_time_house_id as $k => $value){
            $rent_house_id = $value['house_id'];
            $property_name = RentHouse::where('id',$rent_house_id)->pluck('property_name')->first();
            $room_name = RentHouse::where('id',$rent_house_id)->pluck('room_name')->first();
            $property_address = RentHouse::where('id',$rent_house_id)->pluck('address')->first();
            $tenement_full_name = ContractTenement::where('contract_id',$value['id'])->pluck('tenement_full_name')->first();
            $task_data = [
                'user_id'           => $value['user_id'],
                'task_type'         => 6,
                'task_start_time'   => date('Y-m-d H:i:s',time()),
                'task_status'       => 0,
                'task_title'        => 'RENT INCREASES',
                'task_content'      => "RENT INCREASES
Property: $property_address
Tenant name: $tenement_full_name
You have an option to increase the rent if you like to reflect the market rent changes. For residential tenancy, If you would like to increase the rent you must give the tenant at least 60 days’ written notice of a rent increase.",
                'contract_id'       => $value['id'],
                'task_role'         => 1,
                'created_at'        => date('Y-m-d H:i:s',time()),
            ];
            $task_res = Task::insert($task_data);
        }

        //

        $out_time_house_id = RentAdjust::where('effective_date',date('Y-m-d','-120 day'))->select('contract_id')->get();
        foreach ($out_time_house_id as $k => $value){
            $rent_house_id = RentContract::where('id',$value['contract_id'])->pluck('house_id')->first();
            $property_name = RentHouse::where('id',$rent_house_id)->pluck('property_name')->first();
            $room_name = RentHouse::where('id',$rent_house_id)->pluck('room_name')->first();
            $property_address = RentHouse::where('id',$rent_house_id)->pluck('address')->first();
            $tenement_full_name = ContractTenement::where('contract_id',$value['contract_id'])->pluck('tenement_full_name')->first();
            $task_data = [
                'user_id'           => RentContract::where('id',$value['contract_id'])->pluck('user_id')->first(),
                'task_type'         => 6,
                'task_start_time'   => date('Y-m-d H:i:s',time()),
                'task_status'       => 0,
                'task_title'        => 'RENT INCREASES',
                'task_content'      => "RENT INCREASES
Property: $property_address
Tenant name: $tenement_full_name
You have an option to increase the rent if you like to reflect the market rent changes. For residential tenancy, If you would like to increase the rent you must give the tenant at least 60 days’ written notice of a rent increase.",
                'contract_id'       => $value['id'],
                'task_role'         => 1,
                'created_at'        => date('Y-m-d H:i:s',time()),
            ];
            $task_res = Task::insert($task_data);
        }
    }

    /**
     * 押金催款 任务
     */
    public function bondCheck()
    {
        $out_time_house_id = RentArrears::where('created_at','<=',date('Y-m-d H:i:s','-7 day'))->where('arrears_type',1)
            ->where('is_pay','!=',2)->select('contract_id','user_id','tenement_id','need_pay_fee','created_at')->get();
        foreach ($out_time_house_id as $k => $value){
            $contract_id = $value['contract_id'];
            $rent_house_id = RentContract::where('id',$contract_id)->pluck('house_id')->first();
            $room_name = RentHouse::where('id',$rent_house_id)->pluck('room_name')->first();
            $property_address = RentHouse::where('id',$rent_house_id)->pluck('address')->first();
            $tenement_name = Tenement::where('id',$value['tenement_id'])->pluck('first_name')->first();
            $need_pay_fee = $value['need_pay_fee'];
            $dates = ceil((time()-strtotime($value['created_at']))/(3600*24));
            $task_data = [
                'user_id'           => $value['user_id'],
                'task_type'         => 14,
                'task_start_time'   => date('Y-m-d H:i:s',time()),
                'task_status'       => 0,
                'task_title'        => 'BOND ARREARS',
                'task_content'      => "BOND ARREARS
Property: $room_name $property_address
Tenant name: $tenement_name
Bond due : $need_pay_fee
Due date: $dates
The tenant did not pay the bond or not pay in full. Please take any necessary action immediately.",
                'contract_id'       => $value['contract_id'],
                'task_role'         => 1,
                'created_at'        => date('Y-m-d H:i:s',time()),
            ];
            $task_res = Task::insert($task_data);
        }

    }


    /**
 * 押金上缴 任务
 */
    public function bondLodged()
    {
        $out_time_house_id = RentArrears::where('updated_at','<=',date('Y-m-d H:i:s'))->where('arrears_type',1)
            ->where('is_pay',2)->select('contract_id','user_id')->get();
        foreach ($out_time_house_id as $k => $value){
            $contact_id = $value['contract_id'];
            $rent_house_id = RentContract::where('id',$value['contract_id'])->pluck('house_id')->first();
            $property_name = RentHouse::where('id',$rent_house_id)->pluck('property_name')->first();
            $room_name = RentHouse::where('id',$rent_house_id)->pluck('room_name')->first();
            $property_address = RentHouse::where('id',$rent_house_id)->pluck('address')->first();
            $tenement_full_name = ContractTenement::where('contract_id',$contact_id)->pluck('tenement_full_name')->first();

            $task_data = [
                'user_id'           => $value['user_id'],
                'task_type'         => 16,
                'task_start_time'   => date('Y-m-d H:i:s',time()),
                'task_status'       => 0,
                'task_title'        => 'BOND LODGEMENT',
                'task_content'      => "BOND LODGEMENT
Property: $room_name $property_address
Tenant name: $tenement_full_name
You received a bond from above tenancy. You are required to lodge a bond with Tenancy Services within 23 working days of receiving the bond.  Please arrange the bond to be lodged before the deadline to avoid exemplary damages through the Tenancy Tribunal..",
                'contract_id'       => $value['contract_id'],
                'task_role'         => 1,
                'created_at'        => date('Y-m-d H:i:s',time()),
            ];
            $task_res = Task::insert($task_data);
        }
    }


    /**
     *发票催收  任务
     */
    public function ticket()
    {
        $out_time_house_id = OrderArrears::where('created_at','<=',date('Y-m-d H:i:s','-7 day'))
            ->where('is_pay','!=',2)->select('order_id','user_id')->get();
        foreach ($out_time_house_id as $k => $value){
            $task_data = [
                'user_id'           => $value['user_id'],
                'task_type'         => 15,
                'task_start_time'   => date('Y-m-d H:i:s',time()),
                'task_status'       => 0,
                'task_title'        => 'residential relet',
                'task_content'      => 'your contract need relet',
                'order_id'          => $value['order_id'],
                'task_role'         => 1,
                'created_at'        => date('Y-m-d H:i:s',time()),
            ];
            $task_res = Task::insert($task_data);
        }

    }


    /**
     * 催款 任务
     */
    public function arrearsNote()
    {
        $out_time_house_id = RentArrears::where('created_at','<=',date('Y-m-d H:i:s','-7 day'))->whereIn('arrears_type',[2,3])
            ->where('is_pay','!=',2)->select('contract_id','user_id','tenement_id','need_pay_fee','created_at')->get();
        foreach ($out_time_house_id as $k => $value){
            $contract_id = $value['contract_id'];
            $rent_house_id = RentContract::where('id',$contract_id)->pluck('house_id')->first();
            $room_name = RentHouse::where('id',$rent_house_id)->pluck('room_name')->first();
            $property_address = RentHouse::where('id',$rent_house_id)->pluck('address')->first();
            $tenement_name = Tenement::where('id',$value['tenement_id'])->pluck('first_name')->first();
            $need_pay_fee = $value['need_pay_fee'];
            $dates = ceil((time()-strtotime($value['created_at']))/(3600*24));
            $task_data = [
                'user_id'           => $value['user_id'],
                'task_type'         => 18,
                'task_start_time'   => date('Y-m-d H:i:s',time()),
                'task_status'       => 0,
                'task_title'        => 'ARREARS',
                'task_content'      => "ARREARS
Property: $room_name $property_address
Tenant name: $tenement_name
Rent due : $need_pay_fee
Due date: $dates
This tenant is in arrears. You have options to issue: a reminder, 14 days notices, ending the tenancy or submit a tenancy tribunal application.
Please take any necessary action immediately.",
                'contract_id'       => $value['contract_id'],
                'task_role'         => 1,
                'created_at'        => date('Y-m-d H:i:s',time()),
            ];
            $task_res = Task::insert($task_data);
        }

    }


    /**
     * 催款 任务
     */
    public function landlordArrearsNote()
    {
        $out_time_house_id = OrderArrears::where('created_at','<=',date('Y-m-d H:i:s','-7 day'))
            ->where('is_pay','!=',2)->select('order_id','user_id','tenement_id','need_pay_fee','created_at')->get();
        foreach ($out_time_house_id as $k => $value){
            $contract_id = $value['order_id'];
            $providers_id = LandlordOrder::where('id',$contract_id)->pluck('providers_id')->first();
            $rent_house_id = LandlordOrder::where('id',$contract_id)->pluck('rent_house_id')->first();
            $room_name = RentHouse::where('id',$rent_house_id)->pluck('room_name')->first();
            $property_address = RentHouse::where('id',$rent_house_id)->pluck('address')->first();
            $tenement_name = Tenement::where('id',$value['tenement_id'])->pluck('first_name')->first();
            $need_pay_fee = $value['need_pay_fee'];
            $providers_user_id = Providers::where('id',$providers_id)->pluck('user_id')->first();
            $dates = ceil((time()-strtotime($value['created_at']))/(3600*24));
            $task_data = [
                'user_id'           => $providers_user_id,
                'task_type'         => 24,
                'task_start_time'   => date('Y-m-d H:i:s',time()),
                'task_status'       => 0,
                'task_title'        => 'OVER DUE PAYMENT',
                'task_content'      => "OVER DUE PAYMENT
Property:$property_address
Landlord: 房东名字
The above landlord did not pay the invoice on time. Please take any necessary action immediately.",
                'order_id'       => $value['order_id'],
                'task_role'         => 1,
                'created_at'        => date('Y-m-d H:i:s',time()),
            ];
            $task_res = Task::insert($task_data);
        }

    }


    /**
     * @description:月列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function taskListMonth(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();

            $month = $input['month'];
            $month = strtotime($month);
            $sdefaultDate = date("Y-m-01", $month);
            $edefaultDate = date("Y-m-t", $month);
            $w = date('w', strtotime($sdefaultDate));
            $we = date('w',strtotime($edefaultDate));
            //获取本月开始日期，如果$w是0，则表示周日，减去 6 天
            $week_start = date('Y-m-d H:i:s', strtotime("$sdefaultDate -" . $w . ' days'));
            //本月结束日期
            $week_end = date('Y-m-d H:i:s', strtotime("$edefaultDate +" . (7-$we) . ' days')-1);
            $days = (strtotime("$edefaultDate +" . (7-$we) . ' days')-strtotime("$sdefaultDate -" . $w . ' days'))/3600/24;
            for($i=0;$i<$days;$i++){
                $data[date('Y-m-d',strtotime("$sdefaultDate -" . $w . ' days')+3600*24*$i)] =
                    Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime("$sdefaultDate -" . $w . ' days')+3600*24*$i),
                        date('Y-m-d H:i:s',strtotime("$sdefaultDate -" . $w . ' days')+3600*24*($i+1)-1)])->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->count();
            }
            return $this->success('get task list success',$data);

    }


    /**
     * @description:周列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function taskListWeek(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();

            $day = $input['day'];
            $day = strtotime($day);
            $sdefaultDate = date("Y-m-d", $day);
            $w = date('w', strtotime($sdefaultDate));
            //获取本月开始日期，如果$w是0，则表示周日，减去 6 天
            $week_start = date('Y-m-d H:i:s', strtotime("$sdefaultDate -" . $w . ' days'));
            //本月结束日期
            $week_end = date('Y-m-d H:i:s', strtotime("$sdefaultDate +7 "  . ' days')-1);
            for($i=0;$i<7;$i++){
                $data[date('Y-m-d',strtotime("$sdefaultDate -" . $w . ' days')+3600*24*$i)] =
                    Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime("$sdefaultDate -" . $w . ' days')+3600*24*$i),
                        date('Y-m-d H:i:s',strtotime("$sdefaultDate -" . $w . ' days')+3600*24*($i+1)-1)])->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->count();
            }
            return $this->success('get task list success',$data);

    }


    /**
     * @description:日详情
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function taskListDayDetail(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        $day = $input['day'];
        if($input['task_role'] == 1){
            $note_task_type = [2,4,5,7,8,9,10,12,13,17,19,20,21];
            $inspect_task_type = [3,11];
            $bond_task_type = [15,16];
            $arrears_task_type = [14,18];
            $increase_task_type = [6];
            $application_task_type = [1];
            $new_type = [25];
            $note_res =  Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->whereIn('task_type',$note_task_type)->where('task_status',0)->first();
            if($note_res){
                $data['note_res'] = 1;
            }else{
                $data['note_res'] = 2;
            }
            $inspect_res =  Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->whereIn('task_type',$inspect_task_type)->where('task_status',0)->first();
            if($inspect_res){
                $data['inspect_res'] = 1;
            }else{
                $data['inspect_res'] = 2;
            }
            $bond_res =  Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->whereIn('task_type',$bond_task_type)->where('task_status',0)->first();
            if($bond_res){
                $data['bond_res'] = 1;
            }else{
                $data['bond_res'] = 2;
            }
            $arrears_res =  Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->whereIn('task_type',$arrears_task_type)->where('task_status',0)->first();
            if($arrears_res){
                $data['arrears_res'] = 1;
            }else{
                $data['arrears_res'] = 2;
            }
            $increase_res =  Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->whereIn('task_type',$increase_task_type)->where('task_status',0)->first();
            if($increase_res){
                $data['increase_res'] = 1;
            }else{
                $data['increase_res'] = 2;
            }
            $application_res =  Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->whereIn('task_type',$application_task_type)->where('task_status',0)->first();
            if($application_res){
                $data['application_res'] = 1;
            }else{
                $data['application_res'] = 2;
            }
            $new_res =  Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->whereIn('task_type',$new_type)->where('task_status',0)->first();
            if($new_res){
                $data['new_res'] = 1;
            }else{
                $data['new_res'] = 2;
            }
        }else{
            $note_task_type = [22];
            $arrears_task_type = [24];
            $invoice_task_type = [23];
            $new_type = [25];
            $note_res =  Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->whereIn('task_type',$note_task_type)->where('task_status',0)->first();
            if($note_res){
                $data['note_res'] = 1;
            }else{
                $data['note_res'] = 2;
            }

            $arrears_res =  Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->whereIn('task_type',$arrears_task_type)->where('task_status',0)->first();
            if($arrears_res){
                $data['arrears_res'] = 1;
            }else{
                $data['arrears_res'] = 2;
            }

            $invoice_res =  Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->whereIn('task_type',$invoice_task_type)->where('task_status',0)->first();
            if($invoice_res){
                $data['invoice_res'] = 1;
            }else{
                $data['invoice_res'] = 2;
            }
            $new_res =  Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->whereIn('task_type',$new_type)->where('task_status',0)->first();
            if($new_res){
                $data['new_res'] = 1;
            }else{
                $data['new_res'] = 2;
            }
        }
        return $this->success('get task list success',$data);
    }

    /**
     * @description:日列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function taskListDay(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();

            $day = $input['day'];
            $data[0] = Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 01:59:59',strtotime($day))])->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->count();
            $data[1] = Task::whereBetween('task_start_time',[date('Y-m-d 02:00:00',strtotime($day)), date('Y-m-d 03:59:59',strtotime($day))])->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->count();
            $data[2] = Task::whereBetween('task_start_time',[date('Y-m-d 04:00:00',strtotime($day)), date('Y-m-d 05:59:59',strtotime($day))])->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->count();
            $data[3] = Task::whereBetween('task_start_time',[date('Y-m-d 06:00:00',strtotime($day)), date('Y-m-d 07:59:59',strtotime($day))])->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->count();
            $data[4] = Task::whereBetween('task_start_time',[date('Y-m-d 08:00:00',strtotime($day)), date('Y-m-d 09:59:59',strtotime($day))])->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->count();
            $data[5] = Task::whereBetween('task_start_time',[date('Y-m-d 10:00:00',strtotime($day)), date('Y-m-d 11:59:59',strtotime($day))])->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->count();
            $data[6] = Task::whereBetween('task_start_time',[date('Y-m-d 12:00:00',strtotime($day)), date('Y-m-d 13:59:59',strtotime($day))])->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->count();
            $data[7] = Task::whereBetween('task_start_time',[date('Y-m-d 14:00:00',strtotime($day)), date('Y-m-d 15:59:59',strtotime($day))])->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->count();
            $data[8] = Task::whereBetween('task_start_time',[date('Y-m-d 16:00:00',strtotime($day)), date('Y-m-d 17:59:59',strtotime($day))])->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->count();
            $data[9] = Task::whereBetween('task_start_time',[date('Y-m-d 18:00:00',strtotime($day)), date('Y-m-d 19:59:59',strtotime($day))])->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->count();
            $data[10] = Task::whereBetween('task_start_time',[date('Y-m-d 20:00:00',strtotime($day)), date('Y-m-d 21:59:59',strtotime($day))])->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->count();
            $data[11] = Task::whereBetween('task_start_time',[date('Y-m-d 22:00:00',strtotime($day)), date('Y-m-d 24:59:59',strtotime($day))])->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->count();
            return $this->success('get task list success',$data);

    }

    /**
     * @description:小时详情
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function taskListHourDetail(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if(!$user_info->user_role %2 ){
            return $this->error('2','this account is not a landlord role');
        }else{
            $day = $input['day'];
            if($input['task_role'] == 1){
                $note_task_type = [2,4,5,7,8,9,10,12,13,17,19,20,21];
                $inspect_task_type = [3,11];
                $bond_task_type = [15,16];
                $arrears_task_type = [14,18];
                $increase_task_type = [6];
                $application_task_type = [1];
                $new_type = [25];
                $note_res =  Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
                    ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->whereIn('task_type',$note_task_type)->where('task_status',0)->first();
                if($note_res){
                    $data['note_res'] = 1;
                }else{
                    $data['note_res'] = 2;
                }
                $inspect_res =  Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
                    ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->whereIn('task_type',$inspect_task_type)->where('task_status',0)->first();
                if($inspect_res){
                    $data['inspect_res'] = 1;
                }else{
                    $data['inspect_res'] = 2;
                }
                $bond_res =  Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
                    ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->whereIn('task_type',$bond_task_type)->where('task_status',0)->first();
                if($bond_res){
                    $data['bond_res'] = 1;
                }else{
                    $data['bond_res'] = 2;
                }
                $arrears_res =  Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
                    ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->whereIn('task_type',$arrears_task_type)->where('task_status',0)->first();
                if($arrears_res){
                    $data['arrears_res'] = 1;
                }else{
                    $data['arrears_res'] = 2;
                }
                $increase_res =  Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
                    ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->whereIn('task_type',$increase_task_type)->where('task_status',0)->first();
                if($increase_res){
                    $data['increase_res'] = 1;
                }else{
                    $data['increase_res'] = 2;
                }
                $application_res =  Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
                    ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->whereIn('task_type',$application_task_type)->where('task_status',0)->first();
                if($application_res){
                    $data['application_res'] = 1;
                }else{
                    $data['application_res'] = 2;
                }
                $new_res =  Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
                    ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->whereIn('task_type',$new_type)->where('task_status',0)->first();
                if($new_res){
                    $data['new_res'] = 1;
                }else{
                    $data['new_res'] = 2;
                }
            }else{
                $note_task_type = [22];
                $arrears_task_type = [24];
                $invoice_task_type = [23];
                $new_type = [25];
                $note_res =  Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
                    ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->whereIn('task_type',$note_task_type)->where('task_status',0)->first();
                if($note_res){
                    $data['note_res'] = 1;
                }else{
                    $data['note_res'] = 2;
                }

                $arrears_res =  Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
                    ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->whereIn('task_type',$arrears_task_type)->where('task_status',0)->first();
                if($arrears_res){
                    $data['arrears_res'] = 1;
                }else{
                    $data['arrears_res'] = 2;
                }

                $invoice_res =  Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
                    ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->whereIn('task_type',$invoice_task_type)->where('task_status',0)->first();
                if($invoice_res){
                    $data['invoice_res'] = 1;
                }else{
                    $data['invoice_res'] = 2;
                }
                $new_res =  Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
                    ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->whereIn('task_type',$new_type)->where('task_status',0)->first();
                if($new_res){
                    $data['new_res'] = 1;
                }else{
                    $data['new_res'] = 2;
                }
            }
            return $this->success('get task list success',$data);
        }
    }


    /**
     * @description:新任务
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function newTask(array $input)
    {
        //dd($input);
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();

        $task_data = [
            'user_id'           => $input['user_id'],
            'task_type'         => 25,
            'task_start_time'   => $input['task_start_time'],
            'task_end_time'     => $input['task_end_time'],
            'task_status'       => 0,
            'task_title'        => $input['task_name'],
            'task_content'      => $input['task_details'],
            'task_role'         => $input['task_role'],
            'created_at'        => date('Y-m-d H:i:s',time()),
        ];
        $task_res = Task::insert($task_data);
        return $this->success('add task success');
    }


    /**
     * @description:房东通知列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function noteTaskDayDetail(array $input)
    {
        //dd($input);
        $day = $input['day'];
        $note_task_type = [2,4,5,7,8,9,10,12,13,17,19,20,21];
        $count =  Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
            ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$note_task_type)->count();
        if($count < ($input['page']-1)*3){
            return $this->error('2','no more information');
        }else{
            $res = Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$note_task_type)->offset(($input['page']-1)*3)->limit(3)->get();
            $data['res'] = $res;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/3);
            return $this->success('get task success',$data);
        }

    }

    /**
     * @description:房东检查列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function inspectTaskDayDetail(array $input)
    {
        //dd($input);
        $day = $input['day'];
        $inspect_task_type = [3,11];
        $count =  Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
            ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$inspect_task_type)->count();
        if($count < ($input['page']-1)*3){
            return $this->error('2','no more information');
        }else{
            $res = Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$inspect_task_type)->offset(($input['page']-1)*3)->limit(3)->get();
            $data['res'] = $res;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/3);
            return $this->success('get task success',$data);
        }

    }

    /**
     * @description:房东押金列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function bondTaskDayDetail(array $input)
    {
        //dd($input);
        $day = $input['day'];
        $bond_task_type = [15,16];
        $count =  Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
            ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$bond_task_type)->count();
        if($count < ($input['page']-1)*3){
            return $this->error('2','no more information');
        }else{
            $res = Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$bond_task_type)->offset(($input['page']-1)*3)->limit(3)->get();
            $data['res'] = $res;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/3);
            return $this->success('get task success',$data);
        }

    }

    /**
     * @description:房东租金列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function arrearsTaskDayDetail(array $input)
    {
        //dd($input);
        $day = $input['day'];
        $arrears_task_type = [14,18];
        $count =  Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
            ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$arrears_task_type)->count();
        if($count < ($input['page']-1)*3){
            return $this->error('2','no more information');
        }else{
            $res = Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$arrears_task_type)->offset(($input['page']-1)*3)->limit(3)->get();
            $data['res'] = $res;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/3);
            return $this->success('get task success',$data);
        }

    }

    /**
     * @description:房东租金列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function increaseTaskDayDetail(array $input)
    {
        //dd($input);
        $day = $input['day'];
        $increase_task_type = [6];
        $count =  Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
            ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$increase_task_type)->count();
        if($count < ($input['page']-1)*3){
            return $this->error('2','no more information');
        }else{
            $res = Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$increase_task_type)->offset(($input['page']-1)*3)->limit(3)->get();
            $data['res'] = $res;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/3);
            return $this->success('get task success',$data);
        }

    }


    /**
     * @description:房东申请列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function applicationTaskDayDetail(array $input)
    {
        //dd($input);
        $day = $input['day'];
        $application_task_type = [1];
        $count =  Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
            ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$application_task_type)->count();
        if($count < ($input['page']-1)*3){
            return $this->error('2','no more information');
        }else{
            $res = Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$application_task_type)->offset(($input['page']-1)*3)->limit(3)->get();
            $data['res'] = $res;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/3);
            return $this->success('get task success',$data);
        }

    }


    /**
     * @description:房东通知列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function noteProviderTaskDayDetail(array $input)
    {
        //dd($input);
        $day = $input['day'];
        $note_task_type = [22];
        $count =  Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
            ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$note_task_type)->count();
        if($count < ($input['page']-1)*3){
            return $this->error('2','no more information');
        }else{
            $res = Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$note_task_type)->offset(($input['page']-1)*3)->limit(3)->get();
            $data['res'] = $res;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/3);
            return $this->success('get task success',$data);
        }

    }

    /**
     * @description:房东检查列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function arrearsProvidersTaskDayDetail(array $input)
    {
        //dd($input);
        $day = $input['day'];
        $arrears_task_type = [24];
        $count =  Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
            ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$arrears_task_type)->count();
        if($count < ($input['page']-1)*3){
            return $this->error('2','no more information');
        }else{
            $res = Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$arrears_task_type)->offset(($input['page']-1)*3)->limit(3)->get();
            $data['res'] = $res;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/3);
            return $this->success('get task success',$data);
        }

    }

    /**
     * @description:房东押金列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function invoiceProvidersTaskDayDetail(array $input)
    {
        //dd($input);
        $day = $input['day'];
        $invoice_task_type = [23];
        $count =  Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
            ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$invoice_task_type)->count();
        if($count < ($input['page']-1)*3){
            return $this->error('2','no more information');
        }else{
            $res = Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$invoice_task_type)->offset(($input['page']-1)*3)->limit(3)->get();
            $data['res'] = $res;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/3);
            return $this->success('get task success',$data);
        }

    }

    /**
     * @description:房东押金列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function newTaskDayDetail(array $input)
    {
        //dd($input);
        $day = $input['day'];
        $invoice_task_type = [25];
        $count =  Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
            ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$invoice_task_type)->count();
        if($count < ($input['page']-1)*3){
            return $this->error('2','no more information');
        }else{
            $res = Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$invoice_task_type)->offset(($input['page']-1)*3)->limit(3)->get();
            $data['res'] = $res;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/3);
            return $this->success('get task success',$data);
        }

    }

    public function testTask(){
        Log::info(1112);
    }



    /**
     * @description:房东通知列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function noteTaskHourDetail(array $input)
    {
        //dd($input);
        $day = $input['day'];
        $note_task_type = [2,4,5,7,8,9,10,12,13,17,19,20,21];
        $count =  Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
            ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$note_task_type)->count();
        if($count < ($input['page']-1)*3){
            return $this->error('2','no more information');
        }else{
            $res = Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$note_task_type)->offset(($input['page']-1)*3)->limit(3)->get();
            $data['res'] = $res;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/3);
            return $this->success('get task success',$data);
        }

    }

    /**
     * @description:房东检查列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function inspectTaskHourDetail(array $input)
    {
        //dd($input);
        $day = $input['day'];
        $inspect_task_type = [3,11];
        $count =  Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
            ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$inspect_task_type)->count();
        if($count < ($input['page']-1)*3){
            return $this->error('2','no more information');
        }else{
            $res = Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$inspect_task_type)->offset(($input['page']-1)*3)->limit(3)->get();
            $data['res'] = $res;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/3);
            return $this->success('get task success',$data);
        }

    }

    /**
     * @description:房东押金列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function bondTaskHourDetail(array $input)
    {
        //dd($input);
        $day = $input['day'];
        $bond_task_type = [15,16];
        $count =  Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
            ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$bond_task_type)->count();
        if($count < ($input['page']-1)*3){
            return $this->error('2','no more information');
        }else{
            $res = Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$bond_task_type)->offset(($input['page']-1)*3)->limit(3)->get();
            $data['res'] = $res;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/3);
            return $this->success('get task success',$data);
        }

    }

    /**
     * @description:房东租金列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function arrearsTaskHourDetail(array $input)
    {
        //dd($input);
        $day = $input['day'];
        $arrears_task_type = [14,18];
        $count =  Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
            ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$arrears_task_type)->count();
        if($count < ($input['page']-1)*3){
            return $this->error('2','no more information');
        }else{
            $res = Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$arrears_task_type)->offset(($input['page']-1)*3)->limit(3)->get();
            $data['res'] = $res;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/3);
            return $this->success('get task success',$data);
        }

    }

    /**
     * @description:房东租金列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function increaseTaskHourDetail(array $input)
    {
        //dd($input);
        $day = $input['day'];
        $increase_task_type = [6];
        $count =  Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
            ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$increase_task_type)->count();
        if($count < ($input['page']-1)*3){
            return $this->error('2','no more information');
        }else{
            $res = Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$increase_task_type)->offset(($input['page']-1)*3)->limit(3)->get();
            $data['res'] = $res;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/3);
            return $this->success('get task success',$data);
        }

    }


    /**
     * @description:房东申请列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function applicationTaskHourDetail(array $input)
    {
        //dd($input);
        $day = $input['day'];
        $application_task_type = [1];
        $count =  Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
            ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$application_task_type)->count();
        if($count < ($input['page']-1)*3){
            return $this->error('2','no more information');
        }else{
            $res = Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$application_task_type)->offset(($input['page']-1)*3)->limit(3)->get();
            $data['res'] = $res;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/3);
            return $this->success('get task success',$data);
        }

    }


    /**
     * @description:房东通知列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function noteProviderTaskHourDetail(array $input)
    {
        //dd($input);
        $day = $input['day'];
        $note_task_type = [22];
        $count =  Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
            ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$note_task_type)->count();
        if($count < ($input['page']-1)*3){
            return $this->error('2','no more information');
        }else{
            $res = Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$note_task_type)->offset(($input['page']-1)*3)->limit(3)->get();
            $data['res'] = $res;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/3);
            return $this->success('get task success',$data);
        }

    }

    /**
     * @description:房东检查列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function arrearsProvidersTaskHourDetail(array $input)
    {
        //dd($input);
        $day = $input['day'];
        $arrears_task_type = [24];
        $count =  Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
            ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$arrears_task_type)->count();
        if($count < ($input['page']-1)*3){
            return $this->error('2','no more information');
        }else{
            $res = Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$arrears_task_type)->offset(($input['page']-1)*3)->limit(3)->get();
            $data['res'] = $res;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/3);
            return $this->success('get task success',$data);
        }

    }

    /**
     * @description:房东押金列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function invoiceProvidersTaskHourDetail(array $input)
    {
        //dd($input);
        $day = $input['day'];
        $invoice_task_type = [23];
        $count =  Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
            ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$invoice_task_type)->count();
        if($count < ($input['page']-1)*3){
            return $this->error('2','no more information');
        }else{
            $res = Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$invoice_task_type)->offset(($input['page']-1)*3)->limit(3)->get();
            $data['res'] = $res;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/3);
            return $this->success('get task success',$data);
        }

    }


    /**
     * @description:房东押金列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function newTaskHourDetail(array $input)
    {
        //dd($input);
        $day = $input['day'];
        $invoice_task_type = [25];
        $count =  Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
            ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$invoice_task_type)->count();
        if($count < ($input['page']-1)*3){
            return $this->error('2','no more information');
        }else{
            $res = Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])
                ->where('user_id',$input['user_id'])->where('task_role',$input['task_role'])->where('task_status',0)->whereIn('task_type',$invoice_task_type)->offset(($input['page']-1)*3)->limit(3)->get();
            $data['res'] = $res;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/3);
            return $this->success('get task success',$data);
        }

    }


    /**
     * @description:完成任务
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function finishTask(array $input)
    {
        //dd($input);
        $task_id = $input['task_id'];
        $model = new Task();
        $res = $model->where('id',$task_id)->update(['task_status' => 1,'updated_at'=>date('Y-h-d H:i:s',time())]);
        if($res){
            return $this->success('finish task success');
        }else{
            return $this->error('2','finish task failed');
        }

    }



    /**
     * @description:修改任务时间
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function extensionTask(array $input)
    {
        //dd($input);
        $task_id = $input['task_id'];
        $task_start_time = $input['task_start_time'];
        $model = new Task();
        $res = $model->where('id',$task_id)->update(['task_start_time' => $task_start_time,'updated_at'=>date('Y-h-d H:i:s',time())]);
        if($res){
            return $this->success('extension task success');
        }else{
            return $this->error('2','extension task failed');
        }

    }



    /**
     * @description:未完成任务
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function unsolveTask(array $input)
    {
        //dd($input);
        $task_id = $input['task_id'];
        $model = new Task();
        $res = $model->where('id',$task_id)->update(['task_status' => 2,'updated_at'=>date('Y-h-d H:i:s',time())]);
        if($res){
            return $this->success('unsolve  task success');
        }else{
            return $this->error('2','unsolve task failed');
        }

    }

}