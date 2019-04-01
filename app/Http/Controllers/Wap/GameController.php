<?php

namespace App\Http\Controllers\Wap;

use App\Lib\WeChat\JsSdk;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GameController extends CommonController
{
    /**
     * @description:游戏初始化
     * @author: syg <13971394623@163.com>
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function loading(Request $request)
    {
        $openId = $request->get('user')->openid;
        $passport_id = service('Passport')->getPassportId($openId);
        $share_passport_id = $request->share_passport_id;
        if(!$share_passport_id){
            $share_passport_id = 0;
        }
        $app = app('wechat');;
        $config = $app->js->config(['onMenuShareTimeline','onMenuShareAppMessage'], true,$beta = false, false);
        $share_config = service('System')->getShareConfig();
        return view('wap.game.page',[
            'share_passport_id' => $share_passport_id,
            'passport_id' => $passport_id,
            'config' => $config,
            'share_config' => $share_config,
        ]);
    }


    public function showloading(Request $request)
    {
        $passport_id = $request->passport_id;
        $share_passport_id = $request->share_passport_id;
        if(!$share_passport_id){
            $share_passport_id = 0;
        }
        return view('wap.game.loading',[
            'share_passport_id' => $share_passport_id,
            'passport_id' => $passport_id,
        ]);
    }

    /**
     * @description:游戏初始化
     * @author: syg <13971394623@163.com>
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $share_passport_id = $request->share_passport_id;
        $passport_id = $request->passport_id;
        $gameConfig = service('System')->getGameConfig();
        return view('wap.game.index',[
            'share_passport_id' => $share_passport_id,
            'passport_id' => $passport_id,
            'game_config' => $gameConfig,
        ]);
    }


    /**
     * @description:游戏页面
     * @author: syg <13971394623@163.com>
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function game(Request $request)
    {
        $share_passport_id = $request->share_passport_id;
        $passport_id = $request->passport_id;
        return view('wap.game.game',[
            'share_passport_id' => $share_passport_id,
            'passport_id' => $passport_id,
        ]);
    }

    /**
     * @description:游戏完成后提交
     * @author: syg <13971394623@163.com>
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function commit(Request $request)
    {
        $input['share_passport_id'] = $request->share_passport_id;
        $input['passport_id'] = $request->passport_id;
        $input['score'] = $request->score;
        service('Game')->addGameInfo($input);
        $gameList = service('Game')->getTop3($input);
        return view('wap.game.gameList',[
            'Top3' => $gameList['Top3'],
            'passport_info' => $gameList['passport_info'],
            'passport_id' => $input['passport_id'],
            'share_passport_id' => $input['share_passport_id'],
        ]);
    }


    /**
     * @description:游戏积分排行
     * @author: syg <13971394623@163.com>
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function rank(Request $request)
    {
        $input = $request->all();
        $pageSize = $input['pageSize'];
        $pageNumber = $input['pageNumber'];
        $passport_id = $input['passport_id'];
        $rank = service('Game')->getRank($input);
        return view('wap.game.gameRank',[
            'rank' => $rank,
            'pageSize' => $pageSize,
            'pageNumber' => $pageNumber,
            'passport_id' => $passport_id,
        ]);
    }

    public function scoreInfo(Request $request)
    {
        if($request->isMethod('post')){
            $passport_id = $request->input('passport_id');
            $pageNumber = $request->input('pageNumber');
            $pageSize = $request->input('pageSize');
            $scoreInfo = service('Game')->getScoreInfoByPage($passport_id,$pageNumber,$pageSize);
            return view('wap.game.scoreList',[
                'sharedInfo' => $scoreInfo['shared_info'],
                'pageNumber' => $pageNumber,
            ]);
        }else{
            $passport_id = $request->passport_id;
            $user_id = $request->user_id;
            $scoreInfo = service('Game')->getScoreInfo($passport_id);
            return view('wap.game.scoreInfo',[
                'sharedInfo' => $scoreInfo['shared_info'],
                'passportInfo' => $scoreInfo['passport_info'],
                'passport_id' => $user_id,
            ]);
        }

    }

}
