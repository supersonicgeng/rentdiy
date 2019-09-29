<?php
/**
 * 房屋检查服务层
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1 0001
 * Time: 下午 4:25
 */

namespace App\Services;


use App\Lib\Util\QueryPager;
use App\Model\ContractTenement;
use App\Model\Inspect;
use App\Model\InspectChattel;
use App\Model\InspectCheck;
use App\Model\InspectRoom;
use App\Model\Key;
use App\Model\Landlord;
use App\Model\LandlordOrder;
use App\Model\Operator;
use App\Model\Providers;
use App\Model\Region;
use App\Model\RentContact;
use App\Model\RentHouse;
use App\Model\RentPic;
use App\Model\Task;
use App\Model\Tender;
use App\Model\UnPlatInspectChattel;
use App\Model\UnPlatInspectCheck;
use App\Model\UnPlatInspectList;
use App\Model\UnPlatInspectRoom;
use App\Model\Verify;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\In;

class InspectService extends CommonService
{
    /**
     * @description:新增检查
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function inspectAdd(array $input)
    {
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if(!$user_info->user_role %2 ){
            return $this->error('2','this account is not a landlord role');
        }else{
            $model = new Inspect();
            if($input['inspect_category'] == 1) { // 整租检查
                $group_id = LandlordOrder::max('group_id'); // 获得目前存入的最大group_id
                $inspect_data = [
                    'rent_house_id' => $input['rent_house_id'],
                    'contract_id' => $input['contract_id'],
                    'inspect_name' => $input['inspect_name'],
                    'inspect_method' => $input['inspect_method'],
                    'inspect_category' => $input['inspect_category'],
                    'inspect_start_date' => $input['inspect_start_date'],
                    'inspect_end_date' => $input['inspect_end_date'],
                    'inspect_note' => $input['inspect_note'],
                    'chattel_note' => $input['chattel_note'],
                    'created_at' => date('Y-m-d H:i:s', time()),
                ];
                $res = $model->insertGetId($inspect_data);
                if(strtotime($input['inspect_start_date'])-time() > 3600*48 ){
                    $rent_house_id = $input['rent_house_id'];
                    $contract_id = $input['contract_id'];
                    $start_time = $input['inspect_start_date'];
                    $property_name = RentHouse::where('id',$rent_house_id)->pluck('property_name')->first();
                    $room_name = RentHouse::where('id',$rent_house_id)->pluck('room_name')->first();
                    $property_address = RentHouse::where('id',$rent_house_id)->pluck('address')->first();
                    $tenement_full_name = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_full_name')->first();
                    $task_data = [
                        'user_id'           => $input['user_id'],
                        'task_type'         => 7,
                        'task_start_time'   => date('Y-m-d H:i:s',time()+3600*48),
                        'task_status'       => 0,
                        'task_title'        => 'PROPERTY INSPECTION REMINDER',
                        'task_content'      => "PROPERTY INSPECTION REMINDER
Property: $property_address
Tenant name: $tenement_full_name
Scheduled inspection date: $start_time
An inspection has been scheduled about date, please communicate with the tenant and available on the date for the inspection.",
                        'inspect_id'        => $res,
                        'task_role'         => 1,
                        'created_at'        => date('Y-m-d H:i:s',time()),
                    ];
                    $task_res = Task::insert($task_data);
                }else{
                    $rent_house_id = $input['rent_house_id'];
                    $contract_id = $input['contract_id'];
                    $start_time = $input['inspect_start_date'];
                    $property_name = RentHouse::where('id',$rent_house_id)->pluck('property_name')->first();
                    $room_name = RentHouse::where('id',$rent_house_id)->pluck('room_name')->first();
                    $property_address = RentHouse::where('id',$rent_house_id)->pluck('address')->first();
                    $tenement_full_name = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_full_name')->first();
                    $task_data = [
                        'user_id'           => $input['user_id'],
                        'task_type'         => 7,
                        'task_start_time'   => date('Y-m-d H:i:s',time()+3600*24),
                        'task_status'       => 0,
                        'task_title'        => 'PROPERTY INSPECTION REMINDER',
                        'task_content'      => "PROPERTY INSPECTION REMINDER
Property: $property_address
Tenant name: $tenement_full_name
Scheduled inspection date: $start_time
An inspection has been scheduled about date, please communicate with the tenant and available on the date for the inspection.",
                        'inspect_id'        => $res,
                        'task_role'         => 1,
                        'created_at'        => date('Y-m-d H:i:s',time()),
                    ];
                    $task_res = Task::insert($task_data);
                }
                if ($res) {
                    static $error = 0;
                    // 财产清单
                    foreach ($input['chattel_list'] as $k => $v) {
                        $chattel_data = [
                            'inspect_id' => $res,
                            'chattel_name' => $v['chattel_name'],
                            'chattel_num' => $v['chattel_num'],
                            'created_at' => date('Y-m-d H:i:s', time()),
                        ];
                        $chattel_res = InspectChattel::insert($chattel_data);
                        if (!$chattel_res) {
                            $error += 1;
                        }
                    }
                    // 检查房间存入
                    foreach ($input['room_list'] as $k => $v) {
                        foreach ($v['items'] as $key => $value) {
                            $room_data = [
                                'inspect_id' => $res,
                                'room_name' => $v['room_name'],
                                'items' => $value,
                                'created_at' => date('Y-m-d H:i:s', time()),
                            ];
                            $room_res = InspectRoom::insert($room_data);
                            if (!$room_res) {
                                $error += 1;
                            }
                        }
                    }
                    if ($input['inspect_method'] == 2) {
                        // 发布市场
                        $order_sn = orderId();
                        $room_info = RentHouse::where('id', $input['rent_house_id'])->first();
                        $order_data = [
                            'inspect_id' => $res,
                            'user_id' => $input['user_id'],
                            'order_sn' => $order_sn,
                            'group_id'  => $group_id+1,
                            'rent_house_id' => $input['rent_house_id'],
                            'District' => $room_info->District,
                            'TA' => $room_info->TA,
                            'Region' => $room_info->Region,
                            'order_type' => 3,
                            'start_time' => $input['inspect_start_date'],
                            'end_time' => $input['inspect_end_date'],
                            'requirement' => $input['inspect_note'],
                            'budget' => $input['budget'],
                            'created_at' => date('Y-m-d H:i:s', time()),
                        ];
                        $order_res = LandlordOrder::insert($order_data);
                        if (!$order_res) {
                            $error += 1;
                        }
                    }
                    if ($res && !$error) {
                        // 用户操作节点
                        if(DB::table('user_opeart_log')->where('user_id',$input['user_id'])->first()){
                            $log_data = [
                                'opeartor_method'   => 3,
                                'updated_at'        => date('Y-m-d H:i:s',time()),
                            ];
                            DB::table('user_opeart_log')->where('user_id',$input['user_id'])->update($log_data);
                        }else{
                            $log_data = [
                                'user_id'           => $input['user_id'],
                                'opeartor_method'   => 3,
                                'created_at'        => date('Y-m-d H:i:s',time()),
                            ];
                            DB::table('user_opeart_log')->insert($log_data);
                        }
                        return $this->success('inspect add success');
                    } else {
                        return $this->error('3', 'inspect add failed');
                    }
                } else {
                    return $this->error('3', 'inspect add failed');
                }
            }elseif ($input['inspect_category'] == 2) { // 分租检查
                $group_id = LandlordOrder::max('group_id'); // 获得目前存入的最大group_id
                $inspect_data = [
                    'rent_house_id' => $input['rent_house_id'],
                    'contract_id' => @$input['contract_id'],
                    'inspect_name' => $input['inspect_name'],
                    'inspect_method' => $input['inspect_method'],
                    'inspect_category' => $input['inspect_category'],
                    'inspect_start_date' => $input['inspect_start_date'],
                    'inspect_end_date' => $input['inspect_end_date'],
                    'inspect_note' => $input['inspect_note'],
                    'chattel_note' => $input['chattel_note'],
                    'created_at' => date('Y-m-d H:i:s', time()),
                ];
                $res = $model->insertGetId($inspect_data);
                if(strtotime($input['inspect_start_date'])-time() > 3600*48 ){
                    $rent_house_id = $input['rent_house_id'];
                    $contract_id = $input['contract_id'];
                    $start_time = $input['inspect_start_date'];
                    $property_name = RentHouse::where('id',$rent_house_id)->pluck('property_name')->first();
                    $room_name = RentHouse::where('id',$rent_house_id)->pluck('room_name')->first();
                    $property_address = RentHouse::where('id',$rent_house_id)->pluck('address')->first();
                    $tenement_full_name = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_full_name')->first();
                    $task_data = [
                        'user_id'           => $input['user_id'],
                        'task_type'         => 7,
                        'task_start_time'   => date('Y-m-d H:i:s',time()+3600*48),
                        'task_status'       => 0,
                        'task_title'        => 'PROPERTY INSPECTION REMINDER',
                        'task_content'      => "PROPERTY INSPECTION REMINDER
Property: $property_address
Tenant name: $tenement_full_name
Scheduled inspection date: $start_time
An inspection has been scheduled about date, please communicate with the tenant and available on the date for the inspection.",
                        'inspect_id'        => $res,
                        'task_role'         => 1,
                        'created_at'        => date('Y-m-d H:i:s',time()),
                    ];
                    $task_res = Task::insert($task_data);
                }else{
                    $rent_house_id = $input['rent_house_id'];
                    $contract_id = $input['contract_id'];
                    $start_time = $input['inspect_start_date'];
                    $property_name = RentHouse::where('id',$rent_house_id)->pluck('property_name')->first();
                    $room_name = RentHouse::where('id',$rent_house_id)->pluck('room_name')->first();
                    $property_address = RentHouse::where('id',$rent_house_id)->pluck('address')->first();
                    $tenement_full_name = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_full_name')->first();
                    $task_data = [
                        'user_id'           => $input['user_id'],
                        'task_type'         => 7,
                        'task_start_time'   => date('Y-m-d H:i:s',time()+3600*24),
                        'task_status'       => 0,
                        'task_title'        => 'PROPERTY INSPECTION REMINDER',
                        'task_content'      => "PROPERTY INSPECTION REMINDER
Property: $property_address
Tenant name: $tenement_full_name
Scheduled inspection date: $start_time
An inspection has been scheduled about date, please communicate with the tenant and available on the date for the inspection.",
                        'inspect_id'        => $res,
                        'task_role'         => 1,
                        'created_at'        => date('Y-m-d H:i:s',time()),
                    ];
                    $task_res = Task::insert($task_data);

                }
                if ($res) {
                    static $error = 0;
                    // 财产清单
                    foreach ($input['chattel_list'] as $k => $v) {
                        $chattel_data = [
                            'inspect_id' => $res,
                            'chattel_name' => $v['chattel_name'],
                            'chattel_num' => $v['chattel_num'],
                            'created_at' => date('Y-m-d H:i:s', time()),
                        ];
                        $chattel_res = InspectChattel::insert($chattel_data);
                        if (!$chattel_res) {
                            $error += 1;
                        }
                    }
                    // 检查房间存入
                    foreach ($input['room_list'] as $k => $v) {
                        foreach ($v['items'] as $key => $value) {
                            $room_data = [
                                'inspect_id' => $res,
                                'room_name' => RentHouse::where('id',$input['rent_house_id'])->pluck('room_name')->first(),
                                'items' => $value,
                                'created_at' => date('Y-m-d H:i:s', time()),
                            ];
                            $room_res = InspectRoom::insert($room_data);
                            if (!$room_res) {
                                $error += 1;
                            }
                        }
                    }
                    if ($input['inspect_method'] == 2) {
                        // 发布市场
                        $order_sn = orderId();
                        $room_info = RentHouse::where('id', $input['rent_house_id'])->first();
                        $order_data = [
                            'inspect_id' => $res,
                            'user_id' => $input['user_id'],
                            'group_id'  => $group_id+1,
                            'order_sn' => $order_sn,
                            'rent_house_id' => $input['rent_house_id'],
                            'District' => $room_info->District,
                            'TA' => $room_info->TA,
                            'Region' => $room_info->Region,
                            'order_type' => 3,
                            'start_time' => $input['inspect_start_date'],
                            'end_time' => $input['inspect_end_date'],
                            'requirement' => $input['inspect_note'],
                            'budget' => $input['budget'],
                            'created_at' => date('Y-m-d H:i:s', time()),
                        ];
                        $order_res = LandlordOrder::insert($order_data);
                        if (!$order_res) {
                            $error += 1;
                        }
                    }
                    if ($res && !$error) {
                        // 用户操作节点
                        if(DB::table('user_opeart_log')->where('user_id',$input['user_id'])->first()){
                            $log_data = [
                                'opeartor_method'   => 3,
                                'updated_at'        => date('Y-m-d H:i:s',time()),
                            ];
                            DB::table('user_opeart_log')->where('user_id',$input['user_id'])->update($log_data);
                        }else{
                            $log_data = [
                                'user_id'           => $input['user_id'],
                                'opeartor_method'   => 3,
                                'created_at'        => date('Y-m-d H:i:s',time()),
                            ];
                            DB::table('user_opeart_log')->insert($log_data);
                        }
                        DB::table('user_opeart_log')->insert($log_data);
                        return $this->success('inspect add success');
                    } else {
                        return $this->error('3', 'inspect add failed');
                    }
                } else {
                    return $this->error('3', 'inspect add failed');
                }
            }elseif ($input['inspect_category'] == 3) { // 批量检查
                static $error = 0;
                $group_id = LandlordOrder::max('group_id'); // 获得目前存入的最大group_id
                foreach($input['room_list'] as $k => $v){
                    $inspect_data = [
                        'rent_house_id'         => $v['rent_house_id'],
                        'contract_id'           => @$input['contract_id'],
                        'inspect_name'          => $input['inspect_name'],
                        'inspect_method'        => $input['inspect_method'],
                        'inspect_category'      => $input['inspect_category'],
                        'inspect_start_date'    => $input['inspect_start_date'],
                        'inspect_end_date'      => $input['inspect_end_date'],
                        'inspect_note'          => $input['inspect_note'],
                        'chattel_note'          => $input['chattel_note'],
                        'created_at'            => date('Y-m-d H:i:s', time()),
                    ];
                    $room_res = $model->insertGetId($inspect_data);
                    if(strtotime($input['inspect_start_date'])-time() > 3600*48 ){
                        $rent_house_id = $input['rent_house_id'];
                        $contract_id = $input['contract_id'];
                        $start_time = $input['inspect_start_date'];
                        $property_name = RentHouse::where('id',$rent_house_id)->pluck('property_name')->first();
                        $room_name = RentHouse::where('id',$rent_house_id)->pluck('room_name')->first();
                        $property_address = RentHouse::where('id',$rent_house_id)->pluck('address')->first();
                        $tenement_full_name = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_full_name')->first();
                        $task_data = [
                            'user_id'           => $input['user_id'],
                            'task_type'         => 7,
                            'task_start_time'   => date('Y-m-d H:i:s',time()+3600*48),
                            'task_status'       => 0,
                            'task_title'        => 'PROPERTY INSPECTION REMINDER',
                            'task_content'      => "PROPERTY INSPECTION REMINDER
Property: $property_address
Tenant name: $tenement_full_name
Scheduled inspection date: $start_time
An inspection has been scheduled about date, please communicate with the tenant and available on the date for the inspection.",
                            'inspect_id'        => $room_res,
                            'task_role'         => 1,
                            'created_at'        => date('Y-m-d H:i:s',time()),
                        ];
                        $task_res = Task::insert($task_data);
                    }else{
                        $rent_house_id = $input['rent_house_id'];
                        $contract_id = $input['contract_id'];
                        $start_time = $input['inspect_start_date'];
                        $property_name = RentHouse::where('id',$rent_house_id)->pluck('property_name')->first();
                        $room_name = RentHouse::where('id',$rent_house_id)->pluck('room_name')->first();
                        $property_address = RentHouse::where('id',$rent_house_id)->pluck('address')->first();
                        $tenement_full_name = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_full_name')->first();
                        $task_data = [
                            'user_id'           => $input['user_id'],
                            'task_type'         => 7,
                            'task_start_time'   => date('Y-m-d H:i:s',time()+3600*24),
                            'task_status'       => 0,
                            'task_title'        => 'PROPERTY INSPECTION REMINDER',
                            'task_content'      => "PROPERTY INSPECTION REMINDER
Property: $property_address
Tenant name: $tenement_full_name
Scheduled inspection date: $start_time
An inspection has been scheduled about date, please communicate with the tenant and available on the date for the inspection.",
                            'inspect_id'        => $room_res,
                            'task_role'         => 1,
                            'created_at'        => date('Y-m-d H:i:s',time()),
                        ];
                        $task_res = Task::insert($task_data);
                    }
                    $inspect_id[] = $room_res;
                    if(!$room_res){
                        $error += 1;
                    }else {
                        // 财产清单
                        foreach ($input['chattel_list'] as $key => $value) {
                            $chattel_data = [
                                'inspect_id' => $room_res,
                                'chattel_name' => $value['chattel_name'],
                                'chattel_num' => $value['chattel_num'],
                                'created_at' => date('Y-m-d H:i:s', time()),
                            ];
                            $chattel_res = InspectChattel::insert($chattel_data);
                            if (!$chattel_res) {
                                $error += 1;
                            }
                        }
                        // 房屋
                        foreach ($v['items'] as $key => $value) {
                            $room_data = [
                                'inspect_id' => $room_res,
                                'rent_house_id' => $v['rent_house_id'],
                                'room_name' => RentHouse::where('id',$v['rent_house_id'])->pluck('room_name')->first(),
                                'items' => $value,
                                'created_at' => date('Y-m-d H:i:s', time()),
                            ];
                            $res = InspectRoom::insert($room_data);
                            if (!$res) {
                                $error += 1;
                            }
                        }
                        if ($input['inspect_method'] == 2) {
                            // 发布市场
                            $order_sn = orderId();
                            $room_info = RentHouse::where('id', $v['rent_house_id'])->first();
                            $order_data = [
                                'inspect_id' => $room_res,
                                'user_id' => $input['user_id'],
                                'order_sn' => $order_sn,
                                'group_id' => $group_id+1,
                                'rent_house_id' => $v['rent_house_id'],
                                'District' => $room_info->District,
                                'TA' => $room_info->TA,
                                'Region' => $room_info->Region,
                                'order_type' => 3,
                                'start_time' => $input['inspect_start_date'],
                                'end_time' => $input['inspect_end_date'],
                                'requirement' => $input['inspect_note'],
                                'budget' => $input['budget'],
                                'created_at' => date('Y-m-d H:i:s', time()),
                            ];
                            $order_res = LandlordOrder::insert($order_data);
                            if (!$order_res) {
                                $error += 1;
                            }
                        }
                    }
                    }
                if(!$error){
                    // 用户操作节点
                    if(DB::table('user_opeart_log')->where('user_id',$input['user_id'])->first()){
                        $log_data = [
                            'opeartor_method'   => 3,
                            'updated_at'        => date('Y-m-d H:i:s',time()),
                        ];
                        DB::table('user_opeart_log')->where('user_id',$input['user_id'])->update($log_data);
                    }else{
                        $log_data = [
                            'user_id'           => $input['user_id'],
                            'opeartor_method'   => 3,
                            'created_at'        => date('Y-m-d H:i:s',time()),
                        ];
                        DB::table('user_opeart_log')->insert($log_data);
                    }
                    DB::table('user_opeart_log')->insert($log_data);
                    return $this->success('inspect add success');
                }else{
                    return $this->error('3', 'inspect add failed');
                }
            }
        }
    }




    /**
 * @description:检查列表
 * @author: syg <13971394623@163.com>
 * @param $code
 * @param $message
 * @param array|null $data
 * @return \Illuminate\Http\JsonResponse
 */
    public function inspectList(array $input)
    {
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if(!$user_info->user_role %2 ){
            return $this->error('2','this account is not a landlord role');
        }else{
            $model = new Inspect();
            $page = $input['page'];
            $count = $model->where('rent_house_id',$input['rent_house_id'])->count();
            if($count < ($page-1)*3){
                return $this->error('3','no more inspect info');
            }
            $res = $model->where('rent_house_id',$input['rent_house_id'])->orderByDesc('id')->offset(($page-1)*3)->limit(3)->get()->toArray();
            if($res){
                $data['inspect_list'] = $res;
                $data['house_info'] = RentHouse::where('id',$input['rent_house_id'])->select('District','TA','Region','bedroom_no','bathroom_no','parking_no','garage_no','require_renter','address')->first();
                $data['house_info']['full_address'] = $data['house_info']['address'].','.Region::getName($data['house_info']['District']).','.Region::getName($data['house_info']['TA']).','.Region::getName($data['house_info']['Region']);
                $data['total_page'] = ceil($count/3);
                $data['current_page'] = $page;
                return $this->success('get inspect list success',$data);
            }else{
                return $this->error('2','get inspect list fail');
            }
        }
    }




    /**
     * @description:检查列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function inspectDetail(array $input)
    {
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if(!$user_info->user_role %2 ){
            return $this->error('2','this account is not a landlord role');
        }else{
            $model = new Inspect();
            $inspect_id = $input['inspect_id'];
            $res = $model->where('id',$inspect_id)->first()->toArray();
            if(!$res){
                return $this->error('2','get inspect detail failed');
            }else{
                $chattel_info = InspectChattel::where('inspect_id',$inspect_id)->select('chattel_name','chattel_num')->get()->toArray();
                if($res['inspect_category'] == 1){
                    $room_name = InspectRoom::where('inspect_id',$input['inspect_id'])->groupBy('room_name')->get()->toArray();
                    foreach($room_name as $k => $v){
                        $item_info[$k]['room_name'] =  $v['room_name'];
                        $item_info[$k]['items'] =  InspectRoom::where('inspect_id',$input['inspect_id'])->where('room_name',$v['room_name'])->pluck('items');
                    }
                }else{
                    $item_info[0]['room_name'] =  '';
                    $item_info[0]['items'] =  InspectRoom::where('inspect_id',$input['inspect_id'])->pluck('items');
                }
                $data['inspect_info'] = $res;
                $data['chattel_info'] = $chattel_info;
                $data['item_info']  = $item_info;
                return $this->success('get inspect detail success',$data);
            }
        }
    }


    /**
     * @description:检查项目
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function inspectItem(array $input)
    {
        $model = new Inspect();
        $inspect_res = $model->where('id',$input['inspect_id'])->first();
        $chattel_info = InspectChattel::where('inspect_id',$input['inspect_id'])->get()->toArray();
        if($inspect_res->inspect_category == 1){
            $room_name = InspectRoom::where('inspect_id',$input['inspect_id'])->groupBy('room_name')->get()->toArray();
            if(!$room_name){
                return $this->error('2','no inspect info');
            }
            foreach($room_name as $k => $v){
                $data['data'][][$v['room_name']] =  InspectRoom::where('inspect_id',$input['inspect_id'])->where('room_name',$v['room_name'])->get()->toArray();
            }
            $data['chattel'] = $chattel_info;
            return $this->success('get inspect item success',$data);
        }elseif ($inspect_res->inspect_category == 2){
            $data['data'][]['room_name'] = InspectRoom::where('inspect_id',$input['inspect_id'])->get()->toArray();
            if(!$data){
                return $this->error('2','no inspect info');
            }
            $data['chattel'] = $chattel_info;
            return $this->success('get inspect item success',$data);
        }else {
            $room_name = InspectRoom::where('inspect_id', $input['inspect_id'])->groupBy('room_name')->get()->toArray();
            if(!$room_name){
                return $this->error('2','no inspect info');
            }
            foreach ($room_name as $k => $v) {
                $data['data'][][$v['room_name']] = InspectRoom::where('inspect_id', $input['inspect_id'])->where('room_name', $v['room_name'])->get()->toArray();
            }
            $data['chattel'] = $chattel_info;
            return $this->success('get inspect item success', $data);

        }
    }


    /**
     * @description:批量检查 房屋列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function inspectGroupRoom(array $input)
    {
        $model = new RentHouse();
        $group_id = $model->where('id',$input['rent_house_id'])->pluck('group_id')->first();
        $room_name = $model->where('group_id',$group_id)->select('id','room_name')->get()->toArray();
        if($room_name){
            $data['room_name'] = $room_name;
            return $this->success('get room name success',$data);
        }else{
            return $this->error('2','get room name failed');
        }
    }



    /**
     * @description:房东开始检查
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function inspectCheck(array $input)
    {
        // 修改检查表
        static $error = 0;
        $model = new InspectRoom();
        foreach ($input['items_list'] as $key => $value){
            if(isset($value['id'])){
                $room_data = [
                    'accept'        => $value['accept'],
                    'photo1'        => $value['photo1'],
                    'photo2'        => $value['photo2'],
                    'photo3'        => $value['photo3'],
                    'photo4'        => $value['photo4'],
                    'inspect_note'  => $value['inspect_note'],
                    'video_url'     => $value['video_url'],
                    'updated_at'    => date('Y-m-d H:i:s',time()),
                ];
                $res = $model->where('id',$value['id'])->update($room_data);
            }else{
                $room_data = [
                    'inspect_id'    => $input['inspect_id'],
                    'room_name'     => $value['room_name'],
                    'items'         => $value['items'],
                    'accept'        => $value['accept'],
                    'photo1'        => $value['photo1'],
                    'photo2'        => $value['photo2'],
                    'photo3'        => $value['photo3'],
                    'photo4'        => $value['photo4'],
                    'inspect_note'  => $value['inspect_note'],
                    'video_url'     => $value['video_url'],
                    'created_at'    => date('Y-m-d H:i:s',time()),
                ];
                $res = $model->insert($room_data);
            }
            if(!$res){
                $error += 1;
            }
            // 将问题存入问题表中
            //TODO

        }

        foreach ($input['chattel_list'] as $key => $value){
            if(isset($value['id'])){
                $room_data = [
                    'chattel_num'   => $value['chattel_num'],
                    'accept'        => $value['accept'],
                    'photo1'        => $value['photo1'],
                    'photo2'        => $value['photo2'],
                    'photo3'        => $value['photo3'],
                    'photo4'        => $value['photo4'],
                    'inspect_note'  => $value['inspect_note'],
                    'updated_at'    => date('Y-m-d H:i:s',time()),
                ];
                $res = InspectChattel::where('id',$value['id'])->update($room_data);
            }else{
                $room_data = [
                    'inspect_id'    => $input['inspect_id'],
                    'chattel_name'  => $value['chattel_name'],
                    'chattel_num'   => $value['chattel_num'],
                    'accept'        => $value['accept'],
                    'photo1'        => $value['photo1'],
                    'photo2'        => $value['photo2'],
                    'photo3'        => $value['photo3'],
                    'photo4'        => $value['photo4'],
                    'inspect_note'  => $value['inspect_note'],
                    'created_at'    => date('Y-m-d H:i:s',time()),
                ];
                $res = InspectChattel::insert($room_data);
            }
            if(!$res){
                $error += 1;
            }
            // 将问题存入问题表中
            //TODO

        }
       if($error){
           return $this->error('2','check failed');
       }else{
           return $this->success('check success');
       }

    }


    /**
     * @description:检查编辑
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function inspectEdit(array $input)
    {
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if(!$user_info->user_role %2 ){
            return $this->error('2','this account is not a landlord role');
        }else{
            $model = new Inspect();
            $order_id = LandlordOrder::where('inspect_id',$input['inspect_id'])->pluck('id')->first();
            $tender_res = Tender::where('order_id',$order_id)->first();
            if($tender_res){
                return $this->error('4','you check order already have a  tender can not edit this inspect ');
            }
            if($input['inspect_category'] == 1) { // 整租检查
                $group_id = LandlordOrder::max('group_id'); // 获得目前存入的最大group_id
                $inspect_data = [
                    'rent_house_id' => $input['rent_house_id'],
                    'contract_id' => $input['contract_id'],
                    'inspect_name' => $input['inspect_name'],
                    'inspect_method' => $input['inspect_method'],
                    'inspect_category' => $input['inspect_category'],
                    'inspect_start_date' => $input['inspect_start_date'],
                    'inspect_end_date' => $input['inspect_end_date'],
                    'inspect_note' => $input['inspect_note'],
                    'chattel_note' => $input['chattel_note'],
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ];
                $res = $model->where('id',$input['inspect_id'])->update($inspect_data);
                if ($res) {
                    static $error = 0;
                    // 清除所有的检查清单 和财产清单
                    InspectChattel::where('inspect_id',$input['inspect_id'])->delete();
                    InspectRoom::where('inspect_id',$input['inspect_id'])->delete();
                    LandlordOrder::where('inspect_id',$input['inspect_id'])->delete();
                    // 财产清单
                    foreach ($input['chattel_list'] as $k => $v) {
                        $chattel_data = [
                            'inspect_id' => $input['inspect_id'],
                            'chattel_name' => $v['chattel_name'],
                            'chattel_num' => $v['chattel_num'],
                            'created_at' => date('Y-m-d H:i:s', time()),
                        ];
                        $chattel_res = InspectChattel::insert($chattel_data);
                        if (!$chattel_res) {
                            $error += 1;
                        }
                    }
                    // 检查房间存入
                    foreach ($input['room_list'] as $k => $v) {
                        foreach ($v['items'] as $key => $value) {
                            $room_data = [
                                'inspect_id' => $input['inspect_id'],
                                'room_name' => $v['room_name'],
                                'items' => $value,
                                'created_at' => date('Y-m-d H:i:s', time()),
                            ];
                            $room_res = InspectRoom::insert($room_data);
                            if (!$room_res) {
                                $error += 1;
                            }
                        }
                    }
                    if ($input['inspect_method'] == 2) {
                        // 发布市场
                        $order_sn = orderId();
                        $room_info = RentHouse::where('id', $input['rent_house_id'])->first();
                        $order_data = [
                            'inspect_id' => $input['inspect_id'],
                            'user_id' => $input['user_id'],
                            'order_sn' => $order_sn,
                            'group_id'  => $group_id+1,
                            'rent_house_id' => $input['rent_house_id'],
                            'District' => $room_info->District,
                            'TA' => $room_info->TA,
                            'Region' => $room_info->Region,
                            'order_type' => 3,
                            'start_time' => $input['inspect_start_date'],
                            'end_time' => $input['inspect_end_date'],
                            'requirement' => $input['inspect_note'],
                            'budget' => $input['budget'],
                            'created_at' => date('Y-m-d H:i:s', time()),
                        ];
                        $order_res = LandlordOrder::insert($order_data);
                        if (!$order_res) {
                            $error += 1;
                        }
                    }
                    if ($res && !$error) {
                        // 更改检查状态为2 更新检查人id 检查操作员id
                        //Inspect::where('id',$input['inspect_id'])->update(['inspect_status'=>2,'check_user_id'=>@$input['check_user_id'],'check_operator_id'=>@$input['check_operator_id']]);
                        return $this->success('inspect add success');
                    } else {
                        return $this->error('3', 'inspect add failed');
                    }
                } else {
                    return $this->error('3', 'inspect add failed');
                }
            }else { // 分租检查
                $group_id = LandlordOrder::max('group_id'); // 获得目前存入的最大group_id
                $inspect_data = [
                    'rent_house_id' => $input['rent_house_id'],
                    'contract_id' => @$input['contract_id'],
                    'inspect_name' => $input['inspect_name'],
                    'inspect_method' => $input['inspect_method'],
                    'inspect_category' => $input['inspect_category'],
                    'inspect_start_date' => $input['inspect_start_date'],
                    'inspect_end_date' => $input['inspect_end_date'],
                    'inspect_note' => $input['inspect_note'],
                    'chattel_note' => $input['chattel_note'],
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ];
                $res = $model->where('id',$input['inspect_id'])->update($inspect_data);
                if ($res) {
                    static $error = 0;
                    // 清除所有的检查清单 和财产清单
                    InspectChattel::where('inspect_id',$input['inspect_id'])->delete();
                    InspectRoom::where('inspect_id',$input['inspect_id'])->delete();
                    LandlordOrder::where('inspect_id',$input['inspect_id'])->delete();
                    // 财产清单
                    foreach ($input['chattel_list'] as $k => $v) {
                        $chattel_data = [
                            'inspect_id' => $input['inspect_id'],
                            'chattel_name' => $v['chattel_name'],
                            'chattel_num' => $v['chattel_num'],
                            'created_at' => date('Y-m-d H:i:s', time()),
                        ];
                        $chattel_res = InspectChattel::insert($chattel_data);
                        if (!$chattel_res) {
                            $error += 1;
                        }
                    }
                    // 检查房间存入
                    foreach ($input['room_list'] as $k => $v) {
                        foreach ($v['items'] as $key => $value) {
                            $room_data = [
                                'inspect_id' => $input['inspect_id'],
                                'room_name' => $v['room_name'],
                                'items' => $value,
                                'created_at' => date('Y-m-d H:i:s', time()),
                            ];
                            $room_res = InspectRoom::insert($room_data);
                            if (!$room_res) {
                                $error += 1;
                            }
                        }
                    }
                    if ($input['inspect_method'] == 2) {
                        // 发布市场
                        $order_sn = orderId();
                        $room_info = RentHouse::where('id', $input['rent_house_id'])->first();
                        $order_data = [
                            'inspect_id' => $input['inspect_id'],
                            'user_id' => $input['user_id'],
                            'group_id'  => $group_id+1,
                            'order_sn' => $order_sn,
                            'rent_house_id' => $input['rent_house_id'],
                            'District' => $room_info->District,
                            'TA' => $room_info->TA,
                            'Region' => $room_info->Region,
                            'order_type' => 3,
                            'start_time' => $input['inspect_start_date'],
                            'end_time' => $input['inspect_end_date'],
                            'requirement' => $input['inspect_note'],
                            'budget' => $input['budget'],
                            'created_at' => date('Y-m-d H:i:s', time()),
                        ];
                        $order_res = LandlordOrder::insert($order_data);
                        if (!$order_res) {
                            $error += 1;
                        }
                    }
                    if ($res && !$error) {
                        return $this->success('inspect add success');
                    } else {
                        return $this->error('3', 'inspect add failed');
                    }
                } else {
                    return $this->error('3', 'inspect add failed');
                }
            }/*elseif ($input['inspect_category'] == 3) { // 批量检查
                static $error = 0;
                $group_id = $model->max('group_id'); // 获得目前存入的最大group_id
                foreach($input['room_list'] as $k => $v){
                    $inspect_data = [
                        'rent_house_id'         => $v['rent_house_id'],
                        'contract_id'           => @$input['contract_id'],
                        'inspect_name'          => $input['inspect_name'],
                        'inspect_method'        => $input['inspect_method'],
                        'inspect_category'      => $input['inspect_category'],
                        'inspect_start_date'    => $input['inspect_start_date'],
                        'inspect_end_date'      => $input['inspect_end_date'],
                        'inspect_note'          => $input['inspect_note'],
                        'chattel_note'          => $input['chattel_note'],
                        'created_at'            => date('Y-m-d H:i:s', time()),
                    ];
                    $room_res = $model->where('id',$v['inspect_id'])->update($inspect_data);
                    $inspect_id[] = $room_res;
                    if(!$room_res){
                        $error += 1;
                    }else {
                        // 清除所有的检查清单 和财产清单
                        InspectChattel::where('inspect_id',$v['inspect_id'])->delete();
                        InspectRoom::where('inspect_id',$v['inspect_id'])->delete();
                        LandlordOrder::where('inspect_id',$v['inspect_id'])->delete();
                        // 财产清单
                        foreach ($input['chattel_list'] as $key => $value) {
                            $chattel_data = [
                                'inspect_id' => $room_res,
                                'chattel_name' => $value['chattel_name'],
                                'chattel_num' => $value['chattel_num'],
                                'created_at' => date('Y-m-d H:i:s', time()),
                            ];
                            $chattel_res = InspectChattel::insert($chattel_data);
                            if (!$chattel_res) {
                                $error += 1;
                            }
                        }
                        // 房屋
                        foreach ($v['items'] as $key => $value) {
                            $room_data = [
                                'inspect_id' => $room_res,
                                'rent_house_id' => $v['rent_house_id'],
                                'room_name' => $v['room_name'],
                                'items' => $value,
                                'created_at' => date('Y-m-d H:i:s', time()),
                            ];
                            $res = InspectRoom::insert($room_data);
                            if (!$res) {
                                $error += 1;
                            }
                        }
                        if ($input['inspect_method'] == 2) {
                            // 发布市场
                            $order_sn = orderId();
                            $room_info = RentHouse::where('id', $v['rent_house_id'])->first();
                            $order_data = [
                                'inspect_id' => $room_res,
                                'user_id' => $input['user_id'],
                                'order_sn' => $order_sn,
                                'group_id' => $group_id+1,
                                'rent_house_id' => $v['rent_house_id'],
                                'District' => $room_info->District,
                                'TA' => $room_info->TA,
                                'Region' => $room_info->Region,
                                'order_type' => 3,
                                'start_time' => $input['inspect_start_date'],
                                'end_time' => $input['inspect_end_date'],
                                'requirement' => $input['inspect_note'],
                                'budget' => $input['budget'],
                                'created_at' => date('Y-m-d H:i:s', time()),
                            ];
                            $order_res = LandlordOrder::insert($order_data);
                            if (!$order_res) {
                                $error += 1;
                            }
                        }
                    }
                }
                if(!$error){
                    return $this->success('inspect add success');
                }else{
                    return $this->error('3', 'inspect add failed');
                }*/

        }
    }


    /**
     * @description:房东开始检查
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function inspectDeleteRoom(array $input)
    {
        $inspect_id = $input['inspect_id'];
        $room_name = $input['room_name'];
        $res = InspectRoom::where('inspect_id',$inspect_id)->where('room_name',$room_name)->delete();
        if($res){
            return $this->success('delete room success');
        }else{
            return $this->error('2','deleted room failed');
        }

    }

    /**
     * @description:房东开始检查
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function inspectDeleteItem(array $input)
    {
        $item_id = $input['item_id'];
        $res = InspectRoom::whereIn('id',$item_id)->delete();
        if($res){
            return $this->success('delete item success');
        }else{
            return $this->error('2','deleted item failed');
        }

    }

    /**
     * @description:房东开始检查
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function inspectDeleteChattel(array $input)
    {
        $chattel_id = $input['chattel_id'];
        $res = InspectChattel::whereIn('id',$chattel_id)->delete();
        if($res){
            return $this->success('delete chattel success');
        }else{
            return $this->error('2','deleted chattel failed');
        }

    }


    /**
     * @description:检查确认
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function inspectConfirm(array $input)
    {
        $check = InspectCheck::where('inspect_id',$input['inspect_id'])->first();
        if($check){
            return $this->error('3','already confirm');
        }
        $check_data =  [
            'inspect_id'        => $input['inspect_id'],
            'inspector_note'    => $input['inspector_note'],
            'tenement_note'     => $input['tenement_note'],
            'other_note'        => $input['other_note'],
            'upload_url'        => $input['upload_url'],
            'inspector_sign'    => $input['inspector_sign'],
            'tenement_sign'     => $input['tenement_sign'],
            'select1'           => $input['select1'],
            'select2'           => $input['select2'],
            'select3'           => $input['select3'],
            'select4'           => $input['select4'],
            'select5'           => $input['select5'],
            'select6'           => $input['select6'],
            'select7'           => $input['select7'],
            'repair_note'       => $input['repair_note'],
            'created_at'        => date('Y-m-d H:i:s',time()),
        ];
        $res = InspectCheck::insert($check_data);
        if($res){
            // 更改状态
            $inspect_method = Inspect::where('id',$input['inspect_id'])->first();
            if($inspect_method->inspect_method == 2){
                if(isset($input['operator_id'])){
                    $check_name = Operator::where('id',$input['operator_id'])->pluck('operator_name')->first();
                }else{
                    $check_name = Providers::where('user_id',$input['user_id'])->pluck('first_name')->first();
                }
                Inspect::where('id',$input['inspect_id'])->update(['inspect_status'=>3,'inspect_completed_date'=>date('Y-m-d',time()),'check_name'=>$check_name,'updated_at'=>date('Y-m-d H:i:s',time())]);
            }else{
                if(isset($input['operator_id'])){
                    $check_name = Operator::where('id',$input['operator_id'])->pluck('operator_name')->first();
                }else{
                    $check_name = Landlord::where('user_id',$input['user_id'])->pluck('first_name')->first();
                }
                Inspect::where('id',$input['inspect_id'])->update(['inspect_status'=>4,'inspect_completed_date'=>date('Y-m-d',time()),'check_name'=>$check_name,'updated_at'=>date('Y-m-d H:i:s',time())]);
            }
            // 房屋操作节点
            $house_log_data = [
                'user_id'   => $input['user_id'],
                'rent_house_id' => DB::table('inspect_list')->where('id',$input['inspect_id'])->pluck('rent_house_id')->first(),
                'log_type'      => 4,
                'created_at'    => date('Y-m-d H:i:s',time()),
            ];
            DB::table('house_log')->insert($house_log_data);
            return $this->success('inspect confirm success');
        }else{
            return $this->error('2','inspect confirm failed');
        }
    }


    /**
     * @description:检查记录
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function inspectRecord(array $input)
    {
        $inspect_id = $input['inspect_id'];
        $model = new InspectRoom();
        $res = $model->where('inspect_id',$inspect_id)->where('accept','>',1)->get()->toArray();
        $data['res'] = $res;
        $data['inspect_method'] = Inspect::where('id',$inspect_id)->pluck('inspect_method')->first();
        if($res){
            return $this->success('get record success',$data);
        }else{
            return $this->error('2','no record');
        }

    }


    /**
     * @description:检查记录
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function landlordCheckDetail(array $input)
    {
        $inspect_id = $input['inspect_id'];
        $model = new InspectRoom();
        $res = $model->where('inspect_id',$inspect_id)/*->where('accept',2)*/->get()->toArray();
        $data['issues_res'] = $res;
        $data['confirm_res'] = InspectCheck::where('inspect_id',$inspect_id)->first()->toArray();
        $data['house_res'] = RentHouse::where('id',$input['rent_house_id'])->first()->toArray();
        if($res){
            return $this->success('get record success',$data);
        }else{
            return $this->error('2','no record');
        }

    }


    /**
     * @description:发布维修订单
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function addIssuesBatch(array $input)
    {
        $group_id = LandlordOrder::max('group_id'); // 获得目前存入的最大group_id
        $issues_id = $input['issues_id'];
        static $error = 0;
        foreach ($issues_id as $k => $v){
            $order_sn = orderId();
            $room_info = RentHouse::where('id', $input['rent_house_id'])->first();
            $order_data = [
                'issue_id' => $v,
                'user_id' => $input['user_id'],
                'group_id'  => $group_id+1,
                'order_sn' => $order_sn,
                'rent_house_id' => $input['rent_house_id'],
                'order_name'    => $input['order_name'],
                'jobs'          => $input['jobs'],
                'District' => $room_info->District,
                'TA' => $room_info->TA,
                'Region' => $room_info->Region,
                'order_type' => 4,
                'start_time' => $input['repair_start_date'],
                'end_time' => $input['repair_end_date'],
                'requirement' => $input['issues_note'],
                'budget' => $input['budget'],
                'created_at' => date('Y-m-d H:i:s', time()),
            ];
            $res = LandlordOrder::insert($order_data);
            // 更改检查列表状态
            InspectRoom::where('id',$v)->update(['accept'=>3,'updated_at'=>date('Y-m-d H:i:s',time())]);
            if(!$res){
                $error += 1;
            }
        }
        if(!$error){
            return $this->success('send order market success');
        }else{
            return $this->error('2','send order market failed');
        }

    }

    /**
     * @description:房东确认检查
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function landlordConfirm(array $input)
    {
        $inspect_id = $input['inspect_id'];
        $model = new Inspect();
        $res = $model->where('id',$inspect_id)->update(['inspect_status'=> 4,'updated_at'=>date('Y-m-d H:i:s',time())]);
        if($res){
            return $this->success('landlord confirm success');
        }else{
            return $this->error('2','landlord confirm failed');
        }

    }

    /**
     * @description:检查记录
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function issueRecord(array $input)
    {
        $inspect_id = $input['inspect_id'];
        $model = new InspectRoom();
        $res = $model->where('inspect_id',$inspect_id)->where('accept',2)->get()->toArray();
        $data['res'] = $res;
        if($res){
            return $this->success('get record success',$data);
        }else{
            return $this->error('2','no record');
        }

    }


    /**
     * @description:发布维修订单
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function addIssues(array $input)
    {
        $group_id = LandlordOrder::max('group_id'); // 获得目前存入的最大group_id
        // 存入inspect_room表
        static $error = 0;
        foreach ($input['items'] as $k => $v) {
            $room_data = [
                'items'         => $v['items'],
                'accept'        => 2,
                'photo1'        => $v['photo1'],
                'photo2'        => $v['photo2'],
                'photo3'        => $v['photo3'],
                'photo4'        => $v['photo4'],
                'inspect_note'  => $v['inspect_note'],
                'video_url'     => $v['video_url'],
                'created_at'    => date('Y-m-d H:i:s',time()),
            ];
            $issues_res = InspectRoom::insertGetId($room_data);
            $order_sn = orderId();
            $room_info = RentHouse::where('id', $input['rent_house_id'])->first();
            $order_data = [
                'issue_id' => $issues_res,
                'user_id' => $input['user_id'],
                'group_id'  => $group_id+1,
                'order_sn' => $order_sn,
                'rent_house_id' => $input['rent_house_id'],
                'jobs'          => $input['jobs'],
                'order_name'    => $input['order_name'],
                'District' => $room_info->District,
                'TA' => $room_info->TA,
                'Region' => $room_info->Region,
                'order_type' => 4,
                'start_time' => $input['repair_start_date'],
                'end_time' => $input['repair_end_date'],
                'requirement' => $input['issues_note'],
                'budget' => $input['budget'],
                'created_at' => date('Y-m-d H:i:s', time()),
            ];
            $res = LandlordOrder::insert($order_data);
        }
        if(!$res){
            $error += 1;
        }
        if(!$error){
            return $this->success('send order market success');
        }else{
            return $this->error('2','send order market failed');
        }
    }


    /**
     * @description:新增检查
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function unPlatInspectAdd(array $input)
    {
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        $model = new UnPlatInspectList();
        if($input['inspect_category'] == 1) { // 整租检查
            $inspect_data = [
                'user_id' => $input['user_id'],
                'property_name' => $input['property_name'],
                'property_address' => $input['property_address'],
                'bedroom_num' => $input['bedroom_num'],
                'bathroom_num' => $input['bathroom_num'],
                'landlord_name' => $input['landlord_name'],
                'landlord_post_address' => $input['landlord_post_address'],
                'landlord_email' => $input['landlord_email'],
                'landlord_phone' => $input['landlord_phone'],
                'property_type' => $input['property_type'],
                'start_time' => $input['start_time'],
                'end_time' => $input['end_time'],
                'inspect_category' => $input['inspect_category'],
                'chattel_note'  => $input['chattel_note'],
                'inspect_note'  => $input['inspect_note'],
                'created_at' => date('Y-m-d H:i:s', time()),
            ];
            $res = $model->insertGetId($inspect_data);
            if ($res) {
                static $error = 0;
                // 财产清单
                foreach ($input['chattel_list'] as $k => $v) {
                    $chattel_data = [
                        'inspect_id' => $res,
                        'chattel_name' => $v['chattel_name'],
                        'chattel_num' => $v['chattel_num'],
                        'created_at' => date('Y-m-d H:i:s', time()),
                    ];
                    $chattel_res = UnPlatInspectChattel::insert($chattel_data);
                    if (!$chattel_res) {
                        $error += 1;
                    }
                }
                // 检查房间存入
                foreach ($input['room_list'] as $k => $v) {
                    foreach ($v['items'] as $key => $value) {
                        $room_data = [
                            'inspect_id' => $res,
                            'room_name' => $v['room_name'],
                            'items' => $value,
                            'created_at' => date('Y-m-d H:i:s', time()),
                        ];
                        $room_res = UnPlatInspectRoom::insert($room_data);
                        if (!$room_res) {
                            $error += 1;
                        }
                    }
                }
                return $this->success('inspect add success');
            } else {
                return $this->error('3', 'inspect add failed');
            }
        }elseif ($input['inspect_category'] == 2) { // 批量检查
            static $error = 0;
            foreach($input['room_list'] as $k => $v){
                $inspect_data = [
                    'user_id' => $input['user_id'],
                    'property_name' => $input['property_name'],
                    'property_address' => $input['property_address'],
                    'bedroom_num' => $input['bedroom_num'],
                    'bathroom_num' => $input['bathroom_num'],
                    'landlord_name' => $input['landlord_name'],
                    'landlord_post_address' => $input['landlord_post_address'],
                    'landlord_email' => $input['landlord_email'],
                    'landlord_phone' => $input['landlord_phone'],
                    'property_type' => $input['property_type'],
                    'start_time' => $input['start_time'],
                    'end_time' => $input['end_time'],
                    'inspect_category' => $input['inspect_category'],
                    'chattel_note'  => $input['chattel_note'],
                    'inspect_note'  => $input['inspect_note'],
                    'created_at' => date('Y-m-d H:i:s', time()),
                ];
                $res = $model->insertGetId($inspect_data);

                    // 财产清单
                    foreach ($input['chattel_list'] as $key => $value) {
                        $chattel_data = [
                            'inspect_id' => $res,
                            'chattel_name' => $value['chattel_name'],
                            'chattel_num' => $value['chattel_num'],
                            'created_at' => date('Y-m-d H:i:s', time()),
                        ];
                        $chattel_res = UnPlatInspectChattel::insert($chattel_data);
                        if (!$chattel_res) {
                            $error += 1;
                        }
                    }
                    // 房屋
                    foreach ($v['items'] as $key => $value) {
                        $room_data = [
                            'inspect_id' => $res,
                            'room_name' =>  $v['room_name'],
                            'items' => $value,
                            'created_at' => date('Y-m-d H:i:s', time()),
                        ];
                        $item_res = UnPlatInspectRoom::insert($room_data);
                        if (!$item_res) {
                            $error += 1;
                        }
                    }
                }
            if(!$error){
                return $this->success('inspect add success');
            }else{
                return $this->error('3', 'inspect add failed');
            }
        }
    }


    /**
     * @description:非平台房屋检查列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function unPlatInspectList(array $input)
    {
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        $model = new UnPlatInspectList();
        $page = $input['page'];
        $count = $model->where('user_id',$input['user_id'])->count();
        if($count < ($page-1)*5){
            return $this->error('3','no more inspect info');
        }
        $res = $model->where('user_id',$input['user_id'])->offset(($page-1)*5)->limit(5)->get()->toArray();
        if($res){
            $data['inspect_list'] = $res;
            $data['total_page'] = ceil($count/5);
            $data['current_page'] = $page;
            return $this->success('get inspect list success',$data);
        }else{
            return $this->error('2','get inspect list fail');
        }
    }


    /**
     * @description:非平台检查详细
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function unPlatInspectDetail(array $input)
    {
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        $model = new UnPlatInspectList();
        $inspect_id = $input['inspect_id'];
        $res = $model->where('id',$inspect_id)->first()->toArray();
        if(!$res){
            return $this->error('2','get inspect detail failed');
        }else {
            $chattel_info = UnPlatInspectChattel::where('inspect_id', $inspect_id)->select('chattel_name', 'chattel_num')->get()->toArray();
            $room_name = UnPlatInspectRoom::where('inspect_id', $input['inspect_id'])->groupBy('room_name')->get()->toArray();
            foreach ($room_name as $k => $v) {
                $item_info[$k]['room_name'] = $v['room_name'];
                $item_info[$k]['items'] = UnPlatInspectRoom::where('inspect_id', $input['inspect_id'])->where('room_name', $v['room_name'])->pluck('items');
            }
            $data['inspect_info'] = $res;
            $data['chattel_info'] = $chattel_info;
            $data['item_info'] = $item_info;
            return $this->success('get inspect detail success', $data);
        }
    }


    /**
     * @description:非平台检查项目
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function unPlatInspectItem(array $input)
    {
        $model = new UnPlatInspectList();
        $inspect_res = $model->where('id',$input['inspect_id'])->first();
        $chattel_info = UnPlatInspectChattel::where('inspect_id',$input['inspect_id'])->get()->toArray();
        $room_name = UnPlatInspectRoom::where('inspect_id',$input['inspect_id'])->groupBy('room_name')->get()->toArray();
        if(!$room_name){
            return $this->error('2','no inspect info');
        }
        foreach($room_name as $k => $v){
            $data['data'][][$v['room_name']] =  UnPlatInspectRoom::where('inspect_id',$input['inspect_id'])->where('room_name',$v['room_name'])->get()->toArray();
        }
        $data['chattel'] = $chattel_info;
        return $this->success('get inspect item success',$data);
    }


    /**
     * @description:非平台检查编辑
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function unPlatInspectEdit(array $input)
    {
        $model = new UnPlatInspectList();
        $inspect_data = [
            'user_id' => $input['user_id'],
            'property_name' => $input['property_name'],
            'property_address' => $input['property_address'],
            'bedroom_num' => $input['bedroom_num'],
            'bathroom_num' => $input['bathroom_num'],
            'landlord_name' => $input['landlord_name'],
            'landlord_post_address' => $input['landlord_post_address'],
            'landlord_email' => $input['landlord_email'],
            'landlord_phone' => $input['landlord_phone'],
            'property_type' => $input['property_type'],
            'start_time' => $input['start_time'],
            'end_time' => $input['end_time'],
            'inspect_category' => $input['inspect_category'],
            'chattel_note'  => $input['chattel_note'],
            'inspect_note'  => $input['inspect_note'],
            'created_at' => date('Y-m-d H:i:s', time()),
        ];
        $res = $model->where('id',$input['inspect_id'])->update($inspect_data);
        if ($res) {
            static $error = 0;
            // 清除所有的检查清单 和财产清单
            UnPlatInspectChattel::where('inspect_id',$input['inspect_id'])->delete();
            UnPlatInspectRoom::where('inspect_id',$input['inspect_id'])->delete();
            // 财产清单
            foreach ($input['chattel_list'] as $k => $v) {
                $chattel_data = [
                    'inspect_id' => $input['inspect_id'],
                    'chattel_name' => $v['chattel_name'],
                    'chattel_num' => $v['chattel_num'],
                    'created_at' => date('Y-m-d H:i:s', time()),
                ];
                $chattel_res = UnPlatInspectChattel::insert($chattel_data);
                if (!$chattel_res) {
                    $error += 1;
                }
            }
            // 检查房间存入
            foreach ($input['room_list'] as $k => $v) {
                foreach ($v['items'] as $key => $value) {
                    $room_data = [
                        'inspect_id' => $input['inspect_id'],
                        'room_name' => $v['room_name'],
                        'items' => $value,
                        'created_at' => date('Y-m-d H:i:s', time()),
                    ];
                    $room_res = UnPlatInspectRoom::insert($room_data);
                    if (!$room_res) {
                        $error += 1;
                    }
                }
            }
            if ($res && !$error) {
                return $this->success('inspect add success');
            } else {
                return $this->error('3', 'inspect add failed');
            }
        } else {
            return $this->error('3', 'inspect add failed');
        }
    }


    /**
     * @description:房东开始检查
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function unPlatInspectDeleteRoom(array $input)
    {
        $inspect_id = $input['inspect_id'];
        $room_name = $input['room_name'];
        $res = UnPlatInspectRoom::where('inspect_id',$inspect_id)->where('room_name',$room_name)->delete();
        if($res){
            return $this->success('delete room success');
        }else{
            return $this->error('2','deleted room failed');
        }

    }

    /**
     * @description:房东开始检查
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function unPlatInspectDeleteItem(array $input)
    {
        $item_id = $input['item_id'];
        $res = UnPlatInspectRoom::whereIn('id',$item_id)->delete();
        if($res){
            return $this->success('delete item success');
        }else{
            return $this->error('2','deleted item failed');
        }

    }

    /**
     * @description:房东开始检查
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function unPlatInspectDeleteChattel(array $input)
    {
        $chattel_id = $input['chattel_id'];
        $res = UnPlatInspectChattel::whereIn('id',$chattel_id)->delete();
        if($res){
            return $this->success('delete chattel success');
        }else{
            return $this->error('2','deleted chattel failed');
        }

    }


    /**
     * @description:非平台服务商开始检查
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function unPlatInspectCheck(array $input)
    {
        // 修改检查表
        static $error = 0;
        $model = new UnPlatInspectRoom();
        foreach ($input['items_list'] as $key => $value){
            if(isset($value['id'])){
                $room_data = [
                    'accept'        => $value['accept'],
                    'photo1'        => $value['photo1'],
                    'photo2'        => $value['photo2'],
                    'photo3'        => $value['photo3'],
                    'photo4'        => $value['photo4'],
                    'inspect_note'  => $value['inspect_note'],
                    'video_url'     => $value['video_url'],
                    'updated_at'    => date('Y-m-d H:i:s',time()),
                ];
                $res = $model->where('id',$value['id'])->update($room_data);
            }else{
                $room_data = [
                    'inspect_id'    => $input['inspect_id'],
                    'room_name'     => $value['room_name'],
                    'items'         => $value['items'],
                    'accept'        => $value['accept'],
                    'photo1'        => $value['photo1'],
                    'photo2'        => $value['photo2'],
                    'photo3'        => $value['photo3'],
                    'photo4'        => $value['photo4'],
                    'inspect_note'  => $value['inspect_note'],
                    'video_url'     => $value['video_url'],
                    'created_at'    => date('Y-m-d H:i:s',time()),
                ];
                $res = $model->insert($room_data);
            }
            if(!$res){
                $error += 1;
            }
            // 将问题存入问题表中
            //TODO

        }

        foreach ($input['chattel_list'] as $key => $value){
            if(isset($value['id'])){
                $room_data = [
                    'chattel_num'   => $value['chattel_num'],
                    'accept'        => $value['accept'],
                    'photo1'        => $value['photo1'],
                    'photo2'        => $value['photo2'],
                    'photo3'        => $value['photo3'],
                    'photo4'        => $value['photo4'],
                    'inspect_note'  => $value['inspect_note'],
                    'updated_at'    => date('Y-m-d H:i:s',time()),
                ];
                $res = UnPlatInspectChattel::where('id',$value['id'])->update($room_data);
            }else{
                $room_data = [
                    'inspect_id'    => $input['inspect_id'],
                    'chattel_name'  => $value['chattel_name'],
                    'chattel_num'   => $value['chattel_num'],
                    'accept'        => $value['accept'],
                    'photo1'        => $value['photo1'],
                    'photo2'        => $value['photo2'],
                    'photo3'        => $value['photo3'],
                    'photo4'        => $value['photo4'],
                    'inspect_note'  => $value['inspect_note'],
                    'created_at'    => date('Y-m-d H:i:s',time()),
                ];
                $res = InspectChattel::insert($room_data);
            }
            if(!$res){
                $error += 1;
            }
            // 将问题存入问题表中
            //TODO

        }
        if($error){
            return $this->error('2','check failed');
        }else{
            return $this->success('check success');
        }

    }


    /**
     * @description:检查确认
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function unPlatInspectConfirm(array $input)
    {
        $check = UnPlatInspectCheck::where('inspect_id',$input['inspect_id'])->first();
        if($check){
            return $this->error('3','already confirm');
        }
        $check_data =  [
            'inspect_id'        => $input['inspect_id'],
            'inspector_note'    => $input['inspector_note'],
            'tenement_note'     => $input['tenement_note'],
            'other_note'        => $input['other_note'],
            'upload_url'        => $input['upload_url'],
            'inspector_sign'    => $input['inspector_sign'],
            'tenement_sign'     => $input['tenement_sign'],
            'select1'           => $input['select1'],
            'select2'           => $input['select2'],
            'select3'           => $input['select3'],
            'select4'           => $input['select4'],
            'select5'           => $input['select5'],
            'select6'           => $input['select6'],
            'select7'           => $input['select7'],
            'repair_note'       => $input['repair_note'],
            'created_at'        => date('Y-m-d H:i:s',time()),
        ];
        $res = InspectCheck::insert($check_data);
        if($res){
            // 更改状态
            UnPlatInspectList::where('id',$input['inspect_id'])->update(['inspect_status'=>2,'updated_at'=>date('Y-m-d H:i:s',time())]);
            return $this->success('inspect confirm success');
        }else{
            return $this->error('2','inspect confirm failed');
        }
    }

    /**
     * @description:检查记录
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function unPlanInspectRecord(array $input)
    {
        $inspect_id = $input['inspect_id'];
        $model = new UnPlatInspectRoom();
        $res = $model->where('inspect_id',$inspect_id)->where('accept','>',1)->get()->toArray();
        $data['res'] = $res;
        $data['inspect_method'] = Inspect::where('id',$inspect_id)->pluck('inspect_method')->first();
        if($res){
            return $this->success('get record success',$data);
        }else{
            return $this->error('2','no record');
        }

    }

}