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
use App\Model\ContractTenement;
use App\Model\FeeReceive;
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
use Maatwebsite\Excel\Facades\Excel;

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
        if($input['arrears_type'] == 3){
            $rent_house_id = RentContract::where('id',$input['contract_id'])->pluck('house_id')->first();
            $contract_sn = RentContract::where('id',$input['contract_id'])->pluck('contract_id')->first();
            $tenement_info = ContractTenement::where('contract_id',$input['contract_id'])->first();
            $rent_house_info = RentHouse::where('id',$rent_house_id)->first();
            $fee_data = [
                'user_id'           => $input['user_id'],
                'contract_id'       => $input['contract_id'],
                'contract_sn'       => $contract_sn,
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
                'subject_code'      => Tenement::where('id',$tenement_info->tenement_id)->pluck('subject_code')->first(),
                'tex'               => $input['tex'],
                'discount'          => $input['discount'],
                'items_name'        => $input['items_name'],
                'describe'          => $input['describe'],
                'note'              => $input['note'],
                'expire_date'       => date('Y-m-d ',time()+3600*24*8),
                'District'          => $rent_house_info->District,
                'TA'                => $rent_house_info->TA,
                'Region'            => $rent_house_info->Region,
                'upload_url'        => $input['upload_url'],
                'created_at'        => date('Y-m-d H:i:s',time()),
            ];
            $res = $model->insert($fee_data);
        }else{
            $rent_house_id = RentContract::where('id',$input['contract_id'])->pluck('house_id')->first();
            $contract_sn = RentContract::where('id',$input['contract_id'])->pluck('contract_id')->first();
            $tenement_info = ContractTenement::where('contract_id',$input['contract_id'])->first();
            $rent_house_info = RentHouse::where('id',$rent_house_id)->first();
            $fee_data = [
                'user_id'           => $input['user_id'],
                'contract_id'       => $input['contract_id'],
                'contract_sn'       => $contract_sn,
                'rent_house_id'     => $rent_house_id,
                'tenement_id'       => $tenement_info->tenement_id,
                'tenement_name'     => $tenement_info->tenement_full_name,
                'tenement_email'    => $tenement_info->tenement_email,
                'tenement_phone'    => $tenement_info->tenement_phone,
                'arrears_type'      => 4,
                'property_name'     => $rent_house_info->property_name,
                'arrears_fee'       => ($input['number']*$input['unit_price'])*(1-$input['discount']/100)*(1+$input['tex']/100),
                'is_pay'            => 1,
                'pay_fee'           => 0,
                'need_pay_fee'      => ($input['number']*$input['unit_price'])*(1-$input['discount']/100)*(1+$input['tex']/100),
                'number'            => $input['number'],
                'unit_price'        => $input['unit_price'],
                'subject_code'      => Tenement::where('id',$tenement_info->tenement_id)->pluck('subject_code')->first(),
                'tex'               => $input['tex'],
                'discount'          => $input['discount'],
                'items_name'        => $input['items_name'],
                'describe'          => $input['describe'],
                'note'              => $input['note'],
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
        $count = $model->where('user_id',$input['user_id'])->where('contract_id',$input['contract_id'])->whereIn('arrears_type',[3,4])->get();
        $count = count($count);
        if($count <= ($input['page']-1)*10){
            return $this->error('2','no more fee information');
        }else{
            $fee_data = $model->where('user_id',$input['user_id'])->where('contract_id',$input['contract_id'])->whereIn('arrears_type',[3,4])->offset(($input['page']-1)*10)->limit(10)->get()->toArray();
            static $amount_price = 0;
            static $discount = 0;
            static $gts = 0;
            foreach ($fee_data as $k => $v){
                $amount_price += $v['unit_price']*$v['number'];
                $discount += ($v['unit_price']*$v['number'])*$v['discount']/100;
                $gts += ($v['unit_price']*$v['number'])*(1-$v['discount']/100)*$v['tex']/100;
            }
            $tenement_id = ContractTenement::where('contract_id',$input['contract_id'])->pluck('tenement_id')->first();
            $data['subject_code'] = Tenement::where('id',$tenement_id)->pluck('subject_code')->first();
            $data['total_price'] = round(($amount_price-$discount+$gts),2);
            $data['amount_price'] = round($amount_price,2);
            $data['discount'] = round($discount,2);
            $data['gts'] = round($gts,2);
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
                                    // 匹配成功的 费用单 更新对应的 对账id
                                    /*$match_res->bank_check_id = $bank_check_res;
                                    $match_res->update();*/
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
                   /* foreach ($match_up_res as $k => $v){
                        $res[$k]['bank_check_id'] = $v['id'];
                        $res[$k]['tenement_name'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('tenement_name')->first();
                        $res[$k]['payment_amount'] = $v['amount'];
                        $res[$k]['payment_date'] = $v['bank_check_date'];
                        $res[$k]['match_code'] = $v['match_code'];
                        $res[$k]['arrears_amount'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('need_pay_fee')->first();
                        $res[$k]['arrears_type'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('arrears_type')->first();
                        $res[$k]['invoice_date'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('created_at')->first();
                        $res[$k]['due_date'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('expire_date')->first();
                        $res[$k]['subject_code'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('subject_code')->first();
                    }
                    $check_data['check_res'] = $res;*/
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
                                    // 匹配成功的 费用单 更新对应的 对账id
                                    /*$match_res->bank_check_id = $bank_check_res;
                                    $match_res->update();*/
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
                    /*foreach ($match_up_res as $k => $v){
                        $res[$k]['bank_check_id'] = $v['id'];
                        $res[$k]['tenement_name'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('tenement_name')->first();
                        $res[$k]['payment_amount'] = $v['amount'];
                        $res[$k]['payment_date'] = $v['bank_check_date'];
                        $res[$k]['match_code'] = $v['match_code'];
                        $res[$k]['arrears_amount'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('need_pay_fee')->first();
                        $res[$k]['arrears_type'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('arrears_type')->first();
                        $res[$k]['invoice_date'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('created_at')->first();
                        $res[$k]['due_date'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('expire_date')->first();
                        $res[$k]['subject_code'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('subject_code')->first();
                    }
                    $check_data['check_res'] = $res;*/
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
                                    // 匹配成功的 费用单 更新对应的 对账id
                                   /* $match_res->bank_check_id = $bank_check_res;
                                    $match_res->update();*/
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
                    /*foreach ($match_up_res as $k => $v){
                        $res[$k]['bank_check_id'] = $v['id'];
                        $res[$k]['tenement_name'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('tenement_name')->first();
                        $res[$k]['payment_amount'] = $v['amount'];
                        $res[$k]['payment_date'] = $v['bank_check_date'];
                        $res[$k]['match_code'] = $v['match_code'];
                        $res[$k]['arrears_amount'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('need_pay_fee')->first();
                        $res[$k]['arrears_type'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('arrears_type')->first();
                        $res[$k]['invoice_date'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('created_at')->first();
                        $res[$k]['due_date'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('expire_date')->first();
                        $res[$k]['subject_code'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('subject_code')->first();
                    }
                    $check_data['check_res'] = $res;*/
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
                                    // 匹配成功的 费用单 更新对应的 对账id
                                    /*$match_res->bank_check_id = $bank_check_res;
                                    $match_res->update();*/
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
                    /*foreach ($match_up_res as $k => $v){
                        $res[$k]['bank_check_id'] = $v['id'];
                        $res[$k]['tenement_name'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('tenement_name')->first();
                        $res[$k]['payment_amount'] = $v['amount'];
                        $res[$k]['payment_date'] = $v['bank_check_date'];
                        $res[$k]['match_code'] = $v['match_code'];
                        $res[$k]['arrears_amount'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('need_pay_fee')->first();
                        $res[$k]['arrears_type'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('arrears_type')->first();
                        $res[$k]['invoice_date'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('created_at')->first();
                        $res[$k]['due_date'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('expire_date')->first();
                        $res[$k]['subject_code'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('subject_code')->first();
                    }
                    $check_data['check_res'] = $res;*/
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
                                    // 匹配成功的 费用单 更新对应的 对账id
                                    /*$match_res->bank_check_id = $bank_check_res;
                                    $match_res->update();*/
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
                   /* foreach ($match_up_res as $k => $v){
                        $res[$k]['bank_check_id'] = $v['id'];
                        $res[$k]['tenement_name'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('tenement_name')->first();
                        $res[$k]['payment_amount'] = $v['amount'];
                        $res[$k]['payment_date'] = $v['bank_check_date'];
                        $res[$k]['match_code'] = $v['match_code'];
                        $res[$k]['arrears_amount'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('need_pay_fee')->first();
                        $res[$k]['arrears_type'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('arrears_type')->first();
                        $res[$k]['invoice_date'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('created_at')->first();
                        $res[$k]['due_date'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('expire_date')->first();
                        $res[$k]['subject_code'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('subject_code')->first();
                    }
                    $check_data['check_res'] = $res;*/
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
                                    // 匹配成功的 费用单 更新对应的 对账id
                                   /* $match_res->bank_check_id = $bank_check_res;
                                    $match_res->update();*/
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
                    /*foreach ($match_up_res as $k => $v){
                        $res[$k]['bank_check_id'] = $v['id'];
                        $res[$k]['tenement_name'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('tenement_name')->first();
                        $res[$k]['payment_amount'] = $v['amount'];
                        $res[$k]['payment_date'] = $v['bank_check_date'];
                        $res[$k]['match_code'] = $v['match_code'];
                        $res[$k]['arrears_amount'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('need_pay_fee')->first();
                        $res[$k]['arrears_type'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('arrears_type')->first();
                        $res[$k]['invoice_date'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('created_at')->first();
                        $res[$k]['due_date'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('expire_date')->first();
                        $res[$k]['subject_code'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('subject_code')->first();
                    }
                    $check_data['check_res'] = $res;*/
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
                    /*foreach ($match_up_res as $k => $v){
                        $res[$k]['bank_check_id'] = $v['id'];
                        $res[$k]['tenement_name'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('tenement_name')->first();
                        $res[$k]['payment_amount'] = $v['amount'];
                        $res[$k]['payment_date'] = $v['bank_check_date'];
                        $res[$k]['match_code'] = $v['match_code'];
                        $res[$k]['arrears_amount'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('need_pay_fee')->first();
                        $res[$k]['arrears_type'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('arrears_type')->first();
                        $res[$k]['invoice_date'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('created_at')->first();
                        $res[$k]['due_date'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('expire_date')->first();
                        $res[$k]['subject_code'] = RentArrears::where('id',$v['match_arrears_id'])->pluck('subject_code')->first();
                    }
                    $check_data['check_res'] = $res;*/
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
            }
        }
        $un_confirm = BankCheck::where('check_id',$input['check_id'])->where('is_checked',3)->get();
        foreach ($un_confirm as $k => $v){
            RentArrears::where('id',$v['match_arrears_id'])->update(['bank_check_id'=> null]);
            BankCheck::where('id',$v['id'])->update(['match_arrears_id'=> null,'is_checked'=>2]);
        }
       /* // 未确认的账目
        $un_confirm = BankCheck::where('check_id',$input['check_id'])->where('is_checked',1)->get();
        if($un_confirm){
            $un_confirm = $un_confirm->toArray();
            $data['un_confirm'] = $un_confirm;
        }
        // 用户未处理费用单列表
        $arrears_un_confirm = RentArrears::where('user_id',$input['user_id'])->whereIn('arrears_type',[1,2,3])->whereIn('is_pay',[1,3])->get();
        if($arrears_un_confirm){
            $arrears_un_confirm = $arrears_un_confirm->toArray();
            $data['arrears_un_confirm'] = $arrears_un_confirm;
        }
        // 用户余额
        $balance = RentContract::where('user_id',$input['user_id'])->where('balance','>',0)->get();
        if($balance){
            $balance = $balance->toArray();
            $data['balance'] = $balance;
        }*/
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
        $check_history = BankCheck::where('user_id',$input['user_id'])->where('is_checked','>',3)->get();
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
        $check_history = BankCheck::where('user_id',$input['user_id'])->where('is_checked',1)->get();
        if($check_history){
            $count = BankCheck::where('user_id',$input['user_id'])->where('is_checked',1)->count();
            if($count <= ($input['page']-1)*5){
                return $this->error('2','get check history failed');
            }else{
                $check_history = BankCheck::where('user_id',$input['user_id'])->where('is_checked',1)->offset(($input['page']-1)*5)->limit(5)->get()->toArray();
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
        if($input['amount']){
            if($input['invoice_date'] && $input['tenement_name']){
                $sql = '(SELECT  SUM(arrears_fee) AS SUMM ,contract_id FROM order_arrears WHERE user_id = '.$input['user_id'].' AND arrears_type in (\'3\',\'4\') AND tenement_name like \'%'.$input['tenement_name'].'%\' AND created_at BETWEEN \''.date('Y-m-d H:i:s',strtotime($input['invoice_date'])).'\' AND \''.date('Y-m-d H:i:s',strtotime($input['invoice_date'])+3600*24-1).'\' GROUP BY contract_id) AS T WHERE T.SUMM > '.$input['amount'];
                $count = DB::table(DB::raw($sql))->get()->toArray();
            }elseif(!$input['invoice_date']){
                $sql = '(SELECT  SUM(arrears_fee) AS SUMM ,contract_id FROM order_arrears WHERE user_id = '.$input['user_id'].' AND arrears_type in (\'3\',\'4\') AND tenement_name like \'%'.$input['tenement_name'].'%\' GROUP BY contract_id) AS T WHERE T.SUMM > '.$input['amount'];
                $count = DB::table(DB::raw($sql))->get()->toArray();
            }elseif(!$input['tenement_name']){
                $sql = '(SELECT  SUM(arrears_fee) AS SUMM ,contract_id FROM order_arrears WHERE user_id = '.$input['user_id'].' AND arrears_type in (\'3\',\'4\') AND created_at BETWEEN \''.date('Y-m-d H:i:s',strtotime($input['invoice_date'])).'\' AND \''.date('Y-m-d H:i:s',strtotime($input['invoice_date'])+3600*24-1).'\' GROUP BY contract_id) AS T WHERE T.SUMM > '.$input['amount'];
                $count = DB::table(DB::raw($sql))->get()->toArray();
            }else{
                $sql = '(SELECT  SUM(arrears_fee) AS SUMM ,contract_id FROM order_arrears WHERE user_id = '.$input['user_id'].' AND arrears_type in (\'3\',\'4\') GROUP BY contract_id) AS T WHERE T.SUMM > '.$input['amount'];
                $count = DB::table(DB::raw($sql))->get()->toArray();
            }
            $res_count = count($count);
            if($res_count <= ($input['page']-1)*10){
                return $this->error('2','no more fee information');
            }else{
                $res = DB::table(DB::raw($sql))->offset(($input['page']-1)*10)->limit(10)->get()->toArray();
                foreach ($res as $k => $v){
                    $v = (array)$v;
                    $fee_res = OrderArrears::where('contract_id',$v['contract_id'])->get()->toArray();
                    $fee_list[$k]['order_id'] = $fee_res[0]['order_id'];
                    $fee_list[$k]['order_sn'] = $fee_res[0]['order_sn'];
                    $fee_list[$k]['invoice_date'] = '';
                    $fee_list[$k]['payment_due'] = '';
                    $fee_list[$k]['amount'] = 0;
                    foreach ($fee_res as $key => $value){
                        $fee_list[$k]['invoice_date'] = $value['created_at'];
                        $fee_list[$k]['payment_due'] = $value['expire_date'];
                        $fee_list[$k]['amount'] += $value['arrears_fee'];
                    }
                }
                $data['fee_list'] = $fee_list;
                $data['current_page'] = $input['page'];
                $data['total_page'] = ceil($res_count/10);
                return $this->success('get fee list success',$data);
            }
        }else{
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
                'landlord_name'     => \App\Model\User::where('id',$order_info->user_id)->pluck('nickname'),
                'items_name'        => $v['items_name'],
                'describe'          => $v['describe'],
                'unit_price'        => $v['unit_price'],
                'number'            => $v['number'],
                'invoice_sn'        => invoiceSn(),
                'subject_code'      => $v['subject_code'],
                'discount'          => $v['discount'],
                'tex'               => $v['tex'],
                'arrears_fee'       => ($v['unit_price']*$v['number'])*(100-$v['discount'])/100*(100+$v['tex'])/100,
                'need_pay_fee'      => ($v['unit_price']*$v['number'])*(100-$v['discount'])/100*(100+$v['tex'])/100,
                'Region'            => $order_info->Region,
                'TA'                => $order_info->TA,
                'District'          => $order_info->District,
                'pay_fee'           => 0,
                'is_pay'            => 1,
                'created_at'        => date('Y-m-d H:i:s',time()),
            ];
            $arrears_res = $model->insert($order_arrears_data);
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
        }
    }





}