<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0，maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <title>寻找金仔</title>
    <!--游戏即将开始页面-->
    <link rel="stylesheet" href="/game/css/style.css">
    <style>
        body{
            width:100vw;
            height:100vh;
            overflow:hidden;
        }
        .wrap{
            background:url("/game/image/04.png") no-repeat center bottom,url("/game/image/01.png") no-repeat 0 0;
            background-size:100% auto,100% 100%;
            width:100%;
            height:100%;
            position:relative;
        }
        .countDownBox,.btnBox{
            position:absolute;
            width:100%;
            left:0;
            text-align:center;
        }
        .countDownBox{
            top:8%;
            color:#fff;
        }
        .countDown{
            font-size:10em;
        }
        .font1{
            font-size:1.4em;
        }
        @keyframes loading-font{
            0%   {
                width:.3rem;
                height:.3rem;
            }
            50%  {
                width:.5rem;
                height:.5rem;
            }
            100% {
                width:.3rem;
                height:.3rem;
            }
        }
        .load-font{
            align-self:flex-end;
            background:#fff;
            width:.3rem;
            height:.3rem;
            margin:.3rem;
            border-radius:50%;
            animation-name:loading-font;
            animation-duration:1s;
            animation-delay:0s;
            animation-iteration-count:infinite;
        }
        .btnBox{
            bottom:40%;
        }
        .btnBox img{
            width:30%;
            margin:0 10px;
        }


    </style>
</head>
<body>
<div class="wrap">
    <div class="countDownBox">
        <p class="countDown">3</p>
        <p class="font1 flex-justify-center">
            <span>游戏即将开始</span>
            <span class="load-font"></span>
            <span class="load-font"></span>
            <span class="load-font"></span>
        </p>
    </div>
    <div class="btnBox">
        <a href="{{url('/wap/game/commit',['score'=>'0','passport_id'=>$passport_id,'share_passport_id'=>$share_passport_id])}}">
            <img src="/game/image/startGame/08.png" alt="">
        </a>
    </div>
    <!--小汽车-->
    <div class="car">
        <img src="/game/image/index/10.png" alt="">
    </div>
</div>

</body>
<script src="/game/js/setRem.js"></script>
<script>
    setRem(18/414);
    var countDown=document.querySelector(".countDown"),
        time=parseInt(countDown.innerHTML);
    function dataTime(time,callback){
        setTimeout(function(){
            time--;
            countDown.innerHTML=time;
            if(!time){
                callback();
                return;
            };
            dataTime(time,callback);
        },1000)

    }
    dataTime(time,function(){
        {{--window.location.href="{{url('/wap/game/gamepage',['share_passport_id' => $share_passport_id,'passport_id'=>$passport_id])}}";--}}
        window.location.replace("{{url('/wap/game/gamepage',['share_passport_id' => $share_passport_id,'passport_id'=>$passport_id])}}");
    });
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
</script>
</html>