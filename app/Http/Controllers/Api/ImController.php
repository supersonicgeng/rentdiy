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
            'from'  => $send,
            'to'    => $to,
            'msg'   => $message
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
        $group = Im::where('from',$user)->groupBy('to')->get();
        if($group){// 查看该用户发送的消息
            $group = $group->toArray();
            foreach ($group as $k => $v){
                $send_msg = Im::where('from',$user)->where('to',$v['to']);
                $recive_msg = Im::where('to',$user)->where('from',$v['to']);
                $total_msg[] = $send_msg->union($recive_msg)->get()->toArray();// 已发消息 和对方返回的消息
                $to[] = $v['to'];
            }
            $other_msg = Im::whereNotIn('from',$to)->where('to',$user)->groupBy('from')->get(); // 无回复的 消息列表
            if($other_msg){
                $other_msg = $other_msg->toArray();
                foreach ($other_msg as $k => $v){
                    $total_msg[] = Im::where('from',$v['from'])->where('to',$user)->get()->toArray();
                }
            }
            $msg['msg'] = $total_msg;
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
