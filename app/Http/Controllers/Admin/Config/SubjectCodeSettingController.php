<?php

namespace App\Http\Controllers\Admin\Config;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SubjectCodeSettingController extends Controller
{
    //特征码配置展示页
    public function index()
    {
        $items = DB::table("subject_code")->get();

        return view('admin.config.subject_code_setting.index', compact('items'));
    }
    // 特征码显示
    public function create()
    {
        return view('admin.config.subject_code_setting.create', compact('items'));
    }


    public function store(Request $request)
    {
        $data = [
            'items' => $request->items,
            'subject_code'  => $request->subject_code,
        ];


            DB::table('subject_code')->insert($data);

        return redirect(route('config.subjectCodeSetting.index'))->with('notice', '新增成功');
    }




    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = DB::table('subject_code')->where('id', $id)->first();

        return view('admin.config.subject_code_setting.edit', compact('item'));
    }


    /**
     *
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        DB::table('subject_code')->where('id', $id)->update([
            'items' => $request->items,
            'subject_code'  => $request->subject_code,
        ]);

        return redirect(route('config.subjectCodeSetting.index'))->with('notice', '编辑成功');
    }

    /**
     *
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        DB::table('subject_code')->delete($id);
        return ['status' => 1, 'msg' => '删除成功'];
    }


}
