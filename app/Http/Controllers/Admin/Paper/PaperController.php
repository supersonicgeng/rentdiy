<?php

namespace App\Http\Controllers\Admin\Paper;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PaperController extends Controller
{
    public function index(Request $request)
    {
        $where = function ($query) use ($request) {
            //按管理员名称搜索
            if ($request->has('send_name') and $request->send_name != '') {
                $search = "%".$request->send_name."%";
                $query->where("p.send_name",'like',$search);
            }

            if ($request->has('is_send') and $request->is_send != ''){
                $search = $request->is_send;
                $query->where('p.is_send',$search);
            }

        };
        $res = DB::table('paper_send as p')->where($where)->paginate(20);
        return view("admin.paper.index", compact("res"));
    }

    public function paperPrint(Request $request)
    {
        $paper_ids = $request->checked_id;
        // 修改 状态
        DB::table('paper_send')->whereIn('id',$paper_ids)->update(['is_send'=>1]);
        // 打印邮件
        $url = 'http://66666.frp.zhan2345.com/toPrint?ids='.implode(',',$paper_ids);
        $http = new \GuzzleHttp\Client();
        $response = $http->get($url);
        return ['status' => 1, 'msg' => '打印成功'];
    }
}
