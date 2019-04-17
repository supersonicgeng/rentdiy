<?php

namespace App\Http\Controllers\Api;

use App\Lib\Util\ResponseUtil;
use App\Model\Img;
use App\Model\Oss;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use JohnLui\AliyunOSS;

class UploaderController extends CommonController
{

    public function imageUploader(Request $request){
        if (!$request->hasFile('file')) {
            return $this->error(1,'上传文件不存在');
        }
        $file = $request->file('file');
        if (!$file->isValid()) {
            return $this->error(2,'文件上传过程中出错,请重新上传!');
        }
        if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), Img::$IMG_EXT)) {
            return $this->error(3,'图片格式不正确');
        }
        $ext                     = $file->getClientOriginalExtension();
        $dir = $request->get('dir')?$request->get('dir').DIRECTORY_SEPARATOR:'';
        $save_path               = storage_path(Img::$SAVE_PATH).$dir . date('Y-m-d');
        $storage_file_name       = md5_file($file->getRealPath());
        $storage_file_name_thumb = 'thumb_'.$storage_file_name;
        //config(['upload.driver' => 'oss']);
        if(config('upload.driver') == 'local'){
            if ($file->move($save_path, $storage_file_name . '.' . $ext)) {
                Image::make($save_path . DIRECTORY_SEPARATOR . $storage_file_name . '.' . $ext)->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($save_path . DIRECTORY_SEPARATOR . $storage_file_name_thumb . '.' . $ext);
                $data = [
                    'img'       => $dir . date('Y-m-d') . DIRECTORY_SEPARATOR . $storage_file_name . '.' . $ext,
                    'img_thumb' => $dir . date('Y-m-d') . DIRECTORY_SEPARATOR . $storage_file_name_thumb . '.' . $ext
                ];
                return $this->success('图片上传成功',$data);
            } else {
                return $this->error(4,'图片上传失败');
            }
        }else if(config('upload.driver') == 'oss'){
            $oss = AliyunOSS::boot(config('upload.oss.city'),config('upload.oss.networkType'),config('upload.oss.isInternal'),config('upload.oss.accessId'),config('upload.oss.accessKey'));
            $oss = $oss->setBucket(config('upload.oss.bucket'));
            $oss->uploadFile($dir.$storage_file_name . '.' . $ext, $file->getRealPath(),['ContentType' => $file->getMimeType()]);
            //$url = $oss->getPublicUrl($dir.$storage_file_name . '.' . $ext, $file->getRealPath(),['ContentType' => $file->getMimeType()]);
            $dirs =  substr($dir,0,strlen($dir)-1);
            $url = config('upload.oss.imgUrl').'/'.$dirs.'%5C'.$storage_file_name.'.'.$ext;
            $data = [
                'img'       => $url,
            ];
            return $this->success('图片上传成功',$data);
        }


    }

    public function imageUploaderBase64(Request $request){
        $input     = $request->all();
        $data      = explode(',', $input['file']);
        $dir = $request->get('dir')?$request->get('dir').DIRECTORY_SEPARATOR:'';
        $save_path               = storage_path(Img::$SAVE_PATH).$dir . date('Y-m-d');
        if (!file_exists(storage_path(Img::$SAVE_PATH).$dir)) {
            mkdir(storage_path(Img::$SAVE_PATH).$dir);
        }
        if (!file_exists($save_path)) {
            mkdir($save_path);
        }
        $ext = explode(';',explode('/',$data[0])[1])[0];
        $storage_file_name = uniqid('',true).'.'.$ext;
        $storage_file_name_thumb = 'thumb_'.$storage_file_name;
        if (!file_put_contents($save_path.DIRECTORY_SEPARATOR.$storage_file_name, base64_decode($data[1]))) {
            return $this->error(4,'图片上传失败');
        }else{
            Image::make($save_path.DIRECTORY_SEPARATOR.$storage_file_name)->resize(330, 192, function ($constraint) {
                $constraint->aspectRatio();
            })->save($save_path . DIRECTORY_SEPARATOR . $storage_file_name_thumb);
            $datar = [
                'img'       => imgShow($dir . date('Y-m-d') . DIRECTORY_SEPARATOR . $storage_file_name,true),
                'img_val' => $dir . date('Y-m-d') . DIRECTORY_SEPARATOR . $storage_file_name
            ];
            return $this->success('图片上传成功',$datar);
        }

    }



    public function fileUploader(Request $request){
        if (!$request->hasFile('file')) {
            return $this->error(1,'上传文件不存在');
        }
        $file = $request->file('file');
        if (!$file->isValid()) {
            return $this->error(2,'文件上传过程中出错,请重新上传!');
        }
        if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), Img::$FILE_EXT)) {
            return $this->error(3,'文件格式不正确');
        }
        $ext                     = $file->getClientOriginalExtension();
        $dir = $request->get('dir')?$request->get('dir').DIRECTORY_SEPARATOR:'';
        $save_path               = storage_path(Img::$SAVE_PATH).$dir . date('Y-m-d');
        $storage_file_name       = md5_file($file->getRealPath());
        $storage_file_name_thumb = 'thumb_'.$storage_file_name;

        if(config('upload.driver') == 'local'){
            /*if ($file->move($save_path, $storage_file_name . '.' . $ext)) {
                Image::make($save_path . DIRECTORY_SEPARATOR . $storage_file_name . '.' . $ext)->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($save_path . DIRECTORY_SEPARATOR . $storage_file_name_thumb . '.' . $ext);
                $data = [
                    'img'       => $dir . date('Y-m-d') . DIRECTORY_SEPARATOR . $storage_file_name . '.' . $ext,
                    'img_thumb' => $dir . date('Y-m-d') . DIRECTORY_SEPARATOR . $storage_file_name_thumb . '.' . $ext
                ];
                return $this->success('文件上传成功',$data);
            } else {
                return $this->error(4,'文件上传失败');
            }*/
        }else if(config('upload.driver') == 'oss'){
            $oss = AliyunOSS::boot(config('upload.oss.city'),config('upload.oss.networkType'),config('upload.oss.isInternal'),config('upload.oss.accessId'),config('upload.oss.accessKey'));
            $oss = $oss->setBucket(config('upload.oss.bucket'));
            $oss->uploadFile($dir.$storage_file_name . '.' . $ext, $file->getRealPath(),['ContentType' => $file->getMimeType()]);
            //$url = $oss->getPublicUrl($dir.$storage_file_name . '.' . $ext, $file->getRealPath(),['ContentType' => $file->getMimeType()]);
            $dirs =  substr($dir,0,strlen($dir)-1);
            $url = config('upload.oss.imgUrl').'/'.$dirs.'%5C'.$storage_file_name.'.'.$ext;
            $data = [
                'url'       => $url,
            ];
            return $this->success('文件上传成功',$data);
        }


    }

}
