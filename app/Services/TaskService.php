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
use App\Model\Driver;
use App\Model\DriverTakeOver;
use App\Model\Level;
use App\Model\OrderArrears;
use App\Model\Plant;
use App\Model\PlantOperateLog;
use App\Model\RentAdjust;
use App\Model\RentArrears;
use App\Model\RentContract;
use App\Model\RentHouse;
use App\Model\RouteItems;
use App\Model\ScoreLog;
use App\Model\SignLog;
use App\Model\SysSign;
use App\Model\Task;
use App\Model\UserEvaluate;
use App\Model\UserEvaluateTag;
use App\Model\Verify;
use App\Model\VerifyLog;
use App\User;
use Carbon\Carbon;
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
                'task_type'         => 3,
                'task_start_time'   => date('Y-m-d H:i:s',time()),
                'task_status'       => 0,
                'task_title'        => 'insurance update',
                'task_content'      => 'your house need update insurance',
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
        $out_time_house_id = RentContract::where('rent_end_date',date('Y-m-d','+60 day'))->where('contract_type',1)->select('id','user_id')->get();
        foreach ($out_time_house_id as $k => $value){
            $task_data = [
                'user_id'           => $value['user_id'],
                'task_type'         => 5,
                'task_start_time'   => date('Y-m-d H:i:s',time()),
                'task_status'       => 0,
                'task_title'        => 'residential relet',
                'task_content'      => 'your contract need relet',
                'contract_id'       => $value['id'],
                'task_role'         => 1,
                'created_at'        => date('Y-m-d H:i:s',time()),
            ];
            $task_res = Task::insert($task_data);
        }


        // 待续约商业租约
        $out_time_house_id = RentContract::where('rent_end_date',date('Y-m-d','+60 day'))->where('contract_type',4)->select('id','user_id')->get();
        foreach ($out_time_house_id as $k => $value){
            $task_data = [
                'user_id'           => $value['user_id'],
                'task_type'         => 5,
                'task_start_time'   => date('Y-m-d H:i:s',time()),
                'task_status'       => 0,
                'task_title'        => 'residential relet',
                'task_content'      => 'your contract need relet',
                'contract_id'       => $value['id'],
                'task_role'         => 1,
                'created_at'        => date('Y-m-d H:i:s',time()),
            ];
            $task_res = Task::insert($task_data);
        }
        // 7天提醒
        $out_time_house_id = RentContract::where('rent_end_date',date('Y-m-d','+7 day'))->select('id','user_id')->get();
        foreach ($out_time_house_id as $k => $value){
            $task_data = [
                'user_id'           => $value['user_id'],
                'task_type'         => 5,
                'task_start_time'   => date('Y-m-d H:i:s',time()),
                'task_status'       => 0,
                'task_title'        => 'residential relet',
                'task_content'      => 'your contract need relet',
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
        $out_time_house_id = RentContract::where('rent_start_date',date('Y-m-d','-120 day'))->select('id','user_id')->get();
        foreach ($out_time_house_id as $k => $value){
            $task_data = [
                'user_id'           => $value['user_id'],
                'task_type'         => 6,
                'task_start_time'   => date('Y-m-d H:i:s',time()),
                'task_status'       => 0,
                'task_title'        => 'residential relet',
                'task_content'      => 'your contract need relet',
                'contract_id'       => $value['id'],
                'task_role'         => 1,
                'created_at'        => date('Y-m-d H:i:s',time()),
            ];
            $task_res = Task::insert($task_data);
        }

        //

        $out_time_house_id = RentAdjust::where('effective_date',date('Y-m-d','-120 day'))->select('contract_id')->get();
        foreach ($out_time_house_id as $k => $value){
            $task_data = [
                'user_id'           => RentContract::where('id',$value['id'])->pluck('user_id')->first(),
                'task_type'         => 6,
                'task_start_time'   => date('Y-m-d H:i:s',time()),
                'task_status'       => 0,
                'task_title'        => 'residential relet',
                'task_content'      => 'your contract need relet',
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
            ->where('is_pay','!=',2)->select('contract_id','user_id')->get();
        foreach ($out_time_house_id as $k => $value){
            $task_data = [
                'user_id'           => $value['user_id'],
                'task_type'         => 9,
                'task_start_time'   => date('Y-m-d H:i:s',time()),
                'task_status'       => 0,
                'task_title'        => 'residential relet',
                'task_content'      => 'your contract need relet',
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
            $task_data = [
                'user_id'           => $value['user_id'],
                'task_type'         => 11,
                'task_start_time'   => date('Y-m-d H:i:s',time()),
                'task_status'       => 0,
                'task_title'        => 'residential relet',
                'task_content'      => 'your contract need relet',
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
        if(!$user_info->user_role %2 ){
            return $this->error('2','this account is not a landlord role');
        }else{
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
                        date('Y-m-d H:i:s',strtotime("$sdefaultDate -" . $w . ' days')+3600*24*($i+1)-1)])->where('user_id',$input['user_id'])->count();
            }
            return $this->success('get task list success',$data);
        }
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
        if(!$user_info->user_role %2 ){
            return $this->error('2','this account is not a landlord role');
        }else{
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
                        date('Y-m-d H:i:s',strtotime("$sdefaultDate -" . $w . ' days')+3600*24*($i+1)-1)])->where('user_id',$input['user_id'])->count();
            }
            return $this->success('get task list success',$data);
        }
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
        if(!$user_info->user_role %2 ){
            return $this->error('2','this account is not a landlord role');
        }else{
            $day = $input['day'];
            $data = Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 23:59:59',strtotime($day))])->where('user_id',$input['user_id'])->get();
            return $this->success('get task list success',$data);
        }
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
        if(!$user_info->user_role %2 ){
            return $this->error('2','this account is not a landlord role');
        }else{
            $day = $input['day'];
            $data[0] = Task::whereBetween('task_start_time',[date('Y-m-d 00:00:00',strtotime($day)), date('Y-m-d 01:59:59',strtotime($day))])->where('user_id',$input['user_id'])->count();
            $data[1] = Task::whereBetween('task_start_time',[date('Y-m-d 02:00:00',strtotime($day)), date('Y-m-d 03:59:59',strtotime($day))])->where('user_id',$input['user_id'])->count();
            $data[2] = Task::whereBetween('task_start_time',[date('Y-m-d 04:00:00',strtotime($day)), date('Y-m-d 05:59:59',strtotime($day))])->where('user_id',$input['user_id'])->count();
            $data[3] = Task::whereBetween('task_start_time',[date('Y-m-d 06:00:00',strtotime($day)), date('Y-m-d 07:59:59',strtotime($day))])->where('user_id',$input['user_id'])->count();
            $data[4] = Task::whereBetween('task_start_time',[date('Y-m-d 08:00:00',strtotime($day)), date('Y-m-d 09:59:59',strtotime($day))])->where('user_id',$input['user_id'])->count();
            $data[5] = Task::whereBetween('task_start_time',[date('Y-m-d 10:00:00',strtotime($day)), date('Y-m-d 11:59:59',strtotime($day))])->where('user_id',$input['user_id'])->count();
            $data[6] = Task::whereBetween('task_start_time',[date('Y-m-d 12:00:00',strtotime($day)), date('Y-m-d 13:59:59',strtotime($day))])->where('user_id',$input['user_id'])->count();
            $data[7] = Task::whereBetween('task_start_time',[date('Y-m-d 14:00:00',strtotime($day)), date('Y-m-d 15:59:59',strtotime($day))])->where('user_id',$input['user_id'])->count();
            $data[8] = Task::whereBetween('task_start_time',[date('Y-m-d 16:00:00',strtotime($day)), date('Y-m-d 17:59:59',strtotime($day))])->where('user_id',$input['user_id'])->count();
            $data[9] = Task::whereBetween('task_start_time',[date('Y-m-d 18:00:00',strtotime($day)), date('Y-m-d 19:59:59',strtotime($day))])->where('user_id',$input['user_id'])->count();
            $data[10] = Task::whereBetween('task_start_time',[date('Y-m-d 20:00:00',strtotime($day)), date('Y-m-d 21:59:59',strtotime($day))])->where('user_id',$input['user_id'])->count();
            $data[11] = Task::whereBetween('task_start_time',[date('Y-m-d 22:00:00',strtotime($day)), date('Y-m-d 24:59:59',strtotime($day))])->where('user_id',$input['user_id'])->count();
            return $this->success('get task list success',$data);
        }
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
            $data = Task::whereBetween('task_start_time',[date('Y-m-d H:i:s',strtotime($day)), date('Y-m-d H:i:s',strtotime($day)+7200-1)])->where('user_id',$input['user_id'])->get();
            return $this->success('get task list success',$data);
        }
    }


    public function testTask(){
        Log::info(1112);
    }

}