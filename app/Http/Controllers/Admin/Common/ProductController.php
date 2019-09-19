<?php

namespace App\Http\Controllers\Admin\Common;

use App\Http\Services\GoodsUpdate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Shop\Good;

class ProductController extends Controller
{
    /***
     * 多选择商品列表
     */
    public function index(Request $request)
    {

        $order = 'id';
        $desc = 'asc';

        if ($request->has('_sort') and $request->_sort != '') {
            $res = explode(',', $request->_sort);
            $order = $res[0];
            $desc = $res[1];
        }

        $where = function ($query) use ($request) {

            if ($request->has('num_iid') and $request->num_iid != '') {
                $search = $request->num_iid;
                $query->where('num_iid', $search);
            }

            if ($request->has('title') and $request->title != '') {
                $search = $request->title;
                $query->whereRaw('MATCH (fenci) AGAINST ("' . $search . '")');
//                $query->whereRaw("locate(" . $search . ", 'title')>0");
//                $query->where('title', 'like', $search . '%');
            }


            //店铺类型
            if ($request->has('user_type') and $request->user_type != '-1') {
                $search = $request->user_type;
                $query->where('user_type', $search);
            }

            //是否品牌
            if ($request->has('is_brand') and $request->is_brand != '-1') {
                $search = $request->is_brand;
                $query->where('is_brand', $search);
            }

            //是否首页置顶
            if ($request->has('is_index_send') and $request->is_index_send != '-1') {
                $search = $request->is_index_send;

                if ($search == 1) {
                    $query->where('index_weight', '>', 0);
                } else {
                    $query->where('index_weight', 0);
                }
            }


            //是否分类置顶
            if ($request->has('is_cate_send') and $request->is_cate_send != '-1') {
                $search = $request->is_cate_send;
                if ($search == 1) {
                    $query->where('weight', '>', 0);
                } else {
                    $query->where('weight', 0);
                }
            }

//            if ($request->has('ids') and $request->ids != '' and $request->ids !='undefined') {
//                $ids = explode(',', $request->ids);
//                $query->whereNotIn('id', $ids);
//            }
        };

        $goods = Good::where('is_on', 1)->where($where)->orderBy($order, $desc)->paginate(10);


        $ids = explode(',', $request->ids);
//        return $ids;
        return view('admin.common.product.index', compact('goods', 'ids'));
    }


    /***
     * 单选商品列表
     */
    public function single(Request $request)
    {
        $order = 'id';
        $desc = 'asc';

        if ($request->has('_sort') and $request->_sort != '') {
            $res = explode(',', $request->_sort);
            $order = $res[0];
            $desc = $res[1];
        }

        $where = function ($query) use ($request) {

            if ($request->has('num_iid') and $request->num_iid != '') {
                $search = $request->num_iid;
                $query->where('num_iid', $search);
            }

            if ($request->has('title') and $request->title != '') {
                $search = $request->title;
                $query->whereRaw('MATCH (fenci) AGAINST ("' . $search . '")');
            }

            //店铺类型
            if ($request->has('user_type') and $request->user_type != '-1') {
                $search = $request->user_type;
                $query->where('user_type', $search);
            }

            //是否品牌
            if ($request->has('is_brand') and $request->is_brand != '-1') {
                $search = $request->is_brand;
                $query->where('is_brand', $search);
            }

            //是否首页置顶
            if ($request->has('is_index_send') and $request->is_index_send != '-1') {
                $search = $request->is_index_send;

                if ($search == 1) {
                    $query->where('index_weight', '>', 0);
                } else {
                    $query->where('index_weight', 0);
                }
            }


            //是否分类置顶
            if ($request->has('is_cate_send') and $request->is_cate_send != '-1') {
                $search = $request->is_cate_send;
                if ($search == 1) {
                    $query->where('weight', '>', 0);
                } else {
                    $query->where('weight', 0);
                }
            }

//            //人工选品
//            if ($request->has('is_deal') and $request->is_deal != '-1') {
//                $search = $request->is_deal;
//                if ($search == 1) {
//                    $query->where('admin_id', '>', 0);
//                } else {
//                    $query->where('weight', 0);
//                }
//            }

        };

        $goods = Good::where('is_on', 1)->where($where)->orderBy($order, $desc)->paginate(10);

        return view('admin.common.product.single', compact('goods'));
    }

    /***
     * 更新商品库
     */
    public function update(Request $request)
    {
        $res = GoodsUpdate::update($request->num_iid);
       return $res;
    }
}
