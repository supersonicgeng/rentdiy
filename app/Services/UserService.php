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
use App\Model\Order;
use App\Model\Passport;
use App\Model\PassportReward;
use App\Model\PassportStore;
use App\Model\Plant;
use App\Model\PlantOperateLog;
use App\Model\Providers;
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
use Illuminate\Support\Facades\Validator;

class UserService extends CommonService
{
    /**
     * @description:用户注册
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function Register(array $input)
    {
        //dd($input);
        $model = new \App\Model\User();
        $account = $input['account'];
        $password = $input['password'];
        $r_password = $input['r_password'];
        $verify_code = $input['verify_code'];
        $user_role = $input['user_role'];
        $house_number = $input['house_number'];
        $jobs = $input['jobs'];
        $jobs = implode(',',$jobs);
        // 确认密码和密码一致性
        if($password != $r_password){
            return $this->error('2','the confirm_password is not match password ,pls try again');
        }
        // 验证账号唯一
        if(strpos($account,'@') ){ // 邮箱注册
            $register = $model->where('e_mail',$account)->first();
            if($register){
                return $this->error('3','this account already register');
            }
        }else{ // 手机注册
            $register = $model->where('phone',$account)->first();
            if($register){
                return $this->error('3','this account already register');
            }
        }
        // 验证验证码
        $verify = $this->verify($account,$verify_code,1);
        if($verify['code'] != 0){
            return $this->error($verify['code'],$verify['msg']);
        }
        if($user_role == 1){ //房东注册
            if(strpos($account,'@') ){ // 邮箱注册
                $data = [
                    'e_mail'        => $account,
                    'user_role'     => 1,
                    'house_number'  => $house_number,
                    'password'      => md5($password),
                    'created_at'    => date('Y-m-d H:i:s', time()),
                ];
            }else{ // 手机注册
                $data = [
                    'phone'         => $account,
                    'user_role'     => 1,
                    'house_number'  => $house_number,
                    'password'      => md5($password),
                    'created_at'    => date('Y-m-d H:i:s', time()),
                ];
            }
        }elseif ($user_role == 2){ //服务商注册
            if(strpos($account,'@') ){ // 邮箱注册
                $data = [
                    'e_mail'        => $account,
                    'user_role'     => 2,
                    'jobs'          => $jobs,
                    'password'      => md5($password),
                    'created_at'    => date('Y-m-d H:i:s', time()),
                ];
            }else{ // 手机注册
                $data = [
                    'phone'         => $account,
                    'user_role'     => 2,
                    'jobs'          => $jobs,
                    'password'      => md5($password),
                    'created_at'    => date('Y-m-d H:i:s', time()),
                ];
            }
        }else{
            if(strpos($account,'@') ){ // 邮箱注册
                $data = [
                    'e_mail'        => $account,
                    'user_role'     => 4,
                    'password'      => md5($password),
                    'created_at'    => date('Y-m-d H:i:s', time()),
                ];
            }else{ // 手机注册
                $data = [
                    'phone'         => $account,
                    'user_role'     => 4,
                    'password'      => md5($password),
                    'created_at'    => date('Y-m-d H:i:s', time()),
                ];
            }
        }

        $res = $model->insert($data);
        if($res){
            return $this->success('register OK');
        }else{
            return $this->error(3,'register failed');
        }

    }


    /**
     * @description:验证验证码
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify($account,$code,$verify_type)
    {
        if($info = Verify::where('account',$account)->where('verify_type',$verify_type)->where('code',$code)->first()){ //查是否有验证码记录
            if($info->verify_status == 2){ // 是否使用
                return $this->error(4,'the verify code has been used');
            }
            if((time() > strtotime($info->expire_time)) ){ // 是否超期
                return $this->error(2,'the verify code has expired');
            }
            $info->verify_status = 2;
            $info->update();
            return $this->success('verify OK');
        }else{
            return $this->error(1,'not have this verify code');
        }
    }


    /**
     * @description:用户登录
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function Login(array $input)
    {
        $account = $input['account'];
        $password = $input['password'];
        // 验证账号
        $model = new \App\Model\User();
        if(strpos($account,'@') ) { // 邮箱登陆
            $res = $model->where('e_mail',$account)->first();
            if(!$res){
                return $this->error('2','this email is not register');
            }elseif ($res->password != md5($password)){
                return $this->error('3','the password is wrong');
            }else{
                $token = md5($res->id.time().mt_rand(100,999));
                $res->login_token = $token; //生成token
                $res->login_expire_time = date('Y-m-d H:i:s',time()+7200);
                $res->update();
                if($res->user_role % 2){
                    $res1 = Landlord::where('user_id',$res->id)->where('deleted_at',null)->select('id as landlord_id','landlord_name')->get()->toArray();
                }
                if($res->user_role >= 4){
                    $res2 = Tenement::where('user_id',$res->id)->where('deleted_at',null)->select('id as tenement_id')->get()->toArray();
                }
                if($res->user_role == 2 || $res->user_role == 3 || $res->user_role == 6 || $res->user_role == 7){
                    $res3 = Providers::where('user_id',$res->id)->where('deleted_at',null)->select('id as service_id','service_name')->get()->toArray();
                }
                dd($res2);
                if(!$res1){
                    $res['landlord_info'] = [
                        'landlord_id'   => '',
                        'landlord_name' => '',
                    ];
                }else{
                    $res['landlord_info'] = $res1;
                }
                if(!$res2){
                    $res['tenement_info'] = [
                        'tenement_id'   => '',
                    ];
                }else{
                    $res['tenement_info'] = $res2;
                }
                if(!$res3){
                    $res['providers_info'] = [
                        'providers_id'   => '',
                        'providers_name' => '',
                    ];
                }else{
                    $res['providers_info'] = $res3;
                }
                $res = $res->toArray();
                return $this->success('login OK',$res);
            }
        }else{
            $res = $model->where('phone',$account)->first();
            if(!$res){
                return $this->error('4','this phone is not register');
            }elseif ($res->password != md5($password)){
                return $this->error('3','the password is wrong');
            }else{
                $token = md5($res->id.time().mt_rand(100,999));
                $res->login_token = $token; // 生成token
                $res->login_expire_time = date('Y-m-d H:i:s',time()+7200);
                $res->update();
                if($res->user_role % 2){
                    $res['landlord_info'] = Landlord::where('user_id',$res->id)->where('deleted_at',null)->select('id as landlord_id','landlord_name')->get()->toArray();
                }
                if($res->user_role >= 4){
                    $res['tenement_info'] = Tenement::where('user_id',$res->id)->where('deleted_at',null)->select('id as tenement_id')->get()->toArray();
                }
                if($res->user_role == 2 || $res->user_role == 3 || $res->user_role == 6 || $res->user_role == 7){
                    $res['providers_info'] = Providers::where('user_id',$res->id)->where('deleted_at',null)->select('id as service_id','service_name')->get()->toArray();
                }
                $res = $res->toArray();
                return $this->success('login OK',$res);
            }
        }
    }

    /**
     * @description:修改密码
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(array $input)
    {
        $account = $input['account'];
        $password = $input['password'];
        $change_password = $input['change_password'];
        $r_password = $input['r_password'];
        // 验证账号
        $model = new \App\Model\User();
        if(strpos($account,'@') ) { // 邮箱修改
            $res = $model->where('e_mail',$account)->first();
            if(!$res){
                return $this->error('2','this email is not register');
            }elseif ($res->password != md5($password)){
                return $this->error('3','the password is wrong');
            }else{
                if($change_password != $r_password){
                    return $this->error('5','the confirm_password is not match password ,pls try again');
                }else{
                    $res->password = md5($change_password);
                    $res->update();
                    return $this->success('change password OK');
                }
            }
        }else{ // 手机修改
            $res = $model->where('phone',$account)->first();
            if(!$res){
                return $this->error('4','this phone is not register');
            }elseif ($res->password != md5($password)){
                return $this->error('3','the password is wrong');
            }else{
                if($change_password != $r_password){
                    return $this->error('5','the confirm_password is not match password ,pls try again');
                }else{
                    $res->password = md5($change_password);
                    $res->update();
                    return $this->success('change password OK');
                }
            }
        }
    }


    /**
     * @description:忘记密码
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgetPassword(array $input)
    {
        $account = $input['account'];
        $change_password = $input['change_password'];
        $r_password = $input['r_password'];
        $model = new \App\Model\User();
        if(strpos($account,'@')){
            $res = $model->where('e_mail',$account)->first();
            if(!$res){
                return $this->error('2','this email is not register');
            }else{
                if($change_password != $r_password){
                    return $this->error('5','the confirm_password is not match password ,pls try again');
                }else{
                    $res->password = md5($change_password);
                    $res->update();
                    return $this->success('change password OK');
                }
            }
        }else{
            $res = $model->where('phone',$account)->first();
            if(!$res){
                return $this->error('2','this email is not register');
            }else{
                if($change_password != $r_password){
                    return $this->error('5','the confirm_password is not match password ,pls try again');
                }else{
                    $res->password = md5($change_password);
                    $res->update();
                    return $this->success('change password OK');
                }
            }
        }
    }

    /**
     * @description:用户获得各角色下的角色id
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserRoleId(array $input)
    {
        $user_id = $input['user_id'];
        $user_info = \App\Model\User::where('id',$user_id)->first();
        if($user_info->user_role % 2){
            $res['landlord_info'] = Landlord::where('user_id',$user_id)->where('deleted_at',null)->select('id as landlord_id','landlord_name')->get()->toArray();
        }
        if($user_info->user_role >= 4){
            $res['tenement_info'] = Tenement::where('user_id',$user_id)->where('deleted_at',null)->select('id as tenement_id')->get()->toArray();
        }
        if($user_info->user_role == 2 || $user_info->user_role == 3 || $user_info->user_role == 6 || $user_info->user_role == 7){
            $res['providers_info'] = Providers::where('user_id',$user_id)->where('deleted_at',null)->select('id as service_id','service_name')->get()->toArray();
        }
        return $this->success('get role id success',$res);
    }


    /**
     * @description:成为房东
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function becomeLandlord(array $input)
    {
        $user_id = $input['user_id'];
        $user_info = \App\Model\User::where('id',$user_id)->first();
        if($user_info->user_role % 2){
            return $this->error('3','you already a landlord role');
        }
        $house_number = $input['house_number'];
        $user_info->house_number = $house_number;
        $user_info->user_role = $user_info->user_role+1;
        if($user_info->user_role > 7){
            return $this->error('4','update wrong');
        }
        $user_info->save();
        return $this->success('become landlord success');
    }

    /**
     * @description:成为服务商
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function becomeProviders(array $input)
    {
        $user_id = $input['user_id'];
        $user_info = \App\Model\User::where('id',$user_id)->first();
        if($user_info->user_role == 2 || $user_info->user_role == 3 || $user_info->user_role == 6 || $user_info->user_rolee == 7){
            return $this->error('3','you already a providers role');
        }
        $jobs = $input['jobs'];
        $jobs = implode(',',$jobs);
        $user_info->jobs = $jobs;
        $user_info->user_role = $user_info->user_role+2;
        if($user_info->user_role > 7){
            return $this->error('4','update wrong');
        }
        $user_info->save();
        return $this->success('become providers success');
    }

    /**
     * @description:成为租客
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function becomeTenement(array $input)
    {
        $user_id = $input['user_id'];
        $user_info = \App\Model\User::where('id',$user_id)->first();
        if($user_info->user_role >= 4){
            return $this->error('3','you already a tenement role');
        }
        $user_info->user_role = $user_info->user_role+4;
        if($user_info->user_role > 7){
            return $this->error('4','update wrong');
        }
        $user_info->save();
        return $this->success('become tenement success');
    }


    /**
     * @description:更新头像
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateHeadImg(array $input)
    {
        $user_id = $input['user_id'];
        $user_info = \App\Model\User::where('id',$user_id)->first();
        $user_info->head_img = $input['head_img'];
        $user_info->save();
        return $this->success('update head img success');
    }


    /**
     * @description:增加手机
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function addPhone(array $input)
    {
        $user_id = $input['user_id'];
        $user_info = \App\Model\User::where('id',$user_id)->first();
        if($user_info->phone){
            return $this->error('2','you account already have a phone');
        }
        $phone = $input['account'];
        if(\App\Model\User::where('phone',$phone)->first()){
            return $this->error('3','this phone already register');
        }
        $verify_code = $input['verify_code'];
        // 验证验证码
        $verify = $this->verify($phone,$verify_code,1);
        if($verify['code'] != 0){
            return $this->error($verify['code'],$verify['msg']);
        }
        $user_info->phone = $phone;
        $user_info->save();
        return $this->success('add phone success');
    }


    /**
     * @description:增加邮箱
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function addEmail(array $input)
    {
        $user_id = $input['user_id'];
        $user_info = \App\Model\User::where('id',$user_id)->first();
        if($user_info->e_mail){
            return $this->error('2','you account already have a email');
        }
        $e_mail = $input['account'];
        if(\App\Model\User::where('e_mail',$e_mail)->first()){
            return $this->error('3','this phone already register');
        }
        $verify_code = $input['verify_code'];
        // 验证验证码
        $verify = $this->verify($e_mail,$verify_code,1);
        if($verify['code'] != 0){
            return $this->error($verify['code'],$verify['msg']);
        }
        $user_info->e_mail = $e_mail;
        $user_info->save();
        return $this->success('add e_mail success');
    }
}