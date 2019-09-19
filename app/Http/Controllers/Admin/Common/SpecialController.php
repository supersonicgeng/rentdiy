<?php

namespace App\Http\Controllers\Admin\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Shop\Special;
use App\Models\Shop\Cate;

class SpecialController extends Controller
{
    /***
     * 商品专题列表
     */
    public function index()
    {
        $specials = Special::paginate();

        $cates = Cate::all();

        foreach ($specials as $s) {
            $arr = explode(',', $s->cates_id);
            $s->cate_names = $cates->whereIn('id', $arr)->pluck('name');

        }

        return view('admin.common.special.index', compact('specials'));
    }

}
