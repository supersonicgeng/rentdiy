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
            if($input['inspect_category'] == 1){ // 整租检查
                $inspect_data = [
                    'rent_house_id'             => $input['rent_house_id'],
                    'contract_id'               => $input['contract_id'],
                    'inspect_method'            => $input['inspect_method'],
                    'inspect_category'          => $input['inspect_category'],
                    'inspect_start_date'        => $input['inspect_start_date'],
                    'inspect_end_date'          => $input['inspect_end_date'],
                    'inspect_note'              => $input['inspect_note'],
                    'created_at'                => date('Y-m-d H:i:s',time()),
                ];
                $res = $model->insertGetId($inspect_data);
                if($res){
                    static $error = 0;
                    // 财产清单
                    foreach($input['chattel_list'] as $k => $v){
                        $chattel_data = [
                            'inspect_id'    => $res,
                            'chattel_name'  => $v['chattel_name'],
                            'chattel_num'   => $v['chattel_num'],
                            'created_at'    => date('Y-m-d H:i:s',time()),
                        ];
                        $chattel_res = InspectChattel::insert($chattel_data);
                        if(!$chattel_res) {
                            $error += 1;
                        }
                    }
                    // 检查房间存入
                    foreach($input['room_list'] as $k => $v){
                        $room_data = [
                            'inspect_id'    => $res,
                            'room_name'     => $v['room_name'],
                            'items'         => $v['items'],
                            'created_at'    => date('Y-m-d H:i:s',time()),
                        ];
                        $room_res = InspectRoom::insert($room_data);
                        if($room_res){
                            $error += 1;
                        }
                    }
                    if($res && !$error){
                        return $this->success('inspect add success');
                    }else{
                        return $this->error('3','inspect add failed');
                    }
                }elseif ($input['inspect_category'] == 2) { // 分租检查
                    $inspect_data = [
                        'rent_house_id'         => $input['rent_house_id'],
                        'contract_id'           => $input['contract_id'],
                        'inspect_method'        => $input['inspect_method'],
                        'inspect_category'      => $input['inspect_category'],
                        'inspect_start_date'    => $input['inspect_start_date'],
                        'inspect_end_date'      => $input['inspect_end_date'],
                        'inspect_note'          => $input['inspect_note'],
                        'created_at'            => date('Y-m-d H:i:s', time()),
                    ];
                    $res = $model->insertGetId($inspect_data);
                    if ($res) {
                        static $error = 0;
                        // 财产清单
                        foreach ($input['chattel_list'] as $k => $v) {
                            $chattel_data = [
                                'inspect_id'    => $res,
                                'chattel_name'  => $v['chattel_name'],
                                'chattel_num'   => $v['chattel_num'],
                                'created_at'    => date('Y-m-d H:i:s', time()),
                            ];
                            $chattel_res = InspectChattel::insert($chattel_data);
                            if (!$chattel_res) {
                                $error += 1;
                            }
                        }
                        // 检查房间存入
                        foreach ($input['room_list'] as $k => $v) {
                            $room_data = [
                                'inspect_id'    => $res,
                                'room_name'     => $v['room_name'],
                                'items'         => $v['items'],
                                'created_at'    => date('Y-m-d H:i:s', time()),
                            ];
                            $room_res = InspectRoom::insert($room_data);
                            if ($room_res) {
                                $error += 1;
                            }
                        }
                        if ($res && !$error) {
                            return $this->success('inspect add success');
                        } else {
                            return $this->error('3', 'inspect add failed');
                        }
                    }elseif ($input['inspect_category'] == 3) { // 批量检查
                        $inspect_data = [
                            'rent_house_id'         => $input['rent_house_id'],
                            'contract_id'           => $input['contract_id'],
                            'inspect_method'        => $input['inspect_method'],
                            'inspect_category'      => $input['inspect_category'],
                            'inspect_start_date'    => $input['inspect_start_date'],
                            'inspect_end_date'      => $input['inspect_end_date'],
                            'inspect_note'          => $input['inspect_note'],
                            'created_at'            => date('Y-m-d H:i:s', time()),
                        ];
                        $res = $model->insertGetId($inspect_data);
                        if ($res) {
                            static $error = 0;
                            // 财产清单
                            foreach ($input['chattel_list'] as $k => $v) {
                                $chattel_data = [
                                    'inspect_id'    => $res,
                                    'chattel_name'  => $v['chattel_name'],
                                    'chattel_num'   => $v['chattel_num'],
                                    'created_at'    => date('Y-m-d H:i:s', time()),
                                ];
                                $chattel_res = InspectChattel::insert($chattel_data);
                                if (!$chattel_res) {
                                    $error += 1;
                                }
                            }
                            // 检查房间存入
                            foreach ($input['room_list'] as $k => $v) {
                                $room_data = [
                                    'inspect_id'    => $res,
                                    'rent_house_id' => $v['rent_house_id'],
                                    'room_name'     => $v['room_name'],
                                    'items'         => $v['items'],
                                    'created_at'    => date('Y-m-d H:i:s', time()),
                                ];
                                $room_res = InspectRoom::insert($room_data);
                                if ($room_res) {
                                    $error += 1;
                                }
                            }
                            if ($res && !$error) {
                                return $this->success('inspect add success');
                            } else {
                                return $this->error('3', 'inspect add failed');
                            }
                        }
                    }
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
}