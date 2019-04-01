<?php
namespace App\Lib\WeChat;
/**
  * wechat class for php
*/
use Illuminate\Support\Facades\Input;
use App\Helpers\WeixinAutoReplyHelper;
use App\Helpers\UserHelper;
use ReflectionMethod;
use ReflectionClass;
use Auth;

class WechatCallback
{

    protected $token;
    protected $weixinAutoReplyHelper;
    protected $userHelper;

    public function __construct($token)
    {
        $this->token = $token;
        $this->weixinAutoReplyHelper = new WeixinAutoReplyHelper();
        $this->userHelper = new UserHelper();
    }

    public function exec()
    {
        if (isset($_GET["echostr"])) {
            $this->valid();
        } else {
            $this->processToResponse();
        }
    }

    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit();
        }
    }

    public function processToResponse()
    {
        $postStr = file_get_contents('php://input', 'r');
        if (!empty($postStr)){
            libxml_disable_entity_loader(true);
            $requestObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $requestObj->FromUserName;
            $toUsername = $requestObj->ToUserName;
            $msgType = $requestObj->MsgType;

            switch ($msgType) {
              case 'text':
                $this->processTextMessage($requestObj, trim($requestObj->Content));
                break;
              case 'event':
                $eventType = $requestObj->Event;
                if ($eventType == 'subscribe') {
                    if (isset($requestObj->EventKey)) {
                        $eventKeyCode = str_replace('qrscene_', '', $requestObj->EventKey);
                        $this->processCustomScanQrBeforeUserSubsribed($requestObj, $eventKeyCode, $requestObj->Ticket);
                    } else {
                        $this->processSubsribeEventMessage($requestObj);
                    }
                } else if ($eventType == 'unsubscribe') {
                    $this->processUnsubscribeEventMessage($requestObj);
                } else if ($eventType == 'SCAN') {
                    $this->processCustomScanQrAfterUserHasEverSubsribed($requestObj, $requestObj->EventKey, $requestObj->Ticket);
                } else if ($eventType == 'LOCATION') {
                    $this->processCustomReportLocationEvent($requestObj, $requestObj->Latitude, $requestObj->Longitude, $requestObj->Precision);
                } else if ($eventType == 'CLICK') {
                    $this->processClickEventMessage($requestObj, $requestObj->EventKey);
                } else if ($eventType == 'VIEW') {
                    $this->processViewEventMessage($requestObj, $requestObj->EventKey);
                }
                break;
              case 'image':
                # code...
                break;
              case 'voice':
                # code...
                break;
              case 'video':
                # code...
                break;
              case 'shortvideo':
                # code...
                break;
              case 'location':
                # code...
                break;
              case 'link':
                # code...
                break;
              default:
                # code...
                break;
            }
        } else {
            echo "";
            exit;
        }
    }

    private function processCustomScanQrBeforeUserSubsribed($requestObj, $keyCode, $ticket)
    {
        $this->userHelper->createNewWxUser($requestObj->FromUserName);
    }

    private function processCustomScanQrAfterUserHasEverSubsribed($requestObj, $scene_id, $ticket)
    {

    }

    private function processCustomReportLocationEvent($requestObj, $latitude, $longitude, $precision)
    {

    }

    private function processTextMessage($requestObj, $keyword)
    {
        $replySettings = $this->weixinAutoReplyHelper->getReplySettings();

        //每一个文本消息的配置项目对象：
        //[
        //    'keyword' : (用户输入的关键词)
        //    'matchType' : (1、全字匹配，2、全字大小写匹配，3、包含匹配，4、包含大小写匹配)
        //                  当存在keyword的matchType为全字匹配时，则不可以同时支持大小写匹配，但是全字和包含可以共存，优先级全字大于包含
        //    'replyType' : (1、直接回复，2、上下文回复，3，表达式回复（函数回复）)
        //    'replyText' : 当replyType=1直接回复时，直接向公众号用户输出replyText.
        //                  当replyType=2时，
        //]
        //对于keyword的处理逻辑:
        //首先根据user的openid去数据库里查找当前用户是否处于上下文状态下（WeixinUserOpenid.context=1, WeixinUserOpenid.expire_time未过期）
        //如果发现当前用户处于上下文状态下，则获得上下文key=WeixinUserOpenid.context_key
        //根据context_key去检索配置项里所有replyType=2并且replyText里存在头[context_key:xxx]的配置项：
        //              比如；根据当前用户openid发现该用户处于上下文状态下，并且context_key=1,2
        //              那么就去配置项目里检索所有replyType=2的项目，然后逐个检索它们的replyText里是否有头字符表达式：[context_key:1,2]
        //              找到该配置项目之后，去掉头表达式[context_key:1,2]，剩下的内容就是回复的字符串，并且回复之后需要再次回写数据库
        //              回写更新WeixinUserOpenid的context、expire_time、context_key三个字段，context_key=%之前的context_key%,%keyword%
        //如果根据上述逻辑没有找到任何回复设置或者该用户当前并不处于上下文状态下，则
        //              遍历所有配置项目，如果发现有符合规则的keyword，则输出该keyword的返回字符串replyText
        //              如果当replyType=3表达式回复时，需要通过反射调用系统函数来执行动作，函数表达式即为replyText
        //              最后需要将该基于该用户openid的上下文状态修改为不处于上下文状态下
        //                      （WeixinUserOpenid.context=0, WeixinUserOpenid.expire_time=null, WeixinUserOpenid.context_key=null）

        $time = time();
        $textTpl = $this->getTextResponseTemplate();
        $msgType = "text";
        $response = '你好，欢迎关注优优照护';
        $resultStr = sprintf($textTpl, $requestObj->FromUserName, $requestObj->ToUserName, $time, $msgType, $response);
        echo $resultStr;
    }

    private function processClickEventMessage($requestObj, $eventKey)
    {
        $menuActionSettings = $this->weixinAutoReplyHelper->getMenuActionSettings();
        foreach ($menuActionSettings as $setting) {
            if ($setting['key'] == $eventKey) {
                if ($setting['replyType'] == WechatConstants::$MENU_ACTION_TYPE_TEXT) {
                    $time = time();
                    $textTpl = $this->getTextResponseTemplate();
                    $msgType = "text";
                    $response = $setting['replyText'];
                    $resultStr = sprintf($textTpl, $requestObj->FromUserName, $requestObj->ToUserName, $time, $msgType, $response);
                    echo $resultStr;
                    return;
                } else if ($setting['replyType'] == WechatConstants::$MENU_ACTION_TYPE_EXEC) {
                    $expression = explode('->', $setting['replyText']);
                    $className = $expression[0];
                    $methodName = $expression[1];
                    $method = new ReflectionMethod($className, $methodName);
                    $method->setAccessible(TRUE);
                    $class = new ReflectionClass($className);
                    $instance  = $class->newInstanceArgs([]);
                }
            }
        }
    }

    private function processViewEventMessage($requestObj, $eventKey)
    {
        $wxuser = $this->userHelper->createNewWxUser($requestObj->FromUserName);
        if (isset($wxuser)) {
            if (!Auth::check()) {
              session_id($requestObj->FromUserName);
                Auth::loginUsingId($wxuser->id);
                \Storage::disk('local')->put('test_view_event.json', json_encode([
                    'user'=>Auth::user(),
                ]));
            }
        }
    }

    private function processSubsribeEventMessage($requestObj)
    {
        $this->userHelper->createNewWxUser($requestObj->FromUserName);
    }

    private function processUnsubscribeEventMessage($requestObj)
    {

    }

    private function getTextResponseTemplate()
    {
        return "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            <FuncFlag>0</FuncFlag>
            </xml>";
    }

    private function checkSignature()
    {
        $signature = Input::get("signature");
        $timestamp = Input::get("timestamp");
        $nonce = Input::get("nonce");
        $tmpArr = array($this->token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

    public function responseMsg($response)
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

          //extract post data
        if (!empty($postStr)){
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $time = time();
            $textTpl = $this->getTextResponseTemplate();
            if(!empty( $keyword ))
            {
                $msgType = "text";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $response);
                echo $resultStr;
            }else{
                echo "Input something...";
            }
        } else {
            echo "";
            exit;
        }
    }
}

?>
