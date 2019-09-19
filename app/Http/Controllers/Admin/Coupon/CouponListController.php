<?php

namespace App\Http\Controllers\Admin\Coupon;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class CouponListController extends Controller
{
    /***
     * 充值配置
     */

//充值配置展示页
    public function index(Request $request)
    {

        $items = DB::table("coupon_list")->orderBy("created_at", "desc")->paginate(20);

        return view("admin.coupon.coupon_list.index", compact("items"));

    }


}
