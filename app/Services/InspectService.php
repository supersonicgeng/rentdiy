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
     * @description:新增检查编辑
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
}