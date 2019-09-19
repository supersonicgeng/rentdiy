<?php

namespace App\Http\Controllers\Admin;


use App\Http\Services\OrderService1;
use App\Models\Customer;

use App\Models\Order;
use App\Models\OrderProfit;
use App\Models\System\Permission;
use App\Models\TbOrder;
use App\Models\Withdraw;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\CustomerInfo;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{

    /**
     * 首页仪表盘
     */
    public function index()
    {

//        $fb = new \Facebook\Facebook([
//            'app_id' => '2336245916486482',
//            'app_secret' => 'ecc22a01d55efc66d860159b60963b1a',
//            'default_graph_version' => 'v3.2',
//            //'default_access_token' => '{access-token}', // optional
//        ]);

// Use one of the helper classes to get a Facebook\Authentication\AccessToken entity.
//   $helper = $fb->getRedirectLoginHelper();
//   $helper = $fb->getJavaScriptHelper();
//   $helper = $fb->getCanvasHelper();
//   $helper = $fb->getPageTabHelper();

//        try {
//            // Get the \Facebook\GraphNodes\GraphUser object for the current user.
//            // If you provided a 'default_access_token', the '{access-token}' is optional.
//            $response = $fb->get('/me', '{access-token}');
//        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
//            // When Graph returns an error
//            echo 'Graph returned an error: ' . $e->getMessage();
//            exit;
//        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
//            // When validation fails or other local issues
//            echo 'Facebook SDK returned an error: ' . $e->getMessage();
//            exit;
//        }
//
//        dd($response);

//             $client = new \Google_Client(['client_id'=>'288789996790-8j11as1hninv897nor26l6aulu6v1chr.apps.googleusercontent.com']);
//
//        $payload = $client->verifyIdToken("eyJhbGciOiJSUzI1NiIsImtpZCI6IjA1YTAyNjQ5YTViNDVjOTBmZGZlNGRhMWViZWZhOWMwNzlhYjU5M2UiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJhY2NvdW50cy5nb29nbGUuY29tIiwiYXpwIjoiMjg4Nzg5OTk2NzkwLThqMTFhczFobmludjg5N25vcjI2bDZhdWx1NnYxY2hyLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwiYXVkIjoiMjg4Nzg5OTk2NzkwLThqMTFhczFobmludjg5N25vcjI2bDZhdWx1NnYxY2hyLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwic3ViIjoiMTA5NzA5MzkyMDA3MzE0ODcwMzA3IiwiZW1haWwiOiJwZW5namluY21AZ21haWwuY29tIiwiZW1haWxfdmVyaWZpZWQiOnRydWUsImF0X2hhc2giOiJmZHlYZ09Tb0FlZnhOV3F1RUVGajRnIiwibmFtZSI6IuW9remUpiIsInBpY3R1cmUiOiJodHRwczovL2xoNi5nb29nbGV1c2VyY29udGVudC5jb20vLUxEaU5UWk1MbmNvL0FBQUFBQUFBQUFJL0FBQUFBQUFBQUFBL0FDSGkzcmZHVGwyd2Rsc0xieTFUTWlHeGdJNGxFeHpwTEEvczk2LWMvcGhvdG8uanBnIiwiZ2l2ZW5fbmFtZSI6IumUpiIsImZhbWlseV9uYW1lIjoi5b2tIiwibG9jYWxlIjoiemgtQ04iLCJpYXQiOjE1Njc1NzgwMzMsImV4cCI6MTU2NzU4MTYzMywianRpIjoiZmM0NDc2YWExZThiYTRhMTYwNGM4MGEwNmIzZTM5YjA5N2U3ZTY0YSJ9.X0PkLpxSOXSfR3Q7H0996PkpWw03wCOlAQ-JeRz1tkxIEoeIg2nWnWbwZSd0baoWYRkg2OFV_92D-UhkDCTcNfgR-daFjT7xsOJuLBw1Ls1ZMM5AWqFKWwqrAVjyYWTXDAMJlZH_nUPozA1_FzR5B6jCO53Hoda6-9FFMTvFY8gxtlPlCaZmytZQeasqAacokGQ58W-GGT4IH1FzG1oBC3WZgIYNzDaXK4OaGPnUYVc1a8bE4zWamxNxIRHVBi3QwJkkF1NXFT3rZllvIRyRcg5_5YF--8PJwLEwNnpRWaAZGMFqSMcPRoTMAf0NMYjJhFqb79bn6jUD_tfZYxJaGw");
//        if ($payload) {
//
//            dd($payload);
//
//            $userid = $payload['sub'];
//            // If request specified a G Suite domain:
//            //$domain = $payload['hd'];
//        } else {
//            // Invalid ID token
//            return 123;
//        }

//        $pf_ids = \DB::select('SELECT order_id FROM order_profit WHERE `status`=1 AND order_id IN(SELECT id FROM orders WHERE order_type=1) GROUP BY order_id');
//        $pf_ids = collect($pf_ids)->pluck('order_id');
//        $g_ids = \DB::select('SELECT order_id FROM tb_orders WHERE earning_time < "2019-02-28 23:59:59" AND tk_status=3');
//        $g_ids = collect($g_ids)->pluck('order_id');

//        $else = [];
//
//        foreach ($pf_ids as $id) {
//            $tb = TbOrder::where('order_id', $id)->first();
//
//            if (!$tb) {
//                $else[] = $id;
//            }
//        }
//        return $else;
//        return $pf_ids->diff($g_ids);

//        return compact('pf_ids', 'g_ids');
//
//        $data = \DB::table('tb_orders as t')
//            ->select('t.order_type as type_name', 't.trade_id',
//                't.num_iid', 't.good_id', 't.item_title', 'g.name as cate_name', 't.price', 't.alipay_total_price', 't.item_num',
//                't.total_commission_rate', 't.pub_share_pre_fee',
//                'r.v1', 'r.v1_p', 'r.v2', 'r.v2_p', 'r.v3', 'r.v3_p', 'r.v4', 'r.v4_p', 'r.platform_p', 'r.order_id',
//                't.tk_status', 't.earning_time', 't.create_time')
//            ->leftJoin('order_report as r', function ($join) {
//                $join->on('r.order_id', 't.order_id');
//            })
//            ->leftJoin('cates as g', 'g.id', 't.cate_id')
//            ->get();
//
//        $a = $data->where('v1', 515)->where('tk_status', 3)->sum('v1_p');
//
//
////        $a1 = OrderProfit::where('f_id',515)->where('status',1)->sum('order_id');
//
//
//        $b = $data->where('v2', 515)->where('tk_status', 3)->sum('v2_p');
//
//
//        $c = $data->where('v3', 515)->where('tk_status', 3)->sum('v3_p');
//        $d = $data->where('v4', 515)->where('tk_status', 3)->sum('v4_p');

//       $h = collect([$a,$b,$c,$d])->collapse()->all();
//
//       return $a1->diff($h);
//        return $a + $b + $c + $d;


//        $profits = OrderProfit::whereIn('order_id', [576, 673, 960])->where('status', 1)->get();
//
//        foreach ($profits as $p) {
//            $p->status = 0;
//            $p->save();
//
//            $customer_id = $p->f_id;
//            $customer = CustomerInfo::where('customer_id', $customer_id)->first();
//            $customer->balance = $customer->balance - $p->f_prof;
//            $customer->confirmed_income =$customer->confirmed_income - $p->f_prof;
//            $customer->save();
//        }
//        $p= OrderProfit::where('f_id',515)->where('status',1)->sum('f_prof');
//        $f= OrderProfit::where('f_id',515)->where('status',0)->sum('f_prof');
//
//        CustomerInfo::where('customer_id',515)->update(['balance'=>$p,'confirmed_income'=>$p,'forecast'=>$f]);

        return view('admin.index');
    }


    public function clear()
    {

        Cache::flush();

        return back()->with('notice', '清除缓存成功');
    }


}
