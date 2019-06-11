<?php
/**
 * 费用单服务层
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
use App\Model\Tenement;
use App\Model\TenementNote;
use App\Model\Verify;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class FeeService extends CommonService
{
    /**
     * @description:添加费用单
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function feeAdd(array $input)
    {
        $model = new RentArrears();
        if($input['contract_id']){
            $rent_house_id = RentContract::where('id',$input['contract_id'])->pluck('house_id')->first();
            $tenement_info = ContractTenement::where('contract_id',$input['contract_id'])->first();
            $rent_house_info = RentHouse::where('id',$rent_house_id)->first();
            $fee_data = [
                'user_id'           => $input['user_id'],
                'contract_id'       => $input['contract_id'],
                'contract_sn'       => $input['contract_sn'],
                'rent_house_id'     => $rent_house_id,
                'tenement_id'       => $tenement_info->tenement_id,
                'tenement_name'     => $tenement_info->tenement_full_name,
                'tenement_email'    => $tenement_info->tenement_email,
                'tenement_phone'    => $tenement_info->tenement_phone,
                'arrears_type'      => 3,
                'property_name'     => $rent_house_info->property_name,
                'arrears_fee'       => ($input['number']*$input['unit_price'])*(1-$input['discount']/100)*(1+$input['tex']/100),
                'is_pay'            => 1,
                'pay_fee'           => 0,
                'need_pay_fee'      => ($input['number']*$input['unit_price'])*(1-$input['discount']/100)*(1+$input['tex']/100),
                'number'            => $input['number'],
                'unit_price'        => $input['unit_price'],
                'subject_code'      => $input['subject_code'],
                'tex'               => $input['tex'],
                'discount'          => $input['discount'],
                'items_name'        => $input['items_name'],
                'describe'          => $input['describe'],
                'expire_date'       => date('Y-m-d ',time()+3600*24*8),
                'District'          => $rent_house_info->District,
                'TA'                => $rent_house_info->TA,
                'Region'            => $rent_house_info->Region,
                'upload_url'        => $input['upload_url'],
                'created_at'        => date('Y-m-d H:i:s',time()),
            ];
            $res = $model->insert($fee_data);
        }else{
            $rent_house_id = $input['rent_house_id'];
            $rent_house_info = RentHouse::where('id',$rent_house_id)->first();
            $fee_data = [
                'user_id'           => $input['user_id'],
                'rent_house_id'     => $rent_house_id,
                'arrears_type'      => 4,
                'property_name'     => $rent_house_info->property_name,
                'arrears_fee'       => ($input['number']*$input['unit_price'])*(1-$input['discount'])*(1+$input['tex']),
                'is_pay'            => 1,
                'pay_fee'           => 0,
                'need_pay_fee'      => ($input['number']*$input['unit_price'])*(1-$input['discount'])*(1+$input['tex']),
                'number'            => $input['number'],
                'unit_price'        => $input['unit_price'],
                'subject_code'      => $input['subject_code'],
                'tex'               => $input['tex'],
                'discount'          => $input['discount'],
                'items_name'        => $input['items_name'],
                'describe'          => $input['describe'],
                'expire_date'       => date('Y-m-d ',time()+3600*24*8),
                'District'          => $rent_house_info->District,
                'TA'                => $rent_house_info->TA,
                'Region'            => $rent_house_info->Region,
                'upload_url'        => $input['upload_url'],
                'created_at'        => date('Y-m-d H:i:s',time()),
            ];
            $res = $model->insert($fee_data);
        }
        if(!$res){
            return $this->error('2','add rent fee failed');
        }else{
            return $this->success('add rent fee success');
        }
    }



    /**
     * @description:获得租约列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getContractList(array $input)
    {
        $model = new RentContract();
        $res = $model->where('user_id',$input['user_id'])->select('id','contract_id')->get();
        if($res){
            $res = $res->toArray();
            $data['contract_list'] = $res;
            return $this->success('get contract list success',$data);
        }else{
            return $this->error('2','get contract list failed');
        }
    }


    /**
     * @description:发送通知
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendNotice(array $input)
    {

    }



    /**
     * @description:追欠款清单
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function arrearsList(array $input)
    {
        $model = new RentArrears();
        if($input['property_name']){
            $model = $model->where('property_name','like','%'.$input['property_name'].'%');
        }
        if($input['District']){
            $model = $model->where('District',$input['District']);
        }
        if($input['TA']){
            $model = $model->where('TA',$input['TA']);
        }
        if($input['Region']){
            $model = $model->where('Region',$input['Region']);
        }
        $model = $model->whereIn('arrears_type',[1,2,3]);
        $count = $model->where('user_id',$input['user_id'])->pluck('contract_id')->groupBy('contract_id');
        $count = count($count);
        if($count <= ($input['page']-1)*10){
            return $this->error('2','no more fee information');
        }else{
            static $total_arrears_all = 0;
            static $total_rent_all = 0;
            static $paid_all = 0;
            static $rent_arrears_all = 0;
            static $other_arrears_all = 0;
            $res = $model->where('user_id',$input['user_id'])->offset(($input['page']-1)*10)->limit(10)->select('contract_id')->gropuBy('contract_id')->toArray();
            dd($res);
            foreach ($res as $k => $v){
                $fee_res = $model->where('contract_id',$v)->get()->toArray();
                $fee_count = count($fee_res);
                $fee_list[$k]['tenement_name'] = $fee_res[0]['tenement_name'];
                $fee_list[$k]['tenement_email'] = $fee_res[0]['tenement_email'];
                $fee_list[$k]['property_name'] = $fee_res[0]['property_name'];
                $fee_list[$k]['total_stay'] = $fee_res[0]['tenement_name'];
                $fee_list[$k]['contract_sn'] = $fee_res[0]['contract_sn'];
                $fee_list[$k]['contract_id'] = $fee_res[0]['contract_id'];
                $fee_list[$k]['rent_per_week'] = RentHouse::where('id',$fee_res[0]['rent_house_id'])->pluck('rent_fee_pre_week')->first();
                $fee_list[$k]['expire_date'] = $fee_res[$fee_count-1]['expire_date'];
                static $total_arrears = 0;
                static $total_rent = 0;
                static $paid = 0;
                static $rent_arrears = 0;
                static $other_arrears = 0;
                foreach ($fee_res as $key => $value){
                    if($value['arrears_type'] == 1 || $value['arrears_type'] == 2 || $value['arrears_type'] == 3){
                        $total_arrears += $value['need_pay_fee'];
                        $total_rent += $value['arrears_fee'];
                        $paid += $value['pay_fee'];
                        if($value['arrears_type'] == 2){
                            $rent_arrears += $value['need_pay_fee'];
                        }elseif($value['arrears_type'] == 1 || $value['arrears_type'] == 3){
                            $other_arrears += $value['need_pay_fee'];
                        }
                    }
                }
                $fee_list[$k]['total_arrears'] = $total_arrears;
                $fee_list[$k]['total_rent'] = $total_rent;
                $fee_list[$k]['paid'] = $paid;
                $fee_list[$k]['rent_arrears'] = $rent_arrears;
                $fee_list[$k]['other_arrears'] = $other_arrears;
                $total_arrears_all += $total_arrears;
                $total_rent_all += $total_rent;
                $paid_all += $paid;
                $rent_arrears_all += $rent_arrears;
                $other_arrears_all += $other_arrears;
            }
            $data['fee_list'] = $fee_list;
            $data['total_arrears_all'] = $total_arrears_all;
            $data['total_rent_all'] = $total_rent_all;
            $data['paid_all'] = $paid_all;
            $data['rent_arrears_all'] = $rent_arrears_all;
            $data['other_arrears_all'] = $other_arrears_all;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/10);
            return $this->success('get arrears success',$data);
        }
    }



    /**
     * @description:追欠款详情
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function arrearsDetail(array $input)
    {
        $model = new RentArrears();
        $count = $model->where('user_id',$input['user_id'])->where('contract_id',$input['contract_id'])->whereIn('is_pay',[1,3])->get();
        $count = count($count);
        if($count <= ($input['page']-1)*4){
            return $this->error('2','no more fee information');
        }else{
            $tenement_id = ContractTenement::where('contract_id',$input['contract_id'])->pluck('tenement_id')->first();
            $data['tenement_info'] = Tenement::where('id',$tenement_id)->select('tenement_id','phone','mobile','email')->first();
            $fee_detail = $model->where('user_id',$input['user_id'])->where('contract_id',$input['contract_id'])->whereIn('is_pay',[1,3])->offset(($input['page']-1)*4)
                ->limit(4)->get()->toArray();
            $data['fee_detail'] = $fee_detail;
            $data['tenement_note'] = TenementNote::where('user_id',$input['user_id'])->where('tenement_id',$tenement_id)->get()->toArray();
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/4);
            return $this->success('get arrears success',$data);
        }
    }


    /**
     * @description:费用单清单
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function feeList(array $input)
    {
        $model = new RentArrears();
        if($input['tenement_name']){
            $model = $model->where('tenement_name','like','%'.$input['property_name'].'%');
        }
        if($input['invoice_date']){
            $model = $model->where('created_at','>',date('Y-m-d H:i:s',strtotime($input['invoice_date'])))->where('created_at','<',date('Y-m-d H:i:s',strtotime($input['invoice_date'].'+ 1 days')));
        }
        if($input['TA']){
            $model = $model->where('TA',$input['TA']);
        }
        if($input['Region']){
            $model = $model->where('Region',$input['Region']);
        }
        $model = $model->whereIn('arrears_type',[1,2,3]);
        $count = $model->where('user_id',$input['user_id'])->pluck('contract_id')->groupBy('contract_id');
        $count = count($count);
        if($count <= ($input['page']-1)*10){
            return $this->error('2','no more fee information');
        }else{
            static $total_arrears_all = 0;
            static $total_rent_all = 0;
            static $paid_all = 0;
            static $rent_arrears_all = 0;
            static $other_arrears_all = 0;
            $res = $model->where('user_id',$input['user_id'])->offset(($input['page']-1)*10)->limit(10)->pluck('contract_id')->groupBy('contract_id');
            foreach ($res as $k => $v){
                $fee_res = $model->where('contract_id',$v)->get()->toArray();
                $fee_count = count($fee_res);
                $fee_list[$k]['tenement_name'] = $fee_res[0]['tenement_name'];
                $fee_list[$k]['tenement_email'] = $fee_res[0]['tenement_email'];
                $fee_list[$k]['property_name'] = $fee_res[0]['property_name'];
                $fee_list[$k]['total_stay'] = $fee_res[0]['tenement_name'];
                $fee_list[$k]['contract_sn'] = $fee_res[0]['contract_sn'];
                $fee_list[$k]['contract_id'] = $fee_res[0]['contract_id'];
                $fee_list[$k]['rent_per_week'] = RentHouse::where('id',$fee_res[0]['rent_house_id'])->pluck('rent_fee_pre_week')->first();
                $fee_list[$k]['expire_date'] = $fee_res[$fee_count-1]['expire_date'];
                static $total_arrears = 0;
                static $total_rent = 0;
                static $paid = 0;
                static $rent_arrears = 0;
                static $other_arrears = 0;
                foreach ($fee_res as $key => $value){
                    if($value['arrears_type'] == 1 || $value['arrears_type'] == 2 || $value['arrears_type'] == 3){
                        $total_arrears += $value['need_pay_fee'];
                        $total_rent += $value['arrears_fee'];
                        $paid += $value['pay_fee'];
                        if($value['arrears_type'] == 2){
                            $rent_arrears += $value['need_pay_fee'];
                        }elseif($value['arrears_type'] == 1 || $value['arrears_type'] == 3){
                            $other_arrears += $value['need_pay_fee'];
                        }
                    }
                }
                $fee_list[$k]['total_arrears'] = $total_arrears;
                $fee_list[$k]['total_rent'] = $total_rent;
                $fee_list[$k]['paid'] = $paid;
                $fee_list[$k]['rent_arrears'] = $rent_arrears;
                $fee_list[$k]['other_arrears'] = $other_arrears;
                $total_arrears_all += $total_arrears;
                $total_rent_all += $total_rent;
                $paid_all += $paid;
                $rent_arrears_all += $rent_arrears;
                $other_arrears_all += $other_arrears;
            }
            $data['fee_list'] = $fee_list;
            $data['total_arrears_all'] = $total_arrears_all;
            $data['total_rent_all'] = $total_rent_all;
            $data['paid_all'] = $paid_all;
            $data['rent_arrears_all'] = $rent_arrears_all;
            $data['other_arrears_all'] = $other_arrears_all;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/10);
            return $this->success('get arrears success',$data);
        }
    }



    /**
     * @description:费用单详情
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function feeDetail(array $input)
    {
        $model = new RentArrears();
        $count = $model->where('user_id',$input['user_id'])->where('contract_id',$input['contract_id'])->whereIn('is_pay',[1,3])->get();
        $count = count($count);
        if($count <= ($input['page']-1)*4){
            return $this->error('2','no more fee information');
        }else{
            $tenement_id = ContractTenement::where('contract_id',$input['contract_id'])->pluck('tenement_id')->first();
            $data['tenement_info'] = Tenement::where('id',$tenement_id)->select('tenement_id','phone','mobile','email')->first();
            $fee_detail = $model->where('user_id',$input['user_id'])->where('contract_id',$input['contract_id'])->whereIn('is_pay',[1,3])->offset(($input['page']-1)*4)
                ->limit(4)->get();
            $data['fee_detail'] = $fee_detail;
            $data['tenement_note'] = TenementNote::where('user_id',$input['user_id'])->where('tenement_id',$tenement_id)->get()->toArray();
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/4);
            return $this->success('get arrears success',$data);
        }
    }
}