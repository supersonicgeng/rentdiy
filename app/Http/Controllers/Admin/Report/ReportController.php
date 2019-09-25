<?php

namespace App\Http\Controllers\Admin\Report;

use App\Model\Inspect;
use App\Model\RentArrears;
use App\Model\RentHouse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function chargeList(Request $request)
    {
        $where = function ($query) use ($request) {
            //按管理员名称搜索
            if ($request->has('userName') and $request->userName != '') {
                $search = "%".$request->userName."%";
                $query->where("u.nickname","like",$search);
            }

            if ($request->has('dateRange') and $request->dateRange != '') {
                $arr = explode(" - ", $request->dateRange);
                $dateStart = $arr[0];
                $dateEnd = $arr[1];


                $query->whereBetween('c.created_at', [$dateStart, $dateEnd]);

            }
            if($request->has('user_role')){
                $search = $request->user_role;
                if($search == 1){
                    $query->whereIn('u.user_role',[1,3,5,7]);
                }elseif ($search == 2){
                    $query->whereIn('u.user_role',[2,3,6,7]);
                }else{
                    $query->where('u.user_role','!=',4);
                }
            }

        };
        $res = DB::table('charge_list as c')
            ->leftJoin('user as u','c.user_id','u.id')->where('c.charge_type',1)->where('c.charge_status',2)
            ->where($where)
            ->select('c.id','u.nickname','u.phone','u.e_mail','c.charge_fee','c.free_fee','c.created_at','u.user_role')
            ->orderByDesc('c.created_at')->paginate(20);
        return view("admin.report.chargeList.index", compact("res"));
    }


    public function userInfo(Request $request)
    {
        $data['landlord_count'] = DB::table('user')->whereIn('user_role',[1,3,5,7])->count();
        $data['landlord_total_income'] = DB::table('expense')->where('user_cost_role',1)->sum('total_cost') ;
        $data['landlord_service_income'] = DB::table('expense')->whereIn('expense_type',[3,4,5,6])->sum('total_cost') ;
        $data['landlord_msg_income'] = DB::table('expense')->where('expense_type',1)->where('user_cost_role',1)->sum('total_cost') ;
        $data['landlord_paper_income'] = DB::table('expense')->where('expense_type',2)->where('user_cost_role',1)->sum('total_cost') ;
        $data['landlord_vip_income'] = DB::table('charge_list')->where('charge_type','!=',1)->where('charge_status',2)->sum('charge_fee') ;
        $data['landlord_expense_cost'] = DB::table('expense')->where('user_cost_role',1)->sum('expense_cost');
        $data['landlord_expense_cost_without_gts'] = ceil($data['landlord_expense_cost']/1.15);
        $data['landlord_expense_cost_gts'] = $data['landlord_expense_cost']-$data['landlord_expense_cost_without_gts'];
        $data['landlord_discount'] = DB::table('expense')->where('user_cost_role',1)->sum('discount');
        $data['landlord_arrears'] = DB::table('user')->whereIn('user_role',[1,3,5,7])->where('balance','<',0)->sum('balance');
        $data['landlord_charge'] = DB::table('charge_list')->where('charge_type',1)->where('charge_role',1)->sum('charge_fee');
        $data['landlord_total_fee'] = $data['landlord_expense_cost'] + $data['landlord_discount'];
        $data['landlord_balance'] = DB::table('user')->whereIn('user_role',[1,3,5,7])->where('balance','>',0)->sum('balance');
        $data['provider_count'] = DB::table('user')->whereIn('user_role',[2,6])->count();
        $data['provider_total_income'] = DB::table('expense')->where('user_cost_role',2)->sum('total_cost') ;
        $data['provider_service_income'] = DB::table('expense')->whereIn('expense_type',[7,8,9,10,11])->sum('total_cost') ;
        $data['provider_msg_income'] = DB::table('expense')->where('expense_type',1)->where('user_cost_role',2)->sum('total_cost') ;
        $data['provider_paper_income'] = DB::table('expense')->where('expense_type',2)->where('user_cost_role',2)->sum('total_cost') ;
        $data['provider_vip_income'] = 0;
        $data['provider_expense_cost'] = DB::table('expense')->where('user_cost_role',2)->sum('expense_cost');
        $data['provider_expense_cost_without_gts'] = ceil($data['provider_expense_cost']/1.15);
        $data['provider_expense_cost_gts'] = $data['provider_expense_cost']-$data['provider_expense_cost_without_gts'];
        $data['provider_discount'] = DB::table('expense')->where('user_cost_role',2)->sum('discount');
        $data['provider_arrears'] = DB::table('user')->whereIn('user_role',[2,4])->where('balance','<',0)->sum('balance');
        $data['provider_charge'] = DB::table('charge_list')->where('charge_type',2)->where('charge_role',1)->sum('charge_fee');
        $data['provider_total_fee'] = $data['provider_expense_cost'] + $data['provider_discount'];
        $data['provider_balance'] = DB::table('user')->whereIn('user_role',[2,6])->where('balance','>',0)->sum('balance');
        return view("admin.report.userInfo.index")->with('data',$data);
    }


    public function userDetail(Request $request)
    {
        $where = function ($query) use ($request) {
            //按管理员名称搜索
            if ($request->has('userName') and $request->userName != '') {
                $search = "%".$request->userName."%";
                $query->where("u.nickname","like",$search);
            }

            if ($request->has('dateRange') and $request->dateRange != '') {
                $arr = explode(" - ", $request->dateRange);
                $dateStart = $arr[0];
                $dateEnd = $arr[1];


                $query->whereBetween('u.created_at', [$dateStart, $dateEnd]);

            }
            if($request->has('user_role')){
                $search = $request->user_role;
                if($search == 1){
                    $query->whereIn('u.user_role',[1,3,5,7]);
                }elseif ($search == 2){
                    $query->whereIn('u.user_role',[2,3,6,7]);
                }
            }else{
                $query->whereIn('u.user_role',[1,3,5,7]);
            }

        };
        $res = DB::table('user as u')->where($where)->paginate(20);
        foreach ($res as $k => $v){
            if($request->user_role == 1){
                $res[$k]->total_income = DB::table('expense')->where('user_id',$v->id)->where('user_cost_role',1)->sum('total_cost') ;
                $res[$k]->service_income = DB::table('expense')->where('user_id',$v->id)->whereIn('expense_type',[3,4,5,6])->sum('total_cost') ;
                $res[$k]->msg_income = DB::table('expense')->where('user_id',$v->id)->where('expense_type',1)->where('user_cost_role',1)->sum('total_cost') ;
                $res[$k]->paper_income = DB::table('expense')->where('user_id',$v->id)->where('expense_type',2)->where('user_cost_role',1)->sum('total_cost') ;
                $res[$k]->vip_income = DB::table('charge_list')->where('user_id',$v->id)->where('charge_type','!=',1)->where('charge_status',2)->sum('charge_fee') ;
                $res[$k]->expense_cost = DB::table('expense')->where('user_id',$v->id)->where('user_cost_role',1)->sum('expense_cost');
                $res[$k]->expense_cost_without_gts = ceil($res[$k]->expense_cost/1.15);
                $res[$k]->expense_cost_gts = $res[$k]->expense_cost-$res[$k]->expense_cost_without_gts;
                $res[$k]->discount= DB::table('expense')->where('user_id',$v->id)->where('user_cost_role',1)->sum('discount');
                $res[$k]->arrears = DB::table('user')->where('id',$v->id)->where('balance','<',0)->sum('balance')?DB::table('user')->where('id',$v->id)->where('balance','<',0)->sum('balance'):0;
                $res[$k]->charge = DB::table('charge_list')->where('user_id',$v->id)->where('charge_type',1)->where('charge_role',1)->sum('charge_fee');
                $res[$k]->balance = DB::table('user')->where('id',$v->id)->where('balance','>',0)->sum('balance')?DB::table('user')->where('id',$v->id)->where('balance','>',0)->sum('balance'):0;
            }else{
                $res[$k]->total_income = DB::table('expense')->where('user_id',$v->id)->where('user_cost_role',2)->sum('total_cost') ;
                $res[$k]->service_income = DB::table('expense')->where('user_id',$v->id)->whereIn('expense_type',[7,8,9,10,11])->sum('total_cost') ;
                $res[$k]->msg_income = DB::table('expense')->where('user_id',$v->id)->where('expense_type',1)->where('user_cost_role',2)->sum('total_cost') ;
                $res[$k]->paper_income = DB::table('expense')->where('user_id',$v->id)->where('expense_type',2)->where('user_cost_role',2)->sum('total_cost') ;
                $res[$k]->vip_income = 0 ;
                $res[$k]->expense_cost = DB::table('expense')->where('user_id',$v->id)->where('user_cost_role',2)->sum('expense_cost');
                $res[$k]->expense_cost_without_gts = ceil($res[$k]->expense_cost/1.15);
                $res[$k]->expense_cost_gts = $res[$k]->expense_cost-$res[$k]->expense_cost_without_gts;
                $res[$k]->discount= DB::table('expense')->where('user_id',$v->id)->where('user_cost_role',2)->sum('discount');
                $res[$k]->arrears = DB::table('user')->where('id',$v->id)->where('balance','<',0)->sum('balance')?DB::table('user')->where('id',$v->id)->where('balance','<',0)->sum('balance'):0;
                $res[$k]->charge = DB::table('charge_list')->where('user_id',$v->id)->where('charge_type',1)->where('charge_role',2)->sum('charge_fee');
                $res[$k]->balance = DB::table('user')->where('id',$v->id)->where('balance','>',0)->sum('balance')?DB::table('user')->where('id',$v->id)->where('balance','>',0)->sum('balance'):0;
            }
        }
        return view("admin.report.userDetail.index", compact("res"));
    }

    public function expenseList(Request $request)
    {
        $where = function ($query) use ($request) {
            //按管理员名称搜索
            if ($request->has('userName') and $request->userName != '') {
                $search = "%".$request->userName."%";
                $query->where("u.nickname","like",$search);
            }

            if ($request->has('dateRange') and $request->dateRange != '') {
                $arr = explode(" - ", $request->dateRange);
                $dateStart = $arr[0];
                $dateEnd = $arr[1];


                $query->whereBetween('e.created_at', [$dateStart, $dateEnd]);

            }
            if($request->has('user_role')){
                $search = $request->user_role;
                if($search == 1){
                    $query->whereIn('u.user_role',[1,3,5,7]);
                }elseif ($search == 2){
                    $query->whereIn('u.user_role',[2,3,6,7]);
                }else{
                    $query->where('u.user_role','!=',4);
                }
            }

        };
        $res = DB::table('expense as e')
            ->leftJoin('user as u','e.user_id','u.id')
            ->where($where)
            ->select('e.id','u.nickname','u.phone','u.e_mail','e.expense_type','e.total_cost','e.user_cost_role','e.created_at','u.user_role')
            ->orderByDesc('e.created_at')->paginate(20);
        return view("admin.report.expenseList.index", compact("res"));
    }

    public function landlordAnalyze(Request $request)
    {

        $where = function ($query) use ($request) {
            //按管理员名称搜索
            if ($request->has('opeartor_method') and $request->opeartor_method != '') {
                $search = $request->opeartor_method;
                $query->where("uo.opeartor_method",$search);
            }
            if ($request->has('login_time') and $request->login_time != ''){
                $search = $request->login_time;
                $query->where('u.login_expire_time','<',date('Y-m-d H:i:s',strtotime('-'.$search.'days')));
            }
            $query->whereIn('u.user_role',[1,3,5,7]);
        };
        $res = DB::table('user as u')->leftJoin('user_opeart_log as uo','u.id','uo.user_id')
            ->where($where)->paginate(20);
        foreach ($res as $k => $v){
            $res[$k]->house_num = DB::table('rent_house')->where('user_id',$v->id)->count();
            $un_logint_day = floor((time()-strtotime($v->login_expire_time))/3600/24);
            if($un_logint_day < 0){
                $un_logint_day = 0;
            }
            $res[$k]->un_logint_day = $un_logint_day;
        }
        return view("admin.report.landlordAnalyze.index", compact("res"));
    }
}
