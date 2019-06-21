<?php

namespace App\Http\Controllers\Api;

use App\Model\Im;
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

    public function getImInfo(Request $request)
    {
        $user = 'user_'.$request->user_id;
        $contact_id = $request->contact_id;
        $send_msg = Im::where('from',$user)->where('to',$contact_id);
        $recive_msg = Im::where('to',$user)->where('from',$contact_id);
        $total_msg = $send_msg->union($recive_msg)->orderBy('id')->get()->toArray();// 已发消息 和对方返回的消息
        $msg['msg'] = $total_msg;
        return  $this->success('get im msg success',$msg);
    }


    public function getImList(Request $request)
    {
        $user = 'user_'.$request->user_id;
        $group = Im::where('from',$user)->groupBy('to')->get();
        if($group){// 查看该用户发送的消息
            $group = $group->toArray();
            foreach ($group as $k => $v){
                $to[] = $v['to'];
            }
            $other_msg = Im::whereNotIn('from',$to)->where('to',$user)->groupBy('from')->get(); // 无回复的 消息列表
            if($other_msg){
                $to[] = $v['from'];
            }
            $msg['list'] = $to;
            return  $this->success('get im msg success',$msg);
        }
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
