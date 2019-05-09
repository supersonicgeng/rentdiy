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
use App\Model\Inspect;
use App\Model\InspectChattel;
use App\Model\InspectRoom;
use App\Model\Key;
use App\Model\LandlordOrder;
use App\Model\Region;
use App\Model\RentContact;
use App\Model\RentHouse;
use App\Model\RentPic;
use App\Model\Verify;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

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
            }elseif ($input['inspect_category'] == 2) { // 分租检查
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
            }elseif ($input['inspect_category'] == 3) { // 批量检查
                dd($input);
                static $error = 0;
                foreach($input['room_list'] as $k => $v){
                    foreach ($v['items'] as $key => $value){
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
                        $room_res = $model->insertGetId($inspect_data);;
                        if(!$room_res){
                            $error += 1;
                        }else{
                            // 财产清单
                            foreach ($input['chattel_list'] as $k => $v) {
                                $chattel_data = [
                                    'inspect_id'    => $room_res,
                                    'chattel_name'  => $v['chattel_name'],
                                    'chattel_num'   => $v['chattel_num'],
                                    'created_at'    => date('Y-m-d H:i:s', time()),
                                ];
                                $chattel_res = InspectChattel::insert($chattel_data);
                                if (!$chattel_res) {
                                    $error += 1;
                                }
                            }
                            // 房屋
                            foreach ($v['items'] as $key => $value){
                                $room_data = [
                                    'inspect_id'    => $room_res,
                                    'rent_house_id' => $v['rent_house_id'],
                                    'room_name'     => $v['room_name'],
                                    'items'         => $value,
                                    'created_at'    => date('Y-m-d H:i:s',time()),
                                ];
                                $res = InspectRoom::insert($room_data);
                                if(!$res){
                                    $error += 1;
                                }
                            }
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
            $res = $model->where('rent_house_id',$input['rent_house_id'])->offset(($page-1)*3)->limit(3)->get()->toArray();
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
        if($inspect_res->inspect_category == 1){
            $room_name = InspectRoom::where('inspect_id',$input['inspect_id'])->groupBy('room_name')->get()->toArray();
            foreach($room_name as $k => $v){
                $data[][$v['room_name']] =  InspectRoom::where('inspect_id',$input['inspect_id'])->where('room_name',$v['room_name'])->get()->toArray();
            }
            return $this->success('get inspect item success',$data);
        }elseif ($inspect_res->inspect_category == 2){
            $data[]['room_name'] = InspectRoom::where('inspect_id',$input['inspect_id'])->groupBy('room_name')->get()->toArray();
            return $this->success('get inspect item success',$data);
        }else {
            $room_name = InspectRoom::where('inspect_id', $input['inspect_id'])->groupBy('room_name')->get()->toArray();
            foreach ($room_name as $k => $v) {
                $data[][$v['room_name']] = InspectRoom::where('inspect_id', $input['inspect_id'])->where('room_name', $v['room_name'])->get()->toArray();
            }
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

}