<?php
/**
 * 帮助服务层
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1 0001
 * Time: 下午 4:25
 */

namespace App\Services;


use App\Lib\Util\QueryPager;
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

class HelpService extends CommonService
{
    /**
     * @description:发送邮件验证码
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMailVerify(array $input)
    {
        $model = new Verify();
        @$expire_time = $model->where('account',$input['account'])->where('verify_type',$input['verify_type'])->orderBy('id','desc')->first()->expire_time;
        if(0 <strtotime($expire_time)-time() && strtotime($expire_time)-time() <10){
            return $this->error('2','the verify is send to you email pls check your email');
        }
        $to = $input['account']; //接受验证码邮箱
        $code = rand_string(4,1); //生成验证码
        //将验证码信息存入verify表中
        $data = [
            'account'       => $input['account'],
            'verify_type'   => $input['verify_type'],
            'code'          => $code,
            'verify_status' => 1,
            'expire_time'   => date('Y-m-d H:i:s',time()+300),
        ];
        $res = Verify::insert($data);
        if($res){
            $subject = 'Verify mail';
            Mail::send('email.verify',['code' => $code],function($code) use($to,$subject){
                $code->to($to)->subject($subject);
            });
            return $this->success('verify_code send success',['code' => $code]);
        }else{
            return $this->error('3','send verify failed pls try again');
        }


    }

    /**
     * @description:发送手机验证码
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendPhoneVerify(array $input)
    {
        $model = new Verify();
        @$expire_time = $model->where('account',$input['account'])->where('verify_type',$input['verify_type'])->orderBy('id','desc')->first()->expire_time;
        if(0 <strtotime($expire_time)-time() && strtotime($expire_time)-time() <10){
            return $this->error('2','the verify is send to you email pls check your email');
        }
        // TODO 手机发送验证码
        //$to = $input['account']; //接受验证码邮箱
        $code = rand_string(4,1); //生成验证码
        //将验证码信息存入verify表中
        $data = [
            'account'       => $input['account'],
            'verify_type'   => $input['verify_type'],
            'code'          => $code,
            'verify_status' => 1,
            'expire_time'   => date('Y-m-d H:i:s',time()+300),
        ];
        $res = Verify::insert($data);
        if($res){
            /*$subject = 'Verify mail';
            Mail::send('email.verify',['code' => $code],function($code) use($to,$subject){
                $code->to($to)->subject($subject);
            });*/
            return $this->success('verify_code send success',['code' => $code]);
        }else{
            return $this->error('3','send verify failed pls try again');
        }
    }



    /**
     * @description:获得州信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRegion()
    {
        $res = DB::table('region')->where('level',1)->get(['region_name','region_number as region_id']);
        if($res){
            return $this->success('region get success',$res);
        }else{
            return $this->error('2','region get failed');
        }
    }


    /**
     * @description:获得市信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTa($region_id)
    {
        $res = DB::table('region')->where('level',2)->where('super_number',$region_id)->get(['region_name as ta_name','region_number as ta_id']);
        if($res){
            return $this->success('ta get success',$res);
        }else{
            return $this->error('2','ta get failed');
        }
    }

       /**
     * @description:获得区信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDistrict($ta_id)
    {
        $res = DB::table('region')->where('level',3)->where('super_number',$ta_id)->get(['region_name as district_name','region_number as district_id']);
        if($res){
            return $this->success('district get success',$res);
        }else{
            return $this->error('2','district get failed');
        }
    }


    /**
     * @description:获得区信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLat(array $input)
    {
        $addres = $input['address'].','.$input['district_name'].','.$input['ta_name'].','.$input['region_name'];
        $request = 'https://maps.google.com/maps/api/geocode/json?address='.$addres.'&sensor=true_or_false&key=AIzaSyArr-6TU1Je2fy8opX3qFcSlUQiaD7mK2g';
        $res = file_get_contents($request);
        if($res){
            return $this->success('get lat success',$res);
        }else{
            return $this->error('2','district get failed');
        }
    }


}