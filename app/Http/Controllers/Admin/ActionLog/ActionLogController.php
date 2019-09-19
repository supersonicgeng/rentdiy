<?php

namespace App\Http\Controllers\Admin\ActionLog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ActionLogController extends Controller
{
    /***
     * 商品订单报表
     */
    public function index(Request $request)
    {

        $where = function ($query) use ($request) {
            //按管理员名称搜索
            if ($request->has('userName') and $request->userName != '') {
                $search = "%".$request->userName."%";
                $query->where("user_name","like",$search);
            }

            if ($request->has('dateRange') and $request->dateRange != '') {
                $arr = explode(" - ", $request->dateRange);
                $dateStart = $arr[0];
                $dateEnd = $arr[1];


                $query->whereBetween('created_at', [$dateStart, $dateEnd]);

            }

        };


        $items = DB::table("user_logs")->where($where)->orderBy("id", "desc")->paginate(20);

        return view('admin.action_log.action_log.index', compact('items'));
    }
}
