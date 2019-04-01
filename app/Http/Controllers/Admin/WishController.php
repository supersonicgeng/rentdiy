<?php

namespace App\Http\Controllers\Admin;

use App\Model\Region;
use Illuminate\Http\Request;
use Excel;

class WishController extends CommonController
{
    public function wishList(Request $request){
        if ($request->ajax()) {
            $input = $request->all();
            $list  = service('Wish')->wishList($input);
            return view('admin.wish.listAjax', ['list' => $list]);
        } else {
            return view('admin.wish.list');
        }
    }

    /**
     * 心愿添加
     * @author  hkw <hkw925@qq.com>
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function wishAdd(Request $request){
        if ($request->isMethod('post')) {
            $input  = $request->all();
            $result = service('Wish')->wishAdd($input);
            if ($result['code'] == 0) {
                return $this->success('添加成功', null, url('manage/wishList'));
            } else {
                return $this->error($result['code'], $result['msg']);
            }
        } else {
            return view('admin.wish.add');
        }
    }

    /**
     * 心愿编辑
     * @author  hkw <hkw925@qq.com>
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function wishEdit(Request $request){
        if ($request->isMethod('post')) {
            $input  = $request->all();
            $result = service('Wish')->wishEdit($request->id, $input);
            if ($result['code'] == 0) {
                return $this->success('修改成功', null, url('manage/wishList'));
            } else {
                return $this->error($result['code'], $result['msg']);
            }
        } else {
            return view('admin.wish.edit', ['info' => service('Wish')->wishInfo($request->id)]);
        }
    }

    /**
     * 心愿删除
     * @author  hkw <hkw925@qq.com>
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function wishDel(Request $request)
    {
        $result = service('Wish')->wishDel($request->id);
        if ($result['code'] == 0) {
            return $this->success('删除成功', null, url('manage/wishList'));
        } else {
            return $this->error($result['code'], $result['msg']);
        }
    }

    /**
     * 表格导入
     * @author  hkw <hkw925@qq.com>
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function wishImport(Request $request){
        $file   = $request->file('file');
        //$file->getRealPath();
        $result = service('Wish')->excelUpload($file);
        if ($result['code'] != 0) {
            return $this->error($result['code'], $result['msg'], null, null);
        } else {
            return $this->success($result['msg'], null, url('manage/wishList'));
        }
    }

    /**
     * 导出数据
     * @author  hkw <hkw925@qq.com>
     * @return \Illuminate\Http\JsonResponse
     */
    public function wishExport(){
        $data = service('Wish')->allData();
        $title      = [
            '标题', '姓名','省','市','区', '详细地址', '联系方式','发布人','过期时间','发布时间'
        ];
        $cellData[] = $title;
        foreach ($data as $v) {
            $cellData[] = [
                $v->title,
                $v->username,
                Region::name($v->province),
                Region::name($v->city),
                Region::name($v->county),
                $v->address,
                $v->phone,
                $v->group_id,
                $v->expired_at,
                $v->created_at,
            ];
        }
        Excel::create('心愿数据', function ($excel) use ($cellData) {
            $excel->sheet('sheet', function ($sheet) use ($cellData) {
                $sheet->setTitle('心愿数据');
                $sheet->cell('A1:J1', function($cells){
                    // 设置该范围内单元格
                    $cells->setFontWeight('bold');
                   // $cells->setAligment('center');
                });
                $sheet->setWidth(array(
                    'A' => 30,
                    'B' => 20,
                    'C' => 20,
                    'D' => 20,
                    'E' => 20,
                    'F' => 50,
                    'G' => 20,
                    'H' => 25,
                    'I' => 25,
                    'J' => 25,
                ))->rows($cellData);
            });
        })->export('xls');
    }
}
