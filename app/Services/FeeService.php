<?php
/**
 * 费用单服务层
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1 0001
 * Time: 下午 4:25
 */

namespace App\Services;


use App\Imports\UsersImport;
use App\Lib\Util\QueryPager;
use App\Model\BankCheck;
use App\Model\Bond;
use App\Model\BondRefund;
use App\Model\BondTransfer;
use App\Model\BusinessContract;
use App\Model\ContractTenement;
use App\Model\EntireContract;
use App\Model\FeeReceive;
use App\Model\Landlord;
use App\Model\LandlordOrder;
use App\Model\OperatorRoom;
use App\Model\OrderArrears;
use App\Model\Providers;
use App\Model\Region;
use App\Model\RentArrears;
use App\Model\RentContact;
use App\Model\RentContract;
use App\Model\RentHouse;
use App\Model\RentPic;
use App\Model\SeparateContract;
use App\Model\Tenement;
use App\Model\TenementNote;
use App\Model\Verify;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;
use setasign\Fpdi\PdfParser\StreamReader;

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
        $fee_sn = feeSn();
        foreach ($input['arrears_list'] as $k => $v){
            if($v['arrears_type'] == 3){
                $rent_house_id = RentContract::where('id',$input['contract_id'])->pluck('house_id')->first();
                $contract_sn = RentContract::where('id',$input['contract_id'])->pluck('contract_id')->first();
                $tenement_info = ContractTenement::where('contract_id',$input['contract_id'])->first();
                $rent_house_info = RentHouse::where('id',$rent_house_id)->first();
                $fee_data = [
                    'user_id'           => $input['user_id'],
                    'fee_sn'            => $fee_sn,
                    'contract_id'       => $input['contract_id'],
                    'contract_sn'       => $contract_sn,
                    'rent_house_id'     => $rent_house_id,
                    'tenement_id'       => $tenement_info->tenement_id,
                    'tenement_name'     => $tenement_info->tenement_full_name,
                    'tenement_email'    => $tenement_info->tenement_email,
                    'tenement_phone'    => $tenement_info->tenement_phone,
                    'arrears_type'      => 3,
                    'property_name'     => $rent_house_info->property_name,
                    'arrears_fee'       => ($v['number']*$v['unit_price'])*(1-$v['discount']/100)*(1+$v['tex']/100),
                    'is_pay'            => 1,
                    'pay_fee'           => 0,
                    'need_pay_fee'      => ($v['number']*$v['unit_price'])*(1-$v['discount']/100)*(1+$v['tex']/100),
                    'number'            => $v['number'],
                    'unit_price'        => $v['unit_price'],
                    'subject_code'      => Tenement::where('id',$tenement_info->tenement_id)->pluck('subject_code')->first(),
                    'tex'               => $v['tex'],
                    'discount'          => $v['discount'],
                    'items_name'        => $v['items_name'],
                    'describe'          => $v['describe'],
                    'note'              => $v['note'],
                    'effect_date'       => $input['effect_date'],
                    'expire_date'       => $input['expire_date'],
                    'District'          => $rent_house_info->District,
                    'TA'                => $rent_house_info->TA,
                    'Region'            => $rent_house_info->Region,
                    'upload_url'        => $v['upload_url'],
                    'created_at'        => date('Y-m-d H:i:s',time()),
                ];
                $res = $model->insert($fee_data);
            }else {
                $rent_house_id = RentContract::where('id', $input['contract_id'])->pluck('house_id')->first();
                $contract_sn = RentContract::where('id', $input['contract_id'])->pluck('contract_id')->first();
                $tenement_info = ContractTenement::where('contract_id', $input['contract_id'])->first();
                $rent_house_info = RentHouse::where('id', $rent_house_id)->first();
                $fee_data = [
                    'user_id' => $input['user_id'],
                    'fee_sn'            => $fee_sn,
                    'contract_id' => $input['contract_id'],
                    'contract_sn' => $contract_sn,
                    'rent_house_id' => $rent_house_id,
                    'tenement_id' => $tenement_info->tenement_id,
                    'tenement_name' => $tenement_info->tenement_full_name,
                    'tenement_email' => $tenement_info->tenement_email,
                    'tenement_phone' => $tenement_info->tenement_phone,
                    'arrears_type' => 4,
                    'property_name' => $rent_house_info->property_name,
                    'arrears_fee' => ($v['number'] * $v['unit_price']) * (1 - $v['discount'] / 100) * (1 + $v['tex'] / 100),
                    'is_pay' => 1,
                    'pay_fee' => 0,
                    'need_pay_fee' => ($v['number'] * $v['unit_price']) * (1 - $v['discount'] / 100) * (1 + $v['tex'] / 100),
                    'number' => $v['number'],
                    'unit_price' => $v['unit_price'],
                    'subject_code' => Tenement::where('id', $tenement_info->tenement_id)->pluck('subject_code')->first(),
                    'tex' => $v['tex'],
                    'discount' => $v['discount'],
                    'items_name' => $v['items_name'],
                    'describe' => $v['describe'],
                    'note' => $v['note'],
                    'effect_date'       => $input['effect_date'],
                    'expire_date'       => $input['expire_date'],
                    'District' => $rent_house_info->District,
                    'TA' => $rent_house_info->TA,
                    'Region' => $rent_house_info->Region,
                    'upload_url' => $input['upload_url'],
                    'created_at' => date('Y-m-d H:i:s', time()),
                ];
                $res = $model->insert($fee_data);
            }
        }
        if(!$res){
            return $this->error('2','add rent fee failed');
        }else{
            return $this->success('add rent fee success');
        }
    }


    /**
     * @description:商业费用单获取分摊率
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRate(array $input)
    {
        $model = new RentArrears();
        $contract_id = $input['contract_id'];
        $items_name = $input['items_name'];
        $res = $model->where('contract_id',$contract_id)->where('items_name',$items_name)->orderByDesc('id')->pluck('rate')->first();
        if(!$res){
            $res = 100;
            return $this->success('get rate success',$res);
        }else{
            return $this->success('get rate success',$res);
        }
    }

    /**
     * @description:添加费用单
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function feeAddBusiness(array $input)
    {
        $model = new RentArrears();
        $fee_sn = feeSn();
        foreach ($input['arrears_list'] as $k => $v){
            if($v['arrears_type'] == 3){
                $rent_house_id = RentContract::where('id',$input['contract_id'])->pluck('house_id')->first();
                $contract_sn = RentContract::where('id',$input['contract_id'])->pluck('contract_id')->first();
                $tenement_info = ContractTenement::where('contract_id',$input['contract_id'])->first();
                $rent_house_info = RentHouse::where('id',$rent_house_id)->first();
                $fee_data = [
                    'user_id'           => $input['user_id'],
                    'fee_sn'            => $fee_sn,
                    'contract_id'       => $input['contract_id'],
                    'contract_sn'       => $contract_sn,
                    'rent_house_id'     => $rent_house_id,
                    'tenement_id'       => $tenement_info->tenement_id,
                    'tenement_name'     => $tenement_info->tenement_full_name,
                    'tenement_email'    => $tenement_info->tenement_email,
                    'tenement_phone'    => $tenement_info->tenement_phone,
                    'arrears_type'      => 3,
                    'property_name'     => $rent_house_info->property_name,
                    'arrears_fee'       => ($v['number']*$v['unit_price'])*(1-$v['discount']/100)*(1+$v['tex']/100)*$v['rate']/100,
                    'is_pay'            => 1,
                    'pay_fee'           => 0,
                    'need_pay_fee'      => ($v['number']*$v['unit_price'])*(1-$v['discount']/100)*(1+$v['tex']/100)*$v['rate']/100,
                    'number'            => $v['number'],
                    'unit_price'        => $v['unit_price'],
                    'subject_code'      => Tenement::where('id',$tenement_info->tenement_id)->pluck('subject_code')->first(),
                    'tex'               => $v['tex'],
                    'discount'          => $v['discount'],
                    'items_name'        => $v['items_name'],
                    'describe'          => $v['describe'],
                    'note'              => $v['note'],
                    'effect_date'       => $input['effect_date'],
                    'expire_date'       => $input['expire_date'],
                    'District'          => $rent_house_info->District,
                    'TA'                => $rent_house_info->TA,
                    'Region'            => $rent_house_info->Region,
                    'upload_url'        => $v['upload_url'],
                    'rate'              => $v['rate'],
                    'created_at'        => date('Y-m-d H:i:s',time()),
                ];
                $res = $model->insert($fee_data);
            }else{
                $rent_house_id = RentContract::where('id',$input['contract_id'])->pluck('house_id')->first();
                $contract_sn = RentContract::where('id',$input['contract_id'])->pluck('contract_id')->first();
                $tenement_info = ContractTenement::where('contract_id',$input['contract_id'])->first();
                $rent_house_info = RentHouse::where('id',$rent_house_id)->first();
                $fee_data = [
                    'user_id'           => $v['user_id'],
                    'contract_id'       => $v['contract_id'],
                    'contract_sn'       => $contract_sn,
                    'rent_house_id'     => $rent_house_id,
                    'tenement_id'       => $tenement_info->tenement_id,
                    'tenement_name'     => $tenement_info->tenement_full_name,
                    'tenement_email'    => $tenement_info->tenement_email,
                    'tenement_phone'    => $tenement_info->tenement_phone,
                    'arrears_type'      => 4,
                    'property_name'     => $rent_house_info->property_name,
                    'arrears_fee'       => ($v['number']*$v['unit_price'])*(1-$v['discount']/100)*(1+$v['tex']/100)*$v['rate']/100,
                    'is_pay'            => 1,
                    'pay_fee'           => 0,
                    'need_pay_fee'      => ($v['number']*$v['unit_price'])*(1-$v['discount']/100)*(1+$v['tex']/100)*$v['rate']/100,
                    'number'            => $v['number'],
                    'unit_price'        => $v['unit_price'],
                    'subject_code'      => Tenement::where('id',$tenement_info->tenement_id)->pluck('subject_code')->first(),
                    'tex'               => $v['tex'],
                    'discount'          => $v['discount'],
                    'items_name'        => $v['items_name'],
                    'describe'          => $v['describe'],
                    'note'              => $v['note'],
                    'effect_date'       => $input['effect_date'],
                    'expire_date'       => $input['expire_date'],
                    'District'          => $rent_house_info->District,
                    'TA'                => $rent_house_info->TA,
                    'Region'            => $rent_house_info->Region,
                    'upload_url'        => $v['upload_url'],
                    'rate'              => $v['rate'],
                    'created_at'        => date('Y-m-d H:i:s',time()),
                ];
                $res = $model->insert($fee_data);
            }
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
        if(isset($input['operator_id'])){
            $operator_id = $input['operator_id'];
            $room_list = OperatorRoom::where('operator_id',$operator_id)->pluck('house_id');
            $model = $model->whereIn('house_id',$room_list);
        }
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
        if($input['tenement_name']){
            $model = $model->where('tenement_name','like','%'.$input['tenement_name'].'%');
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
        if(isset($input['operator_id'])){
            $operator_id = $input['operator_id'];
            $room_list = OperatorRoom::where('operator_id',$operator_id)->pluck('house_id');
            $model = $model->whereIn('rent_house_id',$room_list);
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
            $res = $model->where('user_id',$input['user_id'])->offset(($input['page']-1)*10)->limit(10)->select('contract_id')->groupBy('contract_id')->get()->toArray();
            foreach ($res as $k => $v){
                $fee_res = RentArrears::where('contract_id',$v['contract_id'])->get()->toArray();
                $fee_count = count($fee_res);
                $fee_list[$k]['tenement_name'] = $fee_res[0]['tenement_name'];
                $fee_list[$k]['tenement_email'] = $fee_res[0]['tenement_email'];
                $fee_list[$k]['property_name'] = $fee_res[0]['property_name'];
                $fee_list[$k]['contract_sn'] = $fee_res[0]['contract_sn'];
                $fee_list[$k]['contract_id'] = $fee_res[0]['contract_id'];
                $fee_list[$k]['rent_per_week'] = RentHouse::where('id',$fee_res[0]['rent_house_id'])->pluck('rent_fee_pre_week')->first();
                $fee_list[$k]['expire_date'] = $fee_res[$fee_count-1]['expire_date'];
                $total_arrears = 0;
                $total_rent = 0;
                $paid = 0;
                $rent_arrears = 0;
                $other_arrears = 0;
                foreach ($fee_res as $key => $value){
                    if($value['arrears_type'] == 1 || $value['arrears_type'] == 2 || $value['arrears_type'] == 3){
                        $total_arrears += $value['need_pay_fee'];
                        $total_rent += $value['arrears_fee'];
                        $paid += $value['pay_fee'];
                        if($value['arrears_type'] == 2){
                            $rent_arrears += $value['need_pay_fee'];
                            if($value['rent_circle'] == 1){
                                $fee_list[$k]['total_stay'] = $value['rent_times'].'weeks';
                            }elseif ($value['rent_circle'] == 2){
                                $fee_list[$k]['total_stay'] = ($value['rent_times']*2).'weeks';
                            }elseif ($value['rent_circle'] == 3){
                                $fee_list[$k]['total_stay'] = $value['rent_times'].'month';
                            }
                            $fee_list[$k]['total_stay'] = $value['rent_times'];
                        }elseif($value['arrears_type'] == 1 || $value['arrears_type'] == 3){
                            $other_arrears += $value['need_pay_fee'];
                        }
                    }
                }
                $fee_list[$k]['total_arrears'] = round($total_arrears,2);
                $fee_list[$k]['total_rent'] = round($total_rent,2);
                $fee_list[$k]['paid'] = round($paid,2);
                $fee_list[$k]['rent_arrears'] = round($rent_arrears,2);
                $fee_list[$k]['other_arrears'] = round($other_arrears,2);
                $total_arrears_all += $total_arrears;
                $total_rent_all += $total_rent;
                $paid_all += $paid;
                $rent_arrears_all += $rent_arrears;
                $other_arrears_all += $other_arrears;
            }
            $data['fee_list'] = $fee_list;
            $data['total_arrears_all'] = round($total_arrears_all,2);
            $data['total_rent_all'] = round($total_rent_all,2);
            $data['paid_all'] = round($paid_all,2);
            $data['rent_arrears_all'] = round($rent_arrears_all,2);
            $data['other_arrears_all'] = round($other_arrears_all,2);
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
            $data['tenement_info'] = Tenement::where('id',$tenement_id)->select('tenement_id','first_name','phone','mobile','email')->first();
            $fee_detail = $model->where('user_id',$input['user_id'])->where('contract_id',$input['contract_id'])->whereIn('is_pay',[1,3])->offset(($input['page']-1)*4)
                ->limit(4)->get()->toArray();
            $data['fee_detail'] = $fee_detail;
            $data['tenement_note'] = TenementNote::where('user_id',$input['user_id'])->where('tenement_id',$tenement_id)->get()->toArray();
            $data['tenement_name'] = $data['tenement_info']->first_name;
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
        if($input['amount']){
            if($input['invoice_date'] && $input['tenement_name']){
                $sql = '(SELECT  SUM(arrears_fee) AS SUMM ,contract_id FROM rent_arrears WHERE user_id = '.$input['user_id'].' AND arrears_type in (\'3\',\'4\') AND tenement_name like \'%'.$input['tenement_name'].'%\' AND created_at BETWEEN \''.date('Y-m-d H:i:s',strtotime($input['invoice_date'])).'\' AND \''.date('Y-m-d H:i:s',strtotime($input['invoice_date'])+3600*24-1).'\' GROUP BY contract_id) AS T WHERE T.SUMM > '.$input['amount'];
                $count = DB::table(DB::raw($sql))->get()->toArray();
            }elseif(!$input['invoice_date']){
                $sql = '(SELECT  SUM(arrears_fee) AS SUMM ,contract_id FROM rent_arrears WHERE user_id = '.$input['user_id'].' AND arrears_type in (\'3\',\'4\') AND tenement_name like \'%'.$input['tenement_name'].'%\' GROUP BY contract_id) AS T WHERE T.SUMM > '.$input['amount'];
                $count = DB::table(DB::raw($sql))->get()->toArray();
            }elseif(!$input['tenement_name']){
                $sql = '(SELECT  SUM(arrears_fee) AS SUMM ,contract_id FROM rent_arrears WHERE user_id = '.$input['user_id'].' AND arrears_type in (\'3\',\'4\') AND created_at BETWEEN \''.date('Y-m-d H:i:s',strtotime($input['invoice_date'])).'\' AND \''.date('Y-m-d H:i:s',strtotime($input['invoice_date'])+3600*24-1).'\' GROUP BY contract_id) AS T WHERE T.SUMM > '.$input['amount'];
                $count = DB::table(DB::raw($sql))->get()->toArray();
            }else{
                $sql = '(SELECT  SUM(arrears_fee) AS SUMM ,contract_id FROM rent_arrears WHERE user_id = '.$input['user_id'].' AND arrears_type in (\'3\',\'4\') GROUP BY contract_id) AS T WHERE T.SUMM > '.$input['amount'];
                $count = DB::table(DB::raw($sql))->get()->toArray();
            }
            $res_count = count($count);
            if($res_count <= ($input['page']-1)*10){
                return $this->error('2','no more fee information');
            }else{
                $res = DB::table(DB::raw($sql))->offset(($input['page']-1)*10)->limit(10)->get()->toArray();
                foreach ($res as $k => $v){
                    $v = (array)$v;
                    $fee_res = RentArrears::where('contract_id',$v['contract_id'])->get()->toArray();
                    $fee_list[$k]['contract_id'] = $fee_res[0]['contract_id'];
                    $fee_list[$k]['contract_sn'] = $fee_res[0]['contract_sn'];
                    $fee_list[$k]['contract_type'] = RentContract::where('id',$fee_res[0]['contract_id'])->pluck('contract_type')->first();
                    $fee_list[$k]['tenement_name'] = $fee_res[0]['tenement_name'];
                    $fee_list[$k]['invoice_date'] = '';
                    $fee_list[$k]['payment_due'] = '';
                    $fee_list[$k]['amount'] = 0;
                    foreach ($fee_res as $key => $value){
                        if($value['arrears_type'] == 3){
                            $fee_list[$k]['invoice_date'] = $value['created_at'];
                            $fee_list[$k]['payment_due'] = $value['expire_date'];
                            $fee_list[$k]['amount'] += $value['arrears_fee'];
                        }
                    }
                }
                $data['fee_list'] = $fee_list;
                $data['current_page'] = $input['page'];
                $data['total_page'] = ceil($res_count/10);
                return $this->success('get fee list success',$data);
            }
        }else{
            if($input['tenement_name']){
                $model = $model->where('tenement_name','like','%'.$input['tenement_name'].'%');
            }
            if($input['invoice_date']){
                $model = $model->where('created_at','>',date('Y-m-d H:i:s',strtotime($input['invoice_date'])))->where('created_at','<',date('Y-m-d H:i:s',strtotime($input['invoice_date'])+3600*24));
            }
            $count = $model->where('user_id',$input['user_id'])->pluck('contract_id')->groupBy('contract_id');
            $count = count($count);
            if($count <= ($input['page']-1)*10){
                return $this->error('2','no more fee information');
            }else{
                $res = $model->where('user_id',$input['user_id'])->offset(($input['page']-1)*10)->limit(10)->select('contract_id')->groupBy('contract_id')->get()->toArray();
                foreach ($res as $k => $v){
                    $fee_res = RentArrears::where('contract_id',$v['contract_id'])->get()->toArray();
                    $fee_list[$k]['contract_id'] = $fee_res[0]['contract_id'];
                    $fee_list[$k]['contract_sn'] = $fee_res[0]['contract_sn'];
                    $fee_list[$k]['contract_type'] = RentContract::where('id',$fee_res[0]['contract_id'])->pluck('contract_type')->first();
                    $fee_list[$k]['tenement_name'] = $fee_res[0]['tenement_name'];
                    $fee_list[$k]['invoice_date'] = '';
                    $fee_list[$k]['payment_due'] = '';
                    $fee_list[$k]['amount'] = 0;
                    foreach ($fee_res as $key => $value){
                        if($value['arrears_type'] == 3){
                            $fee_list[$k]['invoice_date'] = $value['created_at'];
                            $fee_list[$k]['payment_due'] = $value['expire_date'];
                            $fee_list[$k]['amount'] += $value['arrears_fee'];
                        }
                    }
                }
                $data['fee_list'] = $fee_list;
                $data['current_page'] = $input['page'];
                $data['total_page'] = ceil($count/10);
                return $this->success('get fee list success',$data);
            }
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
        if($input['include_gts'] == 2){
            $model = $model->where('tex',0);
        }
        if($input['include_gts'] == 3){
            $model = $model->where('tex','>','0');
        }
        if($input['start_date'] && $input['end_date']){
            $model = $model->whereBetween('created_at',[$input['start_date'],$input['end_date']]);
        }
        if($input['arrears_type']){
            $model = $model->where('arrears_type',$input['arrears_type']);
        }
        $count = $model->where('user_id',$input['user_id'])->where('fee_sn',$input['fee_sn'])->whereIn('arrears_type',[3,4])->get();
        $count = count($count);
        if($count <= ($input['page']-1)*10){
            return $this->error('2','no more fee information');
        }else{
            $fee_data = $model->where('user_id',$input['user_id'])->where('fee_sn',$input['fee_sn'])->whereIn('arrears_type',[3,4])->offset(($input['page']-1)*10)->limit(10)->get()->toArray();
            static $amount_price = 0;
            static $discount = 0;
            static $gts = 0;
            $rate = 0;
            foreach ($fee_data as $k => $v){
                $amount_price += $v['unit_price']*$v['number'];
                $discount += ($v['unit_price']*$v['number'])*$v['discount']/100;
                $gts += ($v['unit_price']*$v['number'])*(1-$v['discount']/100)*$v['tex']/100;
                $rate += ($v['unit_price']*$v['number'])*(1-$v['discount']/100)*$v['tex']/100*$v['rate']/100;
            }
            $tenement_id = ContractTenement::where('contract_id',$input['contract_id'])->pluck('tenement_id')->first();
            $data['subject_code'] = Tenement::where('id',$tenement_id)->pluck('subject_code')->first();
            $data['total_price'] = round(($amount_price-$discount+$gts),2);
            $data['amount_price'] = round($amount_price,2);
            $data['discount'] = round($discount,2);
            $data['gts'] = round($gts,2);
            $data['rate'] = $rate;
            $data['fee_data'] = $fee_data;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/10);
            return $this->success('get arrears success',$data);
        }
    }

    /**
     * @description:现金收据清单
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function cashList(array $input)
    {
        $model = new RentArrears();
        if($input['amount']){
            if($input['pay_date'] && $input['tenement_name']){
                $sql = '(SELECT  SUM(need_pay_fee) AS SUMM ,contract_id FROM rent_arrears WHERE user_id = '.$input['user_id'].' AND arrears_type in (\'1\', \'2\',\'3\') AND tenement_name like \'%'.$input['tenement_name'].'%\' AND expire_date BETWEEN \''.date('Y-m-d H:i:s',strtotime($input['invoice_date'])).'\' AND \''.date('Y-m-d H:i:s',strtotime($input['invoice_date'])+3600*24-1).'\' GROUP BY contract_id) AS T WHERE T.SUMM > '.$input['amount'];
                $count = DB::table(DB::raw($sql))->get()->toArray();
            }elseif(!$input['pay_date']){
                $sql = '(SELECT  SUM(need_pay_fee) AS SUMM ,contract_id FROM rent_arrears WHERE user_id = '.$input['user_id'].' AND arrears_type in (\'1\', \'2\',\'3\') AND tenement_name like \'%'.$input['tenement_name'].'%\' GROUP BY contract_id) AS T WHERE T.SUMM > '.$input['amount'];
                $count = DB::table(DB::raw($sql))->get()->toArray();
            }elseif(!$input['tenement_name']){
                $sql = '(SELECT  SUM(need_pay_fee) AS SUMM ,contract_id FROM rent_arrears WHERE user_id = '.$input['user_id'].' AND arrears_type in (\'1\', \'2\',\'3\') AND expire_date BETWEEN \''.date('Y-m-d H:i:s',strtotime($input['invoice_date'])).'\' AND \''.date('Y-m-d H:i:s',strtotime($input['invoice_date'])+3600*24-1).'\' GROUP BY contract_id) AS T WHERE T.SUMM > '.$input['amount'];
                $count = DB::table(DB::raw($sql))->get()->toArray();
            }else{
                $sql = '(SELECT  SUM(need_pay_fee) AS SUMM ,contract_id FROM rent_arrears WHERE user_id = '.$input['user_id'].' AND arrears_type in (\'1\', \'2\',\'3\') GROUP BY contract_id) AS T WHERE T.SUMM > '.$input['amount'];
                $count = DB::table(DB::raw($sql))->get()->toArray();
            }
            $res_count = count($count);
            if($res_count <= ($input['page']-1)*10){
                return $this->error('2','no more fee information');
            }else{
                $res = DB::table(DB::raw($sql))->offset(($input['page']-1)*10)->limit(10)->get()->toArray();
                foreach ($res as $k => $v){
                    $v = (array)$v;
                    $fee_res = RentArrears::where('contract_id',$v['contract_id'])->get()->toArray();
                    $fee_list[$k]['contract_id'] = $v['contract_id'];
                    $fee_list[$k]['contract_sn'] = $fee_res[0]['contract_sn'];
                    $fee_list[$k]['tenement_name'] = $fee_res[0]['tenement_name'];
                    $fee_list[$k]['payment_due'] = '';
                    $fee_list[$k]['amount'] = 0;
                    foreach ($fee_res as $key => $value){
                        $fee_list[$k]['payment_due'] = $value['expire_date'];
                        $fee_list[$k]['amount'] += $value['need_pay_fee'];
                    }
                }
                $data['fee_list'] = $fee_list;
                $data['current_page'] = $input['page'];
                $data['total_page'] = ceil($res_count/10);
                return $this->success('get fee list success',$data);
            }
        }else{
            if($input['tenement_name']){
                $model = $model->where('tenement_name','like','%'.$input['tenement_name'].'%');
            }
            if($input['pay_date']){
                $model = $model->where('created_at','>',date('Y-m-d H:i:s',strtotime($input['expire_date'])))->where('created_at','<',date('Y-m-d H:i:s',strtotime($input['expire_date'])+3600*24));
            }
            $count = $model->where('user_id',$input['user_id'])->pluck('contract_id')->groupBy('contract_id');
            $count = count($count);
            if($count <= ($input['page']-1)*10){
                return $this->error('2','no more fee information');
            }else{
                $res = $model->where('user_id',$input['user_id'])->offset(($input['page']-1)*10)->limit(10)->select('contract_id')->groupBy('contract_id')->get()->toArray();
                foreach ($res as $k => $v){
                    $fee_res = RentArrears::where('contract_id',$v['contract_id'])->whereIn('arrears_type',[1,2,3])->get()->toArray();
                    $fee_list[$k]['contract_id'] = $v['contract_id'];
                    $fee_list[$k]['contract_sn'] = $fee_res[0]['contract_sn'];
                    $fee_list[$k]['tenement_name'] = $fee_res[0]['tenement_name'];
                    $fee_list[$k]['payment_due'] = '';
                    $fee_list[$k]['amount'] = 0;
                    foreach ($fee_res as $key => $value){
                        $fee_list[$k]['payment_due'] = $value['expire_date'];
                        $fee_list[$k]['amount'] += round($value['need_pay_fee'],2);
                    }
                }
                $data['fee_list'] = $fee_list;
                $data['current_page'] = $input['page'];
                $data['total_page'] = ceil($count/10);
                return $this->success('get fee list success',$data);
            }
        }
    }



    /**
     * @description:现金收据详情
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function cashDetail(array $input)
    {
        $model = new RentArrears();
        $fee_data = $model->where('user_id',$input['user_id'])->where('contract_id',$input['contract_id'])->whereIn('arrears_type',[1,2,3])->get()->toArray();
        static $bond_arrears = 0;
        static $rent_arrears = 0;
        static $expense_arrears = 0;
        foreach ($fee_data as $k => $v){
            if($v['arrears_type'] == 1){
                $bond_arrears += $v['need_pay_fee'];
            }elseif ($v['arrears_type'] == 2){
                $rent_arrears += $v['need_pay_fee'];
            }elseif ($v['arrears_type'] == 3){
                $expense_arrears += $v['need_pay_fee'];
            }
        }
        $data['headimg'] = Tenement::where('id',$fee_data[0]['tenement_id'])->pluck('headimg')->first();
        $data['tenement_full_name'] = $fee_data[0]['tenement_name'];
        $data['birthday'] = Tenement::where('id',$fee_data[0]['tenement_id'])->pluck('birthday')->first();
        $data['tenement_email'] = $fee_data[0]['tenement_email'];
        $data['tenement_phone'] = $fee_data[0]['tenement_phone'];
        $data['property_name'] = $fee_data[0]['property_name'];
        $data['bond_arrears'] = $bond_arrears;
        $data['rent_arrears'] = $rent_arrears;
        $data['expense_arrears'] = $expense_arrears;
        $data['property_address'] = RentHouse::where('id',$fee_data[0]['rent_house_id'])->pluck('address')->first();
        $data['fee_data'] = $fee_data;
        return $this->success('get arrears success',$data);
    }


    /**
     * @description:现金收据冲账
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function cashPay(array $input)
    {
        $model = new RentArrears();
        static $pay_money = 0;
        static $error = 0;
        $pay_money += $input['pay_amount'];
        foreach ($input['arrears_id'] as $k => $v){
            $need_pay = $model->where('id',$v)->first();
            if($pay_money >= $need_pay->need_pay_fee){ // 支付金额大于应付金额 直接 销账
                // 更改此次费用
                $change_arrears_data = [
                    'is_pay'    => 2,
                    'pay_fee'   => $need_pay->pay_fee+$need_pay->need_pay_fee,
                    'need_pay_fee'  => 0,
                    'pay_date'      => $input['pay_date'],
                    'updated_at'    => date('Y-m-d H:i:s',time()),
                ];
                $change_arrears_res = $model->where('id',$v)->update($change_arrears_data);
                if(!$change_arrears_res){
                    $error += 1;
                }
                // 增加收账数据
                $receive_data = [
                    'arrears_id'    => $v,
                    'pay_money'     => $need_pay->need_pay_fee,
                    'pay_date'      => $input['pay_date'],
                    'pay_method'    => 1,
                    'note'          => $input['note'],
                    'created_at'    => date('Y-m-d H:i:s',time()),
                ];
                $receive_res = FeeReceive::insert($receive_data);
                if(!$receive_res){
                    $error += 1;
                }
                // 修改余额
                $pay_money -= $need_pay->need_pay_fee;
                // 扣服务费用
                if($need_pay->arrears_type == 1){

                }else{
                    // 查看有无VIP
                    $contract_type = RentContract::where('id',$need_pay->contract_id)->pluck('contract_type')->first();
                    $vip = DB::table('vip_list')->where('user_id',$input['user_id'])->where('vip_type',$contract_type)
                        ->where('vip_start_date','<=',date('Y-m-d',time()))->where('vip_end_date','>=',date('Y-m-d',time()))->first();
                    if($vip){ // 有VIP
                        $expense_data = [
                            'expense_sn'    => expenseSn(),
                            'user_id'   => $input['user_id'],
                            'user_cost_role'    => 1,
                            'expense_type'  => $contract_type+2,
                            'expense_cost'  => 0,
                            'discount'      => 0,
                            'total_cost'    => 0,
                            'created_at'    => date('Y-m-d H:i:s',time())
                        ];
                        DB::table('expense')->insert($expense_data);
                    }else{
                        // 查看是否有折扣券
                        $discount = DB::table('coupon_list')->where('used_user_id',$input['user_id'])->where('coupon_type',1)
                            ->where('activated_at','<=',date('Y-m-d',time()))->where('out_time','<=',date('Y-m-d',time()))
                            ->orderByDesc('discount')->first();
                       if($contract_type == 1){
                           $expense_rate = DB::table('sys_config')->where('code','RSF')->pluck('value')->first();
                       }elseif ($contract_type == 2){
                           $expense_rate = DB::table('sys_config')->where('code','BSF')->pluck('value')->first();
                       }elseif ($contract_type == 3){
                           $expense_rate = DB::table('sys_config')->where('code','FSF')->pluck('value')->first();
                       }else{
                           $expense_rate = DB::table('sys_config')->where('code','CSF')->pluck('value')->first();
                       }
                        $expense_cost = $need_pay->arrears_fee*$expense_rate;
                        $user_free_balance = DB::table('user')->where('id',$input['user_id'])->pluck('free_balance')->first();
                        if($user_free_balance >0){
                            if($expense_cost < $user_free_balance){
                                $expense_data = [
                                    'expense_sn'    => expenseSn(),
                                    'user_id'   => $input['user_id'],
                                    'user_cost_role'    => 1,
                                    'expense_type'  => $contract_type+2,
                                    'expense_cost'  => 0,
                                    'discount'      => $expense_cost,
                                    'total_cost'    => $expense_cost,
                                    'created_at'    => date('Y-m-d H:i:s',time())
                                ];
                                DB::table('user')->where('id',$input['user_id'])->decrement('free_balance',$expense_cost); // 抵扣券扣款
                            }else{
                                $expense_data = [
                                    'expense_sn'    => expenseSn(),
                                    'user_id'   => $input['user_id'],
                                    'user_cost_role'    => 1,
                                    'expense_type'  => $contract_type+2,
                                    'expense_cost'  => $expense_cost-$user_free_balance,
                                    'discount'      => $user_free_balance,
                                    'total_cost'    => $expense_cost,
                                    'created_at'    => date('Y-m-d H:i:s',time())
                                ];
                                DB::table('expense')->insert($expense_data);
                                DB::table('user')->where('id',$input['user_id'])->decrement('free_balance',$expense_cost); // 抵扣券扣款
                                DB::table('user')->where('id',$input['user_id'])->decrement('balance',($expense_cost-$user_free_balance)); // 余额扣款
                            }
                        }
                        if($discount){
                            $expense_data = [
                                'expense_sn'    => expenseSn(),
                                'user_id'   => $input['user_id'],
                                'user_cost_role'    => 1,
                                'expense_type'  => $contract_type+2,
                                'expense_cost'  => $expense_cost*(100-$discount->discount)/100,
                                'discount'      => $expense_cost*$discount->discount/100,
                                'total_cost'    => $expense_cost,
                                'created_at'    => date('Y-m-d H:i:s',time())
                            ];
                            DB::table('expense')->insert($expense_data);
                            DB::table('user')->where('id',$input['user_id'])->decrement('balance',($expense_cost*(100-$discount->discount)/100)); // 余额扣款
                        }else{
                            $expense_data = [
                                'expense_sn'    => expenseSn(),
                                'user_id'   => $input['user_id'],
                                'user_cost_role'    => 1,
                                'expense_type'  => $contract_type+2,
                                'expense_cost'  => $expense_cost,
                                'discount'      => 0,
                                'total_cost'    => $expense_cost,
                                'created_at'    => date('Y-m-d H:i:s',time())
                            ];
                            DB::table('expense')->insert($expense_data);
                            // 扣费用
                            $user_free_balance = DB::table('user')->where('id',$input['user_id'])->pluck('free_balance')->first();
                            DB::table('user')->where('id',$input['user_id'])->decrement('balance',$expense_cost); // 余额扣款
                        }

                    }
                }
            }elseif ($need_pay->need_pay_fee > $pay_money && $pay_money >0){ //
                // 更改此次费用
                $change_arrears_data = [
                    'is_pay'        => 3,
                    'pay_fee'       => $need_pay->pay_fee+$pay_money,
                    'need_pay_fee'  => $need_pay->need_pay_fee-$pay_money,
                    'pay_date'      => $input['pay_date'],
                    'updated_at'    => date('Y-m-d H:i:s',time()),
                ];
                $change_arrears_res = $model->where('id',$v)->update($change_arrears_data);
                if(!$change_arrears_res){
                    $error += 1;
                }
                // 增加收账数据
                $receive_data = [
                    'arrears_id'    => $v,
                    'pay_money'     => $pay_money,
                    'pay_date'      => $input['pay_date'],
                    'pay_method'    => 1,
                    'note'          => $input['note'],
                    'created_at'    => date('Y-m-d H:i:s',time()),
                ];
                $receive_res = FeeReceive::insert($receive_data);
                if(!$receive_res){
                    $error += 1;
                }
                // 修改余额
                $pay_money = 0;
            }
        }
        if($pay_money){
            // 增加余额
            $contract_id = $model->where('id',$input['arrears_id'][0])->pluck('contract_id')->first();
            $balance_update_res = RentContract::where('id',$contract_id)->increment('balance',$pay_money);
            if(!$balance_update_res){
                $error += 1;
            }
        }
        if($error){
            return $this->error('2','balance adjust failed');
        }else{
            return $this->success('balance adjust success');
        }
    }



    /**
     * @description:银行对账上传CSV文件
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function bankCheck(array $input,$file)
    {
        $excel = new Excel();
        $data = $excel::toArray(new UsersImport(), $file);
        $data = $data[0];
        if($input['bank_type'] == 'ANZ'){
            if($data[0][0] != 'Type' || $data[0][1] != 'Details' || $data[0][2] != 'Particulars' || $data[0][3] != 'Code' || $data[0][4] != 'Reference' || $data[0][5] != 'Amount' || $data[0][6] != 'Date' || $data[0][7] != 'ForeignCurrencyAmount' || $data[0][8] != 'ConversionCharge'){
                return $this->error('3','the csv file is not the select bank');
            }else{
                $check_id = BankCheck::max('check_id');
                $i = 1;
                static $success_count = 0;
                static $failed_count = 0;
                while (!empty($data[$i][0])) {
                    // 处理数据
                    if($data[$i][0] == 'Deposit' || $data[$i][0] == 'Bill Payment' || $data[$i][0] == 'Direct Credit'){
                        $particulars = $data[$i][2];
                        $code = $data[$i][3];
                        $reference = $data[$i][4];
                        $amount = $data[$i][5];
                        $date = $data[$i][6];
                        $transdate = explode('/',$date);
                        $date = $transdate[1].'/'.$transdate[0].'/'.$transdate[2];
                        $date = date('Y-m-d',strtotime($date));
                        if(strtotime($date) >= strtotime($input['check_start_date'])&&strtotime($date) <= strtotime($input['check_end_date'])+3600*24-1){
                            // 匹配
                            $match_res = RentArrears::where/*('need_pay_fee',$amount)->where*/('user_id',$input['user_id'])/*->whereIn('is_pay',[1,3])*/->where('subject_code',$code)->first();
                            if($match_res){ // 匹配成功
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'ANZ',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_tenement_id' => $match_res->tenement_id,
                                    'match_tenement_name'   => $match_res->tenement_name,
                                    /*'match_arrears_id'  => $match_res->id,*/
                                    'is_checked'        => 2,
                                    'bank_check_detail' => $data[$i][0].''.$data[$i][1].''.$data[$i][2].''.$data[$i][3].''.$data[$i][4].''.$data[$i][5].''.$data[$i][6].''.$data[$i][7].''.$data[$i][8],
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res){ // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i+1;
                                }else{
                                }
                            }else{
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'ANZ',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'is_checked'        => 1,
                                    'bank_check_detail' => $data[$i][0].''.$data[$i][1].''.$data[$i][2].''.$data[$i][3].''.$data[$i][4].''.$data[$i][5].''.$data[$i][6].''.$data[$i][7].''.$data[$i][8],
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res) { // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i + 1;
                                }
                            }
                        }
                    }
                    $i++;
                }
                $match_up_res = BankCheck::where('check_id',$check_id+1)->get()->toArray();
                if($match_up_res){
                    $check_data['check_id'] = $check_id+1;
                    return $this->success('match success',$check_data);
                }else{
                    $check_data['check_id'] = $check_id+1;
                    return $this->error('2','no match data',$check_data);
                }
            }
        }elseif ($input['bank_type'] == 'BNZ'){
            if($data[0][0] != 'Date' || $data[0][1] != 'Amount' || $data[0][2] != 'Payee' || $data[0][3] != 'Particulars' || $data[0][4] != 'Code' || $data[0][5] != 'Reference' || $data[0][6] != 'Tran Type' || $data[0][7] != 'This Party Account' || $data[0][8] != 'Other Party Account' || $data[0][9] != 'Serial' || $data[0][10] != 'Transaction Code' || $data[0][11] != 'Batch Number' || $data[0][12] != 'Originating Bank/Branch' || $data[0][13] != 'Processed Date'){
                return $this->error('3','the csv file is not the select bank');
            }else{
                $check_id = BankCheck::max('check_id');
                $i = 1;
                static $success_count = 0;
                static $failed_count = 0;
                while (!empty($data[$i][0])) {
                    // 处理数据
                    if($data[$i][2] == 'DEPOSIT' || $data[$i][2] == 'BILL PAYMENT' || $data[$i][2] == 'DIRECT CREDIT'){
                        $particulars = $data[$i][3];
                        $code = $data[$i][4];
                        $reference = $data[$i][5];
                        $amount = $data[$i][1];
                        $date = $data[$i][0];
                        $transdate = explode('/',$date);
                        $date = $transdate[1].'/'.$transdate[0].'/'.$transdate[2];
                        $date = date('Y-m-d',strtotime($date));
                        if(strtotime($date) >= strtotime($input['check_start_date'])&&strtotime($date) <= strtotime($input['check_end_date'])+3600*24-1){
                            // 匹配
                            $match_res = RentArrears::where/*('need_pay_fee',$amount)->where*/('user_id',$input['user_id'])/*->whereIn('is_pay',[1,3])*/->where('subject_code',$code)->first();
                            if($match_res){ // 匹配成功
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'BNZ',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_tenement_id' => $match_res->tenement_id,
                                    'match_tenement_name'   => $match_res->tenement_name,
                                    /*'match_arrears_id'  => $match_res->id,*/
                                    'is_checked'        => 2,
                                    'bank_check_detail' => $data[$i][0].''.$data[$i][1].''.$data[$i][2].''.$data[$i][3].''.$data[$i][4].''.$data[$i][5].''.$data[$i][6].''.$data[$i][7].''.$data[$i][8].''.$data[$i][9].''.$data[$i][10].''.$data[$i][11].''.$data[$i][12].''.$data[$i][13],
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res){ // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i+1;
                                }else{
                                }
                            }else{
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'BNZ',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'is_checked'        => 1,
                                    'bank_check_detail' => $data[$i][0].''.$data[$i][1].''.$data[$i][2].''.$data[$i][3].''.$data[$i][4].''.$data[$i][5].''.$data[$i][6].''.$data[$i][7].''.$data[$i][8].''.$data[$i][9].''.$data[$i][10].''.$data[$i][11].''.$data[$i][12].''.$data[$i][13],
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res) { // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i + 1;
                                }
                            }
                        }
                    }
                    $i++;
                }
                $match_up_res = BankCheck::where('check_id',$check_id+1)->get()->toArray();
                if($match_up_res){
                    $check_data['check_id'] = $check_id+1;
                    return $this->success('match success',$check_data);
                }else{
                    $check_data['check_id'] = $check_id+1;
                    return $this->error('2','no match data',$check_data);
                }
            }
        }elseif ($input['bank_type'] == 'westpac'){
            if($data[0][0] != 'Date' || $data[0][1] != 'Amount' || $data[0][2] != 'Other Party' || $data[0][3] != 'Description' || $data[0][4] != 'Reference' || $data[0][5] != 'Particulars' || $data[0][6] != 'Analysis Code'){
                return $this->error('3','the csv file is not the select bank');
            }else{
                $check_id = BankCheck::max('check_id');
                $i = 1;
                static $success_count = 0;
                static $failed_count = 0;
                while (!empty($data[$i][0])) {
                    // 处理数据
                    if($data[$i][3] == 'DEPOSIT' || $data[$i][3] == 'BILL PAYMENT' || $data[$i][3] == 'DIRECT CREDIT'){
                        $particulars = $data[$i][5];
                        $code = $data[$i][6];
                        $reference = $data[$i][4];
                        $amount = $data[$i][1];
                        $date = $data[$i][0];
                        $transdate = explode('/',$date);
                        $date = $transdate[1].'/'.$transdate[0].'/'.$transdate[2];
                        $date = date('Y-m-d',strtotime($date));
                        if(strtotime($date) >= strtotime($input['check_start_date'])&&strtotime($date) <= strtotime($input['check_end_date'])+3600*24-1){
                            // 匹配
                            $match_res = RentArrears::/*where('need_pay_fee',$amount)->*/where('user_id',$input['user_id'])/*->whereIn('is_pay',[1,3])*/->where('subject_code',$code)->first();
                            if($match_res){ // 匹配成功
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'westpac',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_tenement_id' => $match_res->tenement_id,
                                    'match_tenement_name'   => $match_res->tenement_name,
                                    /*'match_arrears_id'  => $match_res->id,*/
                                    'is_checked'        => 2,
                                    'bank_check_detail' => $data[$i][0].''.$data[$i][1].''.$data[$i][2].''.$data[$i][3].''.$data[$i][4].''.$data[$i][5].''.$data[$i][6],
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res){ // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i+1;
                                }else{
                                }
                            }else{
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'westpac',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'is_checked'        => 1,
                                    'bank_check_detail' => $data[$i][0].''.$data[$i][1].''.$data[$i][2].''.$data[$i][3].''.$data[$i][4].''.$data[$i][5].''.$data[$i][6],
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res) { // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i + 1;
                                }
                            }
                        }
                    }
                    $i++;
                }
                $match_up_res = BankCheck::where('check_id',$check_id+1)/*->where('match_arrears_id','>',0)*/->get()->toArray();
                if($match_up_res){
                    $check_data['check_id'] = $check_id+1;
                    return $this->success('match success',$check_data);
                }else{
                    $check_data['check_id'] = $check_id+1;
                    return $this->error('2','no match data',$check_data);
                }
            }
        }elseif ($input['bank_type'] == 'ASB'){
            if($data[6][0] != 'Date' || $data[6][1] != 'Unique Id' || $data[6][2] != 'Tran Type' || $data[6][3] != 'Cheque Number' || $data[6][4] != 'Payee' || $data[6][5] != 'Memo' || $data[6][6] != 'Amount'){
                return $this->error('3','the csv file is not the select bank');
            }else{
                $check_id = BankCheck::max('check_id');
                $i = 8;
                static $success_count = 0;
                static $failed_count = 0;
                while (!empty($data[$i][0])) {
                    // 处理数据
                    if($data[$i][2] == 'CREDIT' || $data[$i][2] == 'D/C'){
                        $particulars = $data[$i][5];
                        $code = $data[$i][5];
                        $reference = $data[$i][4];
                        $amount = $data[$i][6];
                        $date = $data[$i][0];
                        $date = date('Y-m-d',strtotime($date));
                        if(strtotime($date) >= strtotime($input['check_start_date'])&&strtotime($date) <= strtotime($input['check_end_date'])+3600*24-1){
                            // 匹配
                            $match_res = RentArrears::/*where('need_pay_fee',$amount)->*/where('user_id',$input['user_id'])/*->whereIn('is_pay',[1,3])*/->where('subject_code',$code)->first();
                            if($match_res){ // 匹配成功
                                $bank_check_data = [
                                    'user_id'   => $input['user_id'],
                                    'check_id'  => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'ASB',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_tenement_id' => $match_res->tenement_id,
                                    'match_tenement_name'   => $match_res->tenement_name,
                                    /*'match_arrears_id'  => $match_res->id,*/
                                    'is_checked'        => 2,
                                    'bank_check_detail' => $data[$i][0].''.$data[$i][1].''.$data[$i][2].''.$data[$i][3].''.$data[$i][4].''.$data[$i][5].''.$data[$i][6],
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res){ // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i+1;
                                }else{
                                }
                            }else{
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'ASB',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'is_checked'        => 1,
                                    'bank_check_detail' => $data[$i][0].''.$data[$i][1].''.$data[$i][2].''.$data[$i][3].''.$data[$i][4].''.$data[$i][5].''.$data[$i][6],
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res) { // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i + 1;
                                }
                            }
                        }
                    }
                    $i++;
                }
                $match_up_res = BankCheck::where('check_id',$check_id+1)/*->where('match_arrears_id','>',0)*/->get()->toArray();
                if($match_up_res){
                    $check_data['check_id'] = $check_id+1;
                    return $this->success('match success',$check_data);
                }else{
                    $check_data['check_id'] = $check_id+1;
                    return $this->error('2','no match data',$check_data);
                }
            }
        }elseif ($input['bank_type'] == 'kiwi'){
            if(substr($data[0][0],0,2) != '38' ){
                return $this->error('3','the csv file is not the select bank');
            }else{
                $check_id = BankCheck::max('check_id');
                $i = 1;
                static $success_count = 0;
                static $failed_count = 0;
                while (!empty($data[$i][0])) {
                    // 处理数据
                    if($data[$i][3] > 0){
                        $particulars = $data[$i][1];
                        $code = $data[$i][1];
                        $reference = $data[$i][1];
                        $amount = $data[$i][3];
                        $date = $data[$i][0];
                        $transdate = explode('/',$date);
                        $date = $transdate[1].'/'.$transdate[0].'/'.$transdate[2];
                        $date = date('Y-m-d',strtotime($date));
                        if(strtotime($date) >= strtotime($input['check_start_date'])&&strtotime($date) <= strtotime($input['check_end_date'])+3600*24-1){
                            // 匹配
                            $match_res = RentArrears::/*where('need_pay_fee',$amount)->*/where('user_id',$input['user_id'])->/*whereIn('is_pay',[1,3])->*/where('subject_code',$code)->first();
                            if($match_res){ // 匹配成功
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'kiwi',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_tenement_id' => $match_res->tenement_id,
                                    'match_tenement_name'   => $match_res->tenement_name,
                                    /*'match_arrears_id'  => $match_res->id,*/
                                    'is_checked'        => 2,
                                    'bank_check_detail' => $data[$i][0].''.$data[$i][1].''.$data[$i][2].''.$data[$i][3].''.$data[$i][4],
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res){ // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i+1;
                                }else{
                                }
                            }else{
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'kiwi',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'is_checked'        => 1,
                                    'bank_check_detail' => $data[$i][0].''.$data[$i][1].''.$data[$i][2].''.$data[$i][3].''.$data[$i][4],
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res) { // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i + 1;
                                }
                            }
                        }
                    }
                    $i++;
                }
                $match_up_res = BankCheck::where('check_id',$check_id+1)/*->where('match_arrears_id','>',0)*/->get()->toArray();
                if($match_up_res){
                    $check_data['check_id'] = $check_id+1;
                    return $this->success('match success',$check_data);
                }else{
                    $check_data['check_id'] = $check_id+1;
                    return $this->error('2','no match data',$check_data);
                }
            }
        }elseif ($input['bank_type'] == 'TSB'){
            if($data[0][0] != 'Date' || $data[0][1] != 'Amount' || $data[0][2] != 'Reference' || $data[0][3] != 'Description' || $data[0][4] != 'Particulars'){
                return $this->error('3','the csv file is not the select bank');
            }else{
                $check_id = BankCheck::max('check_id');
                $i = 1;
                static $success_count = 0;
                static $failed_count = 0;
                while (!empty($data[$i][0])) {
                    // 处理数据
                    if($data[$i][1] > 0){
                        $particulars = $data[$i][4];
                        $code = $data[$i][3];
                        $reference = $data[$i][2];
                        $amount = $data[$i][1];
                        $date = $data[$i][0];
                        $transdate = explode('/',$date);
                        $date = $transdate[1].'/'.$transdate[0].'/'.$transdate[2];
                        $date = date('Y-m-d',strtotime($date));
                        if(strtotime($date) >= strtotime($input['check_start_date'])&&strtotime($date) <= strtotime($input['check_end_date'])+3600*24-1){
                            // 匹配
                            $match_res = RentArrears::/*where('need_pay_fee',$amount)->*/where('user_id',$input['user_id'])/*->whereIn('is_pay',[1,3])*/->where('subject_code',$code)->first();
                            if($match_res){ // 匹配成功
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'TSB',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_tenement_id' => $match_res->tenement_id,
                                    'match_tenement_name'   => $match_res->tenement_name,
                                    'is_checked'        => 2,
                                    'bank_check_detail' => $data[$i][0].''.$data[$i][1].''.$data[$i][2].''.$data[$i][3].''.$data[$i][4],
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res){ // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i+1;
                                }else{
                                }
                            }else{
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'TSB',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    /*'match_arrears_id'  => $match_res->id,*/
                                    'is_checked'        => 1,
                                    'bank_check_detail' => $data[$i][0].''.$data[$i][1].''.$data[$i][2].''.$data[$i][3].''.$data[$i][4],
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res) { // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i + 1;
                                }
                            }
                        }
                    }
                    $i++;
                }
                $match_up_res = BankCheck::where('check_id',$check_id+1)/*->where('match_arrears_id','>',0)*/->get()->toArray();
                if($match_up_res){
                    $check_data['check_id'] = $check_id+1;
                    return $this->success('match success',$check_data);
                }else{
                    $check_data['check_id'] = $check_id+1;
                    return $this->error('2','no match data',$check_data);
                }
            }
        }elseif ($input['bank_type'] == 'co-operative'){
            if($data[0][0] != 'Date' || $data[0][1] != 'Details' || $data[0][2] != 'Amount' || $data[0][3] != 'Balance'){
                return $this->error('3','the csv file is not the select bank');
            }else{
                $check_id = BankCheck::max('check_id');
                $i = 1;
                $success_count = 0;
                $failed_count = 0;
                while (!empty($data[$i][0])) {
                    // 处理数据
                    $match_data = explode('-',$data[$i][1]);
                    if($match_data[0] == 'DC' || $match_data[0] == 'DEPOSIT'){
                        $particulars =$match_data[1];
                        $code = $match_data[1];
                        $reference = $match_data[1];
                        $amount = $data[$i][2];
                        $date = $data[$i][0];
                        $transdate = explode('/',$date);
                        $date = $transdate[1].'/'.$transdate[0].'/'.$transdate[2];
                        $date = date('Y-m-d',strtotime($date));
                        if(strtotime($date) >= strtotime($input['check_start_date'])&&strtotime($date) <= strtotime($input['check_end_date'])+3600*24-1){
                            // 匹配
                            $match_res = RentArrears::/*where('need_pay_fee',$amount)->*/where('user_id',$input['user_id'])/*->whereIn('is_pay',[1,3])*/->where('subject_code',$code)->first();
                            if($match_res){ // 匹配成功
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'co-operative',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_tenement_id' => $match_res->tenement_id,
                                    'match_tenement_name'   => $match_res->tenement_name,
                                    /*'match_arrears_id'  => $match_res->id,*/
                                    'is_checked'        => 2,
                                    'bank_check_detail' => $data[$i][0].''.$data[$i][1].''.$data[$i][2].''.$data[$i][3],
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res){ // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i+1;
                                }else{
                                    // 匹配成功的 费用单 更新对应的 对账id
                                    /*$match_res->bank_check_id = $bank_check_res;
                                    $match_res->update();*/
                                }
                            }else{
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'co-operative',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'is_checked'        => 1,
                                    'bank_check_detail' => $data[$i][0].''.$data[$i][1].''.$data[$i][2].''.$data[$i][3].''.$data[$i][4],
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res) { // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i + 1;
                                }
                            }
                        }
                    }
                    $i++;
                }
                $match_up_res = BankCheck::where('check_id',$check_id+1)/*->where('match_arrears_id','>',0)*/->get()->toArray();
                if($match_up_res){
                    $check_data['check_id'] = $check_id+1;
                    return $this->success('match success',$check_data);
                }else{
                    $check_data['check_id'] = $check_id+1;
                    return $this->error('2','no match data',$check_data);
                }
            }
        }
    }


    /**
     * @description:银行对账确认符合费用单
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function matchData(array $input)
    {
        $check_id = $input['check_id'];
        $match_code_res = BankCheck::where('check_id',$check_id)->where('is_checked',2)->get()->toArray();
        if($match_code_res){
            foreach ($match_code_res as $k => $v){
                $amount = $v['amount'];
                $tenement_id = $v['match_tenement_id'];
                $match_res = RentArrears::where('user_id',$input['user_id'])->where('need_pay_fee',$amount)->where('arrears_type','<',4)->where('tenement_id',$tenement_id)->where('bank_check_id',null)->first();
                if($match_res){
                    BankCheck::where('id',$v['id'])->update(['match_arrears_id'=> $match_res->id,'is_checked'=>3]);
                    $match_res->bank_check_id = $v['id'];
                    $match_res->update();
                }
            }
            $match_up_res = BankCheck::where('check_id',$check_id)->where('is_checked',3)->get()->toArray();
            if($match_up_res){
                foreach ($match_up_res as $k => $v){
                    $res[$k]['bank_check_id'] = $v['id'];
                    $res[$k]['tenement_name'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('tenement_name')->first();
                    $res[$k]['payment_amount'] = $v['amount'];
                    $res[$k]['payment_date'] = date('m/d/Y',strtotime($v['bank_check_date']));
                    $res[$k]['match_code'] = $v['match_code'];
                    $res[$k]['arrears_amount'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('need_pay_fee')->first();
                    $res[$k]['arrears_type'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('arrears_type')->first();
                    $res[$k]['invoice_date'] = date('m/d/Y',strtotime(RentArrears::where('id',$v['match_arrears_id'])->pluck('created_at')->first()));
                    $res[$k]['due_date'] = date('m/d/Y',strtotime(RentArrears::where('id',$v['match_arrears_id'])->pluck('expire_date')->first()));
                    $res[$k]['subject_code'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('subject_code')->first();
                }
                $check_data['check_res'] = $res;
                $check_data['check_id'] = $check_id;
                return $this->success('match success',$check_data);
            }
            return $this->error('2','no match data');
        }else{
            return $this->error('2','no match data');
        }
    }

    /**
     * @description:银行对账确认符合费用单
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmMatchCheck(array $input)
    {
        $match_ids = $input['match_ids'];
        if(is_array($match_ids)){
            // 修改 已核对的账目
            foreach ($match_ids as $k => $v){
                $bank_check_data = BankCheck::where('id',$v)->first();
                $bank_check_data->is_checked = 4;
                $bank_check_data->updated_at = date('Y-m-d H:i:s',time());
                $bank_check_data->update();
                // 收费单增加数据
                $receives_data = [
                    'arrears_id'    => $bank_check_data->match_arrears_id,
                    'pay_money'     => $bank_check_data->amount,
                    'pay_date'      => $bank_check_data->bank_check_date,
                    'pay_method'    => 2,
                    'bank_check_id' => $v,
                ];
                $receives_res = FeeReceive::insert($receives_data);
                $arrears_data = RentArrears::where('id',$bank_check_data->match_arrears_id)->first();
                $change_arrears_data = [
                    'is_pay'    => 2,
                    'pay_fee'   => $arrears_data->pay_fee+$bank_check_data->amount,
                    'need_pay_fee'  => 0,
                    'pay_date'      => $bank_check_data->bank_check_date,
                    'updated_at'    => date('Y-m-d H:i:s',time()),
                ];
                RentArrears::where('id',$bank_check_data->match_arrears_id)->update($change_arrears_data);
                // 扣服务费用
                if($arrears_data->arrears_type == 1){

                }else{
                    // 查看有无VIP
                    $contract_type = RentContract::where('id',$arrears_data->contract_id)->pluck('contract_type')->first();
                    $vip = DB::table('vip_list')->where('user_id',$input['user_id'])->where('vip_type',$contract_type)
                        ->where('vip_start_date','<=',date('Y-m-d',time()))->where('vip_end_date','>=',date('Y-m-d',time()))->first();
                    if($vip){ // 有VIP
                        $expense_data = [
                            'expense_sn'    => expenseSn(),
                            'user_id'   => $input['user_id'],
                            'user_cost_role'    => 1,
                            'expense_type'  => $contract_type+2,
                            'expense_cost'  => 0,
                            'discount'      => 0,
                            'total_cost'    => 0,
                            'created_at'    => date('Y-m-d H:i:s',time())
                        ];
                        DB::table('expense')->insert($expense_data);
                    }else{
                        // 查看是否有折扣券
                        $discount = DB::table('coupon_list')->where('used_user_id',$input['user_id'])->where('coupon_type',1)
                            ->where('activated_at','<=',date('Y-m-d',time()))->where('out_time','<=',date('Y-m-d',time()))
                            ->orderByDesc('discount')->first();
                        if($contract_type == 1){
                            $expense_rate = DB::table('sys_config')->where('code','RSF')->pluck('value')->first();
                        }elseif ($contract_type == 2){
                            $expense_rate = DB::table('sys_config')->where('code','BSF')->pluck('value')->first();
                        }elseif ($contract_type == 3){
                            $expense_rate = DB::table('sys_config')->where('code','FSF')->pluck('value')->first();
                        }else{
                            $expense_rate = DB::table('sys_config')->where('code','CSF')->pluck('value')->first();
                        }
                        $expense_cost = $arrears_data->arrears_fee*$expense_rate;
                        $user_free_balance = DB::table('user')->where('id',$input['user_id'])->pluck('free_balance')->first();
                        if($user_free_balance >0){
                            if($expense_cost < $user_free_balance){
                                $expense_data = [
                                    'expense_sn'    => expenseSn(),
                                    'user_id'   => $input['user_id'],
                                    'user_cost_role'    => 1,
                                    'expense_type'  => $contract_type+2,
                                    'expense_cost'  => 0,
                                    'discount'      => $expense_cost,
                                    'total_cost'    => $expense_cost,
                                    'created_at'    => date('Y-m-d H:i:s',time())
                                ];
                                DB::table('user')->where('id',$input['user_id'])->decrement('free_balance',$expense_cost); // 抵扣券扣款
                            }else{
                                $expense_data = [
                                    'expense_sn'    => expenseSn(),
                                    'user_id'   => $input['user_id'],
                                    'user_cost_role'    => 1,
                                    'expense_type'  => $contract_type+2,
                                    'expense_cost'  => $expense_cost-$user_free_balance,
                                    'discount'      => $user_free_balance,
                                    'total_cost'    => $expense_cost,
                                    'created_at'    => date('Y-m-d H:i:s',time())
                                ];
                                DB::table('expense')->insert($expense_data);
                                DB::table('user')->where('id',$input['user_id'])->decrement('free_balance',$expense_cost); // 抵扣券扣款
                                DB::table('user')->where('id',$input['user_id'])->decrement('balance',($expense_cost-$user_free_balance)); // 余额扣款
                            }
                        }
                        if($discount){
                            $expense_data = [
                                'expense_sn'    => expenseSn(),
                                'user_id'   => $input['user_id'],
                                'user_cost_role'    => 1,
                                'expense_type'  => $contract_type+2,
                                'expense_cost'  => $expense_cost*(100-$discount->discount)/100,
                                'discount'      => $expense_cost*$discount->discount/100,
                                'total_cost'    => $expense_cost,
                                'created_at'    => date('Y-m-d H:i:s',time())
                            ];
                            DB::table('expense')->insert($expense_data);
                            DB::table('user')->where('id',$input['user_id'])->decrement('balance',($expense_cost*(100-$discount->discount)/100)); // 余额扣款
                        }else{
                            $expense_data = [
                                'expense_sn'    => expenseSn(),
                                'user_id'   => $input['user_id'],
                                'user_cost_role'    => 1,
                                'expense_type'  => $contract_type+2,
                                'expense_cost'  => $expense_cost,
                                'discount'      => 0,
                                'total_cost'    => $expense_cost,
                                'created_at'    => date('Y-m-d H:i:s',time())
                            ];
                            DB::table('expense')->insert($expense_data);
                            // 扣费用
                            $user_free_balance = DB::table('user')->where('id',$input['user_id'])->pluck('free_balance')->first();
                            DB::table('user')->where('id',$input['user_id'])->decrement('balance',$expense_cost); // 余额扣款
                        }


                    }
                }
            }

        }
        $un_confirm = BankCheck::where('check_id',$input['check_id'])->where('is_checked',3)->get();
        foreach ($un_confirm as $k => $v){
            RentArrears::where('id',$v['match_arrears_id'])->update(['bank_check_id'=> null]);
            BankCheck::where('id',$v['id'])->update(['match_arrears_id'=> null,'is_checked'=>2]);
        }
        $data['check_id'] = $input['check_id'];
        // 返回数据
        return $this->success('match check success',$data);
    }

    /**
     * @description:银行对账确认符合费用单
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function unMatchData(array $input)
    {
        // 未确认的账目
        $un_confirm = BankCheck::where('check_id',$input['check_id'])->where('is_checked','<',3)->get();
        if($un_confirm){
            $un_confirm = $un_confirm->toArray();
            foreach ($un_confirm as $k => $v){
                $un_confirm[$k]['bank_check_date'] = date('m/d/Y',strtotime($v['bank_check_date']));
            }
            $data['un_confirm'] = $un_confirm;
        }
        // 用户未处理费用单列表
        $arrears_un_confirm = RentArrears::where('user_id',$input['user_id'])->whereIn('arrears_type',[1,2,3])->whereIn('is_pay',[1,3])->get();
        if($arrears_un_confirm){
            $arrears_un_confirm = $arrears_un_confirm->toArray();
            foreach ($arrears_un_confirm as $k => $v){
                $arrears_un_confirm[$k]['created_at'] = date('m/d/Y',strtotime($v['created_at']));
                $arrears_un_confirm[$k]['expire_date'] = date('m/d/Y',strtotime($v['expire_date']));
            }
            $data['arrears_un_confirm'] = $arrears_un_confirm;
        }
        // 用户余额
        $balance = RentContract::where('user_id',$input['user_id'])->where('balance','>',0)->get();
        if($balance){
            $balance = $balance->toArray();
            $data['balance'] = $balance;
        }
        // 返回数据
        return $this->success('match check success',$data);
    }

    /**
     * @description:银行对账余额调整
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function balanceAdjust(array $input)
    {
        $contract_id = $input['contract_id'];
        $balance_data = RentContract::where('id',$contract_id)->first();
        if($balance_data){
            $balance_data = $balance_data->toArray();
            $data['balance'] = $balance_data;
        }
        $arrears_un_confirm = RentArrears::where('contract_id',$contract_id)->whereIn('arrears_type',[1,2,3])->whereIn('is_pay',[1,3])->get();
        if($arrears_un_confirm){
            $arrears_un_confirm = $arrears_un_confirm->toArray();
            foreach ($arrears_un_confirm as $k => $v){
                $arrears_un_confirm[$k]['created_at'] = date('m/d/Y',strtotime($v['created_at']));
                $arrears_un_confirm[$k]['expire_date'] = date('m/d/Y',strtotime($v['expire_date']));
            }
            $data['arrears_un_confirm'] = $arrears_un_confirm;
        }
        // 返回数据
        return $this->success('get balance data success',$data);
    }

    /**
     * @description:银行对账余额调整确认
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function balanceAdjustConfirm(array $input)
    {
        $contract_id = $input['contract_id'];
        $adjust_info = $input['adjust_info'];
        static $error = 0;
        $model = new RentArrears();
        $balance = RentContract::where('id',$contract_id)->first();
        if(is_array($adjust_info)){
            foreach ($adjust_info as $k => $v){
                $need_pay = $model->where('id',$v['arrears_id'])->first();
                $pay_money = $v['pay_money'];
                if($pay_money == $need_pay->need_pay_fee){ // 支付金额大于应付金额 直接 销账
                    // 更改此次费用
                    $change_arrears_data = [
                        'is_pay'    => 2,
                        'pay_fee'   => $need_pay->pay_fee+$need_pay->need_pay_fee,
                        'need_pay_fee'  => 0,
                        'pay_date'      => date('Y-m-d',time()),
                        'updated_at'    => date('Y-m-d H:i:s',time()),
                    ];
                    $change_arrears_res = $model->where('id',$v)->update($change_arrears_data);
                    if(!$change_arrears_res){
                        $error += 1;
                    }
                    // 增加收账数据
                    $receive_data = [
                        'arrears_id'    => $v['arrears_id'],
                        'pay_money'     => $need_pay->need_pay_fee,
                        'pay_date'      => date('Y-m-d',time()),
                        'pay_method'    => 3,
                        'created_at'    => date('Y-m-d H:i:s',time()),
                    ];
                    $receive_res = FeeReceive::insert($receive_data);
                    RentContract::where('id',$contract_id)->decrement('balance',$pay_money);
                    if(!$receive_res){
                        $error += 1;
                    }
                    // 扣服务费用
                    if($need_pay->arrears_type == 1){

                    }else{
                        // 查看有无VIP
                        $contract_type = RentContract::where('id',$need_pay->contract_id)->pluck('contract_type')->first();
                        $vip = DB::table('vip_list')->where('user_id',$input['user_id'])->where('vip_type',$contract_type)
                            ->where('vip_start_date','<=',date('Y-m-d',time()))->where('vip_end_date','>=',date('Y-m-d',time()))->first();
                        if($vip){ // 有VIP
                            $expense_data = [
                                'expense_sn'    => expenseSn(),
                                'user_id'   => $input['user_id'],
                                'user_cost_role'    => 1,
                                'expense_type'  => $contract_type+2,
                                'expense_cost'  => 0,
                                'discount'      => 0,
                                'total_cost'    => 0,
                                'created_at'    => date('Y-m-d H:i:s',time())
                            ];
                            DB::table('expense')->insert($expense_data);
                        }else{
                            // 查看是否有折扣券
                            $discount = DB::table('coupon_list')->where('used_user_id',$input['user_id'])->where('coupon_type',1)
                                ->where('activated_at','<=',date('Y-m-d',time()))->where('out_time','<=',date('Y-m-d',time()))
                                ->orderByDesc('discount')->first();
                            if($contract_type == 1){
                                $expense_rate = DB::table('sys_config')->where('code','RSF')->pluck('value')->first();
                            }elseif ($contract_type == 2){
                                $expense_rate = DB::table('sys_config')->where('code','BSF')->pluck('value')->first();
                            }elseif ($contract_type == 3){
                                $expense_rate = DB::table('sys_config')->where('code','FSF')->pluck('value')->first();
                            }else{
                                $expense_rate = DB::table('sys_config')->where('code','CSF')->pluck('value')->first();
                            }
                            $expense_cost = $need_pay->arrears_fee*$expense_rate;
                            if($discount){
                                $expense_data = [
                                    'expense_sn'    => expenseSn(),
                                    'user_id'   => $input['user_id'],
                                    'user_cost_role'    => 1,
                                    'expense_type'  => $contract_type+2,
                                    'expense_cost'  => $expense_cost,
                                    'discount'      => $expense_cost*$discount->discount/100,
                                    'total_cost'    => $expense_cost*(100-$discount->discount)/100,
                                    'created_at'    => date('Y-m-d H:i:s',time())
                                ];
                                DB::table('expense')->insert($expense_data);
                                // 扣费用
                                $user_free_balance = DB::table('user')->where('id',$input['user_id'])->pluck('free_balance')->first();
                                if($expense_cost*(100-$discount->discount)/100 > $user_free_balance){ // 扣费大于抵扣卷
                                    DB::table('user')->where('id',$input['user_id'])->update(['free_balance'=>0,'updated_at'=>date('Y-m-d H:i:s',time())]); // 清零抵扣券
                                    DB::table('user')->where('id',$input['user_id'])->decrement('balance',($expense_cost-$user_free_balance)); // 余额扣款

                                }else{
                                    DB::table('user')->where('id',$input['user_id'])->decrement('free_balance',$expense_cost); // 抵扣券扣款
                                }
                            }else{
                                $expense_data = [
                                    'expense_sn'    => expenseSn(),
                                    'user_id'   => $input['user_id'],
                                    'user_cost_role'    => 1,
                                    'expense_type'  => $contract_type+2,
                                    'expense_cost'  => $expense_cost,
                                    'discount'      => 0,
                                    'total_cost'    => $expense_cost,
                                    'created_at'    => date('Y-m-d H:i:s',time())
                                ];
                                DB::table('expense')->insert($expense_data);
                                // 扣费用
                                $user_free_balance = DB::table('user')->where('id',$input['user_id'])->pluck('free_balance')->first();
                                if($expense_cost > $user_free_balance){ // 扣费大于抵扣卷
                                    DB::table('user')->where('id',$input['user_id'])->update(['free_balance'=>0,'updated_at'=>date('Y-m-d H:i:s',time())]); // 清零抵扣券
                                    DB::table('user')->where('id',$input['user_id'])->decrement('balance',($expense_cost-$user_free_balance)); // 余额扣款

                                }else{
                                    DB::table('user')->where('id',$input['user_id'])->decrement('free_balance',$expense_cost); // 抵扣券扣款
                                }
                            }

                        }
                    }
                }elseif ($need_pay->need_pay_fee > $pay_money && $pay_money >0){ //
                    // 更改此次费用
                    $change_arrears_data = [
                        'is_pay'        => 3,
                        'pay_fee'       => $need_pay->pay_fee+$pay_money,
                        'need_pay_fee'  => $need_pay->need_pay_fee-$pay_money,
                        'pay_date'      => date('Y-m-d',time()),
                        'updated_at'    => date('Y-m-d H:i:s',time()),
                    ];
                    $change_arrears_res = $model->where('id',$v)->update($change_arrears_data);
                    if(!$change_arrears_res){
                        $error += 1;
                    }
                    // 增加收账数据
                    $receive_data = [
                        'arrears_id'    => $v['arrears_id'],
                        'pay_money'     => $pay_money,
                        'pay_date'      => date('Y-m-d',time()),
                        'pay_method'    => 3,
                        'created_at'    => date('Y-m-d H:i:s',time()),
                    ];
                    $receive_res = FeeReceive::insert($receive_data);
                    RentContract::where('id',$contract_id)->decrement('balance',$pay_money);
                    if(!$receive_res){
                        $error += 1;
                    }
                }
            }
        }
        // 返回数据
        return $this->success('balance adjust success');
    }



    /**
     * @description:银行对账手工调整
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function bankAdjust(array $input)
    {
        $bank_check_id = $input['bank_check_id'];
        $ban_check_data = BankCheck::where('id',$bank_check_id)->first();
        if($ban_check_data){
            $ban_check_data = $ban_check_data->toArray();
            $ban_check_data['bank_check_date'] = date('m/d/Y',strtotime($ban_check_data['bank_check_date']));
            $data['bank_check_data'] = $ban_check_data;
        }
        $arrears_un_confirm = RentArrears::where('user_id',$input['user_id'])->whereIn('arrears_type',[1,2,3])->whereIn('is_pay',[1,3])->get();
        if($arrears_un_confirm){
            $arrears_un_confirm = $arrears_un_confirm->toArray();
            foreach ($arrears_un_confirm as $k => $v){
                $arrears_un_confirm[$k]['created_at'] = date('m/d/Y',strtotime($v['created_at']));
                $arrears_un_confirm[$k]['expire_date'] = date('m/d/Y',strtotime($v['expire_date']));
            }
            $data['arrears_un_confirm'] = $arrears_un_confirm;
        }
        // 返回数据
        return $this->success('get balance data success',$data);
    }

    /**
     * @description:银行对账手工调整确认
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function bankAdjustConfirm(array $input)
    {
        $bank_check_id = $input['bank_check_id'];
        $adjust_info = $input['adjust_info'];
        static $error = 0;
        $model = new RentArrears();
        $bank_check_res = BankCheck::where('id',$bank_check_id)->first();
        if(is_array($adjust_info)){
            foreach ($adjust_info as $k => $v){
                $need_pay = $model->where('id',$v['arrears_id'])->first();
                $pay_money = $v['pay_money'];
                if($pay_money == $need_pay->need_pay_fee){ // 支付金额大于应付金额 直接 销账
                    // 更改此次费用
                    $change_arrears_data = [
                        'is_pay'    => 2,
                        'pay_fee'   => $need_pay->pay_fee+$need_pay->need_pay_fee,
                        'need_pay_fee'  => 0,
                        'pay_date'      => date('Y-m-d',time()),
                        'updated_at'    => date('Y-m-d H:i:s',time()),
                    ];
                    $change_arrears_res = $model->where('id',$v)->update($change_arrears_data);
                    if(!$change_arrears_res){
                        $error += 1;
                    }
                    // 增加收账数据
                    $receive_data = [
                        'arrears_id'    => $v['arrears_id'],
                        'pay_money'     => $need_pay->need_pay_fee,
                        'pay_date'      => date('Y-m-d',time()),
                        'pay_method'    => 2,
                        'bank_check_id' => $bank_check_id,
                        'created_at'    => date('Y-m-d H:i:s',time()),
                    ];
                    $receive_res = FeeReceive::insert($receive_data);
                    $bank_check_res->amount = $bank_check_res->amount-$pay_money;
                    $bank_check_res->is_checked = 5;
                    $bank_check_res->update();
                    if(!$receive_res){
                        $error += 1;
                    }
                }elseif ($need_pay->need_pay_fee > $pay_money && $pay_money >0){ //
                    // 更改此次费用
                    $change_arrears_data = [
                        'is_pay'        => 3,
                        'pay_fee'       => $need_pay->pay_fee+$pay_money,
                        'need_pay_fee'  => $need_pay->need_pay_fee-$pay_money,
                        'pay_date'      => date('Y-m-d',time()),
                        'updated_at'    => date('Y-m-d H:i:s',time()),
                    ];
                    $change_arrears_res = $model->where('id',$v)->update($change_arrears_data);
                    if(!$change_arrears_res){
                        $error += 1;
                    }
                    // 增加收账数据
                    $receive_data = [
                        'arrears_id'    => $v['arrears_id'],
                        'pay_money'     => $pay_money,
                        'pay_date'      => date('Y-m-d',time()),
                        'pay_method'    => 2,
                        'bank_check_id' => $bank_check_id,
                        'created_at'    => date('Y-m-d H:i:s',time()),
                    ];
                    $receive_res = FeeReceive::insert($receive_data);
                    $bank_check_res->amount = $bank_check_res->amount-$pay_money;
                    $bank_check_res->is_checked = 5;
                    $bank_check_res->update();
                    if(!$receive_res){
                        $error += 1;
                    }
                }
            }
            if($bank_check_res->amount > 0){
                // 增加余额
                $contract_id = $model->where('id',$adjust_info[0]['arrears_id'])->pluck('contract_id')->first();
                $balance = RentContract::where('id',$contract_id)->first();
                $balance->balance += $bank_check_res->amount;
                $balance->update();
                // 删除这个金额
                $bank_check_res->amount = 0;
                $bank_check_res->update();
            }
            // 将此条数据变为已核对
            $bank_check_res->is_checked = 2;
            $bank_check_res->update();
        }
        // 返回数据
        return $this->success('balance adjust success');
    }



    /**
     * @description:银行对账已核对完成列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function historyList(array $input)
    {
        $check_history = BankCheck::where('user_id',$input['user_id'])->where('bank_check_type',1)->where('is_checked','>',3)->get();
        if($check_history){
            $count = BankCheck::where('user_id',$input['user_id'])->where('is_checked','>',3)->count();
            if($count <= ($input['page']-1)*5){
                return $this->error('2','get check history failed');
            }else{
                $check_history = BankCheck::where('user_id',$input['user_id'])->where('is_checked','>',3)->offset(($input['page']-1)*5)->limit(5)->get()->toArray();
                foreach ($check_history as $k => $v){
                    if($v['match_arrears_id']){
                        $check_history[$k]['pay_tenement_name'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('tenement_name')->first();
                    }else{
                        $arrears_id = FeeReceive::where('bank_check_id',$v['id'])->pluck('arrears_id')->first();
                        $check_history[$k]['pay_tenement_name'] = RentArrears::where('id',$arrears_id)->pluck('tenement_name')->first();
                    }
                }
                $data['check_history'] = $check_history;
                $data['current_page'] = (int)$input['page'];
                $data['total_page'] = ceil($count/5);
                return $this->success('get check history success',$data);
            }
        }else{
            return $this->error('2','get check history failed');
        }
    }

    /**
     * @description:银行对账未核对完成列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function unMatchList(array $input)
    {
        $check_history = BankCheck::where('user_id',$input['user_id'])->where('bank_check_type',1)->where('is_checked','<',4)->first();
        if($check_history){
            $count = BankCheck::where('user_id',$input['user_id'])->where('bank_check_type',1)->where('is_checked','<',4)->count();
            if($count <= ($input['page']-1)*5){
                return $this->error('2','get check history failed');
            }else{
                $check_history = BankCheck::where('user_id',$input['user_id'])->where('bank_check_type',1)->where('is_checked','<',4)->offset(($input['page']-1)*5)->limit(5)->get()->toArray();
                foreach ($check_history as $k => $v){
                    if($v['match_arrears_id']){
                        $check_history[$k]['pay_tenement_name'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('tenement_name')->first();
                    }else{
                        $arrears_id = FeeReceive::where('bank_check_id',$v['id'])->pluck('arrears_id')->first();
                        $check_history[$k]['pay_tenement_name'] = RentArrears::where('id',$arrears_id)->pluck('tenement_name')->first();
                    }
                }
                $data['check_history'] = $check_history;
                $data['current_page'] = (int)$input['page'];
                $data['total_page'] = ceil($count/5);
                return $this->success('get check history success',$data);
            }
        }else{
            return $this->error('2','get check history failed');
        }
    }

    /**
     * @description:银行对账详情
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function bankCheckDetail(array $input)
    {
        $bank_check_id = $input['bank_check_id'];
        $bank_check_res = BankCheck::where('id',$bank_check_id)->first()->toArray();
        $tenement_list = RentContract::where('user_id',$input['user_id'])->get()->toArray();
        foreach ($tenement_list as $k => $v){
            $tenement_data[$k]['tenement_name'] = ContractTenement::where('contract_id',$v['id'])->pluck('tenement_full_name')->first();
            $tenement_data[$k]['tenement_id'] = ContractTenement::where('contract_id',$v['id'])->pluck('tenement_id')->first();
            $tenement_data[$k]['contract_sn'] = $v['contract_id'];
            $tenement_data[$k]['contract_id'] = $v['id'];
        }
        if(!$bank_check_res){
            return $this->error('2','get bank check detail failed');
        }else{
            $data['bank_check_data'] = $bank_check_res;
            $data['tenement_list'] = $tenement_data;
            return $this->success('get bank check detail success',$data);
        }
    }


    /**
     * @description:银行对账详情
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function bankCheckList(array $input)
    {
        $check_id = $input['check_id'];
        $count = BankCheck::where('check_id',$check_id)->count();
        if($count <= ($input['page']-1)*5){
            return $this->error('2','get bank check data failed');
        }else{
            $check_res = BankCheck::where('check_id',$check_id)->offset(($input['page']-1)*5)->limit(5)->get()->toArray();
            $data['check_res'] = $check_res;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/5);
            return $this->success('get bank check data success',$data);
        }
    }

    /**
     * @description:银行对账详情 租户信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function bankCheckTenementInfo(array $input)
    {
        $tenement_id = $input['tenement_id'];
        $contract_id = $input['contract_id'];
        $tenement_info = Tenement::where('id',$tenement_id)->first()->toArray();
        $arrears_info = RentArrears::where('contract_id',$contract_id)->whereIn('is_pay',[1,3])->where('arrears_type','<',4)->get();
        if($arrears_info){
            $arrears_info = $arrears_info->toArray();
            $bond_arrears = 0;
            $rent_arrears = 0;
            $expenses_arrears = 0;
            foreach ($arrears_info as $k => $v){
                if($v['arrears_type'] == 1){
                    $bond_arrears += $v['need_pay_fee'];
                }elseif ($v['arrears_type'] == 2){
                    $rent_arrears += $v['need_pay_fee'];
                }elseif ($v['arrears_type'] == 3){
                    $expenses_arrears += $v['need_pay_fee'];
                }
            }
            $total_arrears = $bond_arrears+$rent_arrears+$expenses_arrears;
        }
        $data['tenement_info'] = $tenement_info;
        $data['arrears_info'] = $arrears_info;
        $data['total_arrares'] = $total_arrears;
        $data['bond_arrears'] = $bond_arrears;
        $data['rent_arrears'] = $rent_arrears;
        $data['expenses_arrears'] = $expenses_arrears;
        return $this->success('get tenement info success',$data);
    }


    /**
     * @description:银行对账租户信息 确认租户
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function bankCheckMatch(array $input)
    {
        $tenement_id = $input['tenement_id'];
        $is_check_match_code = $input['is_check_match_code'];
        if($is_check_match_code == 2){
            // 修改匹配码
            Tenement::where('id',$tenement_id)->update(['subject_code'=> $input['code']]);
            RentArrears::where('tenement_id',$tenement_id)->update(['subject_code'=> $input['code']]);
            //
            $bank_check_id = $input['bank_check_id'];
            BankCheck::where('id',$bank_check_id)->update(['match_tenement_id'=> $tenement_id,'is_checked'=>2,'match_tenement_name'=>$input['tenement_full_name']]);
        }else{
            $bank_check_id = $input['bank_check_id'];
            BankCheck::where('id',$bank_check_id)->update(['match_tenement_id'=> $tenement_id,'is_checked'=>2,'match_tenement_name'=>$input['tenement_full_name']]);
        }
        return $this->success('check match update success');
    }


    /**
     * @description:银行手工对账列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function handAdjustList(array $input)
    {
        $tenement_name = $input['tenement_name'];
        $model = new RentArrears();
        $model = $model->where('tenement_name','like','%'.$tenement_name.'%')->where('user_id',$input['user_id'])->where('is_pay','!=',2)->where('arrears_type','<',4);
        $page = $input['page'];
        $count = $model->count();
        if($count < ($page-1)*10){
            return $this->error('2','no arrears information');
        }else{
            $res = $model->offset(($page-1)*10)->limit(10)->get()->toArray();
            $data['arrears_list'] = $res;
            $data['current_page'] = $page;
            $data['total_page'] = ceil($count/10);
            return $this->success('gei arrears list success',$data);
        }
    }

    /**
     * @description:银行手工对账
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function handAdjust(array $input)
    {
        $model = new RentArrears();
        $pay_money = $input['pay_amount'];
        $pay_method = $input['pay_method'];
        $need_pay = $model->where('id',$input['arrears_id'])->first();
        if($pay_money >= $need_pay->need_pay_fee){ // 支付金额大于应付金额 直接 销账
            // 更改此次费用
            $change_arrears_data = [
                'is_pay'    => 2,
                'pay_fee'   => $need_pay->pay_fee+$need_pay->need_pay_fee,
                'need_pay_fee'  => 0,
                'pay_date'      => $input['pay_date'],
                'updated_at'    => date('Y-m-d H:i:s',time()),
            ];
            $change_arrears_res = $model->where('id',$input['arrears_id'])->update($change_arrears_data);
            if(!$change_arrears_res){
                return $this->error('2','hand adjust failed');
            }
            // 增加收账数据
            $receive_data = [
                'arrears_id'    => $input['arrears_id'],
                'pay_money'     => $need_pay->need_pay_fee,
                'pay_date'      => $input['pay_date'],
                'pay_method'    => $pay_method,
                'note'          => $input['note'],
                'created_at'    => date('Y-m-d H:i:s',time()),
            ];
            $receive_res = FeeReceive::insert($receive_data);
            if(!$receive_res){
                return $this->error('2','hand adjust failed');
            }
            // 修改余额
            $pay_money -= $need_pay->need_pay_fee;
        }elseif ($need_pay->need_pay_fee > $pay_money && $pay_money >0){ //
            // 更改此次费用
            $change_arrears_data = [
                'is_pay'        => 3,
                'pay_fee'       => $need_pay->pay_fee+$pay_money,
                'need_pay_fee'  => $need_pay->need_pay_fee-$pay_money,
                'pay_date'      => $input['pay_date'],
                'updated_at'    => date('Y-m-d H:i:s',time()),
            ];
            $change_arrears_res = $model->where('id',$input['arrears_id'])->update($change_arrears_data);
            if(!$change_arrears_res){
                return $this->error('2','hand adjust failed');
            }
            // 增加收账数据
            $receive_data = [
                'arrears_id'    => $input['arrears_id'],
                'pay_money'     => $pay_money,
                'pay_date'      => $input['pay_date'],
                'pay_method'    => $pay_method,
                'note'          => $input['note'],
                'created_at'    => date('Y-m-d H:i:s',time()),
            ];
            $receive_res = FeeReceive::insert($receive_data);
            if(!$receive_res){
                return $this->error('2','hand adjust failed');
            }
            // 修改余额
            $pay_money = 0;
        }
        if($pay_money){
            // 增加余额
            $contract_id = $model->where('id',$input['arrears_id'])->pluck('contract_id')->first();
            $balance_update_res = RentContract::where('id',$contract_id)->increment('balance',$pay_money);
            if(!$balance_update_res){
                return $this->error('2','hand adjust failed');
            }
        }
        return $this->success('balance adjust success');
    }

    /**
     * @description:服务商费用单列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function providersFeeList(array $input)
    {
        $model = new OrderArrears();
        $bill_date = $input['bill_date'];
        $invoice_sn = $input['invoice_sn'];
        $providers_id = $input['providers_id'];
        $model = $model->where('user_id',$input['user_id']);
        if($bill_date){
            $model = $model->where('invoice_date',$bill_date);
        }
        if($providers_id){
            $model = $model->where('providers_id',$providers_id);
        }
        if($invoice_sn){
            $model = $model->where('invoice_sn','like','%'.$invoice_sn.'%');
        }
        $page = $input['page'];
        $count = $model->count();
        if($count <($page-1)*10){
            return $this->error('2','get fee list failed');
        }else{
            $res = $model->offset(($page-1)*10)->limit(10)->orderBy('id','DESC')->get();
            foreach ($res as $k => $v){
                $res[$k]['providers_name'] = Providers::where('id',$v['providers_id'])->pluck('service_name')->first();
            }
            $data['invoice_list'] = $res;
            $data['current_page'] = $page;
            $data['total_page'] = ceil($count/10);
            return $this->success('get invoice list success',$data);
        }
    }


    /**
     * @description:服务商添加费用单(开发票)
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function providersFeeAdd(array $input)
    {
        $model = new OrderArrears();
        $oder_id = $input['order_id'];
        $order_info = LandlordOrder::where('id',$oder_id)->first();
        $error = 0;
        foreach ($input['arrears_list'] as $k => $v){
            $order_arrears_data = [
                'order_id'          => $oder_id,
                'order_sn'          => $order_info->order_sn,
                'user_id'           => $input['user_id'],
                'landlord_user_id'  => $order_info->user_id,
                'providers_id'      => $input['providers_id'],
                'invoice_date'      => $input['invoice_date'],
                'invoice_due_date'  => $input['invoice_due_date'],
                'landlord_name'     => Landlord::where('user_id',$order_info->user_id)->pluck('landlord_name')->first(),
                'items_name'        => $v['items_name'],
                'describe'          => $v['describe'],
                'unit_price'        => $v['unit_price'],
                'number'            => $v['number'],
                'invoice_sn'        => $input['invoice_sn'],
                'subject_code'      => $v['subject_code'],
                'discount'          => $v['discount'],
                'tex'               => $v['tex'],
                'arrears_fee'       => ($v['unit_price']*$v['number'])*(100-$v['discount'])/100*(100+$v['tex'])/100,
                'need_pay_fee'      => ($v['unit_price']*$v['number'])*(100-$v['discount'])/100*(100+$v['tex'])/100,
                'Region'            => $order_info->Region,
                'TA'                => $order_info->TA,
                'District'          => $order_info->District,
                'rent_house_id'     => $order_info->rent_house_id,
                'pay_fee'           => 0,
                'is_pay'            => 1,
                'note'              => $input['note'],
                'created_at'        => date('Y-m-d H:i:s',time()),
            ];
            $arrears_res = $model->insert($order_arrears_data);
            $order_sn = LandlordOrder::where('id',$input['order_id'])->pluck('order_sn')->first();
            $order_required = LandlordOrder::where('id',$input['order_id'])->pluck('requirement')->first();
            $rent_house_id = LandlordOrder::where('id',$input['order_id'])->pluck('rent_house_id')->first();
            $property_address = RentHouse::where('id',$rent_house_id)->pluck('address')->first();
            $providers_id = LandlordOrder::where('id',$input['order_id'])->pluck('providers_id')->first();
            $providers_name = Providers::where('id',$providers_id)->pluck('service_name')->first();
            $landlord_user_id = LandlordOrder::where('id',$input['order_id'])->pluck('user_id')->first();
            $landlord_name = \App\Model\User::where('id',$landlord_user_id)->pluck('nickname')->first();
            $task_data = [
                'user_id'           => $landlord_user_id,
                'task_type'         => 23,
                'task_start_time'   => date('Y-m-d H:i:s',time()),
                'task_status'       => 0,
                'task_title'        => 'INVOICE',
                'task_content'      => "INVOICE
Property:$property_address
Landlord: $landlord_name
Details: $order_required
The above work has been completed, you can issue an invoice to the landlord..",
                'order_id'          => $input['order_id'],
                'task_role'         => 2,
                'created_at'        => date('Y-m-d H:i:s',time()),
            ];
            $task_res = Task::insert($task_data);
            if(!$arrears_res){
                $error += 1;
            }
        }
        if(!$error){
            return $this->success('add invoice success');
        }else{
            return $this->error('2','add invoice failed');
        }
    }

    /**
     * @description:服务商已接订单列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function providersOrderList(array $input)
    {
        $service_ids = Providers::where('user_id',$input['user_id'])->pluck('id');
        $model = new LandlordOrder();
        $res = $model->whereIn('providers_id',$service_ids)->first();
        if(!$res){
            return $this->error('2','no order doing');
        }else{
            $order_res = $model->whereIn('providers_id',$service_ids)->get()->toArray();
            foreach ($order_res as $k => $v){
                $order_list[$k]['order_id'] = $v['id'];
                $order_list[$k]['order_sn'] = $v['order_sn'];
                $order_list[$k]['landlord_name'] = Landlord::where('user_id',$v['user_id'])->pluck('landlord_name')->first();
                $order_list[$k]['phone'] = Landlord::where('user_id',$v['user_id'])->pluck('phone')->first();
                $order_list[$k]['email'] = Landlord::where('user_id',$v['user_id'])->pluck('email')->first();
                $order_list[$k]['address'] = Landlord::where('user_id',$v['user_id'])->pluck('mail_address')->first();
                $order_list[$k]['subject_code'] = Landlord::where('user_id',$v['user_id'])->pluck('subject_code')->first();
            }
            $data['order_info'] = $order_list;
            return $this->success('get order list success',$data);
        }
    }


    /**
     * @description:服务商财务列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function providersFinancialList(array $input)
    {
        $model = new OrderArrears();
        $Region = $input['Region'];
        $TA = $input['TA'];
        $District = $input['District'];
        $landlord_name = $input['landlord_name'];
        $model = $model->where('user_id',$input['user_id']);
        if($Region){
            $model = $model->where('Region',$Region);
        }
        if($TA){
            $model = $model->where('TA',$TA);
        }
        if($District){
            $model = $model->where('District',$District);
        }
        if($landlord_name){
            $model = $model->where('landlord_name','like','%'.$landlord_name.'%');
        }
        $page = $input['page'];
        $count = $model->count();
        if($count <= ($page-1)*10){
            return $this->error('2','get fee list failed');
        }else{
            $res = $model->offset(($page-1)*10)->limit(10)->get()->toArray();
            foreach($res as $k => $v){
                $invoice_list[$k] = $v;
                $invoice_list[$k]['email'] = Landlord::where('user_id',$v['landlord_user_id'])->pluck('landlord_name')->first();
                $invoice_list[$k]['property_name'] = RentHouse::where('id',$v['rent_house_id'])->pluck('property_name')->first();
            }
            $data['invoice_list'] = $invoice_list;
            $data['current_page'] = $page;
            $data['total_page'] = ceil($count/10);
            return $this->success('get invoice list success',$data);
        }
    }

    /**
     * @description: 服务商银行对账上传CSV文件
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function providersBankCheck(array $input,$file)
    {
        $excel = new Excel();
        $data = $excel::toArray(new UsersImport(), $file);
        $data = $data[0];
        if($input['bank_type'] == 'ANZ'){
            if($data[0][0] != 'Type' || $data[0][1] != 'Details' || $data[0][2] != 'Particulars' || $data[0][3] != 'Code' || $data[0][4] != 'Reference' || $data[0][5] != 'Amount' || $data[0][6] != 'Date' || $data[0][7] != 'ForeignCurrencyAmount' || $data[0][8] != 'ConversionCharge'){
                return $this->error('3','the csv file is not the select bank');
            }else{
                $check_id = BankCheck::max('check_id');
                $i = 1;
                static $success_count = 0;
                static $failed_count = 0;
                while (!empty($data[$i][0])) {
                    // 处理数据
                    if($data[$i][0] == 'Deposit' || $data[$i][0] == 'Bill Payment' || $data[$i][0] == 'Direct Credit'){
                        $particulars = $data[$i][2];
                        $code = $data[$i][3];
                        $reference = $data[$i][4];
                        $amount = $data[$i][5];
                        $date = $data[$i][6];
                        $transdate = explode('/',$date);
                        $date = $transdate[1].'/'.$transdate[0].'/'.$transdate[2];
                        $date = date('Y-m-d',strtotime($date));
                        if(strtotime($date) >= strtotime($input['check_start_date'])&&strtotime($date) <= strtotime($input['check_end_date'])+3600*24-1){
                            // 匹配
                            $match_res = OrderArrears::where/*('need_pay_fee',$amount)->where*/('user_id',$input['user_id'])/*->whereIn('is_pay',[1,3])*/->where('subject_code',$code)->first();
                            if($match_res){ // 匹配成功
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'ANZ',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_landlord_id' => $match_res->landlord_user_id,
                                    'match_landlord_name'   => $match_res->landlord_name,
                                    'bank_check_type'       => 2,
                                    /*'match_arrears_id'  => $match_res->id,*/
                                    'is_checked'        => 2,
                                    'bank_check_detail' => $data[$i][0].''.$data[$i][1].''.$data[$i][2].''.$data[$i][3].''.$data[$i][4].''.$data[$i][5].''.$data[$i][6].''.$data[$i][7].''.$data[$i][8],
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res){ // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i+1;
                                }else{
                                }
                            }else{
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'ANZ',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'bank_check_type'   => 2,
                                    'is_checked'        => 1,
                                    'bank_check_detail' => $data[$i][0].''.$data[$i][1].''.$data[$i][2].''.$data[$i][3].''.$data[$i][4].''.$data[$i][5].''.$data[$i][6].''.$data[$i][7].''.$data[$i][8],
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res) { // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i + 1;
                                }
                            }
                        }
                    }
                    $i++;
                }
                $match_up_res = BankCheck::where('check_id',$check_id+1)->get()->toArray();
                if($match_up_res){
                    $check_data['check_id'] = $check_id+1;
                    return $this->success('match success',$check_data);
                }else{
                    $check_data['check_id'] = $check_id+1;
                    return $this->error('2','no match data',$check_data);
                }
            }
        }elseif ($input['bank_type'] == 'BNZ'){
            if($data[0][0] != 'Date' || $data[0][1] != 'Amount' || $data[0][2] != 'Payee' || $data[0][3] != 'Particulars' || $data[0][4] != 'Code' || $data[0][5] != 'Reference' || $data[0][6] != 'Tran Type' || $data[0][7] != 'This Party Account' || $data[0][8] != 'Other Party Account' || $data[0][9] != 'Serial' || $data[0][10] != 'Transaction Code' || $data[0][11] != 'Batch Number' || $data[0][12] != 'Originating Bank/Branch' || $data[0][13] != 'Processed Date'){
                return $this->error('3','the csv file is not the select bank');
            }else{
                $check_id = BankCheck::max('check_id');
                $i = 1;
                static $success_count = 0;
                static $failed_count = 0;
                while (!empty($data[$i][0])) {
                    // 处理数据
                    if($data[$i][2] == 'DEPOSIT' || $data[$i][2] == 'BILL PAYMENT' || $data[$i][2] == 'DIRECT CREDIT'){
                        $particulars = $data[$i][3];
                        $code = $data[$i][4];
                        $reference = $data[$i][5];
                        $amount = $data[$i][1];
                        $date = $data[$i][0];
                        $transdate = explode('/',$date);
                        $date = $transdate[1].'/'.$transdate[0].'/'.$transdate[2];
                        $date = date('Y-m-d',strtotime($date));
                        if(strtotime($date) >= strtotime($input['check_start_date'])&&strtotime($date) <= strtotime($input['check_end_date'])+3600*24-1){
                            // 匹配
                            $match_res = OrderArrears::where/*('need_pay_fee',$amount)->where*/('user_id',$input['user_id'])/*->whereIn('is_pay',[1,3])*/->where('subject_code',$code)->first();
                            if($match_res){ // 匹配成功
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'BNZ',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_landlord_id' => $match_res->landlord_user_id,
                                    'match_landlord_name'   => $match_res->landlord_name,
                                    'bank_check_type'       => 2,
                                    /*'match_arrears_id'  => $match_res->id,*/
                                    'is_checked'        => 2,
                                    'bank_check_detail' => $data[$i][0].''.$data[$i][1].''.$data[$i][2].''.$data[$i][3].''.$data[$i][4].''.$data[$i][5].''.$data[$i][6].''.$data[$i][7].''.$data[$i][8].''.$data[$i][9].''.$data[$i][10].''.$data[$i][11].''.$data[$i][12].''.$data[$i][13],
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res){ // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i+1;
                                }else{
                                }
                            }else{
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'BNZ',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'bank_check_type'       => 2,
                                    'is_checked'        => 1,
                                    'bank_check_detail' => $data[$i][0].''.$data[$i][1].''.$data[$i][2].''.$data[$i][3].''.$data[$i][4].''.$data[$i][5].''.$data[$i][6].''.$data[$i][7].''.$data[$i][8].''.$data[$i][9].''.$data[$i][10].''.$data[$i][11].''.$data[$i][12].''.$data[$i][13],
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res) { // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i + 1;
                                }
                            }
                        }
                    }
                    $i++;
                }
                $match_up_res = BankCheck::where('check_id',$check_id+1)->get()->toArray();
                if($match_up_res){
                    $check_data['check_id'] = $check_id+1;
                    return $this->success('match success',$check_data);
                }else{
                    $check_data['check_id'] = $check_id+1;
                    return $this->error('2','no match data',$check_data);
                }
            }
        }elseif ($input['bank_type'] == 'westpac'){
            if($data[0][0] != 'Date' || $data[0][1] != 'Amount' || $data[0][2] != 'Other Party' || $data[0][3] != 'Description' || $data[0][4] != 'Reference' || $data[0][5] != 'Particulars' || $data[0][6] != 'Analysis Code'){
                return $this->error('3','the csv file is not the select bank');
            }else{
                $check_id = BankCheck::max('check_id');
                $i = 1;
                static $success_count = 0;
                static $failed_count = 0;
                while (!empty($data[$i][0])) {
                    // 处理数据
                    if($data[$i][3] == 'DEPOSIT' || $data[$i][3] == 'BILL PAYMENT' || $data[$i][3] == 'DIRECT CREDIT'){
                        $particulars = $data[$i][5];
                        $code = $data[$i][6];
                        $reference = $data[$i][4];
                        $amount = $data[$i][1];
                        $date = $data[$i][0];
                        $transdate = explode('/',$date);
                        $date = $transdate[1].'/'.$transdate[0].'/'.$transdate[2];
                        $date = date('Y-m-d',strtotime($date));
                        if(strtotime($date) >= strtotime($input['check_start_date'])&&strtotime($date) <= strtotime($input['check_end_date'])+3600*24-1){
                            // 匹配
                            $match_res = OrderArrears::/*where('need_pay_fee',$amount)->*/where('user_id',$input['user_id'])/*->whereIn('is_pay',[1,3])*/->where('subject_code',$code)->first();
                            if($match_res){ // 匹配成功
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'westpac',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_landlord_id' => $match_res->landlord_user_id,
                                    'match_landlord_name'   => $match_res->landlord_name,
                                    'bank_check_type'       => 2,
                                    /*'match_arrears_id'  => $match_res->id,*/
                                    'is_checked'        => 2,
                                    'bank_check_detail' => $data[$i][0].''.$data[$i][1].''.$data[$i][2].''.$data[$i][3].''.$data[$i][4].''.$data[$i][5].''.$data[$i][6],
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res){ // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i+1;
                                }else{
                                }
                            }else{
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'westpac',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'bank_check_type'       => 2,
                                    'is_checked'        => 1,
                                    'bank_check_detail' => $data[$i][0].''.$data[$i][1].''.$data[$i][2].''.$data[$i][3].''.$data[$i][4].''.$data[$i][5].''.$data[$i][6],
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res) { // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i + 1;
                                }
                            }
                        }
                    }
                    $i++;
                }
                $match_up_res = BankCheck::where('check_id',$check_id+1)/*->where('match_arrears_id','>',0)*/->get()->toArray();
                if($match_up_res){
                    $check_data['check_id'] = $check_id+1;
                    return $this->success('match success',$check_data);
                }else{
                    $check_data['check_id'] = $check_id+1;
                    return $this->error('2','no match data',$check_data);
                }
            }
        }elseif ($input['bank_type'] == 'ASB'){
            if($data[6][0] != 'Date' || $data[6][1] != 'Unique Id' || $data[6][2] != 'Tran Type' || $data[6][3] != 'Cheque Number' || $data[6][4] != 'Payee' || $data[6][5] != 'Memo' || $data[6][6] != 'Amount'){
                return $this->error('3','the csv file is not the select bank');
            }else{
                $check_id = BankCheck::max('check_id');
                $i = 8;
                static $success_count = 0;
                static $failed_count = 0;
                while (!empty($data[$i][0])) {
                    // 处理数据
                    if($data[$i][2] == 'CREDIT' || $data[$i][2] == 'D/C'){
                        $particulars = $data[$i][5];
                        $code = $data[$i][5];
                        $reference = $data[$i][4];
                        $amount = $data[$i][6];
                        $date = $data[$i][0];
                        $date = date('Y-m-d',strtotime($date));
                        if(strtotime($date) >= strtotime($input['check_start_date'])&&strtotime($date) <= strtotime($input['check_end_date'])+3600*24-1){
                            // 匹配
                            $match_res = OrderArrears::/*where('need_pay_fee',$amount)->*/where('user_id',$input['user_id'])/*->whereIn('is_pay',[1,3])*/->where('subject_code',$code)->first();
                            if($match_res){ // 匹配成功
                                $bank_check_data = [
                                    'user_id'   => $input['user_id'],
                                    'check_id'  => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'ASB',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_landlord_id' => $match_res->landlord_user_id,
                                    'match_landlord_name'   => $match_res->landlord_name,
                                    'bank_check_type'       => 2,
                                    /*'match_arrears_id'  => $match_res->id,*/
                                    'is_checked'        => 2,
                                    'bank_check_detail' => $data[$i][0].''.$data[$i][1].''.$data[$i][2].''.$data[$i][3].''.$data[$i][4].''.$data[$i][5].''.$data[$i][6],
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res){ // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i+1;
                                }else{
                                }
                            }else{
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'ASB',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'is_checked'        => 1,
                                    'bank_check_type'       => 2,
                                    'bank_check_detail' => $data[$i][0].''.$data[$i][1].''.$data[$i][2].''.$data[$i][3].''.$data[$i][4].''.$data[$i][5].''.$data[$i][6],
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res) { // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i + 1;
                                }
                            }
                        }
                    }
                    $i++;
                }
                $match_up_res = BankCheck::where('check_id',$check_id+1)/*->where('match_arrears_id','>',0)*/->get()->toArray();
                if($match_up_res){
                    $check_data['check_id'] = $check_id+1;
                    return $this->success('match success',$check_data);
                }else{
                    $check_data['check_id'] = $check_id+1;
                    return $this->error('2','no match data',$check_data);
                }
            }
        }elseif ($input['bank_type'] == 'kiwi'){
            if(substr($data[0][0],0,2) != '38' ){
                return $this->error('3','the csv file is not the select bank');
            }else{
                $check_id = BankCheck::max('check_id');
                $i = 1;
                static $success_count = 0;
                static $failed_count = 0;
                while (!empty($data[$i][0])) {
                    // 处理数据
                    if($data[$i][3] > 0){
                        $particulars = $data[$i][1];
                        $code = $data[$i][1];
                        $reference = $data[$i][1];
                        $amount = $data[$i][3];
                        $date = $data[$i][0];
                        $transdate = explode('/',$date);
                        $date = $transdate[1].'/'.$transdate[0].'/'.$transdate[2];
                        $date = date('Y-m-d',strtotime($date));
                        if(strtotime($date) >= strtotime($input['check_start_date'])&&strtotime($date) <= strtotime($input['check_end_date'])+3600*24-1){
                            // 匹配
                            $match_res = OrderArrears::/*where('need_pay_fee',$amount)->*/where('user_id',$input['user_id'])->/*whereIn('is_pay',[1,3])->*/where('subject_code',$code)->first();
                            if($match_res){ // 匹配成功
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'kiwi',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_landlord_id' => $match_res->landlord_user_id,
                                    'match_landlord_name'   => $match_res->landlord_name,
                                    'bank_check_type'       => 2,
                                    /*'match_arrears_id'  => $match_res->id,*/
                                    'is_checked'        => 2,
                                    'bank_check_detail' => $data[$i][0].''.$data[$i][1].''.$data[$i][2].''.$data[$i][3].''.$data[$i][4],
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res){ // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i+1;
                                }else{
                                }
                            }else{
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'kiwi',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'bank_check_type'       => 2,
                                    'is_checked'        => 1,
                                    'bank_check_detail' => $data[$i][0].''.$data[$i][1].''.$data[$i][2].''.$data[$i][3].''.$data[$i][4],
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res) { // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i + 1;
                                }
                            }
                        }
                    }
                    $i++;
                }
                $match_up_res = BankCheck::where('check_id',$check_id+1)/*->where('match_arrears_id','>',0)*/->get()->toArray();
                if($match_up_res){
                    $check_data['check_id'] = $check_id+1;
                    return $this->success('match success',$check_data);
                }else{
                    $check_data['check_id'] = $check_id+1;
                    return $this->error('2','no match data',$check_data);
                }
            }
        }elseif ($input['bank_type'] == 'TSB'){
            if($data[0][0] != 'Date' || $data[0][1] != 'Amount' || $data[0][2] != 'Reference' || $data[0][3] != 'Description' || $data[0][4] != 'Particulars'){
                return $this->error('3','the csv file is not the select bank');
            }else{
                $check_id = BankCheck::max('check_id');
                $i = 1;
                static $success_count = 0;
                static $failed_count = 0;
                while (!empty($data[$i][0])) {
                    // 处理数据
                    if($data[$i][1] > 0){
                        $particulars = $data[$i][4];
                        $code = $data[$i][3];
                        $reference = $data[$i][2];
                        $amount = $data[$i][1];
                        $date = $data[$i][0];
                        $transdate = explode('/',$date);
                        $date = $transdate[1].'/'.$transdate[0].'/'.$transdate[2];
                        $date = date('Y-m-d',strtotime($date));
                        if(strtotime($date) >= strtotime($input['check_start_date'])&&strtotime($date) <= strtotime($input['check_end_date'])+3600*24-1){
                            // 匹配
                            $match_res = OrderArrears::/*where('need_pay_fee',$amount)->*/where('user_id',$input['user_id'])/*->whereIn('is_pay',[1,3])*/->where('subject_code',$code)->first();
                            if($match_res){ // 匹配成功
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'TSB',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_landlord_id' => $match_res->landlord_user_id,
                                    'match_landlord_name'   => $match_res->landlord_name,
                                    'bank_check_type'       => 2,
                                    'is_checked'        => 2,
                                    'bank_check_detail' => $data[$i][0].''.$data[$i][1].''.$data[$i][2].''.$data[$i][3].''.$data[$i][4],
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res){ // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i+1;
                                }else{
                                }
                            }else{
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'TSB',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    /*'match_arrears_id'  => $match_res->id,*/
                                    'bank_check_type'       => 2,
                                    'is_checked'        => 1,
                                    'bank_check_detail' => $data[$i][0].''.$data[$i][1].''.$data[$i][2].''.$data[$i][3].''.$data[$i][4],
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res) { // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i + 1;
                                }
                            }
                        }
                    }
                    $i++;
                }
                $match_up_res = BankCheck::where('check_id',$check_id+1)/*->where('match_arrears_id','>',0)*/->get()->toArray();
                if($match_up_res){
                    $check_data['check_id'] = $check_id+1;
                    return $this->success('match success',$check_data);
                }else{
                    $check_data['check_id'] = $check_id+1;
                    return $this->error('2','no match data',$check_data);
                }
            }
        }elseif ($input['bank_type'] == 'co-operative'){
            if($data[0][0] != 'Date' || $data[0][1] != 'Details' || $data[0][2] != 'Amount' || $data[0][3] != 'Balance'){
                return $this->error('3','the csv file is not the select bank');
            }else{
                $check_id = BankCheck::max('check_id');
                $i = 1;
                $success_count = 0;
                $failed_count = 0;
                while (!empty($data[$i][0])) {
                    // 处理数据
                    $match_data = explode('-',$data[$i][1]);
                    if($match_data[0] == 'DC' || $match_data[0] == 'DEPOSIT'){
                        $particulars = $data[$i][4];
                        $code = $data[$i][3];
                        $reference = $data[$i][2];
                        $amount = $data[$i][1];
                        $date = $data[$i][0];
                        $transdate = explode('/',$date);
                        $date = $transdate[1].'/'.$transdate[0].'/'.$transdate[2];
                        $date = date('Y-m-d',strtotime($date));
                        if(strtotime($date) >= strtotime($input['check_start_date'])&&strtotime($date) <= strtotime($input['check_end_date'])+3600*24-1){
                            // 匹配
                            $match_res = OrderArrears::/*where('need_pay_fee',$amount)->*/where('user_id',$input['user_id'])/*->whereIn('is_pay',[1,3])*/->where('subject_code',$code)->first();
                            if($match_res){ // 匹配成功
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'co-operative',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_landlord_id' => $match_res->landlord_user_id,
                                    'match_landlord_name'   => $match_res->landlord_name,
                                    'bank_check_type'       => 2,
                                    /*'match_arrears_id'  => $match_res->id,*/
                                    'is_checked'        => 2,
                                    'bank_check_detail' => $data[$i][0].''.$data[$i][1].''.$data[$i][2].''.$data[$i][3],
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res){ // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i+1;
                                }else{
                                    // 匹配成功的 费用单 更新对应的 对账id
                                    /*$match_res->bank_check_id = $bank_check_res;
                                    $match_res->update();*/
                                }
                            }else{
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'co-operative',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'bank_check_type'       => 2,
                                    'is_checked'        => 1,
                                    'bank_check_detail' => $data[$i][0].''.$data[$i][1].''.$data[$i][2].''.$data[$i][3].''.$data[$i][4],
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res) { // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i + 1;
                                }
                            }
                        }
                    }
                    $i++;
                }
                $match_up_res = BankCheck::where('check_id',$check_id+1)/*->where('match_arrears_id','>',0)*/->get()->toArray();
                if($match_up_res){
                    $check_data['check_id'] = $check_id+1;
                    return $this->success('match success',$check_data);
                }else{
                    $check_data['check_id'] = $check_id+1;
                    return $this->error('2','no match data',$check_data);
                }
            }
        }
    }

    /**
     * @description:服务商银行对账详情
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function providersBankCheckList(array $input)
    {
        $check_id = $input['check_id'];
        $count = BankCheck::where('check_id',$check_id)->count();
        if($count <= ($input['page']-1)*5){
            return $this->error('2','get bank check data failed');
        }else{
            $check_res = BankCheck::where('check_id',$check_id)->offset(($input['page']-1)*5)->limit(5)->get()->toArray();
            $data['check_res'] = $check_res;
            $data['current_page'] = $input['page'];
            $data['total_page'] = ceil($count/5);
            return $this->success('get bank check data success',$data);
        }
    }


    /**
     * @description: 服务商银行对账详情
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function providersBankCheckDetail(array $input)
    {
        $bank_check_id = $input['bank_check_id'];
        $bank_check_res = BankCheck::where('id',$bank_check_id)->first()->toArray();
        $providers_id = Providers::where('user_id',$input['user_id'])->pluck('id');
        $landlord_list = LandlordOrder::whereIn('providers_id',$providers_id)->get()->toArray();
        foreach ($landlord_list as $k => $v){
            $landlord_data[$k]['landlord_name'] = Landlord::where('user_id',$v['user_id'])->pluck('landlord_name')->first();
            $landlord_data[$k]['landlord_id'] = $v['user_id'];
            $landlord_data[$k]['order_sn'] = $v['order_sn'];
            $landlord_data[$k]['order_id'] = $v['id'];
        }
        if(!$bank_check_res){
            return $this->error('2','get bank check detail failed');
        }else{
            $data['bank_check_data'] = $bank_check_res;
            $data['landlord_list'] = $landlord_data;
            return $this->success('get bank check detail success',$data);
        }
    }

    /**
     * @description:服务商银行对账详情 房东信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function providersBankCheckLandlordInfo(array $input)
    {
        $landlord_id = $input['landlord_id'];
        $order_id = $input['order_id'];
        $landlord_info = Landlord::where('user_id',$landlord_id)->first()->toArray();
        $arrears_info = OrderArrears::where('order_id',$order_id)->whereIn('is_pay',[1,3])->get();
        $total_arrears = 0;
        if($arrears_info){
            $arrears_info = $arrears_info->toArray();
            foreach ($arrears_info as $k => $v){
                $total_arrears += $v['need_pay_fee'];
            }
        }
        $data['landlord_info'] = $landlord_info;
        $data['arrears_info'] = $arrears_info;
        $data['total_arrares'] = $total_arrears;
        return $this->success('get landlord info success',$data);
    }

    /**
     * @description:银行对账确认符合费用单
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function providersMatchData(array $input)
    {
        $check_id = $input['check_id'];
        $match_code_res = BankCheck::where('check_id',$check_id)->where('is_checked',2)->get()->toArray();
        if($match_code_res){
            foreach ($match_code_res as $k => $v){
                $amount = $v['amount'];
                $landlord_id = $v['match_landlord_id'];
                $match_res = OrderArrears::where('user_id',$input['user_id'])->where('need_pay_fee',$amount)->where('landlord_user_id',$landlord_id)->where('bank_check_id',null)->first();
                if($match_res){
                    BankCheck::where('id',$v['id'])->update(['match_order_id'=> $match_res->id,'is_checked'=>3]);
                    $match_res->bank_check_id = $v['id'];
                    $match_res->update();
                }
            }
            $match_up_res = BankCheck::where('check_id',$check_id)->where('is_checked',3)->get()->toArray();
            if($match_up_res){
                foreach ($match_up_res as $k => $v){
                    $res[$k]['bank_check_id'] = $v['id'];
                    $res[$k]['landlord_name'] = OrderArrears::where('id',$v['match_order_id'])->pluck('landlord_name')->first();
                    $res[$k]['payment_amount'] = $v['amount'];
                    $res[$k]['payment_date'] = date('m/d/Y',strtotime($v['bank_check_date']));
                    $res[$k]['match_code'] = $v['match_code'];
                    $res[$k]['arrears_amount'] = OrderArrears::where('id',$v['match_order_id'])->pluck('need_pay_fee')->first();
                    $res[$k]['invoice_date'] = date('m/d/Y',strtotime(RentArrears::where('id',$v['match_arrears_id'])->pluck('created_at')->first()));
                    $res[$k]['due_date'] = date('m/d/Y',strtotime(RentArrears::where('id',$v['match_arrears_id'])->pluck('expire_date')->first()));
                    $res[$k]['subject_code'] = OrderArrears::where('id',$v['match_order_id'])->pluck('subject_code')->first();
                }
                $check_data['check_res'] = $res;
                $check_data['check_id'] = $check_id;
                return $this->success('match success',$check_data);
            }
            return $this->error('2','no match data');
        }else{
            return $this->error('2','no match data');
        }
    }

    /**
     * @description:银行对账确认符合费用单
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function providersConfirmMatchCheck(array $input)
    {
        $match_ids = $input['match_ids'];
        if(is_array($match_ids)){
            // 修改 已核对的账目
            foreach ($match_ids as $k => $v){
                $bank_check_data = BankCheck::where('id',$v)->first();
                $bank_check_data->is_checked = 4;
                $bank_check_data->updated_at = date('Y-m-d H:i:s',time());
                $bank_check_data->update();
                // 收费单增加数据
                $receives_data = [
                    'fee_type'      => 2,
                    'order_id'      => $bank_check_data->match_order_id,
                    'pay_money'     => $bank_check_data->amount,
                    'pay_date'      => $bank_check_data->bank_check_date,
                    'pay_method'    => 2,
                    'bank_check_id' => $v,
                ];
                $receives_res = FeeReceive::insert($receives_data);
                $order_data = OrderArrears::where('id',$bank_check_data->match_order_id)->first();
                $change_order_data = [
                    'is_pay'    => 2,
                    'pay_fee'   => $order_data->pay_fee+$bank_check_data->amount,
                    'need_pay_fee'  => 0,
                    'pay_date'      => $bank_check_data->bank_check_date,
                    'updated_at'    => date('Y-m-d H:i:s',time()),
                ];
                OrderArrears::where('id',$bank_check_data->match_order_id)->update($change_order_data);
                // 扣服务费用
                $order_type = LandlordOrder::where('id',$order_data->order_id)->pluck('order_type')->first();
                // 查看是否有折扣券
                $discount = DB::table('coupon_list')->where('used_user_id',$input['user_id'])->where('coupon_type',1)
                    ->where('activated_at','<=',date('Y-m-d',time()))->where('out_time','<=',date('Y-m-d',time()))
                    ->orderByDesc('discount')->first();
                if($order_type == 1){
                    $expense_rate = DB::table('sys_config')->where('code','PSFL')->pluck('value')->first();
                }elseif ($order_type == 2){
                    $expense_rate = DB::table('sys_config')->where('code','PSFL')->pluck('value')->first();
                }elseif ($order_type == 3){
                    $expense_rate = DB::table('sys_config')->where('code','PSFI')->pluck('value')->first();
                }elseif ($order_type == 4){
                    $expense_rate = DB::table('sys_config')->where('code','PSFM')->pluck('value')->first();
                }else{
                    $expense_rate = DB::table('sys_config')->where('code','PSFLI')->pluck('value')->first();
                }
                $expense_cost = $order_data->arrears_fee*$expense_rate;
                $user_free_balance = DB::table('user')->where('id',$input['user_id'])->pluck('free_balance')->first();
                if($user_free_balance >0){
                    if($expense_cost < $user_free_balance){
                        $expense_data = [
                            'expense_sn'    => expenseSn(),
                            'user_id'   => $input['user_id'],
                            'user_cost_role'    => 2,
                            'expense_type'  => $order_type+6,
                            'expense_cost'  => 0,
                            'discount'      => $expense_cost,
                            'total_cost'    => $expense_cost,
                            'created_at'    => date('Y-m-d H:i:s',time())
                        ];
                        DB::table('user')->where('id',$input['user_id'])->decrement('free_balance',$expense_cost); // 抵扣券扣款
                    }else{
                        $expense_data = [
                            'expense_sn'    => expenseSn(),
                            'user_id'   => $input['user_id'],
                            'user_cost_role'    => 2,
                            'expense_type'  => $order_type+6,
                            'expense_cost'  => $expense_cost-$user_free_balance,
                            'discount'      => $user_free_balance,
                            'total_cost'    => $expense_cost,
                            'created_at'    => date('Y-m-d H:i:s',time())
                        ];
                        DB::table('expense')->insert($expense_data);
                        DB::table('user')->where('id',$input['user_id'])->decrement('free_balance',$expense_cost); // 抵扣券扣款
                        DB::table('user')->where('id',$input['user_id'])->decrement('balance',($expense_cost-$user_free_balance)); // 余额扣款
                    }
                }
                if($discount){
                    $expense_data = [
                        'expense_sn'    => expenseSn(),
                        'user_id'   => $input['user_id'],
                        'user_cost_role'    => 2,
                        'expense_type'  => $order_type+6,
                        'expense_cost'  => $expense_cost*(100-$discount->discount)/100,
                        'discount'      => $expense_cost*$discount->discount/100,
                        'total_cost'    => $expense_cost,
                        'created_at'    => date('Y-m-d H:i:s',time())
                    ];
                    DB::table('expense')->insert($expense_data);
                    DB::table('user')->where('id',$input['user_id'])->decrement('balance',($expense_cost*(100-$discount->discount)/100)); // 余额扣款
                }else{
                    $expense_data = [
                        'expense_sn'    => expenseSn(),
                        'user_id'   => $input['user_id'],
                        'user_cost_role'    => 2,
                        'expense_type'  => $order_type+6,
                        'expense_cost'  => $expense_cost,
                        'discount'      => 0,
                        'total_cost'    => $expense_cost,
                        'created_at'    => date('Y-m-d H:i:s',time())
                    ];
                    DB::table('expense')->insert($expense_data);
                    // 扣费用
                    DB::table('user')->where('id',$input['user_id'])->decrement('balance',$expense_cost); // 余额扣款
                }

            }
        }
        $un_confirm = BankCheck::where('check_id',$input['check_id'])->where('is_checked',3)->get();
        foreach ($un_confirm as $k => $v){
            OrderArrears::where('id',$v['match_order_id'])->update(['bank_check_id'=> null]);
            BankCheck::where('id',$v['id'])->update(['match_arrears_id'=> null,'is_checked'=>2]);
        }
        $data['check_id'] = $input['check_id'];
        // 返回数据
        return $this->success('match check success',$data);
    }


    /**
     * @description:服务商银行对账确认不符合费用单
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function providersUnMatchData(array $input)
    {
        // 未确认的账目
        $un_confirm = BankCheck::where('check_id',$input['check_id'])->where('is_checked','<',3)->get();
        if($un_confirm){
            $un_confirm = $un_confirm->toArray();
            foreach ($un_confirm as $k => $v){
                $un_confirm[$k]['bank_check_date'] = date('m/d/Y',strtotime($v['bank_check_date']));
            }
            $data['un_confirm'] = $un_confirm;
        }
        // 用户未处理费用单列表
        $arrears_un_confirm = OrderArrears::where('user_id',$input['user_id'])->whereIn('is_pay',[1,3])->get();
        if($arrears_un_confirm){
            $arrears_un_confirm = $arrears_un_confirm->toArray();
            foreach ($arrears_un_confirm as $k => $v){
                $arrears_un_confirm[$k]['created_at'] = date('m/d/Y',strtotime($v['invoice_date']));
                $arrears_un_confirm[$k]['expire_date'] = date('m/d/Y',strtotime($v['invoice_due_date']));
            }
            $data['arrears_un_confirm'] = $arrears_un_confirm;
        }
        // 用户余额
        $providers_id = Providers::where('user_id',$input['user_id'])->pluck('id');
        $balance = LandlordOrder::whereIn('providers_id',$providers_id)->where('balance','>',0)->get();
        if($balance){
            $balance = $balance->toArray();
            $data['balance'] = $balance;
        }
        // 返回数据
        return $this->success('match check success',$data);
    }

    /**
     * @description:银行对账余额调整
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function providersBalanceAdjust(array $input)
    {
        $orders_id = $input['order_id'];
        $balance_data = LandlordOrder::where('id',$orders_id)->first();
        if($balance_data){
            $balance_data = $balance_data->toArray();
            $data['balance'] = $balance_data;
        }
        $arrears_un_confirm = OrderArrears::where('order_id',$orders_id)->whereIn('is_pay',[1,3])->get();
        if($arrears_un_confirm){
            $arrears_un_confirm = $arrears_un_confirm->toArray();
            foreach ($arrears_un_confirm as $k => $v){
                $arrears_un_confirm[$k]['created_at'] = date('m/d/Y',strtotime($v['invoice_date']));
                $arrears_un_confirm[$k]['expire_date'] = date('m/d/Y',strtotime($v['invoice_due_date']));
            }
            $data['arrears_un_confirm'] = $arrears_un_confirm;
        }
        // 返回数据
        return $this->success('get balance data success',$data);
    }

    /**
     * @description:银行对账余额调整确认
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function providersBalanceAdjustConfirm(array $input)
    {
        $order_id = $input['order_id'];
        $adjust_info = $input['adjust_info'];
        static $error = 0;
        $model = new OrderArrears();
        $balance = LandlordOrder::where('id',$order_id)->first();
        if(is_array($adjust_info)){
            foreach ($adjust_info as $k => $v){
                $need_pay = $model->where('id',$v['arrears_id'])->first();
                $pay_money = $v['pay_money'];
                if($pay_money == $need_pay->need_pay_fee){ // 支付金额大于应付金额 直接 销账
                    // 更改此次费用
                    $change_arrears_data = [
                        'is_pay'    => 2,
                        'pay_fee'   => $need_pay->pay_fee+$need_pay->need_pay_fee,
                        'need_pay_fee'  => 0,
                        'pay_date'      => date('Y-m-d',time()),
                        'updated_at'    => date('Y-m-d H:i:s',time()),
                    ];
                    $change_arrears_res = $model->where('id',$v)->update($change_arrears_data);
                    if(!$change_arrears_res){
                        $error += 1;
                    }
                    // 增加收账数据
                    $receive_data = [
                        'fee_type'      => 2,
                        'order_id'      => $v['arrears_id'],
                        'pay_money'     => $need_pay->need_pay_fee,
                        'pay_date'      => date('Y-m-d',time()),
                        'pay_method'    => 3,
                        'created_at'    => date('Y-m-d H:i:s',time()),
                    ];
                    $receive_res = FeeReceive::insert($receive_data);
                    LandlordOrder::where('id',$order_id)->decrement('balance',$pay_money);
                    if(!$receive_res){
                        $error += 1;
                    }
                    // 扣服务费用
                    $order_type = LandlordOrder::where('id',$need_pay->order_id)->pluck('order_type')->first();
                    // 查看是否有折扣券
                    $discount = DB::table('coupon_list')->where('used_user_id',$input['user_id'])->where('coupon_type',1)
                        ->where('activated_at','<=',date('Y-m-d',time()))->where('out_time','<=',date('Y-m-d',time()))
                        ->orderByDesc('discount')->first();
                    if($order_type == 1){
                        $expense_rate = DB::table('sys_config')->where('code','PSFL')->pluck('value')->first();
                    }elseif ($order_type == 2){
                        $expense_rate = DB::table('sys_config')->where('code','PSFL')->pluck('value')->first();
                    }elseif ($order_type == 3){
                        $expense_rate = DB::table('sys_config')->where('code','PSFI')->pluck('value')->first();
                    }elseif ($order_type == 4){
                        $expense_rate = DB::table('sys_config')->where('code','PSFM')->pluck('value')->first();
                    }else{
                        $expense_rate = DB::table('sys_config')->where('code','PSFLI')->pluck('value')->first();
                    }
                    $expense_cost = $need_pay->arrears_fee*$expense_rate;
                    $user_free_balance = DB::table('user')->where('id',$input['user_id'])->pluck('free_balance')->first();
                    if($user_free_balance >0){
                        if($expense_cost < $user_free_balance){
                            $expense_data = [
                                'expense_sn'    => expenseSn(),
                                'user_id'   => $input['user_id'],
                                'user_cost_role'    => 2,
                                'expense_type'  => $order_type+6,
                                'expense_cost'  => 0,
                                'discount'      => $expense_cost,
                                'total_cost'    => $expense_cost,
                                'created_at'    => date('Y-m-d H:i:s',time())
                            ];
                            DB::table('user')->where('id',$input['user_id'])->decrement('free_balance',$expense_cost); // 抵扣券扣款
                        }else{
                            $expense_data = [
                                'expense_sn'    => expenseSn(),
                                'user_id'   => $input['user_id'],
                                'user_cost_role'    => 2,
                                'expense_type'  => $order_type+6,
                                'expense_cost'  => $expense_cost-$user_free_balance,
                                'discount'      => $user_free_balance,
                                'total_cost'    => $expense_cost,
                                'created_at'    => date('Y-m-d H:i:s',time())
                            ];
                            DB::table('expense')->insert($expense_data);
                            DB::table('user')->where('id',$input['user_id'])->decrement('free_balance',$expense_cost); // 抵扣券扣款
                            DB::table('user')->where('id',$input['user_id'])->decrement('balance',($expense_cost-$user_free_balance)); // 余额扣款
                        }
                    }
                    if($discount){
                        $expense_data = [
                            'expense_sn'    => expenseSn(),
                            'user_id'   => $input['user_id'],
                            'user_cost_role'    => 2,
                            'expense_type'  => $order_type+6,
                            'expense_cost'  => $expense_cost*(100-$discount->discount)/100,
                            'discount'      => $expense_cost*$discount->discount/100,
                            'total_cost'    => $expense_cost,
                            'created_at'    => date('Y-m-d H:i:s',time())
                        ];
                        DB::table('expense')->insert($expense_data);
                        DB::table('user')->where('id',$input['user_id'])->decrement('balance',($expense_cost*(100-$discount->discount)/100)); // 余额扣款
                    }else{
                        $expense_data = [
                            'expense_sn'    => expenseSn(),
                            'user_id'   => $input['user_id'],
                            'user_cost_role'    => 2,
                            'expense_type'  => $order_type+6,
                            'expense_cost'  => $expense_cost,
                            'discount'      => 0,
                            'total_cost'    => $expense_cost,
                            'created_at'    => date('Y-m-d H:i:s',time())
                        ];
                        DB::table('expense')->insert($expense_data);
                        // 扣费用
                        DB::table('user')->where('id',$input['user_id'])->decrement('balance',$expense_cost); // 余额扣款
                    }
                }elseif ($need_pay->need_pay_fee > $pay_money && $pay_money >0){ //
                    // 更改此次费用
                    $change_arrears_data = [
                        'is_pay'        => 3,
                        'pay_fee'       => $need_pay->pay_fee+$pay_money,
                        'need_pay_fee'  => $need_pay->need_pay_fee-$pay_money,
                        'pay_date'      => date('Y-m-d',time()),
                        'updated_at'    => date('Y-m-d H:i:s',time()),
                    ];
                    $change_arrears_res = $model->where('id',$v)->update($change_arrears_data);
                    if(!$change_arrears_res){
                        $error += 1;
                    }
                    // 增加收账数据
                    $receive_data = [
                        'fee_type'      => 2,
                        'order_id'      => $v['arrears_id'],
                        'pay_money'     => $pay_money,
                        'pay_date'      => date('Y-m-d',time()),
                        'pay_method'    => 3,
                        'created_at'    => date('Y-m-d H:i:s',time()),
                    ];
                    $receive_res = FeeReceive::insert($receive_data);
                    LandlordOrder::where('id',$order_id)->decrement('balance',$pay_money);
                    if(!$receive_res){
                        $error += 1;
                    }
                }
            }
        }
        // 返回数据
        return $this->success('balance adjust success');
    }

    /**
     * @description:服务商银行对账已核对完成列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function providersHistoryList(array $input)
    {
        $check_history = BankCheck::where('user_id',$input['user_id'])->where('bank_check_type',2)->where('is_checked','>',3)->get();
        if($check_history){
            $count = BankCheck::where('user_id',$input['user_id'])->where('is_checked','>',3)->count();
            if($count <= ($input['page']-1)*5){
                return $this->error('2','get check history failed');
            }else{
                $check_history = BankCheck::where('user_id',$input['user_id'])->where('is_checked','>',3)->offset(($input['page']-1)*5)->limit(5)->get()->toArray();
                foreach ($check_history as $k => $v){
                    if($v['match_arrears_id']){
                        $check_history[$k]['pay_landlord_name'] = OrderArrears::where('id',$v['match_order_id'])->pluck('landlord_name')->first();
                    }else{
                        $order_id = FeeReceive::where('bank_check_id',$v['id'])->pluck('order_id')->first();
                        $check_history[$k]['pay_landlord_name'] = OrderArrears::where('id',$order_id)->pluck('landlord_name')->first();
                    }
                }
                $data['check_history'] = $check_history;
                $data['current_page'] = (int)$input['page'];
                $data['total_page'] = ceil($count/5);
                return $this->success('get check history success',$data);
            }
        }else{
            return $this->error('2','get check history failed');
        }
    }


    /**
     * @description:银行对账未核对完成列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function providersUnMatchList(array $input)
    {
        $check_history = BankCheck::where('user_id',$input['user_id'])->where('bank_check_type',2)->where('is_checked','<',4)->first();
        if($check_history){
            $count = BankCheck::where('user_id',$input['user_id'])->where('bank_check_type',2)->where('is_checked','<',4)->count();
            if($count <= ($input['page']-1)*5){
                return $this->error('2','get check history failed');
            }else{
                $check_history = BankCheck::where('user_id',$input['user_id'])->where('bank_check_type',2)->where('is_checked','<',4)->offset(($input['page']-1)*5)->limit(5)->get()->toArray();
                foreach ($check_history as $k => $v){
                    if($v['match_order_id']){
                        $check_history[$k]['pay_landlord_name'] = OrderArrears::where('id',$v['match_order_id'])->pluck('landlord_name')->first();
                    }else{
                        $arrears_id = FeeReceive::where('bank_check_id',$v['id'])->pluck('order_id')->first();
                        $check_history[$k]['pay_landlord_name'] = OrderArrears::where('id',$arrears_id)->pluck('landlord_name')->first();
                    }
                }
                $data['check_history'] = $check_history;
                $data['current_page'] = (int)$input['page'];
                $data['total_page'] = ceil($count/5);
                return $this->success('get check history success',$data);
            }
        }else{
            return $this->error('2','get check history failed');
        }
    }

    /**
     * @description:银行对账手工调整
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function providersBankAdjust(array $input)
    {
        $bank_check_id = $input['bank_check_id'];
        $ban_check_data = BankCheck::where('id',$bank_check_id)->first();
        if($ban_check_data){
            $ban_check_data = $ban_check_data->toArray();
            $ban_check_data['bank_check_date'] = date('m/d/Y',strtotime($ban_check_data['bank_check_date']));
            $data['bank_check_data'] = $ban_check_data;
        }
        $arrears_un_confirm = OrderArrears::where('user_id',$input['user_id'])->whereIn('is_pay',[1,3])->get();
        if($arrears_un_confirm){
            $arrears_un_confirm = $arrears_un_confirm->toArray();
            foreach ($arrears_un_confirm as $k => $v){
                $arrears_un_confirm[$k]['created_at'] = date('m/d/Y',strtotime($v['invoice_date']));
                $arrears_un_confirm[$k]['expire_date'] = date('m/d/Y',strtotime($v['invoice_due_date']));
            }
            $data['arrears_un_confirm'] = $arrears_un_confirm;
        }
        // 返回数据
        return $this->success('get balance data success',$data);
    }

    /**
     * @description:银行对账手工调整确认
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function providersBankAdjustConfirm(array $input)
    {
        $bank_check_id = $input['bank_check_id'];
        $adjust_info = $input['adjust_info'];
        static $error = 0;
        $model = new OrderArrears();
        $bank_check_res = BankCheck::where('id',$bank_check_id)->first();
        if(is_array($adjust_info)){
            foreach ($adjust_info as $k => $v){
                $need_pay = $model->where('id',$v['arrears_id'])->first();
                $pay_money = $v['pay_money'];
                if($pay_money == $need_pay->need_pay_fee){ // 支付金额大于应付金额 直接 销账
                    // 更改此次费用
                    $change_arrears_data = [
                        'is_pay'    => 2,
                        'pay_fee'   => $need_pay->pay_fee+$need_pay->need_pay_fee,
                        'need_pay_fee'  => 0,
                        'pay_date'      => date('Y-m-d',time()),
                        'updated_at'    => date('Y-m-d H:i:s',time()),
                    ];
                    $change_arrears_res = $model->where('id',$v)->update($change_arrears_data);
                    if(!$change_arrears_res){
                        $error += 1;
                    }
                    // 增加收账数据
                    $receive_data = [
                        'fee_type'      => 2,
                        'order_id'      => $v['arrears_id'],
                        'pay_money'     => $need_pay->need_pay_fee,
                        'pay_date'      => date('Y-m-d',time()),
                        'pay_method'    => 2,
                        'bank_check_id' => $bank_check_id,
                        'created_at'    => date('Y-m-d H:i:s',time()),
                    ];
                    $receive_res = FeeReceive::insert($receive_data);
                    $bank_check_res->amount = $bank_check_res->amount-$pay_money;
                    $bank_check_res->is_checked = 5;
                    $bank_check_res->update();
                    if(!$receive_res){
                        $error += 1;
                    }
                }elseif ($need_pay->need_pay_fee > $pay_money && $pay_money >0){ //
                    // 更改此次费用
                    $change_arrears_data = [
                        'is_pay'        => 3,
                        'pay_fee'       => $need_pay->pay_fee+$pay_money,
                        'need_pay_fee'  => $need_pay->need_pay_fee-$pay_money,
                        'pay_date'      => date('Y-m-d',time()),
                        'updated_at'    => date('Y-m-d H:i:s',time()),
                    ];
                    $change_arrears_res = $model->where('id',$v)->update($change_arrears_data);
                    if(!$change_arrears_res){
                        $error += 1;
                    }
                    // 增加收账数据
                    $receive_data = [
                        'fee_type'      => 2,
                        'order_id'      => $v['arrears_id'],
                        'pay_money'     => $pay_money,
                        'pay_date'      => date('Y-m-d',time()),
                        'pay_method'    => 2,
                        'bank_check_id' => $bank_check_id,
                        'created_at'    => date('Y-m-d H:i:s',time()),
                    ];
                    $receive_res = FeeReceive::insert($receive_data);
                    $bank_check_res->amount = $bank_check_res->amount-$pay_money;
                    $bank_check_res->is_checked = 5;
                    $bank_check_res->update();
                    if(!$receive_res){
                        $error += 1;
                    }
                }
            }
            if($bank_check_res->amount > 0){
                // 增加余额
                $contract_id = $model->where('id',$adjust_info[0]['arrears_id'])->pluck('order_id')->first();
                $balance = LandlordOrder::where('id',$contract_id)->first();
                $balance->balance += $bank_check_res->amount;
                $balance->update();
                // 删除这个金额
                $bank_check_res->amount = 0;
                $bank_check_res->update();
            }
            // 将此条数据变为已核对
            $bank_check_res->is_checked = 2;
            $bank_check_res->update();
        }
        // 返回数据
        return $this->success('balance adjust success');
    }

    /**
     * @description:银行手工对账列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function providersHandAdjustList(array $input)
    {
        $landlord_name = $input['landlord_name'];
        $model = new OrderArrears();
        $model = $model->where('landlord_name','like','%'.$landlord_name.'%')->where('user_id',$input['user_id'])->where('is_pay','!=',2);
        $page = $input['page'];
        $count = $model->count();
        if($count < ($page-1)*10){
            return $this->error('2','no arrears information');
        }else{
            $res = $model->offset(($page-1)*10)->limit(10)->get()->toArray();
            $data['arrears_list'] = $res;
            $data['current_page'] = $page;
            $data['total_page'] = ceil($count/10);
            return $this->success('gei arrears list success',$data);
        }
    }

    /**
     * @description:银行手工对账
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function providersHandAdjust(array $input)
    {
        $model = new OrderArrears();
        $pay_money = $input['pay_amount'];
        $pay_method = $input['pay_method'];
        $need_pay = $model->where('id',$input['arrears_id'])->first();
        if($pay_money >= $need_pay->need_pay_fee){ // 支付金额大于应付金额 直接 销账
            // 更改此次费用
            $change_arrears_data = [
                'is_pay'    => 2,
                'pay_fee'   => $need_pay->pay_fee+$need_pay->need_pay_fee,
                'need_pay_fee'  => 0,
                'pay_date'      => $input['pay_date'],
                'updated_at'    => date('Y-m-d H:i:s',time()),
            ];
            $change_arrears_res = $model->where('id',$input['arrears_id'])->update($change_arrears_data);
            if(!$change_arrears_res){
                return $this->error('2','hand adjust failed');
            }
            // 增加收账数据
            $receive_data = [
                'fee_type'      => 2,
                'order_id'      => $input['arrears_id'],
                'pay_money'     => $need_pay->need_pay_fee,
                'pay_date'      => $input['pay_date'],
                'pay_method'    => $pay_method,
                'note'          => $input['note'],
                'created_at'    => date('Y-m-d H:i:s',time()),
            ];
            $receive_res = FeeReceive::insert($receive_data);
            if(!$receive_res){
                return $this->error('2','hand adjust failed');
            }
            // 修改余额
            $pay_money -= $need_pay->need_pay_fee;
        }elseif ($need_pay->need_pay_fee > $pay_money && $pay_money >0){ //
            // 更改此次费用
            $change_arrears_data = [
                'is_pay'        => 3,
                'pay_fee'       => $need_pay->pay_fee+$pay_money,
                'need_pay_fee'  => $need_pay->need_pay_fee-$pay_money,
                'pay_date'      => $input['pay_date'],
                'updated_at'    => date('Y-m-d H:i:s',time()),
            ];
            $change_arrears_res = $model->where('id',$input['arrears_id'])->update($change_arrears_data);
            if(!$change_arrears_res){
                return $this->error('2','hand adjust failed');
            }
            // 增加收账数据
            $receive_data = [
                'fee_type'      => 2,
                'order_id'      => $input['arrears_id'],
                'pay_money'     => $pay_money,
                'pay_date'      => $input['pay_date'],
                'pay_method'    => $pay_method,
                'note'          => $input['note'],
                'created_at'    => date('Y-m-d H:i:s',time()),
            ];
            $receive_res = FeeReceive::insert($receive_data);
            if(!$receive_res){
                return $this->error('2','hand adjust failed');
            }
            // 修改余额
            $pay_money = 0;
        }
        if($pay_money){
            // 增加余额
            $contract_id = $model->where('id',$input['arrears_id'])->pluck('order_id')->first();
            $balance_update_res = LandlordOrder::where('id',$contract_id)->increment('balance',$pay_money);
            if(!$balance_update_res){
                return $this->error('2','hand adjust failed');
            }
        }
        return $this->success('balance adjust success');
    }


    /**
     * @description:银行对账租户信息 确认租户
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function providersBankCheckMatch(array $input)
    {
        $landlord_id = $input['landlord_id'];
        $is_check_match_code = $input['is_check_match_code'];
        if($is_check_match_code == 2){
            // 修改匹配码
            Landlord::where('id',$landlord_id)->update(['subject_code'=> $input['code']]);
            OrderArrears::where('landlord_user_id',$landlord_id)->update(['subject_code'=> $input['code']]);
            //
            $bank_check_id = $input['bank_check_id'];
            BankCheck::where('id',$bank_check_id)->update(['match_landlord_id'=> $landlord_id,'is_checked'=>2,'match_landlord_name'=>$input['landlord_name']]);
        }else{
            $bank_check_id = $input['bank_check_id'];
            BankCheck::where('id',$bank_check_id)->update(['match_landlord_id'=> $landlord_id,'is_checked'=>2,'match_landlord_name'=>$input['landlord_name']]);
        }
        return $this->success('check match update success');
    }


    /**
     * @description:费用单列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function feeListBatch(array $input)
    {
        $model = new RentArrears();
        $page = $input['page'];
        $count = $model->where('contract_id',$input['contract_id'])->groupBy('fee_sn')->count();
        if($count < ($page-1)*10){
            return $this->error('2','get fee list failed');
        }else{
            $fee_sns = $model->where('contract_id',$input['contract_id'])->groupBy('fee_sn')->offset(($page-1)*10)->limit(10)->pluck('fee_sn');
            foreach ($fee_sns as $k => $v){
                $invoice[$k]['invoice_sn'] = $v;
                $invoice[$k]['tenement_name'] = ContractTenement::where('contract_id',$input['contract_id'])->pluck('tenement_full_name')->first();
                $invoice[$k]['invoice_date'] = $model->where('fee_sn',$v)->pluck('effect_date')->first();
                $invoice[$k]['due_date'] = $model->where('fee_sn',$v)->pluck('expire_date')->first();
                $invoice[$k]['amount'] = $model->where('fee_sn',$v)->sum('arrears_fee');
                $invoice[$k]['note'] = $model->where('fee_sn',$v)->pluck('note')->first();
            }
            $data['current_page'] = $page;
            $data['total_page'] = ceil($count/10);
            $data['fee_list'] = $invoice;
            return $this->success('get fee list success',$data);
        }
    }


    /**
     * @description:费用单删除
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function feeDelete(array $input)
    {
        $model = new RentArrears();
        $arrears_id = $input['arrears_id'];
        $res = $model->where('id',$arrears_id)->delete();
        return $this->success('deleted arrears record success');
    }

    /**
     * @description:费用单打印
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function feePrint(array $input)
    {
        $model = new RentArrears();
        $fee_sn = $input['fee_sn'];
        $is_print = $model->where('fee_sn',$fee_sn)->pluck('is_print')->first();
        if(!$is_print){
            $contract_id = $model->where('fee_sn',$fee_sn)->pluck('contract_id')->first();
            $landlord_id = RentContract::where('id',$contract_id)->pluck('landlord_id')->first();
            $issues_day = date('Y-m-d');
            $due_day = $model->where('fee_sn',$fee_sn)->pluck('expire_date')->first();
            $gst = Landlord::where('id',$landlord_id)->pluck('tax_no')->first();
            $tenement_info = ContractTenement::where('contract_id',$contract_id)->first();
            $landlord_info = RentContract::where('id',$contract_id)->first();
            $fee_list = $model->where('fee_sn',$fee_sn)->get();
            $subtotal = 0;
            $discount = 0;
            $gts = 0;
            $contract_type = RentContract::where('id',$contract_id)->pluck('contract_type')->first();
            if($contract_type == 1){
                $bank = EntireContract::where('contract_id',$contract_id)->pluck('bank')->first().EntireContract::where('contract_id',$contract_id)->pluck('branch')->first();
                $bank_account = EntireContract::where('contract_id',$contract_id)->pluck('bank_account')->first();
            }elseif ($contract_type == 2 || $contract_type == 3){
                $bank = SeparateContract::where('contract_id',$contract_id)->pluck('bank')->first().EntireContract::where('contract_id',$contract_id)->pluck('branch')->first();
                $bank_account = SeparateContract::where('contract_id',$contract_id)->pluck('bank_account')->first();
            }else{
                $bank = BusinessContract::where('contract_id',$contract_id)->pluck('bank')->first().EntireContract::where('contract_id',$contract_id)->pluck('branch')->first();
                $bank_account = BusinessContract::where('contract_id',$contract_id)->pluck('bank_account')->first();
            }
            // PDF
            $ip = "{$_SERVER['SERVER_NAME']}";
            $dashboard_pdf_file = "http://".$ip."/pdf/test.pdf";
            $fileContent = file_get_contents($dashboard_pdf_file,'rb');
            $mpdf = new Mpdf();
            $pagecount = $mpdf->setSourceFile(StreamReader::createByString($fileContent));
            for($i=1; $i<=$pagecount;$i++){
                $import_page = $mpdf->importPage($i);
                $mpdf->useTemplate($import_page);
                if($i == 1){
                    $mpdf->WriteText('42',35,(string)$issues_day);
                    $mpdf->WriteText('40','43',(string)$due_day);
                    $mpdf->WriteText('172','35',(string)$gst);
                    $mpdf->WriteText('172','43',(string)$fee_sn);
                    $mpdf->WriteText('29','66',(string)$tenement_info->tenement_full_name);
                    $mpdf->WriteText('34','73',(string)$tenement_info->tenement_service_address);
                    $mpdf->WriteText('30','81',(string)$tenement_info->tenement_mobile);
                    $mpdf->WriteText('29','88',(string)$tenement_info->tenement_e_mail);
                    $mpdf->WriteText('29','111',(string)$landlord_info->landlord_full_name);
                    $mpdf->WriteText('34','118',(string)$landlord_info->landlord_additional_address);
                    $mpdf->WriteText('30','126',(string)$landlord_info->landlord_mobile_phone);
                    $mpdf->WriteText('29','133',(string)$landlord_info->landlord_e_mail);
                    foreach ($fee_list as $k => $v){
                        $mpdf->WriteText(16,155+$k*10,(string)$v->items_name);
                        $mpdf->WriteText(42,155+$k*10,(string)$v->describe);
                        $mpdf->WriteText(92,155+$k*10,(string)$v->unit_price);
                        $mpdf->WriteText(118,155+$k*10,(string)$v->number);
                        $mpdf->WriteText(138,155+$k*10,(string)round($v->unit_price*$v->number*$v->discount,2));
                        $mpdf->WriteText(158,155+$k*10,(string)$v->tex);
                        $mpdf->WriteText(175,155+$k*10,(string)$v->arrears_fee);
                        $subtotal += $v->unit_price*$v->number;
                        $discount += $v->unit_price*$v->number*$v->discount/100;
                        $gts += round(($v->unit_price*$v->number)*(100-$v->discount)/100*($v->tex)/100,2);
                    }
                    $total = $subtotal-$discount+$gts;
                    $mpdf->WriteText(175,214,(string)$subtotal);
                    $mpdf->WriteText(175,222,(string)$discount);
                    $mpdf->WriteText(175,230,(string)$gts);
                    $mpdf->WriteText(175,238,(string)$total);
                    $mpdf->WriteText(35,266,(string)$bank);
                    $mpdf->WriteText(157,266,(string)$bank_account);

                }
                if($i < $pagecount){
                    $mpdf->AddPage();
                }
                //
                $model->where('fee_sn',$fee_sn)->increment('is_print');
            }
            $data['res'] = $mpdf->Output();
            return $this->success('get pdf success',$data);
        }else{
            $contract_id = $model->where('fee_sn',$fee_sn)->pluck('contract_id')->first();
            $landlord_id = RentContract::where('id',$contract_id)->pluck('landlord_id')->first();
            $issues_day = date('Y-m-d');
            $due_day = $model->where('fee_sn',$fee_sn)->pluck('expire_date')->first();
            $gst = Landlord::where('id',$landlord_id)->pluck('tax_no')->first();
            $tenement_info = ContractTenement::where('contract_id',$contract_id)->first();
            $landlord_info = RentContract::where('id',$contract_id)->first();
            $fee_list = $model->where('fee_sn',$fee_sn)->get();
            $subtotal = 0;
            $discount = 0;
            $gts = 0;
            $contract_type = RentContract::where('id',$contract_id)->pluck('contract_type')->first();
            if($contract_type == 1){
                $bank = EntireContract::where('contract_id',$contract_id)->pluck('bank')->first().EntireContract::where('contract_id',$contract_id)->pluck('branch')->first();
                $bank_account = EntireContract::where('contract_id',$contract_id)->pluck('bank_account')->first();
            }elseif ($contract_type == 2 || $contract_type == 3){
                $bank = SeparateContract::where('contract_id',$contract_id)->pluck('bank')->first().EntireContract::where('contract_id',$contract_id)->pluck('branch')->first();
                $bank_account = SeparateContract::where('contract_id',$contract_id)->pluck('bank_account')->first();
            }else{
                $bank = BusinessContract::where('contract_id',$contract_id)->pluck('bank')->first().EntireContract::where('contract_id',$contract_id)->pluck('branch')->first();
                $bank_account = BusinessContract::where('contract_id',$contract_id)->pluck('bank_account')->first();
            }
            // PDF
            $ip = "{$_SERVER['SERVER_NAME']}";
            $dashboard_pdf_file = "http://".$ip."/pdf/test.pdf";
            $fileContent = file_get_contents($dashboard_pdf_file,'rb');
            $mpdf = new Mpdf();
            $pagecount = $mpdf->setSourceFile(StreamReader::createByString($fileContent));
            for($i=1; $i<=$pagecount;$i++){
                $import_page = $mpdf->importPage($i);
                $mpdf->useTemplate($import_page);
                if($i == 1){
                    $mpdf->WriteText('42',35,$issues_day);
                    $mpdf->WriteText('40','43',$due_day);
                    $mpdf->WriteText('172','35',$gst);
                    $mpdf->WriteText('172','43',$fee_sn);
                    $mpdf->WriteText('29','66',$tenement_info->tenement_full_name);
                    $mpdf->WriteText('34','73',$tenement_info->tenement_service_address);
                    $mpdf->WriteText('30','81',$tenement_info->tenement_mobile);
                    $mpdf->WriteText('29','88',$tenement_info->tenement_e_mail);
                    $mpdf->WriteText('29','111',$landlord_info->landlord_full_name);
                    $mpdf->WriteText('34','118',$landlord_info->landlord_additional_address);
                    $mpdf->WriteText('30','126',$landlord_info->landlord_mobile_phone);
                    $mpdf->WriteText('29','133',$landlord_info->landlord_e_mail);
                    foreach ($fee_list as $k => $v){
                        $mpdf->WriteText(16,155+$k*10,(string)$v->items_name);
                        $mpdf->WriteText(42,155+$k*10,(string)$v->describe);
                        $mpdf->WriteText(92,155+$k*10,(string)$v->unit_price);
                        $mpdf->WriteText(118,155+$k*10,(string)$v->number);
                        $mpdf->WriteText(138,155+$k*10,(string)round($v->unit_price*$v->number*$v->discount,2));
                        $mpdf->WriteText(158,155+$k*10,(string)$v->tex);
                        $mpdf->WriteText(175,155+$k*10,(string)$v->arrears_fee);
                        $subtotal += $v->unit_price*$v->number;
                        $discount += $v->unit_price*$v->number*$v->discount/100;
                        $gts += round(($v->unit_price*$v->number)*(100-$v->discount)/100*($v->tex)/100,2);
                    }
                    $total = $subtotal-$discount+$gts;
                    $mpdf->WriteText(175,214,(string)$subtotal);
                    $mpdf->WriteText(175,222,(string)$discount);
                    $mpdf->WriteText(175,230,(string)$gts);
                    $mpdf->WriteText(175,238,(string)$total);
                    $mpdf->WriteText(35,266,(string)$bank);
                    $mpdf->WriteText(157,266,(string)$bank_account);
                    $mpdf->SetWatermarkImage("http://".$ip."/pdf/watermark.png",0.8);//参数一是图片的位置，参数二是透明度
                    $mpdf->showWatermarkImage = true;
                }
                if($i < $pagecount){
                    $mpdf->AddPage();
                }
                //
            }
            $data['res'] = $mpdf->Output();
            return $this->success('get pdf success',$data);
        }

    }

    /**
     * @description:发票打印
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function invoicePrint(array $input)
    {
        $model = new OrderArrears();
        $invoice_sn = $input['invoice_sn'];
        $is_print = $model->where('invoice_sn',$invoice_sn)->pluck('is_print')->first();
        if(!$is_print){
            $order_id = $model->where('invoice_sn',$invoice_sn)->pluck('order_id')->first();
            $providers_id = LandlordOrder::where('id',$order_id)->pluck('providers_id')->first();
            $providers_info = Providers::where('id',$providers_id)->first();
            $issues_day = date('Y-m-d');
            $due_day = $model->where('invoice_sn',$invoice_sn)->pluck('invoice_due_date')->first();
            $gst = Providers::where('id',$providers_id)->pluck('tax_no')->first();
            $landlord_id = LandlordOrder::where('id',$order_id)->pluck('user_id')->first();
            $landlord_info = Landlord::where('user_id',$landlord_id)->first();
            $fee_list = $model->where('invoice_sn',$invoice_sn)->get();
            $subtotal = 0;
            $discount = 0;
            $gts = 0;

            // PDF
            $ip = "{$_SERVER['SERVER_NAME']}";
            $dashboard_pdf_file = "http://".$ip."/pdf/test.pdf";
            $fileContent = file_get_contents($dashboard_pdf_file,'rb');
            $mpdf = new Mpdf();
            $pagecount = $mpdf->setSourceFile(StreamReader::createByString($fileContent));
            for($i=1; $i<=$pagecount;$i++){
                $import_page = $mpdf->importPage($i);
                $mpdf->useTemplate($import_page);
                if($i == 1){
                    $mpdf->WriteText('42',35,(string)$issues_day);
                    $mpdf->WriteText('40','43',(string)$due_day);
                    $mpdf->WriteText('172','35',(string)$gst);
                    $mpdf->WriteText('172','43',(string)$invoice_sn);
                    $mpdf->WriteText('29','66',(string)$landlord_info->landlord_name);
                    $mpdf->WriteText('34','73',(string)$landlord_info->property_address);
                    $mpdf->WriteText('30','81',(string)$landlord_info->tenement_mobile);
                    $mpdf->WriteText('29','88',(string)$landlord_info->email);
                    $mpdf->WriteText('29','111',(string)$providers_info->service_name);
                    $mpdf->WriteText('34','118',(string)$providers_info->mail_address);
                    $mpdf->WriteText('30','126',(string)$providers_info->mobile);
                    $mpdf->WriteText('29','133',(string)$providers_info->email);
                    foreach ($fee_list as $k => $v){
                        $mpdf->WriteText(16,155+$k*10,(string)$v->items_name);
                        $mpdf->WriteText(42,155+$k*10,(string)$v->describe);
                        $mpdf->WriteText(92,155+$k*10,(string)$v->unit_price);
                        $mpdf->WriteText(118,155+$k*10,(string)$v->number);
                        $mpdf->WriteText(138,155+$k*10,(string)round($v->unit_price*$v->number*$v->discount,2));
                        $mpdf->WriteText(158,155+$k*10,(string)$v->tex);
                        $mpdf->WriteText(175,155+$k*10,(string)$v->arrears_fee);
                        $subtotal += $v->unit_price*$v->number;
                        $discount += $v->unit_price*$v->number*$v->discount/100;
                        $gts += round(($v->unit_price*$v->number)*(100-$v->discount)/100*($v->tex)/100,2);
                    }
                    $total = $subtotal-$discount+$gts;
                    $mpdf->WriteText(175,214,(string)$subtotal);
                    $mpdf->WriteText(175,222,(string)$discount);
                    $mpdf->WriteText(175,230,(string)$gts);
                    $mpdf->WriteText(175,238,(string)$total);
                    /*$mpdf->WriteText(35,266,(string)$bank);*/
                    $mpdf->WriteText(157,266,(string)$providers_info->bank_account);

                }
                if($i < $pagecount){
                    $mpdf->AddPage();
                }
                //
                $model->where('invoice_sn',$invoice_sn)->increment('is_print');
            }
            $data['res'] = $mpdf->Output();
            return $this->success('get pdf success',$data);
        }else{
            $order_id = $model->where('invoice_sn',$invoice_sn)->pluck('order_id')->first();
            $providers_id = LandlordOrder::where('id',$order_id)->pluck('providers_id')->first();
            $providers_info = Providers::where('id',$providers_id)->first();
            $issues_day = date('Y-m-d');
            $due_day = $model->where('invoice_sn',$invoice_sn)->pluck('invoice_due_date')->first();
            $gst = Providers::where('id',$providers_id)->pluck('tax_no')->first();
            $landlord_id = LandlordOrder::where('id',$order_id)->pluck('user_id')->first();
            $landlord_info = Landlord::where('user_id',$landlord_id)->first();
            $fee_list = $model->where('invoice_sn',$invoice_sn)->get();
            $subtotal = 0;
            $discount = 0;
            $gts = 0;

            // PDF
            $ip = "{$_SERVER['SERVER_NAME']}";
            $dashboard_pdf_file = "http://".$ip."/pdf/test.pdf";
            $fileContent = file_get_contents($dashboard_pdf_file,'rb');
            $mpdf = new Mpdf();
            $pagecount = $mpdf->setSourceFile(StreamReader::createByString($fileContent));
            for($i=1; $i<=$pagecount;$i++){
                $import_page = $mpdf->importPage($i);
                $mpdf->useTemplate($import_page);
                if($i == 1){
                    $mpdf->WriteText('42',35,(string)$issues_day);
                    $mpdf->WriteText('40','43',(string)$due_day);
                    $mpdf->WriteText('172','35',(string)$gst);
                    $mpdf->WriteText('172','43',(string)$invoice_sn);
                    $mpdf->WriteText('29','66',(string)$landlord_info->landlord_name);
                    $mpdf->WriteText('34','73',(string)$landlord_info->property_address);
                    $mpdf->WriteText('30','81',(string)$landlord_info->tenement_mobile);
                    $mpdf->WriteText('29','88',(string)$landlord_info->email);
                    $mpdf->WriteText('29','111',(string)$providers_info->service_name);
                    $mpdf->WriteText('34','118',(string)$providers_info->mail_address);
                    $mpdf->WriteText('30','126',(string)$providers_info->mobile);
                    $mpdf->WriteText('29','133',(string)$providers_info->email);
                    foreach ($fee_list as $k => $v){
                        $mpdf->WriteText(16,155+$k*10,(string)$v->items_name);
                        $mpdf->WriteText(42,155+$k*10,(string)$v->describe);
                        $mpdf->WriteText(92,155+$k*10,(string)$v->unit_price);
                        $mpdf->WriteText(118,155+$k*10,(string)$v->number);
                        $mpdf->WriteText(138,155+$k*10,(string)round($v->unit_price*$v->number*$v->discount,2));
                        $mpdf->WriteText(158,155+$k*10,(string)$v->tex);
                        $mpdf->WriteText(175,155+$k*10,(string)$v->arrears_fee);
                        $subtotal += $v->unit_price*$v->number;
                        $discount += $v->unit_price*$v->number*$v->discount/100;
                        $gts += round(($v->unit_price*$v->number)*(100-$v->discount)/100*($v->tex)/100,2);
                    }
                    $total = $subtotal-$discount+$gts;
                    $mpdf->WriteText(175,214,(string)$subtotal);
                    $mpdf->WriteText(175,222,(string)$discount);
                    $mpdf->WriteText(175,230,(string)$gts);
                    $mpdf->WriteText(175,238,(string)$total);
                    /*$mpdf->WriteText(35,266,(string)$bank);*/
                    $mpdf->WriteText(157,266,(string)$providers_info->bank_account);
                    $mpdf->SetWatermarkImage("http://".$ip."/pdf/watermark.png",0.8);//参数一是图片的位置，参数二是透明度
                    $mpdf->showWatermarkImage = true;
                }
                if($i < $pagecount){
                    $mpdf->AddPage();
                }

            }
            $data['res'] = $mpdf->Output();
            return $this->success('get pdf success',$data);
        }

    }

    /**
     * @description:服务商费用单列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function providersFeeDetail(array $input)
    {
        $model = new OrderArrears();
        $invoice_sn = $input['invoice_sn'];
        $model = $model->where('user_id',$input['user_id']);
        $model = $model->where('invoice_sn',$invoice_sn);
        $res = $model->get();
        static $amount_price = 0;
        static $discount = 0;
        static $gts = 0;
        foreach ($res as $k => $v){
            $amount_price += $v['unit_price']*$v['number'];
            $discount += ($v['unit_price']*$v['number'])*$v['discount']/100;
            $gts += ($v['unit_price']*$v['number'])*(1-$v['discount']/100)*$v['tex']/100;
            $res[$k]['providers_name'] = Providers::where('id',$v['providers_id'])->pluck('service_name')->first();
        }
        $data['invoice_list'] = $res;
        $data['total_price'] = round(($amount_price-$discount+$gts),2);
        $data['amount_price'] = round($amount_price,2);
        $data['discount'] = round($discount,2);
        $data['gts'] = round($gts,2);
        return $this->success('get invoice list success',$data);
    }
}