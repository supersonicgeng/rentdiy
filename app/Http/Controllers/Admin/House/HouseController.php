<?php

namespace App\Http\Controllers\Admin\House;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class HouseController extends Controller
{
    public function index(Request $request)
    {
        $where = function ($query) use ($request) {
            //按管理员名称搜索
            if ($request->has('property_name') and $request->property_name != '') {
                $search = "%".$request->property_name."%";
                $query->where("h.property_name",'like',$search);
            }
            if ($request->has('userName') and $request->userName != '') {
                $search = "%".$request->userName."%";
                $query->where("u.nickname","like",$search);
            }
            if ($request->has('rent_category') and $request->rent_category != ''){
                $search = $request->rent_category;
                $query->where('h.rent_category',$search);
            }

        };
        $res = DB::table('rent_house as h')->leftJoin('user as u','h.user_id','u.id')
            ->where($where)->select('h.id','h.property_name','u.nickname','h.is_banner','h.banner_sort','h.rent_category')->paginate(20);
        return view("admin.house.index", compact("res"));
    }


    /***
     * 使用或禁用
     */
    public function change_attr(Request $request)
    {
        $attr = $request->attr;
        if(!DB::table('rent_house')->whereId($request->id)->pluck('is_banner')->first()){
            $count = DB::table('rent_house')->where('is_banner',1)->count();
            if($count >= 8){
                return ['status' => 2, 'msg' => '已到最大值 不能展示'];
            }
        }
        $goods = DB::table("rent_house")->whereId($request->id)->first();
        $goods->$attr = $goods->$attr ? 0 : 1;
        DB::table("rent_house")->whereId($request->id)->update([$attr => $goods->$attr]);

        return ['status' => 1, 'msg' => '修改成功'];
    }

}
