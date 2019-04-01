<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/18
 * Time: 14:17
 */

namespace App\Http\Controllers\Admin;


use App\Model\Config;
use App\Model\Group;
use App\Model\Level;
use App\Model\Passport;
use App\Model\PassportStore;
use App\Model\User_score;
use App\Permission;
use App\Role;
use Illuminate\Http\Request;

class MemberController extends CommonController
{
    //用户列表
    public function userList(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $list  = service('Member')->userList($input);
            return view('admin.shop.user.userAjax', ['list' => $list]);
        } else {
            return view('admin.shop.user.user');
        }
    }

    //编辑用户
    public function userInfo(Request $request)
    {
        $id = $request->passport_id;
        if ($request->ajax()) {
            $input = $request->all();
            $res   = service('Passport')->editPassportInfo($input);
            if ($res['code'] == 0) {
                return $this->success('修改成功!');
            } else {
                return $this->error($res['code'], $res['msg'], $res['data']);
            }
        } else {
            $pStore = PassportStore::where('passport_id', '=', $id)->get();
            return view('admin.shop.user.edit', [
                'p_store' => $pStore,
                'id'      => $id
            ]);
        }
    }

    public function gameInfo(Request $request)
    {
        $id = $request->passport_id;
        $gameInfo = service('Game')->getInfo($id);
        $nickname = service('Passport')->userInfo($id);
        //dd($nickname);
        return view('admin.game.info', [
            'id'         => $id,
            'gameInfo'   => $gameInfo,
            'nickname'   => $nickname,
        ]);
    }

    public function gameList(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $list  = service('Game')->getGameList($input);
            return view('admin.game.listAjax', ['list' => $list,'pageNumber'=>$input['pageNumber'],'pageSize'=>$input['pageSize']]);
        } else {
            return view('admin.game.list');
        }
    }

    //客服二维码设置
    public function qrCode(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $res   = service('Passport')->qrCode($input);
            if ($res['code'] == 0) {
                return $this->success('修改成功!');
            } else {
                return $this->error($res['code'], $res['msg'], $res['data']);
            }
        } else {
            $qrcode = Config::where('code', Config::$CONFIG_GROUP_KFQrCode)->value('value');
            return view('admin.shop.user.qrcode', [
                'qrcode' => $qrcode
            ]);
        }
    }

    public function financeSet(Request $request){
        if(request()->isMethod('post')){
            service('System')->paySet($request->input());
            return $this->success('设置成功');
        }else{
            return view('admin.system.financeSet',['info'=>service('System')->financeSetGet()]);
        }
    }

    public function financeList(Request $request){
        if ($request->ajax()) {
            $input = $request->all();
            $list  = service('Passport')->financeList($input);
            return view('admin.passport.financeList.listAjax', ['list' => $list]);
        } else {
            return view('admin.passport.financeList.list');
        }
    }

    public function financePass(Request $request){
        return service('Passport')->financePass($request->id);
    }

    public function financeDeny(Request $request){
        return service('Passport')->financeDeny($request->id);
    }
}
