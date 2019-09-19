<?php

namespace App\Http\Controllers\Admin\Config;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SysSettingController extends Controller
{
    /***
     * 充值配置
     */

    const LB = "LB";


//充值配置展示页
    public function index()
    {

        $data = ["LB", "NUB", "RVF", "RFB", "BVF", "BFB", "FVF", "FFB", "CVF", "CFB", "BF", "RF", "FF", "CF", "SMF", "PMF", "RSF", "BSF", "FSF", "CSF", "PSFL", "HCR", "PSFR", "PSFI", "PSFM", "PSFLI"];


        $items = DB::table("sys_config")->get();

        foreach ($items as $k => $item) {
            if ($item->code == $data[$k]) {
                $res[$data[$k]] = $item->value;
            }
        }


        return view('admin.config.sys_setting.index', compact('res'));
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
     * ajax编辑
     */
    public function change_value(Request $request)
    {

        $code = $request->code;
        $value = $request->value;
        $data = ["RSF", "BSF", "FSF", "CSF", "PSF"];
        if (in_array($code, $data)) {
            $value /= 100;
        }
        DB::table("sys_config")->whereCode($code)->update(["value" => $value]);

        return ['status' => 1, 'msg' => '修改成功'];
    }


}
