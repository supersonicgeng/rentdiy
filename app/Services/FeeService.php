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
    public function addFee(array $input)
    {
        $model = new RentArrears();
        if(isset($input['contract_id'])){
            $rent_house_id = RentContract::where('id',$input['contract_id'])->pluck('house_id')->first();
            $tenement_info = ContractTenement::where('contract_id',$input['contract_id'])->first();
            $rent_house_info = RentHouse::where('id',$rent_house_id)->first();
            $fee_data = [
                'contract_id'       => $input['contract_id'],
                'contract_sn'       => $input['contract_sn'],
                'rent_house_id'     => $rent_house_id,
                'tenement_id'       => $tenement_info->tenement_id,
                'tenement_name'     => $tenement_info->tenement_full_name,
                'tenement_email'    => $tenement_info->tenement_email,
                'tenement_phone'    => $tenement_info->tenement_phone,
                'arrears_type'      => 3,
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
                'created_at'        => date('Y-m-d H:i:s',time()),
            ];
            $res = $model->insert($fee_data);
        }else{
            $rent_house_id = $input['rent_house_id'];
            $rent_house_info = RentHouse::where('id',$rent_house_id)->first();
            $fee_data = [
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
            $data['contract_list'] = $res;
            return $this->success('get contract list success');
        }else{
            return $this->error('2','get contract list failed');
        }
    }

}