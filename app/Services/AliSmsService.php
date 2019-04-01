<?php
/**
 * 手机短信发送服务层
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/15 0015
 * Time: 下午 4:07
 */

namespace App\Services;


use App\Jobs\SendPhone;
use App\Model\VerifyLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Lib\Aliyun\Core\Config;
use App\Lib\Aliyun\Core\Profile\DefaultProfile;
use App\Lib\Aliyun\Core\DefaultAcsClient;
use App\Lib\Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;

class AliSmsService extends CommonService
{
    public static $acsClient = null;
    function __construct()
    {
        Config::load();
    }

    /**
     * 取得AcsClient
     *
     * @return DefaultAcsClient
     */
    public static function getAcsClient() {
        //产品名称:云通信流量服务API产品,开发者无需替换
        $product = "Dysmsapi";

        //产品域名,开发者无需替换
        $domain = "dysmsapi.aliyuncs.com";

        // TODO 此处需要替换成开发者自己的AK (https://ak-console.aliyun.com/)
        $accessKeyId = config('sms.ali.account.access_id'); // AccessKeyId

        $accessKeySecret = config('sms.ali.account.access_secret'); // AccessKeySecret

        // 暂时不支持多Region
        $region = "cn-hangzhou";

        // 服务结点
        $endPointName = "cn-hangzhou";


        if(static::$acsClient == null) {

            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);

            // 增加服务结点
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

            // 初始化AcsClient用于发起请求
            static::$acsClient = new DefaultAcsClient($profile);
        }
        return static::$acsClient;
    }

    /**
     * @description:验证验证码
     * @author: hkw <hkw925@qq.com>
     * @param $phone
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyCode($phone,$code){
        if($info = VerifyLog::where('phone',$phone)->where('code',$code)->first()){
            if($info->status == 1){
                return $this->error(4,'验证码已使用');
            }
            if((time() - strtotime($info->send_time)) > config('sms.ali.template.code.expire')){
                return $this->error(2,'验证码已过期');
            }
            $info->status = 1;
            $info->update();
            return $this->success('验证码正确');
        }else{
            return $this->error(1,'验证码不存在');
        }
    }

    /**
     * @description:发送验证码
     * @author: hkw <hkw925@qq.com>
     * @param $phone
     */
    public function sendCode($phone){
        $code = rand_string(6,1);
        $r = VerifyLog::create([
            'verify_text'=>$code,
            'phone'=>$phone,
            'send_time'=>Carbon::now()->toDateTimeString(),
            'type'=>1,
            'code'=>$code
        ]);
        if($r){
            dispatch((new SendPhone($phone,'code',['code'=>$code]))->onQueue('high'));
        }
    }

    /**
     * @description:发送模板短信
     * @author: hkw <hkw925@qq.com>
     * @param $phone
     * @param $action_code
     * @param array $param
     * @return bool
     */
    public function sendTemplate($phone,$action_code,array $param){
        $required_param = config('sms.ali.template')[$action_code]['params'];
        foreach($required_param as $k=>$v){
            if(!array_key_exists($v,$param)){
                Log::info('sendTemplate Error:'.$v.' required');
                return false;
            }
        }
        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        // 必填，设置短信接收号码
        $request->setPhoneNumbers($phone);

        // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $request->setSignName(config('sms.ali.sign'));

        // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $request->setTemplateCode(config('sms.ali.template')[$action_code]['template_id']);

        // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
        $request->setTemplateParam(json_encode($param, JSON_UNESCAPED_UNICODE));

        // 可选，设置流水号
        //$request->setOutId("yourOutId");

        // 选填，上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
        //$request->setSmsUpExtendCode("1234567");

        // 发起访问请求
        $acsResponse = static::getAcsClient()->getAcsResponse($request);
        if($acsResponse->Code == 'OK'){
            return true;
        }else{
            return false;
        }
    }
}