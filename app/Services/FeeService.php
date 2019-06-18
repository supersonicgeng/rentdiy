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
                'subject_code'      => $input['subject_code'],
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
                'subject_code'      => $input['subject_code'],
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
                    static $total_arrears = 0;
                    foreach ($fee_res as $key => $value){
                        if($value['arrears_type'] == 3){
                            $total_arrears += $value['arrears_fee'];
                            $fee_list[$k]['invoice_date'] = $value['created_at'];
                            $fee_list[$k]['payment_due'] = $value['expire_date'];
                            $fee_list[$k]['amount'] = $total_arrears;
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
                    static $total_arrears = 0;
                    foreach ($fee_res as $key => $value){
                        if($value['arrears_type'] == 3){
                            $total_arrears += $value['arrears_fee'];
                            $fee_list[$k]['invoice_date'] = $value['created_at'];
                            $fee_list[$k]['payment_due'] = $value['expire_date'];
                        }
                        $fee_list[$k]['amount'] = $total_arrears;
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
        if($input['start_date']){
            $model = $model->where('created_at','>',date('Y-m-d H:i:s',$input['start_date']));
        }
        if($input['end_date']){
            $model = $model->where('created_at','<',date('Y-m-d H:i:s',$input['end_date']+3600*24-1));
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
                    static $total_arrears = 0;
                    foreach ($fee_res as $key => $value){
                        $total_arrears += $value['need_pay_fee'];
                        $fee_list[$k]['payment_due'] = $value['expire_date'];
                        $fee_list[$k]['amount'] = $total_arrears;
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
                    $fee_res = RentArrears::where('contract_id',$v['contract_id'])->get()->toArray();
                    $fee_list[$k]['contract_id'] = $v['contract_id'];
                    $fee_list[$k]['contract_sn'] = $fee_res[0]['contract_sn'];
                    $fee_list[$k]['tenement_name'] = $fee_res[0]['tenement_name'];
                    $fee_list[$k]['payment_due'] = '';
                    static $total_arrears = 0;
                    foreach ($fee_res as $key => $value){
                        $total_arrears += $value['need_pay_fee'];
                        $fee_list[$k]['payment_due'] = $value['expire_date'];
                        $fee_list[$k]['amount'] = round($total_arrears,2);
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


    public function test(array $input,$file)
    {
        $excel = new Excel();
        $data = $excel::toArray(new UsersImport(), $file);
        if($input['bank_type'] == 'ANZ'){
            if($data[0][0] != 'Type' || $data[0][1] != 'Details' || $data[0][2] != 'Particulars' || $data[0][3] != 'Code' || $data[0][4] != 'Reference' || $data[0][5] != 'Amount' || $data[0][6] != 'Date' || $data[0][7] != 'ForeignCurrencyAmount' || $data[0][8] != 'ConversionCharge'){
                return $this->error('2','the csv file is not the select bank');
            }else{
                dd($data);
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
                        $date = date('Y-m-d',strtotime($date));
                        dump($date);
                        if(strtotime($date) >= strtotime($input['check_start_date'])&&strtotime($date) <= strtotime($input['check_end_date'])+3600*24-1){
                            // 匹配
                            $match_res = RentArrears::where('need_pay_fee',$amount)->where('user_id',$input['user_id'])->whereIn('is_pay',[1,3])->where('subject_code',$code)->first();
                            dump($match_res);
                            if($match_res){ // 匹配成功
                                $bank_check_data = [
                                    'user_id'   => $input['user_id'],
                                    'check_id'  => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'ANZ',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_arrears_id'  => $match_res->id,
                                    'is_checked'        => 1,
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                dump($bank_check_data);
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res){ // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i+1;
                                }else{
                                    // 匹配成功的 费用单 更新对应的 对账id
                                    $match_res->bank_check_id = $bank_check_res;
                                    $match_res->update();
                                }
                            }else{
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'ANZ',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_arrears_id'  => $match_res->id,
                                    'is_checked'        => 1,
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                dump($bank_check_data);
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
                dump($match_up_res);
                if($match_up_res){
                    foreach ($match_up_res as $k => $v){
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
                    $check_data['check_res'] = $res;
                    $check_data['check_id'] = $check_id+1;
                    return $this->success('match success',$check_data);
                }else{
                    return $this->error('2','no match data');
                }
            }
        }elseif ($input['bank_type'] == 'BNZ'){
            if($data[0][0] != 'Date' || $data[0][1] != 'Amount' || $data[0][2] != 'Payee' || $data[0][3] != 'Particulars' || $data[0][4] != 'Code' || $data[0][5] != 'Reference' || $data[0][6] != 'Tran Type' || $data[0][7] != 'This Party Account' || $data[0][8] != 'Other Party Account' || $data[0][9] != 'Serial' || $data[0][10] != 'Transaction Code' || $data[0][11] != 'Batch Number' || $data[0][12] != 'Originating Bank/Branch' || $data[0][13] != 'Processed Date'){
                return $this->error('2','the csv file is not the select bank');
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
                        $date = date('Y-m-d',strtotime($date));
                        if(strtotime($date) >= strtotime($input['check_start_date'])&&strtotime($date) <= strtotime($input['check_end_date'])+3600*24-1){
                            // 匹配
                            $match_res = RentArrears::where('need_pay_fee',$amount)->where('user_id',$input['user_id'])->whereIn('is_pay',[1,3])->where('subject_code',$code)->first();
                            if($match_res){ // 匹配成功
                                $bank_check_data = [
                                    'user_id'   => $input['user_id'],
                                    'check_id'  => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'BNZ',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_arrears_id'  => $match_res->id,
                                    'is_checked'        => 1,
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res){ // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i+1;
                                }else{
                                    // 匹配成功的 费用单 更新对应的 对账id
                                    $match_res->bank_check_id = $bank_check_res;
                                    $match_res->update();
                                }
                            }else{
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'BNZ',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_arrears_id'  => $match_res->id,
                                    'is_checked'        => 1,
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
                    foreach ($match_up_res as $k => $v){
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
                    $check_data['check_res'] = $res;
                    $check_data['check_id'] = $check_id+1;
                    return $this->success('match success',$check_data);
                }else{
                    return $this->error('2','no match data');
                }
            }
        }elseif ($input['bank_type'] == 'westpac'){
            if($data[0][0] != 'Date' || $data[0][1] != 'Amount' || $data[0][2] != 'Other Party' || $data[0][3] != 'Description' || $data[0][4] != 'Reference' || $data[0][5] != 'Particulars' || $data[0][6] != 'Analysis Code'){
                return $this->error('2','the csv file is not the select bank');
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
                        $date = date('Y-m-d',strtotime($date));
                        if(strtotime($date) >= strtotime($input['check_start_date'])&&strtotime($date) <= strtotime($input['check_end_date'])+3600*24-1){
                            // 匹配
                            $match_res = RentArrears::where('need_pay_fee',$amount)->where('user_id',$input['user_id'])->whereIn('is_pay',[1,3])->where('subject_code',$code)->first();
                            if($match_res){ // 匹配成功
                                $bank_check_data = [
                                    'user_id'   => $input['user_id'],
                                    'check_id'  => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'westpac',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_arrears_id'  => $match_res->id,
                                    'is_checked'        => 1,
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res){ // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i+1;
                                }else{
                                    // 匹配成功的 费用单 更新对应的 对账id
                                    $match_res->bank_check_id = $bank_check_res;
                                    $match_res->update();
                                }
                            }else{
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'westpac',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_arrears_id'  => $match_res->id,
                                    'is_checked'        => 1,
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
                    foreach ($match_up_res as $k => $v){
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
                    $check_data['check_res'] = $res;
                    $check_data['check_id'] = $check_id+1;
                    return $this->success('match success',$check_data);
                }else{
                    return $this->error('2','no match data');
                }
            }
        }elseif ($input['bank_type'] == 'ASB'){
            if($data[6][0] != 'Date' || $data[6][1] != 'Unique Id' || $data[6][2] != 'Tran Type' || $data[6][3] != 'Cheque Number' || $data[6][4] != 'Payee' || $data[6][5] != 'Memo' || $data[6][6] != 'Amount'){
                return $this->error('2','the csv file is not the select bank');
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
                        $amount = $data[$i][1];
                        $date = $data[$i][0];
                        $date = date('Y-m-d',strtotime($date));
                        if(strtotime($date) >= strtotime($input['check_start_date'])&&strtotime($date) <= strtotime($input['check_end_date'])+3600*24-1){
                            // 匹配
                            $match_res = RentArrears::where('need_pay_fee',$amount)->where('user_id',$input['user_id'])->whereIn('is_pay',[1,3])->where('subject_code',$code)->first();
                            if($match_res){ // 匹配成功
                                $bank_check_data = [
                                    'user_id'   => $input['user_id'],
                                    'check_id'  => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'ASB',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_arrears_id'  => $match_res->id,
                                    'is_checked'        => 1,
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res){ // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i+1;
                                }else{
                                    // 匹配成功的 费用单 更新对应的 对账id
                                    $match_res->bank_check_id = $bank_check_res;
                                    $match_res->update();
                                }
                            }else{
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'ASB',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_arrears_id'  => $match_res->id,
                                    'is_checked'        => 1,
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
                    foreach ($match_up_res as $k => $v){
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
                    $check_data['check_res'] = $res;
                    $check_data['check_id'] = $check_id+1;
                    return $this->success('match success',$check_data);
                }else{
                    return $this->error('2','no match data');
                }
            }
        }elseif ($input['bank_type'] == 'kiwi'){
            if(substr($data[0][0],0,2) != '38' ){
                return $this->error('2','the csv file is not the select bank');
            }else{
                $check_id = BankCheck::max('check_id');
                $i = 8;
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
                        $date = date('Y-m-d',strtotime($date));
                        if(strtotime($date) >= strtotime($input['check_start_date'])&&strtotime($date) <= strtotime($input['check_end_date'])+3600*24-1){
                            // 匹配
                            $match_res = RentArrears::where('need_pay_fee',$amount)->where('user_id',$input['user_id'])->whereIn('is_pay',[1,3])->where('subject_code',$code)->first();
                            if($match_res){ // 匹配成功
                                $bank_check_data = [
                                    'user_id'   => $input['user_id'],
                                    'check_id'  => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'kiwi',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_arrears_id'  => $match_res->id,
                                    'is_checked'        => 1,
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res){ // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i+1;
                                }else{
                                    // 匹配成功的 费用单 更新对应的 对账id
                                    $match_res->bank_check_id = $bank_check_res;
                                    $match_res->update();
                                }
                            }else{
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'kiwi',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_arrears_id'  => $match_res->id,
                                    'is_checked'        => 1,
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
                    foreach ($match_up_res as $k => $v){
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
                    $check_data['check_res'] = $res;
                    $check_data['check_id'] = $check_id+1;
                    return $this->success('match success',$check_data);
                }else{
                    return $this->error('2','no match data');
                }
            }
        }elseif ($input['bank_type'] == 'TSB'){
            if($data[0][0] != 'Date' || $data[0][1] != 'Amount' || $data[0][2] != 'Reference' || $data[0][3] != 'Description' || $data[0][4] != 'Particulars'){
                return $this->error('2','the csv file is not the select bank');
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
                        $date = date('Y-m-d',strtotime($date));
                        if(strtotime($date) >= strtotime($input['check_start_date'])&&strtotime($date) <= strtotime($input['check_end_date'])+3600*24-1){
                            // 匹配
                            $match_res = RentArrears::where('need_pay_fee',$amount)->where('user_id',$input['user_id'])->whereIn('is_pay',[1,3])->where('subject_code',$code)->first();
                            if($match_res){ // 匹配成功
                                $bank_check_data = [
                                    'user_id'   => $input['user_id'],
                                    'check_id'  => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'TSB',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_arrears_id'  => $match_res->id,
                                    'is_checked'        => 1,
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res){ // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i+1;
                                }else{
                                    // 匹配成功的 费用单 更新对应的 对账id
                                    $match_res->bank_check_id = $bank_check_res;
                                    $match_res->update();
                                }
                            }else{
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'TSB',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_arrears_id'  => $match_res->id,
                                    'is_checked'        => 1,
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
                    foreach ($match_up_res as $k => $v){
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
                    $check_data['check_res'] = $res;
                    $check_data['check_id'] = $check_id+1;
                    return $this->success('match success',$check_data);
                }else{
                    return $this->error('2','no match data');
                }
            }
        }elseif ($input['bank_type'] == 'co-operative'){
            if($data[0][0] != 'Date' || $data[0][1] != 'Details' || $data[0][2] != 'Amount' || $data[0][3] != 'Balance'){
                return $this->error('2','the csv file is not the select bank');
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
                        $date = date('Y-m-d',strtotime($date));
                        if(strtotime($date) >= strtotime($input['check_start_date'])&&strtotime($date) <= strtotime($input['check_end_date'])+3600*24-1){
                            // 匹配
                            $match_res = RentArrears::where('need_pay_fee',$amount)->where('user_id',$input['user_id'])->whereIn('is_pay',[1,3])->where('subject_code',$code)->first();
                            if($match_res){ // 匹配成功
                                $bank_check_data = [
                                    'user_id'   => $input['user_id'],
                                    'check_id'  => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'co-operative',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_arrears_id'  => $match_res->id,
                                    'is_checked'        => 1,
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res){ // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i+1;
                                }else{
                                    // 匹配成功的 费用单 更新对应的 对账id
                                    $match_res->bank_check_id = $bank_check_res;
                                    $match_res->update();
                                }
                            }else{
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'co-operative',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_arrears_id'  => $match_res->id,
                                    'is_checked'        => 1,
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
                    foreach ($match_up_res as $k => $v){
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
                    $check_data['check_res'] = $res;
                    $check_data['check_id'] = $check_id+1;
                    return $this->success('match success',$check_data);
                }else{
                    return $this->error('2','no match data');
                }
            }
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
                return $this->error('2','the csv file is not the select bank');
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
                            $match_res = RentArrears::where('need_pay_fee',$amount)->where('user_id',$input['user_id'])->whereIn('is_pay',[1,3])->where('subject_code',$code)->first();
                            if($match_res){ // 匹配成功
                                $bank_check_data = [
                                    'user_id'   => $input['user_id'],
                                    'check_id'  => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'ANZ',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_arrears_id'  => $match_res->id,
                                    'is_checked'        => 1,
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res){ // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i+1;
                                }else{
                                    // 匹配成功的 费用单 更新对应的 对账id
                                    $match_res->bank_check_id = $bank_check_res;
                                    $match_res->update();
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
                    foreach ($match_up_res as $k => $v){
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
                    $check_data['check_res'] = $res;
                    $check_data['check_id'] = $check_id+1;
                    return $this->success('match success',$check_data);
                }else{
                    return $this->error('2','no match data');
                }
            }
        }elseif ($input['bank_type'] == 'BNZ'){
            if($data[0][0] != 'Date' || $data[0][1] != 'Amount' || $data[0][2] != 'Payee' || $data[0][3] != 'Particulars' || $data[0][4] != 'Code' || $data[0][5] != 'Reference' || $data[0][6] != 'Tran Type' || $data[0][7] != 'This Party Account' || $data[0][8] != 'Other Party Account' || $data[0][9] != 'Serial' || $data[0][10] != 'Transaction Code' || $data[0][11] != 'Batch Number' || $data[0][12] != 'Originating Bank/Branch' || $data[0][13] != 'Processed Date'){
                return $this->error('2','the csv file is not the select bank');
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
                        $date = date('Y-m-d',strtotime($date));
                        if(strtotime($date) >= strtotime($input['check_start_date'])&&strtotime($date) <= strtotime($input['check_end_date'])+3600*24-1){
                            // 匹配
                            $match_res = RentArrears::where('need_pay_fee',$amount)->where('user_id',$input['user_id'])->whereIn('is_pay',[1,3])->where('subject_code',$code)->first();
                            if($match_res){ // 匹配成功
                                $bank_check_data = [
                                    'user_id'   => $input['user_id'],
                                    'check_id'  => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'BNZ',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_arrears_id'  => $match_res->id,
                                    'is_checked'        => 1,
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res){ // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i+1;
                                }else{
                                    // 匹配成功的 费用单 更新对应的 对账id
                                    $match_res->bank_check_id = $bank_check_res;
                                    $match_res->update();
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
                    foreach ($match_up_res as $k => $v){
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
                    $check_data['check_res'] = $res;
                    $check_data['check_id'] = $check_id+1;
                    return $this->success('match success',$check_data);
                }else{
                    return $this->error('2','no match data');
                }
            }
        }elseif ($input['bank_type'] == 'westpac'){
            if($data[0][0] != 'Date' || $data[0][1] != 'Amount' || $data[0][2] != 'Other Party' || $data[0][3] != 'Description' || $data[0][4] != 'Reference' || $data[0][5] != 'Particulars' || $data[0][6] != 'Analysis Code'){
                return $this->error('2','the csv file is not the select bank');
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
                        $date = date('Y-m-d',strtotime($date));
                        if(strtotime($date) >= strtotime($input['check_start_date'])&&strtotime($date) <= strtotime($input['check_end_date'])+3600*24-1){
                            // 匹配
                            $match_res = RentArrears::where('need_pay_fee',$amount)->where('user_id',$input['user_id'])->whereIn('is_pay',[1,3])->where('subject_code',$code)->first();
                            if($match_res){ // 匹配成功
                                $bank_check_data = [
                                    'user_id'   => $input['user_id'],
                                    'check_id'  => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'westpac',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_arrears_id'  => $match_res->id,
                                    'is_checked'        => 1,
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res){ // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i+1;
                                }else{
                                    // 匹配成功的 费用单 更新对应的 对账id
                                    $match_res->bank_check_id = $bank_check_res;
                                    $match_res->update();
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
                    foreach ($match_up_res as $k => $v){
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
                    $check_data['check_res'] = $res;
                    $check_data['check_id'] = $check_id+1;
                    return $this->success('match success',$check_data);
                }else{
                    return $this->error('2','no match data');
                }
            }
        }elseif ($input['bank_type'] == 'ASB'){
            if($data[6][0] != 'Date' || $data[6][1] != 'Unique Id' || $data[6][2] != 'Tran Type' || $data[6][3] != 'Cheque Number' || $data[6][4] != 'Payee' || $data[6][5] != 'Memo' || $data[6][6] != 'Amount'){
                return $this->error('2','the csv file is not the select bank');
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
                        $amount = $data[$i][1];
                        $date = $data[$i][0];
                        $date = date('Y-m-d',strtotime($date));
                        if(strtotime($date) >= strtotime($input['check_start_date'])&&strtotime($date) <= strtotime($input['check_end_date'])+3600*24-1){
                            // 匹配
                            $match_res = RentArrears::where('need_pay_fee',$amount)->where('user_id',$input['user_id'])->whereIn('is_pay',[1,3])->where('subject_code',$code)->first();
                            if($match_res){ // 匹配成功
                                $bank_check_data = [
                                    'user_id'   => $input['user_id'],
                                    'check_id'  => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'ASB',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_arrears_id'  => $match_res->id,
                                    'is_checked'        => 1,
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res){ // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i+1;
                                }else{
                                    // 匹配成功的 费用单 更新对应的 对账id
                                    $match_res->bank_check_id = $bank_check_res;
                                    $match_res->update();
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
                    foreach ($match_up_res as $k => $v){
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
                    $check_data['check_res'] = $res;
                    $check_data['check_id'] = $check_id+1;
                    return $this->success('match success',$check_data);
                }else{
                    return $this->error('2','no match data');
                }
            }
        }elseif ($input['bank_type'] == 'kiwi'){
            if(substr($data[0][0],0,2) != '38' ){
                return $this->error('2','the csv file is not the select bank');
            }else{
                $check_id = BankCheck::max('check_id');
                $i = 8;
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
                        $date = date('Y-m-d',strtotime($date));
                        if(strtotime($date) >= strtotime($input['check_start_date'])&&strtotime($date) <= strtotime($input['check_end_date'])+3600*24-1){
                            // 匹配
                            $match_res = RentArrears::where('need_pay_fee',$amount)->where('user_id',$input['user_id'])->whereIn('is_pay',[1,3])->where('subject_code',$code)->first();
                            if($match_res){ // 匹配成功
                                $bank_check_data = [
                                    'user_id'   => $input['user_id'],
                                    'check_id'  => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'kiwi',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_arrears_id'  => $match_res->id,
                                    'is_checked'        => 1,
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res){ // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i+1;
                                }else{
                                    // 匹配成功的 费用单 更新对应的 对账id
                                    $match_res->bank_check_id = $bank_check_res;
                                    $match_res->update();
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
                    foreach ($match_up_res as $k => $v){
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
                    $check_data['check_res'] = $res;
                    $check_data['check_id'] = $check_id+1;
                    return $this->success('match success',$check_data);
                }else{
                    return $this->error('2','no match data');
                }
            }
        }elseif ($input['bank_type'] == 'TSB'){
            if($data[0][0] != 'Date' || $data[0][1] != 'Amount' || $data[0][2] != 'Reference' || $data[0][3] != 'Description' || $data[0][4] != 'Particulars'){
                return $this->error('2','the csv file is not the select bank');
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
                        $date = date('Y-m-d',strtotime($date));
                        if(strtotime($date) >= strtotime($input['check_start_date'])&&strtotime($date) <= strtotime($input['check_end_date'])+3600*24-1){
                            // 匹配
                            $match_res = RentArrears::where('need_pay_fee',$amount)->where('user_id',$input['user_id'])->whereIn('is_pay',[1,3])->where('subject_code',$code)->first();
                            if($match_res){ // 匹配成功
                                $bank_check_data = [
                                    'user_id'   => $input['user_id'],
                                    'check_id'  => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'TSB',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'is_checked'        => 1,
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res){ // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i+1;
                                }else{
                                    // 匹配成功的 费用单 更新对应的 对账id
                                    $match_res->bank_check_id = $bank_check_res;
                                    $match_res->update();
                                }
                            }else{
                                $bank_check_data = [
                                    'user_id'           => $input['user_id'],
                                    'check_id'          => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'TSB',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_arrears_id'  => $match_res->id,
                                    'is_checked'        => 1,
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
                    foreach ($match_up_res as $k => $v){
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
                    $check_data['check_res'] = $res;
                    $check_data['check_id'] = $check_id+1;
                    return $this->success('match success',$check_data);
                }else{
                    return $this->error('2','no match data');
                }
            }
        }elseif ($input['bank_type'] == 'co-operative'){
            if($data[0][0] != 'Date' || $data[0][1] != 'Details' || $data[0][2] != 'Amount' || $data[0][3] != 'Balance'){
                return $this->error('2','the csv file is not the select bank');
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
                        $date = date('Y-m-d',strtotime($date));
                        if(strtotime($date) >= strtotime($input['check_start_date'])&&strtotime($date) <= strtotime($input['check_end_date'])+3600*24-1){
                            // 匹配
                            $match_res = RentArrears::where('need_pay_fee',$amount)->where('user_id',$input['user_id'])->whereIn('is_pay',[1,3])->where('subject_code',$code)->first();
                            if($match_res){ // 匹配成功
                                $bank_check_data = [
                                    'user_id'   => $input['user_id'],
                                    'check_id'  => $check_id+1,
                                    'bank_check_date'   => $date,
                                    'bank_sn'           => 'co-operative',
                                    'amount'            => $amount,
                                    'match_code'        => $code,
                                    'match_arrears_id'  => $match_res->id,
                                    'is_checked'        => 1,
                                    'created_at'        => date('Y-m-d H:i:s',time()),
                                ];
                                $bank_check_res = BankCheck::insertGetId($bank_check_data); // 插入待检查表
                                if(!$bank_check_res){ // 插入不成功
                                    $failed_count += 1;
                                    $error_row[] = $i+1;
                                }else{
                                    // 匹配成功的 费用单 更新对应的 对账id
                                    $match_res->bank_check_id = $bank_check_res;
                                    $match_res->update();
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
                    foreach ($match_up_res as $k => $v){
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
                    $check_data['check_res'] = $res;
                    $check_data['check_id'] = $check_id+1;
                    return $this->success('match success',$check_data);
                }else{
                    return $this->error('2','no match data');
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
    public function confirmMatchCheck(array $input)
    {
        $match_ids = $input['match_ids'];
        // 修改 已核对的账目
        foreach ($match_ids as $k => $v){
            $bank_check_data = BankCheck::where('id',$v)->first();
            $bank_check_data->is_check = 2;
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
        // 未确认的账目
        $un_confirm = BankCheck::where('check_id',$input['check_id'])->where('is_check',1)->get();
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

    }
}