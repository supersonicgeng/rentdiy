<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/20 0020
 * Time: 下午 2:03
 */

namespace App\Services;


use App\Lib\Util\QueryPager;
use App\Model\Img;
use App\Model\Region;
use App\Model\VerifyLog;
use App\User;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CommonService
{
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

    /**
     * @description:员工列表
     * @author: hkw <hkw925@qq.com>
     * @param $input
     * @return array
     */
    public function getUserList($input)
    {
        $user = User::whereHas('roles', function ($query) use ($input) {
            if ($input['role_id']) {
                $query->where('role_id', $input['role_id']);
            }
        });
        $user = $user->where('is_super', 1);
        $user = new QueryPager($user);
        return $user->doPaginateSelect2($input, 'id');
    }

    //检查验证码是否正确
    public function checkVerify(Array $input)
    {
        $realVerify = VerifyLog::where('status', 0)->where('phone', $input['phone'])->where('type', 1)->orderBy('send_time', 'desc')->value('code');
        return $realVerify == $input['verify'] ? true : false;
    }

    //生成二维码
    public function makeQrCode($url)
    {
        $save_path = storage_path(Img::$QRCODE) . DIRECTORY_SEPARATOR . date('Y-m-d');
        if (!file_exists(storage_path(Img::$QRCODE))) {
            mkdir(storage_path(Img::$QRCODE));
        }
        if (!file_exists($save_path)) {
            mkdir($save_path);
        }
        $img_name = uniqid('', true) . '.png';
        QrCode::format('png')->size(300)->generate($url, $save_path . DIRECTORY_SEPARATOR . $img_name);
        return date('Y-m-d') . DIRECTORY_SEPARATOR . $img_name;
    }

    /**
     * @description:创建时间选择器
     * @author: hkw <hkw925@qq.com>
     * @param $model
     * @param $input
     * @param string $field 字段
     * @return mixed
     */
    public function buildDateTime($model, $input, $field = 'created_at')
    {
        if (!empty($input['create_at_start']) && !empty($input['create_at_end'])) {
            $model = $model->whereBetween($field, [$input['create_at_start'], Carbon::createFromFormat('Y-m-d', $input['create_at_end'])->addDay()->toDateString()]);
        } elseif (!empty($input['create_at_start'])) {
            $model = $model->where($field, '>', $input['create_at_start']);
        } elseif (!empty($input['create_at_end'])) {
            $model = $model->where($field, '<', Carbon::createFromFormat('Y-m-d', $input['create_at_end'])->addDay()->toDateString());
        }
        return $model;
    }

    /**
     * 自动创建商家id检索
     * @param $model
     * @param string $field
     */
    protected function buildGroup($model, $field = 'group_id')
    {
        if (request()->get('group')) {
            $model = $model->where($field, request()->get('group')->group_id);
        }
        return $model;
    }

    /**
     * 获取当前登录的group_id
     * @author  hkw <hkw925@qq.com>
     * @return int
     */
    protected function groupId()
    {
        if (request()->get('group')) {
            return request()->get('group')->group_id;
        } else {
            return 0;
        }
    }

    /**
     * 检测当前登录人是否有权限操作
     * @author  hkw <hkw925@qq.com>
     * @param $model
     * @return bool
     */
    public function checkGroup($model)
    {
        if (request()->get('group') && $model->group_id != request()->get('group')->group_id) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 电商Sign签名生成
     * @param $data $appkey
     * @return DataSign签名
     */
    function encrypt($data, $appkey)
    {
        return urlencode(base64_encode(md5($data . $appkey)));
    }

    public function getLogisticsType($num)
    {
        $requestData = "{'LogisticCode':'" . $num . "'}";
        $datas       = array(
            'EBusinessID' => config('logistics.EBusinessID'),
            'RequestType' => '2002',
            'RequestData' => urlencode($requestData),
            'DataType'    => '2',
        );

        $datas['DataSign'] = $this->encrypt($requestData, config('logistics.AppKey'));

        $result            = $this->sendPost(config('logistics.ReqURL'), $datas);
        $result = json_decode($result);
        if($result->Success && !empty($result->Shippers)){
//            dd($result);
//            dd( $result->Shippers);
            return  $result->Shippers;
        }else{
            return false;
//            dd($result);
        }
    }

    /*
     * 根据物流单号获取物流信息
     *
     */
    public function orderTracesSubByJson($data, $type= null)
    {
        $logisticType = '';
        if(!$type){
            $logisticType = @$this->getLogisticsType($data['logisticCode'])[0];
            $type = @$logisticType->ShipperCode;
            if(!$type){
                return $this->error(1,'无法识别所属快递公司,请手动输入!');
            }
        }
        $requestData = "{'OrderCode': '" . $data['orderNum'] . "'," .
            "'ShipperCode':'" . $type . "'," .
            "'LogisticCode':'" . $data['logisticCode'] . "'}"
        ;
        $datas       = array(
            'EBusinessID' => config('logistics.EBusinessID'),
            'RequestType' => '1002',
            'RequestData' => urlencode($requestData),
            'DataType'    => '2',
        );
        $datas['DataSign'] = $this->encrypt($requestData, config('logistics.AppKey'));

        $result            = $this->sendPost(config('logistics.ReqURL'), $datas);
        $result = json_decode($result);
        if($result->Success){
            return $this->success('获取成功!',['traces'=>$result->Traces,'logistic'=>$logisticType]);
        }else{
            return $this->error(1,'获取失败');
        }
    }

    /**
     *  post提交数据
     * @param  string $url 请求Url
     * @param  array $datas 提交的数据
     * @return url响应返回的html
     */
    function sendPost($url, $datas)
    {
        $temps = array();
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);
        }
        $post_data = implode('&', $temps);
        $url_info  = parse_url($url);
        if (empty($url_info['port'])) {
            $url_info['port'] = 80;
        }
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader .= "Host:" . $url_info['host'] . "\r\n";
        $httpheader .= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader .= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader .= "Connection:close\r\n\r\n";
        $httpheader .= $post_data;
        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $httpheader);
        $gets       = "";
        $headerFlag = true;
        while (!feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }
        while (!feof($fd)) {
            $gets .= fread($fd, 128);
        }
        fclose($fd);

        return $gets;
    }

    function checkHisums(){
        $hisums_verify = curlGet('http://project.hisums.cn/api/pm/verify/'.config('sms.hisums_verify.pname').'/'.config('sms.hisums_verify.token'));
        if($hisums_verify){
            if($hisums_verify_result = json_decode($hisums_verify,true)){
                if($hisums_verify_result['code'] == 1){
                    return false;
                }
            }else{
                return false;
            }
        }
        return true;
    }
}