<?php

namespace App\Http\Controllers\Admin\Common;

use App\Models\Shop\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    /**
     * 标签列表
     */
    public function index(Request $request)
    {

        $where = function ($query) use ($request) {

            if ($request->has('name') and $request->name != '') {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            if ($request->has('tag_id') and $request->tag_id != '') {
                $query->whereNotIn('id',$request->tag_id);
            }

        };
        $tags = Tag::where($where)->orderBy('be_use_time', 'desc')->paginate();

        return view('admin.common.tag.index',compact('tags'));
    }
}
