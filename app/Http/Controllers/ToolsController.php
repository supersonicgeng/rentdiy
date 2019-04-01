<?php

namespace App\Http\Controllers;

use App\Jobs\PushMessage;
use App\Jobs\SendPhone;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ToolsController extends Controller
{
    protected $water_png = 'admin/img/logo_water.png';
    public function imgGenerate(Request $request){
        //dispatch((new SendPhone(17671231208))->onQueue('sendPhone'));
        for($i=0;$i<100000;$i++){
            dispatch((new PushMessage($i))->onQueue('default'));
        }
        if($request->isMethod('post')){
            $img = Image::make(imgShow($request->get('background')));
            $water = Image::make(imgShow($request->get('logo')))->resize(200, null, function($constraint){       // 调整图像的宽到300，并约束宽高比(高自动)
                $constraint->aspectRatio();
            });
            $img->insert($water,'bottom-right', 40, 40);
            return $img->response('png');
        }else{
            return view('tools.imgGenerate');
        }
    }

    /**
     * @description:水印
     * @author: hkw <hkw925@qq.com>
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function water(Request $request){
        if($request->isMethod('post')){
            $img = Image::make(imgShow($request->get('background')));
            $water = Image::make($this->water_png)->resize($img->width()/10, null, function($constraint){       // 调整图像的宽到300，并约束宽高比(高自动)
                $constraint->aspectRatio();
            });
            $img->insert($water,'bottom-right', floor($img->width()/50), floor($img->width()/50));
            return $img->response(pathinfo(imgShow($request->get('background')))['extension']);
        }else{
            return view('tools.water');
        }
    }
}
