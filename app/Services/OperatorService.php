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
        $model = new Operator();
        $check_res = $model->where('operator_account',$input['operator_account'])->first();
        if($check_res){
            return $this->error('2','the operator account is used pls change a new account');
        }
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
            'created_at'        => date('Y-m-d H:i:s',time())
        ];
        $res = $model->insertGetId($operator_data);
        static $error = 0;
        if($res){
            foreach ($input['house_list'] as $k => $v)
            $data = [
                'operator_id'   => $res,
                'operator_way'  => $input['operator_way'],
                'house_id'      => $v['house_id'],
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
        // 验证账号
        $model = new Operator();
        $res = $model->where('operator_account',$account)->where('password',md5($password))->first();
        if(!$res){
            return $this->error('2','the account or password was wong');
        }else{
            $token = md5($res->id.time().mt_rand(100,999));
            $user_id = $res->user_id;
            $user_info = \App\Model\User::where('id',$user_id)->first();
            $user_token = $user_info->login_token;
            $user_info->login_expire_time = date('Y-m-d H:i:s',time()+7200);
            $user_info->update();
            $res->login_token = $token; //生成token
            $res->login_expire_time = date('Y-m-d H:i:s',time()+7200);
            $res->update();
            $res = $res->toArray();
            $res['user_token'] = $user_token;
            return $this->success('login OK',$res);
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
            'password'          => @$input['password']?$input['password']:$operator_info->password,
            'role'              => @$input['role']?$input['role']:$operator_info->role,
            'start_date'        => @$input['start_date']?$input['start_date']:$operator_info->start_date,
            'end_date'          => @$input['end_date']?$input['end_date']:$operator_info->end_date,
            'email'             => @$input['email']?$input['email']:$operator_info->email,
            'phone'             => @$input['phone']?$input['phone']:$operator_info->phone,
            'updated_at'        => date('Y-m-d H:i:s',time())
        ];
        $res = $model->where('user_id',$user_id)->where('id',$operator_id)->update($operator_data);
        static $error = 0;
        if($res){
            // 删除已经存在的房屋列表
            OperatorRoom::where('operator_id',$operator_id)->update(['deleted_at'=>date('Y-m-d H:i:s',time())]);
            foreach ($input['house_list'] as $k => $v)
                $data = [
                    'operator_id'   => $operator_id,
                    'house_id'      => $v['house_id'],
                    'operator_way'  => $input['operator_way'],
                    'created_at'    => date('Y-m-d H:i:s',time())
                ];
            $insert_res = OperatorRoom::insert($data);
            if(!$insert_res){
                $error += 1;
            }
            if($res && !$error){
                return $this->success('edit operator information success');
            }else{
                return $this->error('2','edit operator information failed');
            }
        }else{
            return $this->error('2','edit operator information failed');
        }
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
                if($value['role'] %2){
                    $res[$key]['role1'] = 1;
                }else{
                    $res[$key]['role1'] = 0;
                }
                if($value['role']  == 2 || $value['role']  == 3 || $value['role']  == 6 || $value['role'] == 7  || $value['role']  == 10 || $value['role']  == 11 ||$value['role']  == 14 ||$value['role']  == 15){
                    $res[$key]['role2'] = 1;
                }else{
                    $res[$key]['role2'] = 0;
                }
                if($value['role']  == 4 || $value['role']  == 5 || $value['role']  == 6 || $value['role'] == 7  || $value['role']  == 12 || $value['role']  == 13 ||$value['role']  == 14 ||$value['role']  == 15){
                    $res[$key]['role3'] = 1;
                }else{
                    $res[$key]['role3'] = 0;
                }
                if($value['role'] > 7){
                    $res[$key]['role4'] = 1;
                }else{
                    $res[$key]['role4'] = 0;
                }
                foreach ($house_id as $k => $v){
                    $res[$key]['house_list'][$k]['house_id'] = $v;
                    $res[$key]['house_list'][$k]['house_name'] = RentHouse::where('id',$v)->pluck('property_name')->first();
                }
            }
            $data['operator_list'] = $res;
            return $this->success('get list success',$data);
        }else{
            return $this->error('3','get list failed');
        }
    }


    /**
     * @description:获得操作员详情
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOperatorDetail(array $input)
    {
        $user_id = $input['user_id'];
        $operator_id = $input['operator_id'];
        $model = new Operator();
        $res = $model->where('user_id',$user_id)->where('id',$operator_id)->first();
        if($res){
            $res = $res->toArray();
            $house_id = OperatorRoom::where('operator_id',$res['id'])->where('deleted_at',null)->pluck('house_id');
            if($res['role'] %2){
                $res['role1'] = 1;
            }else{
                $res['role1'] = 0;
            }
            if($res['role']  == 2 || $res['role']  == 3 || $res['role']  == 6 || $res['role'] == 7  || $res['role']  == 10 || $res['role']  == 11 ||$res['role']  == 14 ||$res['role']  == 15){
                $res['role2'] = 1;
            }else{
                $res['role2'] = 0;
            }
            if($res['role']  == 4 || $res['role']  == 5 || $res['role']  == 6 || $res['role'] == 7  || $res['role']  == 12 || $res['role']  == 13 ||$res['role']  == 14 ||$res['role']  == 15){
                $res['role3'] = 1;
            }else{
                $res['role3'] = 0;
            }
            if($res['role'] > 7){
                $res['role4'] = 1;
            }else{
                $res['role4'] = 0;
            }
            foreach ($house_id as $k => $v){
                $res['house_list'][$k]['house_id'] = $v;
                $res['house_list'][$k]['house_name'] = RentHouse::where('id',$v)->pluck('property_name')->first();
            }
            $data['operator_detail'] = $res;
            return $this->success('get detail success',$data);
        }else{
            return $this->error('3','get detail failed');
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
            $res = $operator_info->update();
            if($res){
                return $this->success('change operator status success');
            }else{
                return $this->error('3','change operator status failed');
            }
        }else{
            $operator_info->is_use = 1;
            $res = $operator_info->update();
            if($res){
                return $this->success('change operator status success');
            }else{
                return $this->error('3','change operator status failed');
            }
        }
    }

    /**
     * @description:房东操作员查看房屋列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHouseList(array $input)
    {
        $model = new RentHouse();
        $operator_id = $input['operator_id'];
        $user_id = $input['user_id'];
        $room_list = OperatorRoom::where('operator_id',$operator_id)->pluck('house_id');
        $res = $model->where('user_id',$user_id)->whereIn('id',$room_list)->where('deleted_at',null)->select('id','District','TA','Region','group_id','rent_category','property_name','property_type','rent_fee_pre_week','building_area','actual_area','pre_rent','least_rent_time','margin_rent','bedroom_no','bathroom_no','parking_no','garage_no','require_renter','short_words','rent_fee','rent_least_fee','can_party','can_pet','can_smoke','other_rule','address','lat','lon','available_date','is_put')->groupBy('group_id')->offset(($page-1)*9)->limit(9)->get()->toArray();
        if($res){
            foreach ($res as $k => $v){
                $res[$k]['house_pic'] =  RentPic::where('rent_house_id',$v['id'])->where('deleted_at',null)->pluck('house_pic')->toArray();
                $res[$k]['full_address'] = $v['address'].','.Region::getName($v['District']).','.Region::getName($v['TA']).','.Region::getName($v['Region']); //地址
            }
            $data['house_list'] = $res;
            return $this->success('get house list success',$data);
        }else{
            return $this->error('2','get house list failed');
        }
    }

}