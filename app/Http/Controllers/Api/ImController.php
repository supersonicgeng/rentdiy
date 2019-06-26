<?php

namespace App\Http\Controllers\Api;

use App\Model\Im;
use App\Model\Landlord;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use link1st\Easemob\App\Easemob;

class ImController extends Controller
{
    public function sendMsg(Request $request)
    {
        $send = $request->send;
        $to = $request->to;
        $message = $request->msg;
        $im_data = [
            'from'          => $send,
            'to'            => $to,
            'msg'           => $message,
            'created_at'    => date('Y-m-d H:i:s',time()),
        ];
        $im = new Im();
        $im::insert($im_data);
        $easemob = new Easemob();
        $res = $easemob->sendMessageText([$to],'users',$message,$send);
        if($res['data'][$to] == 'success'){
            return $this->success('send im success');
        }else{
            return $this->error('2','send im failed');
        }
    }

    public function sendSystemMsg(Request $request)
    {
        $toType = $request->toType;
        if($toType == 1){
            // 给房东发送消息
            $send_user_id = User::whereIn('user_role',[1,3,5,7])->pluck('id');
            foreach ($send_user_id as $k => $v){
                $to[] = 'user_'.$v;
                $message = $request->msg;
                $im_data = [
                    'from'          => 'admin',
                    'to'            => 'user_'.$v,
                    'msg'           => $message,
                    'created_at'    => date('Y-m-d H:i:s',time()),
                ];
                $im = new Im();
                $im::insert($im_data);
            }
        }elseif ($toType == 2){
            // 给服务商发送消息
            $send_user_id = User::whereIn('user_role',[2,3,6,7])->pluck('id');
            foreach ($send_user_id as $k => $v){
                $to[] = 'user_'.$v;
                $message = $request->msg;
                $im_data = [
                    'from'          => 'admin',
                    'to'            => 'user_'.$v,
                    'msg'           => $message,
                    'created_at'    => date('Y-m-d H:i:s',time()),
                ];
                $im = new Im();
                $im::insert($im_data);
            }
        }elseif  ($toType == 3){
            // 给租户发送消息
            $send_user_id = User::whereIn('user_role',[4,5,6,7])->pluck('id');
            foreach ($send_user_id as $k => $v){
                $to[] = 'user_'.$v;
                $message = $request->msg;
                $im_data = [
                    'from'          => 'admin',
                    'to'            => 'user_'.$v,
                    'msg'           => $message,
                    'created_at'    => date('Y-m-d H:i:s',time()),
                ];
                $im = new Im();
                $im::insert($im_data);
            }
        }else{
            // 给所有用户发送消息
            $send_user_id = User::pluck('id');
            foreach ($send_user_id as $k => $v){
                $to[] = 'user_'.$v;
                $message = $request->msg;
                $im_data = [
                    'from'          => 'admin',
                    'to'            => 'user_'.$v,
                    'msg'           => $message,
                    'created_at'    => date('Y-m-d H:i:s',time()),
                ];
                $im = new Im();
                $im::insert($im_data);
            }
        }
        $easemob = new Easemob();
        $res = $easemob->sendMessageText([$to],'users',$message,'admin');
        dd($res);
        if($res['data'][$to] == 'success'){
            return $this->success('send im success');
        }else{
            return $this->error('2','send im failed');
        }
    }

    public function getImInfo(Request $request)
    {
        $user = 'user_'.$request->user_id;
        $im_id = $request->im_id;
        $send_msg = Im::where('from',$user)->where('to',$im_id);
        $recive_msg = Im::where('to',$user)->where('from',$im_id);
        $total_msg = $send_msg->union($recive_msg)->orderBy('id')->get()->toArray();// 已发消息 和对方返回的消息
        $msg['msg'] = $total_msg;
        // 将获得的收到消息改成已读
        Im::where('to',$user)->where('from',$im_id)->update(['is_read'=>1]);
        return  $this->success('get im msg success',$msg);
    }


    public function getImList(Request $request)
    {
        $user = 'user_'.$request->user_id;
        $group = Im::where('from',$user)->groupBy('to')->get();
        if($group){// 查看该用户发送的消息
            $group = $group->toArray();
            foreach ($group as $k => $v){
                $to[]['im_id'] = $v['to'];
            }
            $other_msg = Im::whereNotIn('from',$to)->where('to',$user)->groupBy('from')->get(); // 无回复的 消息列表
            if($other_msg){
                $other_msg = $other_msg->toArray();
                foreach ($other_msg as $k => $v){
                    $to[]['im_id'] = $v['from'];
                }
            }
            foreach ($to as $k=> $v){
                if(Im::where('to',$user)->where('from',$v['im_id'])->where('is_read',0)->first()){
                    $to[$k]['is_read'] = 0;
                }else{
                    $to[$k]['is_read'] = 1;
                }
                $user_id = explode('_',$v['im_id']);
                $user_id = $user_id[1];
                $to[$k]['headimg'] = User::where('id',$user_id)->pluck('head_img')->first();
                $to[$k]['nickname'] = User::where('id',$user_id)->pluck('nickname')->first();
            }
            $msg['list'] = $to;
            return  $this->success('get im msg success',$msg);
        }
    }

    public function getImUserInfo(Request $request)
    {
        $from = $request->from;
        $from_user_id =  explode('_',$from);
        $from_user_id = $from_user_id[1];
        $from_info['headimg'] = User::where('id',$from_user_id)->pluck('head_img')->first();
        $from_info['nickname'] = User::where('id',$from_user_id)->pluck('nickname')->first();
        $data['from_info'] = $from_info;
        return  $this->success('get im msg success',$data);
    }

    public function addFriend(Request $request)
    {
        $easemob = new Easemob();
        $owner_username = 'user_'.$request->user_id;
        $friend_username = $request->friend_username;
        $res = $easemob->addFriend($owner_username,$friend_username);
        dd($res);
    }

    public function getFriendList(Request $request)
    {
        $easemob = new Easemob();
        $owner_username = 'user_'.$request->user_id;
        $res = $easemob->showFriends($owner_username);
        dd($res);
    }

    /**
     * @description:操作成功处理
     * @author: hkw <hkw925@qq.com>
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success($message, $data = [])
    {
        $result         = ['code' => 0, 'msg' => $message];
        $result['data'] = $data;
        return $result;
    }

    /**
     * @description:操作失败处理
     * @author: hkw <hkw925@qq.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function error($code, $message, $data = [])
    {
        $result         = ['code' => $code, 'msg' => $message];
        $result['data'] = $data;
        return $result;
    }
}
