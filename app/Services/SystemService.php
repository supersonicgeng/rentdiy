<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/20 0020
 * Time: 下午 2:15
 */

namespace App\Services;


use App\Lib\Util\QueryPager;
use App\Logistics;
use App\Model\Config;
use App\Model\Driver;
use App\Model\Evaluate;
use App\Model\Passport;
use App\Model\Plant;
use App\Model\PlantOperateLog;
use App\Model\Routes;
use App\Model\SysSign;
use GuzzleHttp\Psr7\Request;

class SystemService extends CommonService
{
    /**
     * @description:获取物流
     * @author: hkw <hkw925@qq.com>
     * @param array $input
     * @return mixed
     */
    public function logistics(array $input)
    {
        $logistics = new Logistics();
        if ($input['name']) {
            $logistics = $logistics->where('name', 'like', '%' . $input['name'] . '%');
        }
        $logistics = new QueryPager($logistics);
        return $logistics->getPage($input, 'id');
    }

    /**
     * @description:获取配置
     * @author: hkw <hkw925@qq.com>
     * @param null $code
     * @param null $group
     * @return \Illuminate\Support\Collection|mixed
     */
    public function getConfig($code = null, $group = null)
    {
        $config = new Config();
        if ($code) {
            return $config->where('status', Config::$CONFIG_STATUS_ON)->where('code', $code)->value('value');
        }
        if ($group) {
            return $config->where('status', Config::$CONFIG_STATUS_ON)->where('group', $group)->pluck('value', 'code');
        }
        return $config->where('status', Config::$CONFIG_STATUS_ON)->pluck('value', 'code');
    }

    /**
     * @description:更新基本配置
     * @author: hkw <hkw925@qq.com>
     * @param array $input
     */
    public function updateShopConfig(array $input)
    {
        service('System')->setConfig(Config::$CONFIG_CODE_SHOP_NAME, $input[Config::$CONFIG_CODE_SHOP_NAME]);
        service('System')->setConfig(Config::$CONFIG_CODE_SHOP_DESC, $input[Config::$CONFIG_CODE_SHOP_DESC]);
        service('System')->setConfig(Config::$CONFIG_CODE_SHOP_KEYWORDS, $input[Config::$CONFIG_CODE_SHOP_KEYWORDS]);
        service('System')->setConfig(Config::$CONFIG_CODE_SHOP_LOGO, $input[Config::$CONFIG_CODE_SHOP_LOGO]);
    }

    /**
     * @description:更新微信配置
     * @author: hkw <hkw925@qq.com>
     * @param array $input
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateWxConfig(array $input)
    {
        $config = new Config();
        if ($config->where('code', Config::$CONFIG_CODE_WEIXIN_CONFIG)->update(['value' => json_encode([
            'url'            => $input['url'],
            'token'          => $input['token'],
            'name'           => $input['name'],
            'encodingaeskey' => $input['encodingaeskey'],
            'appid'          => $input['appid'],
            'secret'         => $input['secret'],
            'qrcode'         => $input['qrcode']
        ])])
        ) {
            return $this->success('操作成功');
        } else {
            return $this->error(1, '操作失败');
        }
    }

    /**
     * @description:更新微信菜单配置
     * @author: hkw <hkw925@qq.com>
     * @param array $input
     */
    public function updateWxMenu(array $input)
    {
        $r = service('System')->getConfig(Config::$CONFIG_CODE_WEIXIN_MENU);
        if ($r) {
            $menu = json_decode($r, true);
            if ($input['parent'] != -1) {
                //是二级菜单
                $menu['button'][$input['parent']]['sub_button'][] = Config::reTreeMenu($input);
            } else {
                //是顶级菜单
                $menu['button'][] = Config::reTreeMenu($input);
            }
        } else {
            $menu             = [];
            $menu['button'][] = Config::reTreeMenu($input);
        }
        service('System')->setConfig(Config::$CONFIG_CODE_WEIXIN_MENU, json_encode($menu));
    }

    public function editWxMenu(array $input, $key1, $key2)
    {
        $menu = json_decode(service('System')->getConfig(Config::$CONFIG_CODE_WEIXIN_MENU), true);
        if ($key2 == -1) {
            $sub_botton            = @$menu['button'][$key1]['sub_button'];
            $menu['button'][$key1] = Config::reTreeMenu($input);
            if ($sub_botton) {
                $menu['button'][$key1]['sub_button'] = $sub_botton;
            }
        } else {
            $menu['button'][$key1]['sub_button'][$key2] = Config::reTreeMenu($input);
        }
        service('System')->setConfig(Config::$CONFIG_CODE_WEIXIN_MENU, json_encode($menu));
    }

    /**
     * @description:删除菜单
     * @author: hkw <hkw925@qq.com>
     * @param $key1
     * @param $key2
     */
    public function delWxMenu($key1, $key2)
    {
        $menu = json_decode(service('System')->getConfig(Config::$CONFIG_CODE_WEIXIN_MENU), true);
        if ($key2 == -1) {
            unset($menu['button'][$key1]);
        } else {
            unset($menu['button'][$key1]['sub_button'][$key2]);
        }
        service('System')->setConfig(Config::$CONFIG_CODE_WEIXIN_MENU, json_encode($menu));
    }

    /**
     * @description:更新菜单到微信
     * @author: hkw <hkw925@qq.com>
     */
    public function pushWxMenu()
    {
        $wechat = app('wechat');
        $r      = service('System')->getConfig(Config::$CONFIG_CODE_WEIXIN_MENU);
        if ($r) {
            $wechat->menu->destroy(); //删除全部自定义菜单
            $menu   = json_decode($r, true);
            $botton = $menu['button'];
            $wechat->menu->add($botton);
        }
    }

    /**
     * @description:更新单个配置
     * @author: hkw <hkw925@qq.com>
     * @param $code
     * @param $value
     * @return \Illuminate\Http\JsonResponse
     */
    public function setConfig($code, $value)
    {
        $config = new Config();
        if ($config->where('code', $code)->update(['value' => $value])) {
            return $this->success('操作成功');
        } else {
            return $this->error(1, '操作失败');
        }
    }

    /**
     * @description:获取签到配置
     * @author: hkw <hkw925@qq.com>
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getSignConfig()
    {
        return SysSign::all();
    }

    /**
     * @description:设置签到配置
     * @author: hkw <hkw925@qq.com>
     * @param array $input
     */
    public function setSignConfig(array $input)
    {
        $data = [];
        foreach ($input['times'] as $k => $v) {
            $data[] = [
                'times' => $k + 1,
                'value' => $v
            ];
        }
        $sys_sign = new SysSign();
        $sys_sign->truncate();
        $sys_sign->insert($data);
    }

    public function getPaySet(){
        return $this->getConfig(null,Config::$CONFIG_GROUP_PAY);
    }

    public function paySet(array $input){
        foreach($input as $k=>$v){
            $this->setConfig($k,$v);
        }
    }

    public function financeSetGet(){
        return $this->getConfig(null,Config::$CONFIG_GROUP_FINANCE);
    }

    public function getAnnouncement(){
        return $this->getConfig(Config::$CONFIG_CODE_ANNOUNCEMENT);
    }

    public function setAnnouncement(array $input){
        $this->setConfig(Config::$CONFIG_CODE_ANNOUNCEMENT,$input[Config::$CONFIG_CODE_ANNOUNCEMENT]);
    }


    public function getGameConfig(){
        $time       = $this->getConfig(Config::$CONFIG_GROUP_GAME_TIME);
        $level      = $this->getConfig(Config::$CONFIG_GROUP_GAME_LEVEL);
        $passScore  = $this->getConfig(Config::$CONFIG_GROUP_GAME_PASS_SCORE);
        $addScore   = $this->getConfig(Config::$CONFIG_GROUP_GAME_ADD_SCORE);
        $instruction  = $this->getConfig(Config::$CONFIG_GROUP_GAME_INSTRUCTION);
        $instruction = htmlspecialchars_decode($instruction);
        $res = [
            'time'        => $time,
            'level'       => $level,
            'passScore'   => $passScore,
            'addScore'    => $addScore,
            'instruction' => $instruction
        ];
        return $res;
    }

    public function setGameConfig(array $input){
        $this->setConfig(Config::$CONFIG_GROUP_GAME_TIME,$input[Config::$CONFIG_GROUP_GAME_TIME]);
        $this->setConfig(Config::$CONFIG_GROUP_GAME_LEVEL,$input[Config::$CONFIG_GROUP_GAME_LEVEL]);
        $this->setConfig(Config::$CONFIG_GROUP_GAME_PASS_SCORE,$input[Config::$CONFIG_GROUP_GAME_PASS_SCORE]);
        $this->setConfig(Config::$CONFIG_GROUP_GAME_ADD_SCORE,$input[Config::$CONFIG_GROUP_GAME_ADD_SCORE]);
        $this->setConfig(Config::$CONFIG_GROUP_GAME_INSTRUCTION,stripslashes($input[Config::$CONFIG_GROUP_GAME_INSTRUCTION]));
    }


    public function getShareConfig(){
        $title       = $this->getConfig(Config::$CONFIG_GROUP_SHARE_TITLE);
        $desc        = $this->getConfig(Config::$CONFIG_GROUP_SHARE_DESC);
        $img         = $this->getConfig(Config::$CONFIG_GROUP_SHARE_IMG);
        $res = [
            'title' => $title,
            'desc'  => $desc,
            'img'   => $img,
        ];
        return $res;
    }

    public function setShareConfig(array $input){
        $this->setConfig(Config::$CONFIG_GROUP_SHARE_TITLE,$input[Config::$CONFIG_GROUP_SHARE_TITLE]);
        $this->setConfig(Config::$CONFIG_GROUP_SHARE_DESC,$input[Config::$CONFIG_GROUP_SHARE_DESC]);
        $this->setConfig(Config::$CONFIG_GROUP_SHARE_IMG,$input[Config::$CONFIG_GROUP_SHARE_IMG]);
    }


}