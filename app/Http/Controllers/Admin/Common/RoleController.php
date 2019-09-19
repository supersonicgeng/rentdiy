<?php

namespace App\Http\Controllers\Admin\Common;

use App\Models\Matuser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Shop\Tag;
use App\Models\Shop\Cate;

class RoleController extends Controller
{
    /***
     * 选择角色
     */
    public function index(Request $request)
    {
        $where = function ($query) use ($request) {
            if ($request->has('mname') and $request->mname != '') {
                $query->where('mname', 'like', '%' . $request->mname . '%');
            }
        };

        $matusers = Matuser::where($where)->paginate();

        foreach ($matusers as $k => $v) {
            if($v->tags!=''){
                $a = Tag::whereIn('id', explode(',', $v->tags))->pluck('name')->toArray();
                $v->tag_name = implode(',', $a);
            }

            if($v->cats !=''){
                $b = Cate::whereIn('id', explode(',',$v->cats))->pluck('name')->toArray();
                $v->cat_name = implode(',', $b);
            }

        }


        return view('admin.common.role.index',compact('matusers'));
    }
}
