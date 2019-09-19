<?php

namespace App\Http\Controllers\Admin\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Shop\Cate;

class CategoryController extends Controller
{
    /***
     * 选择分类列表
     */
    public function index(Request $request)
    {
        $checked = [];

        if ($request->has('ids') and $request->ids != '') {
            $checked = explode(',', $request->ids);
        }

//        $cates = Cate::withCount('children')->where('parent_id', 0)->get();
        $cates = Cate::where('parent_id', 0)->get();

        return view('admin.common.cate.index', compact('cates','checked'));
    }
}
