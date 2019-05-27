<?php
/**
 * 押金服务层
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1 0001
 * Time: 下午 4:25
 */

namespace App\Services;


use App\Lib\Util\QueryPager;
use App\Model\Bond;
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

class BondService extends CommonService
{
    /**
     * @description:押金列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function bondList(array $input)
    {
        $model = new Bond();
        if($input['bond_status']){
            $model = $model->where('bond_status',$input['bond_status']);
        }
        if($input['property_name']){
            $model = $model->where('property_name','like', '%'.$input['property_name'].'%');
        }
        if($input['start_date']){
            $model = $model->where('created_at','>',$input['start_date']);
        }
        if($input['end_date']){
            $model = $model->where('created_at','<',$input['end_date']);
        }
        $page = $input['page'];
        $count = $model->where('user_id',$input['user_id'])->count();
        if($count < ($page-1)*10){
            return $this->error('3','no more information');
        }
        $res = $model->where('user_id',$input['user_id'])->offset(($page-1)*10)->limit(10)->get()->toArray();
        $data['bondList'] = $res;
        $data['total_page'] = ceil($count/10);
        $data['current_page'] = $page;
        return $this->success('get bond list success',$data);
    }


    /**
     * @description:押金上缴
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function addBondLodgedDate(array $input)
    {
        $model = new Bond();
        $lodged_date = $input['lodged_date'];
        $bond_id = $input['bond_id'];
        $lodged_data = [
            'lodged_date'   => $lodged_date,
            'updated_at'    => date('Y-m-d H:i:s',time()),
        ];
        $res = $model->where('id',$bond_id)->update($lodged_data);
        if($res){
            return $this->success('add bond lodged date success');
        }else{
            return $this->error('2','add bond lodged date failed');
        }
    }



    /**
     * @description:押金上缴
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function addBondLodgedSn(array $input)
    {
        $model = new Bond();
        $bond_sn = $input['bond_sn'];
        $bond_id = $input['bond_id'];
        $lodged_data = [
            'bond_sn'       => $bond_sn,
            'bond_status'   => 2,
            'updated_at'    => date('Y-m-d H:i:s',time()),
        ];
        $res = $model->where('id',$bond_id)->update($lodged_data);
        if($res){
            return $this->success('add bond sn success');
        }else{
            return $this->error('2','add bond sn failed');
        }
    }
}