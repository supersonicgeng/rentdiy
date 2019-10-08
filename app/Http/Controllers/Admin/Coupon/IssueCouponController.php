<?php

namespace App\Http\Controllers\Admin\Coupon;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class IssueCouponController extends Controller
{
    /***
     * 充值配置
     */

//充值配置展示页
    public function index(Request $request)
    {

        //按管理员名称搜索
        if ($request->has('word') and $request->word != '') {
            $word = $request->word;
        } else {
            $word = "a123456789aaa";
        }

//
        $item = DB::table("user")->orwhere("e_mail", "like", "%" . $word . "%")
            ->orwhere("phone", "like", "%" . $word . "%")->first();

        return view('admin.coupon.issue_coupon.index', compact("item"));
    }


//发放优惠券页面
    public function makeCoupon(Request $request)
    {
        $id = $request->id;
        return view('admin.coupon.issue_coupon.make_coupon', compact("id"));

    }


//发送优惠券
    public function saveCoupon(Request $request)
    {

        $send = 0;

        if ($request->discount != "" && $request->discountNumber != "") {
            $send = 1;
            for ($i = 0; $i < $request->discountNumber; $i++) {
                $data["coupon_sn"] = time() . rand(100000, 999999);
                $data["coupon_type"] = 1;
                $data["discount"] = $request->discount;
                $data['expens_time'] = $request->discountTime;
                $data["is_used"] = 0;
                $data["used_user_id"] = $request->id;
                $data["is_activated"] = 0;
                $data["deductions"] = 0;
                $data["created_at"] = Carbon::now();
                $data["updated_at"] = Carbon::now();

                DB::table("coupon_list")->insert($data);
                unset($data);
            }
        }

        if ($request->deductions != "" && $request->deductionsNumber != "") {
            $send = 1;
            for ($i = 0; $i < $request->deductionsNumber; $i++) {
                $data["coupon_sn"] = time() . rand(100000, 999999);
                $data["coupon_type"] = 2;
                $data["discount"] = 0;
                $data["is_used"] = 0;
                $data["used_user_id"] = $request->id;
                $data["is_activated"] = 0;
                $data["deductions"] = $request->deductions;
                $data["created_at"] = Carbon::now();
                $data["updated_at"] = Carbon::now();

                DB::table("coupon_list")->insert($data);
                unset($data);
            }
        }
        if ($send == 0) {
            return back()->with('alert', "Please Input Complete");
        } else {
            return back()->with('notice', "Coupon Issue Success");
        }

    }


    /***
     * 使用或禁用
     */
    public function change_attr(Request $request)
    {

        $attr = $request->attr;
        $goods = DB::table("charge")->whereId($request->id)->first();
        $goods->$attr = $goods->$attr ? 0 : 1;
        DB::table("charge")->whereId($request->id)->update([$attr => $goods->$attr]);

        return ['status' => 1, 'msg' => 'Update Success'];
    }






}
