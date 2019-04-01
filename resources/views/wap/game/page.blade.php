<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0，maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <title>ifc Junior</title>
    <link rel="stylesheet" href="/game/css/style.css">
    <link rel="stylesheet" href="/game/css/iconfont.css">
    <style>
        body{
            width:100vw;
            height:100vh;
            overflow:hidden;
        }
    </style>
</head>
<body>
<!--背景音乐-->
<div>
    <audio id="music" autoplay loop>
        <source src="/game/audio/2.mp3" type="audio/mpeg">
        您的浏览器不支持 audio 元素。
    </audio>
</div>
<i class="iconfont"  onclick="Switch(this)" style="position:fixed; top:.8rem; right:1rem; color:#706ad5; font-size:1.5em; font-weight:bold;">&#xe666;</i>
<!--背景音乐-->
<iframe src="{{url('wap/game/showloading',['share_passport_id'=>$share_passport_id,'passport_id'=>$passport_id])}}" frameborder="0" width="100%" height="100%"></iframe>
</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="/admin/js/jquery.min.js"></script>
<script src="/game/js/jquery.extend.js"></script>
<script>
    //一般情况下，这样就可以自动播放了，但是一些奇葩iPhone机不可以
    window.onload=function(){
        document.getElementById('music').play();
    }
    document.addEventListener("WeixinJSBridgeReady", function () {
        document.getElementById('music').play();
    }, false);
    wx.config({
        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: "{{$config['appId']}}", // 必填，公众号的唯一标识
        timestamp:"{{$config['timestamp']}}" , // 必填，生成签名的时间戳
        nonceStr: "{{$config['nonceStr']}}", // 必填，生成签名的随机串
        signature: "{{$config['signature']}}",// 必填，签名
        jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage'] // 必填，需要使用的JS接口列表
    });
    wx.ready(function(){
        wx.onMenuShareTimeline({
            title: "{{$share_config['title']}}", // 分享标题
            link: "{{url('/wap/game/loading',['share_passport_id'=>$passport_id])}}",// 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: "{{$share_config['img']}}", // 分享图标
            success: function () {
                // 用户确认分享后执行的回调函数
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });

        wx.onMenuShareAppMessage({
            title: "{{$share_config['title']}}", // 分享标题
            desc: "{{$share_config['desc']}}", // 分享描述
            link: "{{url('/wap/game/loading',['share_passport_id'=>$passport_id])}}",// 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: "{{$share_config['img']}}", // 分享图标
            type: '', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function () {
            }
        });
    });
    function Switch(_this){
        var mp3=document.getElementById('music'),
            paused=mp3.paused;
        if(paused){
            mp3.play();
            _this.innerHTML="&#xe666;";
        }else{
            mp3.pause();
            _this.innerHTML="&#xe667;";
        }
    }
</script>
</html>