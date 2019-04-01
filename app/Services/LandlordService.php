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

class LandlordService extends CommonService
{
    /**
     * @description:房东增加房东信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function addLandlordInformation(array $input)
    {
        //dd($input);
        $user_info = User::where('id',$input['user_id'])->first();
        if(!$user_info->user_role %2 ){
            return $this->error('2','this account is not a landlord role');
        }else{
            $landlord_info = Landlord::where('user_id',$input['user_id'])->where('deleted_at',null)->count();
            if($landlord_info >11){
                return $this->error('3','you only can add 11 landlord information');
            }else{
                $landlord_data = [
                    'user_id'       => $input['user_id'],
                    'landlord_name' => $input['landlord_name'],
                    'headimg'       => $input['headimg'],
                    'first_name'    => $input['first_name'],
                    'middle_name'   => $input['middle_name'],
                    'last_name'     => $input['last_name'],
                    'tex_no'        => $input['tex_no'],
                    'tel'           => $input['tel'],
                    'phone'         => $input['phone'],
                    'email'         => $input['email'],
                    'address'       => $input['address'],
                    'mail_address'  => $input['mail_address'],
                    'mail_code'     => $input['mail_code'],
                    'bank_account'  => $input['bank_account'],
                    'notice'        => $input['notice'],
                    'created_at'    => date('Y-m-d H:i:s',time()),
                ];
                $model = new Landlord();
                $res = $model->insert($landlord_data);
                if(!$res){
                    return $this->error('4','landlord information add failed');
                }else{
                    return $this->success('landlord information add success');
                }
            }
        }
    }


    /**
     * @description:房东修改房东信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function editLandlordInformation(array $input)
    {
        //dd($input);
        $user_info = User::where('id',$input['user_id'])->first();
        if(!$user_info->user_role %2 ){
            return $this->error('2','this account is not a landlord role');
        }else{
            $landlord_info = Landlord::where('user_id',$input['user_id'])->where('id',$input['landlord_id'])->first();
            if(!$landlord_info){
                return $this->error('3','you the landlord information is not ture');
            }else{
                $landlord_data = [
                    'landlord_name' => @$input['landlord_name']?$input['landlord_name']:$landlord_info->landlord_name,
                    'headimg'       => @$input['headimg']?$input['headimg']:$landlord_info->headimg,
                    'first_name'    => @$input['first_name']?$input['first_name']:$landlord_info->first_name,
                    'middle_name'   => @$input['middle_name']?$input['middle_name']:$landlord_info->middle_name,
                    'last_name'     => @$input['last_name']?$input['last_name']:$landlord_info->last_name,
                    'tex_no'        => @$input['tex_no']?$input['tex_no']:$landlord_info->tex_no,
                    'tel'           => @$input['tel']?$input['tel']:$landlord_info->tel,
                    'phone'         => @$input['phone']?$input['phone']:$landlord_info->phone,
                    'email'         => @$input['email']?$input['email']:$landlord_info->email,
                    'address'       => @$input['address']?$input['address']:$landlord_info->address,
                    'mail_address'  => @$input['mail_address']?$input['mail_address']:$landlord_info->mail_address,
                    'mail_code'     => @$input['mail_code']?$input['mail_code']:$landlord_info->mail_code,
                    'bank_account'  => @$input['bank_account']?$input['bank_account']:$landlord_info->bank_account,
                    'notice'        => @$input['notice']?$input['notice']:$landlord_info->notice,
                    'updated_at'    => date('Y-m-d H:i:s',time()),
                ];
                $model = new Landlord();
                $res = $model->where('id',$input['landlord_id'])->update($landlord_data);
                if(!$res){
                    return $this->error('4','landlord information edit failed');
                }else{
                    return $this->success('landlord information edit success');
                }
            }
        }
    }

    /**
     * @description:房东获得房东列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLandlordList(array $input)
    {
        //dd($input);
        $user_info = User::where('id',$input['user_id'])->first();
        if(!$user_info->user_role %2 ){
            return $this->error('2','this account is not a landlord role');
        }else{
            $landlord_list = Landlord::where('user_id',$input['user_id'])->where('deleted_at',null)->select('id','landlord_name')->get()->toArray();
            if(!$landlord_list){
                return $this->error('3','you not add a landlord ');
            }else{
                return $this->success('get landlord success',$landlord_list);
            }
        }
    }


    /**
     * @description:房东获得房东列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLandlordInformation(array $input)
    {
        //dd($input);
        $user_info = User::where('id',$input['user_id'])->first();
        if(!$user_info->user_role %2 ){
            return $this->error('2','this account is not a landlord role');
        }else{
            $landlord_info = Landlord::where('user_id',$input['user_id'])->where('id',$input['landlord_id'])->first();
            if(!$landlord_info){
                return $this->error('3','you not add a land lord ');
            }else{
                return $this->success('get land lord success',$landlord_info);
            }
        }
    }


    /**
     * @description:房东获得房东列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteLandlordInformation(array $input)
    {
        //dd($input);
        $user_info = User::where('id',$input['user_id'])->first();
        if(!$user_info->user_role %2 ){
            return $this->error('2','this account is not a landlord role');
        }else{
            $res = Landlord::where('user_id',$input['user_id'])->where('id',$input['landlord_id'])->update('deleted_at',date('Y-m-d H:i:s',time()));
            if(!$res){
                return $this->error('3','deleted  land lord ');
            }else{
                return $this->success('delete land lord success');
            }
        }
    }


}