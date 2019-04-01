<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="format-detection" content="telephone=no, email=no">
    <title>分享页面测试</title>
    <script src="/game/js/jweixin-1.2.0.js"></script>
</head>
<body>

</body>
<script>
    wx.config({
        debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: "{{$config['appId']}}", // 必填，公众号的唯一标识
        timestamp:"{{$config['timestamp']}}" , // 必填，生成签名的时间戳
        nonceStr: "{{$config['nonceStr']}}", // 必填，生成签名的随机串
        signature: "{{$config['signature']}}",// 必填，签名
        jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage'] // 必填，需要使用的JS接口列表
    });
</script>
</html>