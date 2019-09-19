<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PhotoController extends Controller
{
    /***
     * @param Request $request
     * @return array
     * 单图上传
     */
    public function store(Request $request)
    {
        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            $allow_types = ['image/png', 'image/jpeg', 'image/gif'];
            if (!in_array($request->image->getMimeType(), $allow_types)) {
                return ['status' => 0, 'msg' => '图片类型不正确！'];
            }

            if ($request->image->getClientSize() > 1024 * 1024 * 6) {
                return ['status' => 0, 'msg' => '图片大小不能超过 6M！'];
            }

            $path = $request->image->store('public/images');

            //上传到本地，返回照片地址
//            return ['status' => 1, 'image' => '/storage' . str_replace('public', '', $path), 'msg' => '上传成功'];

            //上传到七牛

            //绝对路径
            $file_path = storage_path('app/') . $path;

            qiniu_upload($file_path);
            return ['status' => 1, 'image' => 'http://image.jhaomai.com/' . basename($file_path), 'msg' => '上传成功'];

        }
    }

    /***
     * 视频上传
     */
    public function video(Request $request)
    {


        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            $allow_types = ['video/mp4',];
            if (!in_array($request->image->getMimeType(), $allow_types)) {
                return ['status' => 0, 'msg' => '视频类型不正确！请上传mp4格式'];
            }

            if ($request->image->getClientSize() > 1024 * 1024 * 25) {
                return ['status' => 0, 'msg' => '图片大小不能超过 25M！'];
            }

            $path = $request->image->store('public/video');

            //上传到本地，返回地址
//            return ['status' => 1, 'image' => '/storage' . str_replace('public', '', $path), 'msg' => '上传成功'];

            //绝对路径
            $file_path = storage_path('app/') . $path;

            qiniu_upload($file_path);

            $html = file_get_contents('http://image.jhaomai.com/' . basename($file_path) . '?avinfo');
            $res = json_decode($html);
            $duration = $res->format->duration;

            return ['status' => 1, 'duration' => $duration, 'image' => 'http://image.jhaomai.com/' . basename($file_path), 'msg' => '上传成功'];


        }
    }


    /***
     * 编辑器多图上传
     */
    public function editorUpload(Request $request)
    {


        $imgs = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {


                $allow_types = ['image/png', 'image/jpeg', 'image/gif'];
                if (!in_array($file->getMimeType(), $allow_types)) {
                    continue;
                }

                if ($file->getClientSize() > 1024 * 1024 * 6) {
                   continue;
                }

                $path = $file->store('public/images');

                //绝对路径
                $file_path = storage_path('app/') . $path;

                qiniu_upload($file_path);

                $imgs[] = 'http://image.jhaomai.com/' . basename($file_path);


            }
            return response()->json([
                'errno' => 0,
                'data' => $imgs
            ]);
        } else {
            return response()->json([
                'info' => '没有图片'
            ]);
        }

    }
}
