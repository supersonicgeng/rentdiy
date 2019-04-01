<?php

use Illuminate\Database\Seeder;
use App\Model\Config;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->gameConfig();
    }

    public function gameConfig(){
        $config = new Config();
        $data = [
            [
                'name'=>'微信小程序配置',
                'code'=>Config::$CONFIG_CODE_WEIXIN_CONFIG,
                'type'=>Config::$CONFIG_TEXT,
                'note'=>'微信小程序配置',
                'value'=>json_encode(['url'=>url('wechat'),'token'=>'','name'=>'','encodingaeskey'=>'','appid'=>'','secret'=>'']),
                'group'=>Config::$CONFIG_GROUP_WEIXIN
            ],
            [
                'name'=>'游戏时间配置',
                'code'=>Config::$CONFIG_GROUP_GAME_TIME,
                'type'=>Config::$CONFIG_TEXT,
                'note'=>'游戏时间配置',
                'value'=>30,
                'group'=>Config::$CONFIG_GROUP_GAME,
            ],
            [
                'name'=>'游戏最高等级配置',
                'code'=>Config::$CONFIG_GROUP_GAME_LEVEL,
                'type'=>Config::$CONFIG_TEXT,
                'note'=>'游戏最高等级配置',
                'value'=>10,
                'group'=>Config::$CONFIG_GROUP_GAME,
            ],
            [
                'name'=>'游戏初始过关积分配置',
                'code'=>Config::$CONFIG_GROUP_GAME_PASS_SCORE,
                'type'=>Config::$CONFIG_TEXT,
                'note'=>'游戏初始过关积分配置',
                'value'=>10,
                'group'=>Config::$CONFIG_GROUP_GAME,
            ],
            [
                'name'=>'游戏递增过关积分配置',
                'code'=>Config::$CONFIG_GROUP_GAME_ADD_SCORE,
                'type'=>Config::$CONFIG_TEXT,
                'note'=>'游戏递增过关积分配置',
                'value'=>1,
                'group'=>Config::$CONFIG_GROUP_GAME,
            ],
            [
                'name'=>'游戏说明',
                'code'=>Config::$CONFIG_GROUP_GAME_INSTRUCTION,
                'type'=>Config::$CONFIG_TEXT,
                'note'=>'游戏说明',
                'value'=>'遊戲時間為30秒<br/>
                            在圖片中找出金仔為過關<br/>
                            遊戲時間內，通過關數越多積分越高<br/>
                            分享至朋友圈可邀請朋友幫助完成遊戲增加積分<br/>
                            根據遊戲排名，可到现场领取由IFC Mall所赠礼品<br/>',
                'group'=>Config::$CONFIG_GROUP_GAME,
            ],
            [
                'name'=>'分享标题',
                'code'=>Config::$CONFIG_GROUP_SHARE_TITLE,
                'type'=>Config::$CONFIG_TEXT,
                'note'=>'游戏说明',
                'value'=>'寻找金仔',
                'group'=>Config::$CONFIG_GROUP_SHARE,
            ],
            [
                'name'=>'分享描述',
                'code'=>Config::$CONFIG_GROUP_SHARE_DESC,
                'type'=>Config::$CONFIG_TEXT,
                'note'=>'游戏说明',
                'value'=>'香港国际金融中心 IFC Mall 欢迎您！',
                'group'=>Config::$CONFIG_GROUP_SHARE,
            ],
            [
                'name'=>'分享图片',
                'code'=>Config::$CONFIG_GROUP_SHARE_IMG,
                'type'=>Config::$CONFIG_TEXT,
                'note'=>'游戏说明',
                'value'=>'',
                'group'=>Config::$CONFIG_GROUP_SHARE,
            ],
            [
                'name'=>'系统公告',
                'code'=>Config::$CONFIG_CODE_ANNOUNCEMENT,
                'type'=>Config::$CONFIG_TEXT,
                'note'=>'系统公告',
                'value'=>'',
                'group'=>Config::$CONFIG_ANNOUNCEMENT
            ]
        ];
        $config->insert($data);
        echo "生成配置已完成\n";
    }
}
