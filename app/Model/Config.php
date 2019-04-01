<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    public $timestamps = false;
    protected $table = 'sys_config';
    public static $CONFIG_STATUS_ON = 1;
    public static $CONFIG_STATUS_OFF = 0;
    public static $CONFIG_GROUP_WEIXIN = 'weixin';
    public static $CONFIG_GROUP_BASE = 'base';
    public static $CONFIG_GROUP_MENU = 'weixin_menu';
    public static $CONFIG_GROUP_FINANCE = 'finance';
    public static $CONFIG_GROUP_PAY = 'wx_pay';
    public static $CONFIG_ANNOUNCEMENT = 'announcement';
    public static $CONFIG_GROUP_GAME = 'game';
    public static $CONFIG_GROUP_SHARE = 'share';

    public static $CONFIG_CHECKBOX = 'checkbox';
    public static $CONFIG_RADIO = 'radio';
    public static $CONFIG_TEXT = 'text';
    public static $CONFIG_SELECT = 'select';
    public static $CONFIG_FILE = 'file';
    public static $CONFIG_PASSWORD = 'password';

    /*code列表*/
    public static $CONFIG_CODE_WEIXIN_CONFIG = 'wx_config';
    public static $CONFIG_CODE_WEIXIN_MENU = 'wx_menu';
    public static $CONFIG_CODE_SHOP_NAME = 'shop_name';
    public static $CONFIG_CODE_SHOP_LOGO = 'shop_logo';
    public static $CONFIG_CODE_SHOP_DESC = 'shop_description';
    public static $CONFIG_CODE_SHOP_KEYWORDS = 'shop_keywords';
    public static $CONFIG_GROUP_KFQrCode = 'kf_qrcode';
    public static $CONFIG_GROUP_GAME_TIME = 'game_time';
    public static $CONFIG_GROUP_GAME_LEVEL = 'game_level';
    public static $CONFIG_GROUP_GAME_PASS_SCORE = 'pass_score';
    public static $CONFIG_GROUP_GAME_ADD_SCORE = 'add_score';
    public static $CONFIG_GROUP_GAME_INSTRUCTION = 'instruction';
    public static $CONFIG_GROUP_SHARE_TITLE = 'title';
    public static $CONFIG_GROUP_SHARE_DESC = 'desc';
    public static $CONFIG_GROUP_SHARE_IMG = 'img';

   /* public static $CONFIG_CODE_FI = 'min_finance'; //最低提现金额
    public static $CONFIG_CODE_FI_T = 'finance_times'; //提现频率 一天N次
    public static $CONFIG_CODE_FI_RATE = 'finance_rate'; //提现手续费
    public static $CONFIG_CODE_MCHID = 'pay_mchid'; //商户号
    public static $CONFIG_CODE_KEY = 'pay_key'; //商户密钥*/

    public static $CONFIG_CODE_ANNOUNCEMENT = 'announcement_text'; //网站通告


    /*配置*/
    public static $WX_SACN_KEY = 'scan_from_menu';
    public static $WX_MENU_TYPE = [
        'view'=>'打开网页',
        'scancode_push'=>'扫一扫'
    ];

    public static $SMS_TEMPLATE = [
        'register'=>'3128134'
    ];


    /**
     * @description:整理微信单个菜单
     * @author: hkw <hkw925@qq.com>
     * @param array $input
     * @return array
     */
    public static function reTreeMenu(array $input){
        $data = [
            'name'=>$input['name']
        ];
        switch($input['type']){
            case 'view':
                $data['type'] = $input['type'];
                $data['url'] = $input['url'];
                break;
            case 'scancode_push':
                $data['type'] = $input['type'];
                $data['key'] = Config::$WX_SACN_KEY;
                break;
        }
        return $data;
    }
}
