<?php
/**
 * Created by PhpStorm.
 * User: 黄开旺
 * Date: 2018/1/15 0015
 * Time: 下午 2:29
 * 短信验证码配置文件
 */

return [
    'sms_driver'=>'ali',
    'ali'=>[
        'account'=>[
            'access_id'=>'LTAIjpAdsXSmtTHs',  //调用阿里云的key
            'access_secret'=>'ArnbWWw5v8z7S6Jj5Z7fSw9Tgji2XX' //调用阿里云的secret
        ],
        'sign'=>'慧创',
        'template'=>[
            'code'=>[
                'template_id'=>'SMS_121856655',  //模板id
                'params'=>['code'],              //模板参数
                'expire'=>300  //过期限制 0为不限制
            ]
        ]
    ],
    'hisums_verify'=>[
        'pname'=>'doll',
        'token'=>'9A7F41FCD4D5BF269AE7E6BAD2ED24BC'
    ],
];