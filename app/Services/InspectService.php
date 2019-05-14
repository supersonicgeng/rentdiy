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
use App\Model\InspectCheck;
use App\Model\InspectRoom;
use App\Model\Key;
use App\Model\LandlordOrder;
use App\Model\Region;
use App\Model\RentContact;
use App\Model\RentHouse;
use App\Model\RentPic;
use App\Model\Tender;
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
                if($res['inspect_category'] = 1){
                    $room_name = InspectRoom::where('inspect_id',$input['inspect_id'])->groupBy('room_name')->get()->toArray();
                    foreach($room_name as $k => $v){
                        $item_info[$k]['room_name'] =  $v['room_name'];
                        $item_info[$k]['items'] =  InspectRoom::where('inspect_id',$input['inspect_id'])->where('room_name',$v['room_name'])->pluck('items');
                    }
                }else{
                    $item_info[]['items'] =  InspectRoom::where('inspect_id',$input['inspect_id'])->pluck('items');
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
                        Inspect::where('id',$input['inspect_id'])->update(['inspect_status'=>2,'check_user_id'=>@$input['check_user_id'],'check_operator_id'=>@$input['check_operator_id']]);
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
                Inspect::where('id',$input['inspect_id'])->update(['inspect_status'=>3,'updated_at'=>date('Y-m-d H:i:s',time())]);
            }else{
                Inspect::where('id',$input['inspect_id'])->update(['inspect_status'=>2,'updated_at'=>date('Y-m-d H:i:s',time())]);
            }
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
        $res = $model->where('inspect_id',$inspect_id)->where('accept',2)->get()->toArray();
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
                'issues_id' => $v,
                'user_id' => $input['user_id'],
                'group_id'  => $group_id+1,
                'order_sn' => $order_sn,
                'rent_house_id' => $input['rent_house_id'],
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
}