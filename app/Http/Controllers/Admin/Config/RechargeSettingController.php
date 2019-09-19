<?php

namespace App\Http\Controllers\Admin\Config;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RechargeSettingController extends Controller
{
    /***
     * 充值配置
     */

//充值配置展示页
    public function index()
    {
        $items = DB::table("charge")->get();

        return view('admin.config.recharge_setting.index', compact('items'));
    }


//保存设置
    public function store(Request $request)
    {
        $ids = $request->id;
        $chargeFees = $request->charge_fee;
        $freeBalances = $request->free_balance;
        $sorts = $request->sort;
        //   dd($ids, $chargeFees, $freeBalances, $sorts);
        for ($i = 0; $i < count($chargeFees); $i++) {
            $data["charge_fee"] = $chargeFees[$i];
            $data["free_balance"] = $freeBalances[$i];
            $data["sort"] = $sorts[$i];
            DB::table("charge")->whereId($ids[$i])->update($data);
            unset($data);
        }

        return back()->with('notice', "修改成功");

        // return view('admin.config.recharge_setting.index', compact('items'));
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

        return ['status' => 1, 'msg' => '修改成功'];
    }


}
