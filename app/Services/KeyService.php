<?php
/**
 * 钥匙管理服务层
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1 0001
 * Time: 下午 4:25
 */

namespace App\Services;


use App\Lib\Util\QueryPager;
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

class KeyService extends CommonService
{
    /**
     * @description:增加钥匙
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function keyAdd(array $input)
    {
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if(!$user_info->user_role %2 ){
            return $this->error('2','this account is not a landlord role');
        }else{
            $model = new Key();
            $key_data = [
                'user_id'           => $input['user_id'],
                'house_id'          => $input['house_id'],
                'key_name'          => $input['key_name'],
                'borrow_name'       => $input['borrow_name'],
                'tel'               => $input['tel'],
                'e_mail'            => $input['e_mail'],
                'borrow_start_date' => $input['borrow_start_date'],
                'borrow_end_date'   => $input['borrow_end_date'],
                'operator_name'     => $input['operator_name'],
                'key_no'            => $input['key_no'],
                'note'              => $input['note'],
                'created_at'        => date('Y-m-d H:i:s',time()),
            ];
            $res = $model->insert($key_data);
            if($res){
                return $this->success('key add success');
            }else{
                return $this->error('3','key add failed');
            }
        }

    }

    /**
     * @description:增加钥匙
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function keyReturn(array $input)
    {
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if(!$user_info->user_role %2 ){
            return $this->error('2','this account is not a landlord role');
        }else{
            $model = new Key();
            $key_id = $input['key_id'];
            $data = [
                'return_date'   => date('Y-m-d',time()),
                'key_status'    => 1,
            ];
            $res = $model->where('id',$key_id)->update($data);
            if($res){
                return $this->success('key return success');
            }else{
                return $this->error('3','key return failed');
            }
        }

    }


    /**
 * @description:钥匙列表
 * @author: syg <13971394623@163.com>
 * @param $code
 * @param $message
 * @param array|null $data
 * @return \Illuminate\Http\JsonResponse
 */
    public function keyList(array $input)
    {
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if(!$user_info->user_role %2 ){
            return $this->error('2','this account is not a landlord role');
        }else{
            $model = new Key();
            $house_id = $input['house_id'];
            $page = $input['page'];
            $count = $model->where('hosue_id',$house_id)->count();
            if($count < ($page-1)*10){
                return $this->error('4', 'page num wrong');
            }
            $res = $model->where('house_id',$house_id)->offset(($page-1)*10)->limit(10)->get();
            $data['key'] = $res;
            $data['current_page'] = $page;
            $data['total_page'] = ceil($count/10);
            if($res){
                return $this->success('key list get success',$data);
            }else{
                return $this->error('3','key list get failed');
            }
        }

    }


    /**
     * @description:钥匙编辑
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function keyEdit(array $input)
    {
        $user_info = \App\Model\User::where('id',$input['user_id'])->first();
        if(!$user_info->user_role %2 ){
            return $this->error('2','this account is not a landlord role');
        }else{
            $model = new Key();
            $key_data = [
                'key_name'          => $input['key_name'],
                'borrow_name'       => $input['borrow_name'],
                'tel'               => $input['tel'],
                'e_mail'            => $input['e_mail'],
                'borrow_start_date' => $input['borrow_start_date'],
                'borrow_end_date'   => $input['borrow_end_date'],
                'operator_name'     => $input['operator_name'],
                'key_no'            => $input['key_no'],
                'note'              => $input['note'],
                'updated_at'        => date('Y-m-d H:i:s',time()),
            ];
            $res = $model->where('id',$input['key_id'])->update($key_data);
            if($res){
                return $this->success('key edit success');
            }else{
                return $this->error('3','key edit failed');
            }
        }

    }
}