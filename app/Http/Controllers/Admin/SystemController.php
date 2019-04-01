<?php

namespace App\Http\Controllers\Admin;

use App\Model\Config;
use App\Model\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SystemController extends CommonController
{
    /**
     * @description:物流列表
     * @author: hkw <hkw925@qq.com>
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function logistics(Request $request){
        if($request->ajax()){
            $input = $request->all();
            $list = service('System')->logistics($input);
            return view('admin.system.logisticsAjax',['list'=>$list]);
        }else{
            return view('admin.system.logistics');
        }
    }

    /**
     * @description:商城设置
     * @author: hkw <hkw925@qq.com>
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function shopSet(Request $request){
        if($request->isMethod('post')){
            $this->validate($request,[
                Config::$CONFIG_CODE_SHOP_NAME=>'required|max:20',
                Config::$CONFIG_CODE_SHOP_DESC=>'required|max:150',
                Config::$CONFIG_CODE_SHOP_KEYWORDS=>'required|max:100',
                Config::$CONFIG_CODE_SHOP_LOGO=>'required',
            ],[
                Config::$CONFIG_CODE_SHOP_NAME.'.required'=>'请填写商城名称',
                Config::$CONFIG_CODE_SHOP_NAME.'.max'=>'名称太长',
                Config::$CONFIG_CODE_SHOP_DESC.'.required'=>'请填写商城描述',
                Config::$CONFIG_CODE_SHOP_DESC.'.max'=>'描述太长',
                Config::$CONFIG_CODE_SHOP_KEYWORDS.'.required'=>'请填写商城关键字',
                Config::$CONFIG_CODE_SHOP_KEYWORDS.'.max'=>'关键字太长',
                Config::$CONFIG_CODE_SHOP_LOGO.'.required'=>'请上传logo',
            ]);
            service('System')->updateShopConfig($request->all());
            return $this->success('更新成功');
        }else{
            $r = service('System')->getConfig(null,Config::$CONFIG_GROUP_BASE);
            return view('admin.system.shop_config',['config'=>$r]);
        }
    }

    /**
     * @description:微信设置
     * @author: hkw <hkw925@qq.com>
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function wxConfig(Request $request){
        if($request->isMethod('post')){
            $this->validate($request,[
                'url'=>'required|max:200',
                'token'=>'required|max:100',
                'name'=>'required|max:50',
                'encodingaeskey'=>'required|max:100',
                'appid'=>'required|max:20',
                'secret'=>'required|max:100',
                'qrcode'=>'required',
            ],[
                'url.required' => '请填写地址',
                'url.max' => '地址不符合要求',
                'token.required' => '请填写token',
                'token.max' => 'token不符合要求',
                'name.required' => '请填写公众号名称',
                'name.max' => '公众号名称不符合要求',
                'encodingaeskey.required' => '请填写encodingaeskey',
                'encodingaeskey.max' => 'encodingaeskey不符合要求',
                'appid.required' => '请填写appid',
                'appid.max' => 'appid不符合要求',
                'secret.required' => '请填写secret',
                'secret.max' => 'secret不符合要求',
                'qrcode.required' => '请上传二维码',
            ]);
            service('System')->updateWxConfig($request->all());
            return $this->success('更新成功');
        }else{
            $r = service('System')->getConfig(null,Config::$CONFIG_GROUP_WEIXIN);
            return view('admin.system.wx_config',['config'=>json_decode($r[Config::$CONFIG_CODE_WEIXIN_CONFIG],true)]);
        }
    }

    /**
     * @description:微信菜单
     * @author: hkw <hkw925@qq.com>
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function wxMenu(){
        $r = service('System')->getConfig(Config::$CONFIG_CODE_WEIXIN_MENU);
        //dd(json_decode($r,true));
        return view('admin.system.wx_menu',['menus'=>json_decode($r,true)]);
    }

    public function updateWxMenu(Request $request){
        if($request->isMethod('post')){
            $input = $request->input();
            $rule = [
                'name'=>'required|max:10',
            ];
            $msg = [
                'name.required' => '请填写菜单名',
                'name.max' => '菜单名称过长',
            ];
            if($input['type'] == 'view'){
                $rule['url'] = 'required|url';
                $msg['url.required'] = '地址必填';
                $msg['url.url'] = '地址不合法';
            }
            Validator::make($input,$rule,$msg)->after(function ($validator) use($input) {
                if($input['parent'] == -1){
                    $r = json_decode(service('System')->getConfig(Config::$CONFIG_CODE_WEIXIN_MENU),true);
                    if($r && count($r['button'])>=3){
                        $validator->errors()->add('name.count', '一级菜单不能添加3个以上!');
                    }
                }
            })->validate();;
            service('System')->updateWxMenu($request->all());
            return $this->success('更新成功');
        }else{
            $r = service('System')->getConfig(Config::$CONFIG_CODE_WEIXIN_MENU);
            if($request->get('key')){
                $info = $r[$request->get('key')];
            }else{
                $info = [];
            }
            return view('admin.system.update_wx_menu',['info'=>$info,'top_menu'=>json_decode($r,true)]);
        }
    }

    /**
     * @description:删除菜单
     * @author: hkw <hkw925@qq.com>
     * @param $key1
     * @param $key2
     * @return \Illuminate\Http\JsonResponse
     */
    public function delWxMenu($key1,$key2){
        service('System')->delWxMenu($key1,$key2);
        return $this->success('删除成功');
    }

    public function editWxMenu($key1,$key2){
        if(request()->isMethod('post')){
            $input = request()->input();
            $rule = [
                'name'=>'required|max:10',
            ];
            $msg = [
                'name.required' => '请填写菜单名',
                'name.max' => '菜单名称过长',
            ];
            if($input['type'] == 'view'){
                $rule['url'] = 'required|url';
                $msg['url.required'] = '地址必填';
                $msg['url.url'] = '地址不合法';
            }
            Validator::make($input,$rule,$msg)->validate();
            service('System')->editWxMenu($input,$key1,$key2);
            return $this->success('更新成功');
        }else{
            $r = json_decode(service('System')->getConfig(Config::$CONFIG_CODE_WEIXIN_MENU),true);
            if($key2 == -1){
                $info = $r['button'][$key1];
            }else{
                $info = $r['button'][$key1]['sub_button'][$key2];
            }
            return view('admin.system.edit_wx_menu',['info'=>$info,'key1'=>$key1,'key2'=>$key2,'top_menu'=>$r]);
        }
    }

    /**
     * @description:更新菜单到微信
     * @author: hkw <hkw925@qq.com>
     */
    public function refreshWxMenu(){
        service('System')->pushWxMenu();
        return $this->success('已更新到微信');
    }


    /**
     * @description:签到设置
     * @author: hkw <hkw925@qq.com>
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function signSet(Request $request){
        if(request()->isMethod('post')){
            service('System')->setSignConfig($request->input());
            return $this->success('设置成功');
        }else{
            return view('admin.system.signSet',['list'=>service('System')->getSignConfig()]);
        }
    }

    public function paySet(Request $request){
        if(request()->isMethod('post')){
            service('System')->paySet($request->input());
            return $this->success('设置成功');
        }else{
            return view('admin.system.paySet',['info'=>service('System')->getPaySet()]);
        }
    }

    public function announcement(Request $request){
        if(request()->isMethod('post')){
            service('System')->setAnnouncement($request->input());
            return $this->success('设置成功');
        }else{
            return view('admin.system.announcement',['info'=>service('System')->getAnnouncement()]);
        }
    }



    public function gameConfig(Request $request){
        if(request()->isMethod('post')){
            //dd($request->input());
            service('System')->setGameConfig($request->input());
            return $this->success('设置成功');
        }else{
            //dump(service('System')->getGameConfig());exit;
            return view('admin.system.gameConfig',['info'=>service('System')->getGameConfig()]);
        }
    }


    public function shareConfig(Request $request){
        if(request()->isMethod('post')){
            //dd($request->input());
            service('System')->setShareConfig($request->input());
            return $this->success('设置成功');
        }else{
            //dump(service('System')->getGameConfig());exit;
            return view('admin.system.shareConfig',['info'=>service('System')->getShareConfig()]);
        }
    }
}
