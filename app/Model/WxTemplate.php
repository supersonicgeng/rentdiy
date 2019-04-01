<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WxTemplate extends Model
{
    public static $TEMPLATE = [
        'task_notice'         => 'PNTgTCiNYZxsCQprEt8SGbV92U17bMLMvuJ_jejXPfE',
        'task_create'         => 'MfjmVir7AO0OR-5hvHxXBkClijTO9kjobd1oyqyacaQ',
        'price_sure'          => 'eRW9jkxigeoJ0Ocr9L18zRCbfocN0gsz_DwdLFBdSYo', //报价确定通知
        'certificates_notice' => '1pFDmfkQTzyt_Hj4lIbFOItfjZrKz8vhr8JRLCb4EQA',//年审 季审通知
        'insurance_notice'    => '1UvSxkdyOdKdD8h3Mpwa0gcTPak7w5V19766G03sr0Y' //保险到期通知
    ];
    public static $PARAMS = [
        'task_notice'         => [
            'first',
            'keyword1',
            'keyword2',
            'keyword3',
            'remark'
        ],
        'task_create'         => [
            'first',
            'keyword1',  //运单号
            'keyword2',  //始-终点
            'keyword3',  //货物
            'keyword4',  //发货时间
            'remark'
        ],
        'price_sure'          => [
            'first',
            'keyword1',  //报价内容
            'keyword2',  //报价价格
            'keyword3',  //物流公司
            'keyword4',  //确定时间
            'remark'
        ],
        'certificates_notice' => [
            'first',
            'keyword1',//车辆或司机
            'keyword2',//到期信息说明
            'remark'
        ],
        'insurance_notice'=>[
            'first',
            'keyword1',//车牌号
            'keyword2',//司机
            'keyword3',//司机电话
            'keyword4',//保险到期时间
            'remark'
        ]
    ];
}
