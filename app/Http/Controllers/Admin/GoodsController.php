<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class GoodsController extends CommonController
{
    public function goodsCategory(Request $request){
        if ($request->ajax()) {
            $input = $request->all();
            $list  = service('Goods')->goodsCategory($input);
            return view('admin.goods.category.listAjax', ['list' => $list]);
        } else {
            return view('admin.goods.category.list');
        }
    }

    public function goodsCategoryAdd(Request $request){
        if ($request->isMethod('post')) {
            $input  = $request->all();
            $result = service('Goods')->goodsCategoryAdd($input);
            if ($result['code'] == 0) {
                return $this->success('添加成功', null, url('manage/goodsCategory'));
            } else {
                return $this->error($result['code'], $result['msg']);
            }
        } else {
            return view('admin.goods.category.add');
        }
    }

    public function goodsCategoryEdit(Request $request){
        if ($request->isMethod('post')) {
            $input  = $request->all();
            $result = service('Goods')->goodsCategoryEdit($request->id, $input);
            if ($result['code'] == 0) {
                return $this->success('修改成功', null, url('manage/goodsCategory'));
            } else {
                return $this->error($result['code'], $result['msg']);
            }
        } else {
            return view('admin.goods.category.edit', ['info' => service('Goods')->goodsCategoryInfo($request->id)]);
        }
    }

    public function goodsCategoryDel(Request $request){
        $result = service('Goods')->goodsCategoryDel($request->id);
        if ($result['code'] == 0) {
            return $this->success('删除成功', null, url('manage/goodsCategory'));
        } else {
            return $this->error($result['code'], $result['msg']);
        }
    }

    public function goods(Request $request){
        if ($request->ajax()) {
            $input = $request->all();
            $list  = service('Goods')->goods($input);
            return view('admin.goods.listAjax', ['list' => $list]);
        } else {
            return view('admin.goods.list');
        }
    }

    public function goodsAdd(Request $request){
        if ($request->isMethod('post')) {
            $input  = $request->all();
            $result = service('Goods')->goodsAdd($input);
            if ($result['code'] == 0) {
                return $this->success('添加成功', null, url('manage/goods'));
            } else {
                return $this->error($result['code'], $result['msg']);
            }
        } else {
            return view('admin.goods.add',['categorys'=>service('Goods')->allCategory()]);
        }
    }

    public function goodsEdit(Request $request){
        if ($request->isMethod('post')) {
            $input  = $request->all();
            $result = service('Goods')->goodsEdit($request->id, $input);
            if ($result['code'] == 0) {
                return $this->success('修改成功', null, url('manage/goods'));
            } else {
                return $this->error($result['code'], $result['msg']);
            }
        } else {
            return view('admin.goods.edit', ['info' => service('Goods')->goodsInfo($request->id),'categorys'=>service('Goods')->allCategory()]);
        }
    }

    public function goodsDel(Request $request){
        return service('Goods')->goodsDel($request->id);
    }
}
