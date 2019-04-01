<?php
/**
 * 用户服务层
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1 0001
 * Time: 下午 4:25
 */

namespace App\Services;


use App\Lib\Util\QueryPager;
use App\Model\AliPay\AliPayClient;
use App\Model\AliPay\AliPayTransfer;
use App\Model\Config;
use App\Model\Driver;
use App\Model\DriverTakeOver;
use App\Model\Level;
use App\Model\Order;
use App\Model\Passport;
use App\Model\PassportReward;
use App\Model\PassportStore;
use App\Model\Plant;
use App\Model\PlantOperateLog;
use App\Model\RouteItems;
use App\Model\ScoreLog;
use App\Model\SignLog;
use App\Model\SysSign;
use App\Model\UserEvaluate;
use App\Model\UserEvaluateTag;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PassportService extends CommonService
{
    /**
     * @description:微信关注
     * @author: hkw <hkw925@qq.com>
     * @param $openid
     * @return $this|\Illuminate\Database\Eloquent\Model|null|static
     */
    public function subscribe($openid)
    {
        $passport = Passport::where(['openid' => $openid])->first();
        $wechat   = app('wechat');
        $wx_info  = $wechat->user->get($openid);
        if ($passport) {
            $passport->nickname       = $wx_info['nickname'];
            $passport->headimgurl     = $wx_info['headimgurl'];
            $passport->sex            = $wx_info['sex'];
            $passport->country        = $wx_info['country'];
            $passport->province       = $wx_info['province'];
            $passport->city           = $wx_info['city'];
            $passport->subscribe      = 1;
            $passport->subscribe_time = date('Y-m-d H:i:s', $wx_info['subscribe_time']);
            $passport->save();
            return $passport;
        } else {
            $new = Passport::create([
                'openid'         => $wx_info['openid'],
                'unionid'        => @$wx_info['unionid'] ?: '',
                'groupid'        => @$wx_info['groupid'] ?: 0,
                'nickname'       => $wx_info['nickname'],
                'headimgurl'     => $wx_info['headimgurl'],
                'sex'            => $wx_info['sex'],
                'country'        => $wx_info['country'],
                'province'       => $wx_info['province'],
                'city'           => $wx_info['city'],
                'subscribe_time' => date('Y-m-d H:i:s', $wx_info['subscribe_time']),
                'subscribe'      => 1
            ]);
            return $new;
        }
    }

    /**
     * @description:微信取消关注
     * @author: hkw <hkw925@qq.com>
     * @param $openid
     */
    public function unsubscribe($openid)
    {
        $passport = Passport::where(['openid' => $openid])->first();
        if ($passport) {
            $passport->unsubscribe_time = date('Y-m-d H:i:s');
            $passport->subscribe        = 0;
            $passport->save();
        }
    }

    /**
     * @description:获取用户信息
     * @author: hkw <hkw925@qq.com>
     * @param $wx_info
     * @return $this|\Illuminate\Database\Eloquent\Model|null|static
     */
    public function info($wx_info)
    {
        $passport = Passport::where(['openid' => $wx_info['original']['openid']])->first();
        if (!$passport) {
            //不存在添加
            $passport = Passport::create([
                'openid'     => $wx_info['original']['openid'],
                'unionid'    => @$wx_info['original']['unionid'] ?: '',
                'groupid'    => @$wx_info['original']['groupid'] ?: 0,
                'nickname'   => $wx_info['original']['nickname'],
                'headimgurl' => $wx_info['original']['headimgurl'],
                'sex'        => $wx_info['original']['sex'],
                'country'    => $wx_info['original']['country'],
                'province'   => $wx_info['original']['province'],
                'city'       => $wx_info['original']['city'],
            ]);
        } else {
            $passport->nickname   = $wx_info['original']['nickname'];
            $passport->headimgurl = $wx_info['original']['headimgurl'];
            $passport->sex        = $wx_info['original']['sex'];
            $passport->country    = $wx_info['original']['country'];
            $passport->province   = $wx_info['original']['province'];
            $passport->city       = $wx_info['original']['city'];
            $passport->save();
        }
        return $passport;
    }

    public function userInfo($passport_id){
        return Passport::find($passport_id);
    }


    /**
     * @description:微信用户导入
     * @author: hkw <hkw925@qq.com>
     */
    public function wxUserListImport()
    {
        $wechat = app('wechat');
        $users  = $wechat->user->lists();
        foreach ($users['data']['openid'] as $openid) {
            service('Passport')->subscribe($openid);
        }
        echo "导入" . $users['count'] . "个用户\n";
    }

    /**
     * @description:检测是否已签到
     * @author: hkw <hkw925@qq.com>
     * @param $passport_id
     * @return bool
     */
    public function checkSign($passport_id)
    {
        if (SignLog::where('created_at', '>', Carbon::today())->where('passport_id', $passport_id)->first()) {
            return true;//已签到
        } else {
            return false; //未签到
        }
    }

    /**
     * @description:执行签到
     * @author: hkw <hkw925@qq.com>
     * @param $passport_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function doSign($passport_id)
    {
        if (!service('Passport')->checkSign($passport_id)) {
            $last     = SignLog::where('passport_id', $passport_id)->orderBy('created_at', 'desc')->first();
            $passport = Passport::where('passport_id', $passport_id)->first();
            if ($last) {
                if ($last->created_at > Carbon::yesterday() && $last->created_at < Carbon::today()) {
                    $score = SysSign::where('times', $last->times + 1)->value('value');
                    if (!$score) {
                        $score = SysSign::orderBy('times', 'desc')->value('value'); //连续签到后面的次数一直取最大值
                    }
                    $times = $last->times + 1;
                } else {
                    $score = SysSign::where('times', 1)->value('value');
                    $times = 1;
                }
            } else {
                //没有任何记录
                $score = SysSign::where('times', 1)->value('value');
                $times = 1;
            }
            DB::transaction(function () use ($score, $times, $passport) {
                SignLog::create([
                    'passport_id' => $passport->passport_id,
                    'ip'          => request()->getClientIp(),
                    'times'       => $times
                ]);
                //增加积分
                $passport->incrementScore($score);
                ScoreLog::create([
                    'passport_id' => $passport->passport_id,
                    'from'        => 1,
                    'num'         => $score,
                    'plus'        => 1,
                    'note'        => '签到赠送积分'
                ]);
            });
            return $this->success('签到成功');
        } else {
            return $this->error(1, '今日已签到');
        }
    }

    //修改用户信息
    public function editPassportInfo(Array $input)
    {
        $filed = $input['filed'];
        foreach ($filed as $v) {
            if (!is_numeric($v)) {
                return $this->error(1, '输入有误!');
            }
        }
        foreach ($filed as $k => $v) {
            PassportStore::where('id', $k)->update([
                'qty' => $v
            ]);
        }
        return $this->success('修改成功!');
    }

    public function userLogin($user_info)
    {
        $result = service('WechatApi')->getOpendId($user_info['code']);
        if ($result['code'] == 0) {
            $token = md5($result['data']['openid'] . time() . mt_rand(100, 999));
            $passport = Passport::where('openid',$result['data']['openid'])->first();
            if($passport){
                $passport->token = $token;
                $passport->nickname = $user_info['nickName'];
                $passport->headimgurl = $user_info['avatarUrl'];
                $passport->sex = $user_info['gender'];
                $passport->country = @$user_info['country'];
                $passport->province = @$user_info['province'];
                $passport->city = @$user_info['city'];
                if($passport->reffer_id == 0 && $user_info['reffer_id'] > 0 && $user_info['reffer_id'] != $passport->passport_id && $passport->passport_id != Passport::$ORIGIN){
                    $passport->reffer_id = $user_info['reffer_id'];
                }
                $passport->update();
            }else{
                $passport = Passport::create([
                    'openid'     => $result['data']['openid'],
                    'unionid'    => @$result['data']['unionid'],
                    'nickname'   => $user_info['nickName'],
                    'headimgurl' => $user_info['avatarUrl'],
                    'sex'        => $user_info['gender'],
                    'country'    => @$user_info['country'],
                    'province'   => @$user_info['province'],
                    'city'       => @$user_info['city'],
                    'token'      => $token,
                    'reffer_id' => $user_info['reffer_id']
                ]);
            }
            Log::info('小程序传来的场景值为：'.$user_info['reffer_id']);
            if ($passport) {
                return $this->success('用户信息', $passport);
            } else {
                return $this->error(2, '获取用户信息失败');
            }
        } else {
            return $this->error(1, $result['msg']);
        }
    }

    /**
     * 更新推荐人
     * @author  hkw <hkw925@qq.com>
     * @param $passport_id
     * @param $reffer_id
     */
    public function updateReffer($passport_id,$reffer_id){
        $passport = Passport::find($passport_id);
        if($passport->reffer_id == 0 && $reffer_id > 0 && $reffer_id != $passport_id  && $passport_id != Passport::$ORIGIN){
            Log::info('now update reffer_id：'.$reffer_id);
            $passport->reffer_id = $reffer_id;
            $passport->update();
        }
    }

    /**
     * 验证登录
     * @author  hkw <hkw925@qq.com>
     * @param $token
     * @param $passport_id
     * @return bool|mixed|static
     */
    public function checkLogin($token, $passport_id)
    {
        $passport = Passport::find($passport_id);
        if ($passport->token == $token) {
            return $passport;
        } else {
            return false;
        }
    }

    //客服二维码设置
    public function qrCode(Array $input)
    {
        $rule      = [
            'qrcode' => 'required',
        ];
        $msg       = [
            'qrcode.required' => '请上传二维码',
        ];
        $validator = Validator::make($input, $rule, $msg);
        if ($validator->fails()) {
            return $this->error(1, $validator->errors()->first());
        }
        Config::where('code', Config::$CONFIG_GROUP_KFQrCode)->update([
            'value' => $input['qrcode']
        ]);
        return $this->success('修改成功!');
    }

    public function financeAdd(array $input){
        $rule = [
            'passport_id' => 'required',
            'type' => 'required',
            'account' => 'required',
            'username' => 'required',
            'money'      => [
                'required',
                'regex:/(^[1-9]([0-9]+)?(\.[0-9]{1,2})?$)|(^(0){1}$)|(^[0-9]\.[0-9]([0-9])?$)/'
            ]
        ];
        $msg = [
            'passport_id.required' => 'passport_id必须',
            'type.required'      => '类型必须',
            'account.required'      => '请填写提现账户',
            'username.required'      => '请填写账户姓名',
            'money.required'      => '请填写提现金额',
            'money.regex'      => '金额格式不正确'
        ];
        $validator = Validator::make($input, $rule, $msg);
        if ($validator->fails()) {
            return $this->error(1, $validator->errors()->first());
        }
        $reward_config = service('System')->financeSetGet();
        if($input['money'] < $reward_config['min_finance']){
            return $this->error(3,'最少提现'.$reward_config['min_finance'].'元');
        }
        $reward_count = PassportReward::where('passport_id',$input['passport_id'])->where('created_at','>',Carbon::today())->count();
        if($reward_count >= $reward_config['finance_times']){
            return $this->error(4,'今日提现次数已满');
        }
        $passport = Passport::find($input['passport_id']);
        if($passport->reward < $input['money']){
            return $this->error(2,'账户余额不足');
        }
        $rate_money = number_format(($reward_config['finance_rate']/100)*$input['money'],2,'.',''); //平台抽成手续费
        $real_money = number_format($input['money']-$rate_money,2,'.','');
        DB::beginTransaction();
        $r = PassportReward::create([
            'passport_id'=>$input['passport_id'],
            'apply_number'=>PassportReward::createApplyNumber(),
            'type'=>$input['type'],
            'account'=>$input['account'],
            'username'=>$input['username'],
            'apply_money'=>$input['money'],
            'money'=>$real_money,
            'rate'=>$rate_money,
            'status'=>PassportReward::$UNDO
        ]);
        if($r){
            if($passport->decrementReward($input['money'])){
                DB::commit();
                return $this->success('提现申请成功');
            }else{
                DB::rollBack();
                return $this->error(6,'提现申请失败');
            }
        }else{
            DB::rollBack();
            return $this->error(5,'提现申请失败');
        }
    }

    public function financeList(array $input){
        $model = new PassportReward();
        $model = $this->buildDateTime($model, $input);
        if (@$input['username']) {
            $model = $model->where('username', 'like', '%' . $input['username'] . '%');
        }
        $model = $model->orderBy('created_at', 'desc');
        $pager = new QueryPager($model);
        return $pager->getPage($input, 'id');
    }

    public function rewardList(array $input){
        $query = new PassportReward();
        if(@$input['passport_id']){
            $query = $query->where('passport_id',$input['passport_id']);
        }
        $query = $query->orderBy('created_at', 'desc');
        $page = new QueryPager($query);
        return $page->doPaginate($input, 'id');
    }

    public function storeList(array $input){
        $query = new PassportStore();
        if(@$input['passport_id']){
            $query = $query->where('passport_id',$input['passport_id']);
        }
        $query = $query->orderBy('created_at', 'desc');
        $page = new QueryPager($query);
        $page->setRefectionMethodField('goodsInfo');
        $page->setRefectionMethodField('pic');
        return $page->doPaginate($input, 'id');
    }

    public function orderList(array $input){
        $query = new Order();
        if(@$input['passport_id']){
            $query = $query->where('passport_id',$input['passport_id']);
        }
        if(@$input['status']){
            if($input['status'] == 1){
                $query = $query->where('status',Order::$TYPE_ZERO);
            }
        }
        $query = $query->orderBy('created_time', 'desc');
        $page = new QueryPager($query);
        $page->setRefectionMethodField('goodsInfo');
        $page->setRefectionMethodField('pic');
        return $page->doPaginate($input, 'id');
    }

    public function orderCancel($passport_id,$order_id){
        $order = Order::find($order_id);
        if($order->passport_id != $passport_id){
            return $this->error(1,'无权限操作该订单');
        }
        if($order->status == Order::$TYPE_ZERO){
            $order->status = Order::$TYPE_THREE;
            if($order->update()){
                return $this->success('取消成功');
            }else{
                return $this->error(3,'取消失败');
            }
        }else{
            return $this->error(2,'订单状态不符');
        }
    }

    public function orderInfo($passport_id,$order_id){
        $info = [];
        $order = Order::find($order_id);
        if($order->passport_id != $passport_id){
            return $this->error(1,'无权限查看该订单');
        }

        $goods_info = $order->goodsInfo();
        $gift = $order->goodsInfo()->gift;
        $order_pic = $order->pic();
        $info['info'] = $order->toArray();
        $info['info']['pic'] = $order_pic;
        $info['goods'] = $goods_info->toArray();
        $info['gift'] = $gift->toArray();
        return $this->success('订单详情',$info);
    }

    public function financePass($id,$reason = ''){
        DB::beginTransaction();
        $reward = PassportReward::find($id);
        if($reward->status == PassportReward::$UNDO){
            $reward->status = PassportReward::$PASS;
            $reward->apply_time = Carbon::now();
            if($reward->update()){
                $alipay = new AliPayClient();
                $alipay_request = new AliPayTransfer();
                $alipay_request->setBizContent(json_encode([
                    'out_biz_no'=>$reward->apply_number,
                    'payee_type'=>'ALIPAY_LOGONID',
                    'payee_account'=>$reward->account,
                    'amount'=>$reward->money,
                    'payee_real_name'=>$reward->username,
                    'remark'=>'提现'
                ],JSON_UNESCAPED_UNICODE));
                $result = $alipay->execute($alipay_request);
                $responseNode = str_replace(".", "_", $alipay_request->getApiMethodName()) . "_response";
                $resultCode = $result->$responseNode->code;
                if(!empty($resultCode) && $resultCode == 10000){
                    DB::commit();
                    return $this->success('审核并打款成功');
                } else {
                    DB::rollBack();
                    return $this->error(3,$result->$responseNode->msg);
                }
            }else{
                DB::rollBack();
                return $this->error(2,'审核失败');
            }
        }else{
            DB::rollBack();
            return $this->error(1,'审核状态不对');
        }
    }

    public function financeDeny($id,$reason = ''){
        DB::beginTransaction();
        $reward = PassportReward::find($id);
        if($reward->status == PassportReward::$UNDO){
            $reward->status = PassportReward::$DENY;
            $reward->apply_time = Carbon::now();
            if($reward->update()){
                $passport = Passport::find($reward->passport_id);
                if($passport->incrementRewardW($reward->apply_money)){
                    DB::commit();
                    return $this->success('驳回成功');
                }else{
                    DB::rollBack();
                    return $this->error(3,'审核失败');
                }
            }else{
                DB::rollBack();
                return $this->error(2,'审核失败');
            }
        }else{
            DB::rollBack();
            return $this->error(1,'审核状态不对');
        }
    }

    public function getPassportId($openId)
    {
        $passport = new Passport();
        $passportId = $passport->where('openid',$openId)->pluck('passport_id')->first();
        return $passportId;
    }
}