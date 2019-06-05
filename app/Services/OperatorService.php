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
use App\Model\CheckBuilding;
use App\Model\Config;
use App\Model\Driver;
use App\Model\DriverTakeOver;
use App\Model\Landlord;
use App\Model\Level;
use App\Model\Operator;
use App\Model\OperatorRoom;
use App\Model\Order;
use App\Model\Passport;
use App\Model\PassportReward;
use App\Model\PassportStore;
use App\Model\Plant;
use App\Model\PlantOperateLog;
use App\Model\Providers;
use App\Model\ProvidersCompanyPic;
use App\Model\ProvidersCompanyPromoPic;
use App\Model\RentHouse;
use App\Model\RouteItems;
use App\Model\ScoreLog;
use App\Model\SignLog;
use App\Model\SysSign;
use App\Model\Tenement;
use App\Model\UserEvaluate;
use App\Model\UserEvaluateTag;
use App\Model\Verify;
use App\Model\VerifyLog;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class OperatorService extends CommonService
{
    /**
     * @description:增加操作员信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function addOperatorInformation(array $input)
    {
        //$operator_account = operatorAccount();
        $operator_data = [
            'user_id'           => $input['user_id'],
            'operator_way'      => $input['operator_way'],
            'operator_account'  => $input['operator_account'],
            'operator_name'     => $input['operator_name'],
            'password'          => md5($input['password']),
            'role'              => $input['role'],
            'start_date'        => $input['start_date'],
            'end_date'          => $input['end_date'],
            'email'             => $input['email'],
            'phone'             => $input['phone'],
            'is_use'            => $input['is_use'],
            'create_at'         => date('Y-m-d H:i:s',time())
        ];
        $model = new Operator();
        $res = $model->insertGetId($operator_data);
        static $error = 0;
        if($res){
            foreach ($input['house_id'] as $k => $v)
            $data = [
                'operator_id'   => $res,
                'house_id'      => $v,
                'created_at'    => date('Y-m-d H:i:s',time())
            ];
            $insert_res = OperatorRoom::insert($data);
            if(!$insert_res){
                $error += 1;
            }
        }
        if($res && !$error){
            // 发送邮件
            $subject = 'Account inform';
            $to = $input['email'];
            Mail::send('email.accountCreate',['operator_account' => $input['operator_account'],'password' => $input['password']],function($message) use($to,$subject){
                $message->to($to)->subject($subject);
            });
            //todo 发送短信
            return $this->success('add operator success');
        }else{
            return $this->error('2','add operator failed');
        }
    }



    /**
     * @description:操作员登陆
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(array $input)
    {
        $account = $input['account'];
        $password = $input['password'];
        $operator_way = $input['operator_way'];
        // 验证账号
        $model = new Operator();
        $res = $model->where('operator_account',$account)->where('password',md5($password))->first();
        if(!$res){
            return $this->error('2','the account or password was wong');
        }else{
            $token = md5($res->id.time().mt_rand(100,999));
            $res->login_token = $token; //生成token
            $res->login_expire_time = date('Y-m-d H:i:s',time()+7200);
            if($operator_way == 1){
                if($res->role >4){
                    return $this->error('3','your account is a provider operator');
                }
            }else{
                if($res->role < 5){
                    return $this->error('3','your account is a landlord operator');
                }
            }
            $res->update();
            return $this->success('login OK',$res->toArray());
        }
    }


    /**
     * @description:操作员编辑
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function editOperatorInformation(array $input)
    {
        $user_id = $input['user_id'];
        $operator_id = $input['operator_id'];
        $model = new Operator();
        $operator_info = $model->where('user_id',$user_id)->where('id',$operator_id)->first();
        $operator_data = [
            'operator_account'  => @$input['operator_account']?$input['operator_account']:$operator_info->operator_account,
            'operator_name'     => @$input['operator_name']?$input['operator_name']:$operator_info->operator_name,
            'password'          => @$input['password']?md5($input['password']):$operator_info->password,
            'role'              => @$input['role']?$input['role']:$operator_info->role,
            'start_date'        => @$input['start_date']?$input['start_date']:$operator_info->start_date,
            'end_date'          => @$input['end_date']?$input['end_date']:$operator_info->end_date,
            'email'             => @$input['email']?$input['email']:$operator_info->email,
            'phone'             => @$input['phone']?$input['phone']:$operator_info->phone,
            'is_use'            => @$input['is_use']?$input['is_use']:$operator_info->is_use,
            'update_at'         => date('Y-m-d H:i:s',time())
        ];
        $res = $model->where('user_id',$user_id)->where('id',$operator_id)->update($operator_data);
    }


    /**
     * @description:查询操作员账号
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkOperatorAccount(array $input)
    {
        $operator_account = $input['operator_account'];
        $model = new Operator();
        $res = $model->where('operator_account',$operator_account)->first();
        if($res){
            return $this->error('2','the operator account is used pls change a new account');
        }else{
            return $this->success('the operator account can use');
        }
    }

    /**
     * @description:获得操作员列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOperatorList(array $input)
    {
        $user_id = $input['user_id'];
        $operator_way = $input['operator_way'];
        $model = new Operator();
        $res = $model->where('user_id',$user_id)->where('operator_way',$operator_way)->where('deleted_at',null)->get();
        if($res){
            $res = $res->toArray();
            foreach ($res as $key => $value){
                $house_id = OperatorRoom::where('operator_id',$value['id'])->where('deleted_at',null)->pluck('house_id');
                foreach ($house_id as $k => $v){
                    $res[$key]['house_list']['house_id'][$k] = $v;
                    $res[$key]['house_list']['house_name'][$k] = RentHouse::where('id',$v)->pluck('property_name')->first();
                }
            }
            $data['operator_list'] = $res;
            return $this->success('get list success',$data);
        }else{
            return $this->error('3','get list failed');
        }
    }

    /**
     * @description:修改操作员是否禁用
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeOperatorStatus(array $input)
    {
        $user_id = $input['user_id'];
        $model = new Operator();
        $operator_info = $model->where('user_id',$user_id)->where('id',$input['operator_id'])->first();
        if($operator_info->is_use){
            $operator_info->is_use = 0;
            $res = $model->update();
            if($res){
                return $this->success('change operator status success');
            }else{
                return $this->error('3','change operator status failed');
            }
        }else{
            $operator_info->is_use = 1;
            $res = $model->update();
            if($res){
                return $this->success('change operator status success');
            }else{
                return $this->error('3','change operator status failed');
            }
        }
    }
}