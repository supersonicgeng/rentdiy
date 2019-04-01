<?php

return [
    /*
     * Debug 模式，bool 值：true/false
     *
     * 当值为 false 时，所有的日志都不会记录
     */
    'debug'  => true,

    /*
     * 使用 Laravel 的缓存系统
     */
    'use_laravel_cache' => true,

    /*
     * 账号基本信息，请从微信公众平台/开放平台获取
     */
    'app_id'  => env('WECHAT_APPID', 'wx6aaade7ea8d126d8'),         // AppID
    'secret'  => env('WECHAT_SECRET', '3b952aa9b5545babd74260da30ea6644'),     // AppSecret
    'token'   => env('WECHAT_TOKEN', 'TOKEN'),          // Token
    'aes_key' => env('WECHAT_AES_KEY', '53xVVVXDS1DWcmTx6ENwAzmYVtixZRLYRTgwiamPI11'),                // EncodingAESKey

    /**
     * 开放平台第三方平台配置信息
     */
    // 'open_platform' => [
    //     'app_id'  => env('WECHAT_OPEN_PLATFORM_APPID', ''),
    //     'secret'  => env('WECHAT_OPEN_PLATFORM_SECRET', ''),
    //     'token'   => env('WECHAT_OPEN_PLATFORM_TOKEN', ''),
    //     'aes_key' => env('WECHAT_OPEN_PLATFORM_AES_KEY', ''),
    // ],

    /**
     * 小程序配置信息
     */
     'mini_program' => [
         'app_id'  => env('WECHAT_MINI_PROGRAM_APPID', 'wxbae12605dfd9415d'),
         'secret'  => env('WECHAT_MINI_PROGRAM_SECRET', '9f56981ce2eb135b94dce9441981ad9a'),
         'token'   => env('WECHAT_MINI_PROGRAM_TOKEN', ''),
         'aes_key' => env('WECHAT_MINI_PROGRAM_AES_KEY', ''),
     ],

    /**
     * 路由配置
     */
    'route' => [
        'enabled' => false,         // 是否开启路由
        'attributes' => [           // 路由 group 参数
            'prefix' => null,
            'middleware' => null,
            'as' => 'easywechat::',
        ],
        'open_platform_serve_url' => 'open-platform-serve', // 开放平台服务URL
    ],

    /*
     * 日志配置
     *
     * level: 日志级别，可选为：
     *                 debug/info/notice/warning/error/critical/alert/emergency
     * file：日志文件位置(绝对路径!!!)，要求可写权限
     */
    'log' => [
        'level' => env('WECHAT_LOG_LEVEL', 'debug'),
        'file'  => env('WECHAT_LOG_FILE', storage_path('logs/wechat.log')),
    ],

    /*
     * OAuth 配置
     *
     * only_wechat_browser: 只在微信浏览器跳转
     * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
     * callback：OAuth授权完成后的回调页地址(如果使用中间件，则随便填写。。。)
     */
     'oauth' => [
         'only_wechat_browser' => true,
         'scopes'   => array_map('trim', explode(',', env('WECHAT_OAUTH_SCOPES', 'snsapi_userinfo'))),
         'callback' => env('WECHAT_OAUTH_CALLBACK', PHP_SAPI === 'cli' ? false :url('demo')),
     ],

    /*
     * 微信支付
     */
     'payment' => [
         'merchant_id'        => env('WECHAT_PAYMENT_MERCHANT_ID', '1498267512'),
         'key'                => env('WECHAT_PAYMENT_KEY', '756D42F372CDE0418D03C9FD4E862D23'),
         'cert_path'          => env('WECHAT_PAYMENT_CERT_PATH', 'path/to/your/cert.pem'), // XXX: 绝对路径！！！！
         'key_path'           => env('WECHAT_PAYMENT_KEY_PATH', 'path/to/your/key'),      // XXX: 绝对路径！！！！
         // 'device_info'     => env('WECHAT_PAYMENT_DEVICE_INFO', ''),
         // 'sub_app_id'      => env('WECHAT_PAYMENT_SUB_APP_ID', ''),
         // 'sub_merchant_id' => env('WECHAT_PAYMENT_SUB_MERCHANT_ID', ''),
         // ...
     ],

    /*
     * 开发模式下的免授权模拟授权用户资料
     *
     * 当 enable_mock 为 true 则会启用模拟微信授权，用于开发时使用，开发完成请删除或者改为 false 即可
     */
    'enable_mock' => env('WECHAT_ENABLE_MOCK', true),
    'mock_user' => [
        'openid' => 'oYuMH0Q5akfH4ef_lvb-mAUopFy0',
        // 以下字段为 scope 为 snsapi_userinfo 时需要
        'nickname' => '黄开旺',
        'sex' => '1',
        'province' => '湖北',
        'city' => '黄石',
        'country' => '中国',
        'headimgurl' => 'http://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTIgezB9jcIFpAXIDjxXMs3CFZSYnSpjlMhSD3UR0ZlQ8xNqzBaE6y4TCSib8nGFdcUOL9ibkloyic7oA/0',
    ],
];
