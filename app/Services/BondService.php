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
use App\Model\BondRefund;
use App\Model\BondTransfer;
use App\Model\ContractTenement;
use App\Model\Region;
use App\Model\RentArrears;
use App\Model\RentContact;
use App\Model\RentContract;
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
        $model = new RentArrears();
        if($input['bond_status'] == 2){
            $model = $model->whereIn('bond_status',[1,2,3]);
        }elseif ($input['bond_status'] == 3){
            $model = $model->whereIn('bond_status',[3,4,5,6]);
        }elseif ($input['bond_status'] == 4){
            $model = $model->whereIn('bond_status',[3,7,8,9]);
        }else{
            $model = $model->where('bond_status',1);
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
        $count = $model->where('user_id',$input['user_id'])->where('arrears_type',1)->count();
        if($count < ($page-1)*10){
            return $this->error('3','no more information');
        }
        $res = $model->where('user_id',$input['user_id'])->where('arrears_type',1)->offset(($page-1)*10)->limit(10)->get()->toArray();
        $data['bondList'] = $res;
        $data['total_page'] = ceil($count/10);
        $data['current_page'] = $page;
        return $this->success('get bond list success',$data);
    }


    /**
     * @description:押金欠款列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function bondArrearsList(array $input)
    {
        $model = new RentArrears();
        $model = $model->where('bond_status',1);
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
        $count = $model->where('user_id',$input['user_id'])->where('arrears_type',1)->count();
        if($count < ($page-1)*10){
            return $this->error('3','no more information');
        }
        $res = $model->where('user_id',$input['user_id'])->where('arrears_type',1)->offset(($page-1)*10)->limit(10)->get()->toArray();
        $data['bondList'] = $res;
        $data['total_page'] = ceil($count/10);
        $data['current_page'] = $page;
        return $this->success('get bond list success',$data);
    }

    /**
     * @description:押金欠款列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function bondLodgedList(array $input)
    {
        $model = new RentArrears();
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
        $count = $model->where('user_id',$input['user_id'])->where('arrears_type',1)->count();
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
     * @description:押金退缴列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function bondRefundList(array $input)
    {
        $model = new RentArrears();
        if($input['bond_status'] == 3){
            $model = $model->where('bond_status',3);
        }elseif ($input['bond_status'] == 4){
            $model = $model->where('bond_status',4);
        }elseif ($input['bond_status'] == 5){
            $model = $model->where('bond_status',5);
        }elseif ($input['bond_status'] == 6){
            $model = $model->where('bond_status',6);
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
        $count = $model->where('user_id',$input['user_id'])->where('arrears_type',1)->count();
        if($count < ($page-1)*10){
            return $this->error('3','no more information');
        }
        $res = $model->where('user_id',$input['user_id'])->where('arrears_type',1)->offset(($page-1)*10)->limit(10)->get()->toArray();
        $data['bondList'] = $res;
        $data['total_page'] = ceil($count/10);
        $data['current_page'] = $page;
        return $this->success('get bond list success',$data);
    }


    /**
     * @description:押金转移列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function bondTransformList(array $input)
    {
        $model = new RentArrears();
        if($input['bond_status'] == 2){
            $model = $model->where('bond_status',2);
        }elseif ($input['bond_status'] == 7){
            $model = $model->where('bond_status',7);
        }elseif ($input['bond_status'] == 8){
            $model = $model->where('bond_status',8);
        }elseif ($input['bond_status'] == 9){
            $model = $model->where('bond_status',9);
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
        $count = $model->where('user_id',$input['user_id'])->where('arrears_type',1)->count();
        if($count < ($page-1)*10){
            return $this->error('3','no more information');
        }
        $res = $model->where('user_id',$input['user_id'])->where('arrears_type',1)->offset(($page-1)*10)->limit(10)->get()->toArray();
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
        $model = new RentArrears();
        $lodged_date = $input['lodged_date'];
        $bond_id = $input['bond_id'];
        $lodged_data = [
            'lodged_date'   => $lodged_date,
            'bond_status'   => 2,
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
        $model = new RentArrears();
        $bond_sn = $input['bond_sn'];
        $bond_id = $input['bond_id'];
        $lodged_data = [
            'bond_sn'       => $bond_sn,
            'bond_status'   => 3,
            'updated_at'    => date('Y-m-d H:i:s',time()),
        ];
        $res = $model->where('id',$bond_id)->update($lodged_data);
        if($res){
            return $this->success('add bond sn success');
        }else{
            return $this->error('2','add bond sn failed');
        }
    }

    /**
     * @description:押金退缴信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function refundInfo(array $input)
    {
        $model = new RentArrears();
        $bond_id = $input['bond_id'];
        $contract_id = $model->where('id',$bond_id)->pluck('contract_id')->first();
        $tenement_info = ContractTenement::where('contract_id',$contract_id)->select('tenement_id','tenement_full_name')->get()->toArray();
        foreach ($tenement_info as $k => $v){
            $tenement_info[$k]['tenement_account'] = '';
        }
        $landlord_info = RentContract::where('id',$contract_id)->select('landlord_id','landlord_full_name')->first()->toArray();
        $landlord_info['landlord_account'] = '';
        if($tenement_info && $landlord_info){
            $res['tenement_info'] = $tenement_info;
            $res['landlord_info'] = $landlord_info;
            return $this->success('get refund info success',$res);
        }else{
            return $this->error('2','get refund info failed');
        }
    }


    /**
     * @description:押金退缴
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function refundBond(array $input)
    {
        $model = new BondRefund();
        static $error = 0;
        $tenement_info = $input['tenement_info'];
        foreach ($tenement_info as $k => $v){
            $refund_data = [
                'bond_id'               => $input['bond_id'],
                'tenement_id'           => $v['tenement_id'],
                'tenement_full_name'    => $v['tenement_full_name'],
                'tenement_account'      => $v['tenement_account'],
                'created_at'            => date('Y-m-d H:i:s',time()),
            ];
            $res = $model->insert($refund_data);
            if(!$res){
                $error += 1;
            }
        }
        $landlord_info = $input['landlord_info'];
        foreach ($landlord_info as $k => $v){
            $refund_data = [
                'bond_id'               => $input['bond_id'],
                'landlord_id'           => $v['landlord_id'],
                'landlord_full_name'    => $v['landlord_full_name'],
                'landlord_account'      => $v['landlord_account'],
                'created_at'            => date('Y-m-d H:i:s',time()),
            ];
            $model->insert($refund_data);
            if(!$res){
                $error += 1;
            }
        }
        if(!$error){
            RentArrears::where('id',$input['bond_id'])->update([ 'bond_status'   => 4, 'updated_at'    => date('Y-m-d H:i:s',time()),]);
            return $this->success('bond refund info add success');
        }else{
            return $this->error('2','bond refund info add failed');
        }
    }

    /**
     * @description:押金退缴确认
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function refundBondConfirm(array $input)
    {
        $model = new RentArrears();
        $bond_id = $input['bond_id'];
        $lodged_data = [
            'bond_status'   => 5,
            'updated_at'    => date('Y-m-d H:i:s',time()),
        ];
        $res = $model->where('id',$bond_id)->update($lodged_data);
        if($res){
            return $this->success('bond refund confirm success');
        }else{
            return $this->error('2','bond refund confirm failed');
        }
    }


    /**
     * @description:押金退缴时间
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function refundBondDate(array $input)
    {
        $model = new RentArrears();
        $bond_id = $input['bond_id'];
        $lodged_data = [
            'bond_status'   => 6,
            'refund_date'   => $input['refund_date'],
            'updated_at'    => date('Y-m-d H:i:s',time()),
        ];
        $res = $model->where('id',$bond_id)->update($lodged_data);
        if($res){
            return $this->success('bond refund date update success');
        }else{
            return $this->error('2','bond refund date update failed');
        }
    }

    /**
     * @description:押金退缴
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function transferBond(array $input)
    {
        $model = new BondTransfer();
        static $error = 0;
        $tenement_info = $input['tenement_info'];
        foreach ($tenement_info as $k => $v){
            $refund_data = [
                'bond_id'               => $input['bond_id'],
                'tenement_id'           => $v['tenement_id'],
                'tenement_full_name'    => $v['tenement_full_name'],
                'tenement_account'      => $v['tenement_account'],
                'created_at'            => date('Y-m-d H:i:s',time()),
            ];
            $res = $model->insert($refund_data);
            if(!$res){
                $error += 1;
            }
        }
        $landlord_info = $input['landlord_info'];
        foreach ($landlord_info as $k => $v){
            $refund_data = [
                'bond_id'               => $input['bond_id'],
                'landlord_id'           => $v['landlord_id'],
                'landlord_full_name'    => $v['landlord_full_name'],
                'landlord_account'      => $v['landlord_account'],
                'created_at'            => date('Y-m-d H:i:s',time()),
            ];
            $model->insert($refund_data);
            if(!$res){
                $error += 1;
            }
        }
        if(!$error){
            RentArrears::where('id',$input['bond_id'])->update([ 'bond_status'   => 7, 'updated_at'    => date('Y-m-d H:i:s',time()),]);
            return $this->success('bond transfer info add success');
        }else{
            return $this->error('2','bond transfer info add failed');
        }
    }

    /**
     * @description:押金退缴确认
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function transferBondConfirm(array $input)
    {
        $model = new RentArrears();
        $bond_id = $input['bond_id'];
        $lodged_data = [
            'bond_status'   => 8,
            'updated_at'    => date('Y-m-d H:i:s',time()),
        ];
        $res = $model->where('id',$bond_id)->update($lodged_data);
        if($res){
            return $this->success('bond transfer confirm success');
        }else{
            return $this->error('2','bond transfer confirm failed');
        }
    }


    /**
     * @description:押金退缴时间
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function transferBondDate(array $input)
    {
        $model = new RentArrears();
        $bond_id = $input['bond_id'];
        $lodged_data = [
            'bond_status'   => 9,
            'transfer_date' => $input['transfer_date'],
            'updated_at'    => date('Y-m-d H:i:s',time()),
        ];
        $res = $model->where('id',$bond_id)->update($lodged_data);
        if($res){
            return $this->success('bond transfer date update success');
        }else{
            return $this->error('2','bond transfer date update failed');
        }
    }
}