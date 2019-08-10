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
    public function bondLodged(array $input)
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

}