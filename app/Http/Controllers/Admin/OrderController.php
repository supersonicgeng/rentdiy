<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/2
 * Time: 16:02
 */

namespace App\Http\Controllers\Admin;


use App\Model\Order;
use Illuminate\Http\Request;

class OrderController extends CommonController
{
    /*
     * 订单列表
     * method:any
     * route:manage/order
     */
    public function order(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $list  = service('Order')->order($input);
            return view('admin.order.orderAjax', ['list' => $list]);
        } else {
            return view('admin.order.order');
        }
    }


    /*
     * 取消订单
     * method:any
     * route:manage/order/cancel/{id}
     */
    public function orderCancel(Request $request)
    {
        $id  = $request->id;
        $res = service('Order')->orderDel($id);
        if ($res['code'] == 0) {
            return $this->success('取消成功');
        } else {
            return $this->error($res['code'], $res['msg'], $res['data']);
        }
    }

}