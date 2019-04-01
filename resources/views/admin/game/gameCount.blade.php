<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>寻找小金仔</title>
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
            font-size:11em;
        }
        .font1{
            font-size:1.4em;
            font-family:"繁体";
        }
        @keyframes name1
        {
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
            animation-name:name1;
            animation-duration:1s;
            animation-delay:0s;
            animation-iteration-count:infinite;
            /*width:1rem;*/
            /*font-size:2em;*/
            /*align-self:flex-end;*/
        }
        .btnBox{
            bottom:40%;
        }
        .btnBox img{
            width:30%;
            margin:0 10px;
        }
        .car{
            animation-duration:0s;
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
        <a href="{{url('game/commit',['score'=>'0','passport_id'=>$passport_id,'share_passport_id'=>$share_passport_id])}}">
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
        window.location.href="{{url('game/gamepage',['share_passport_id' => $share_passport_id,'passport_id'=>$passport_id])}}";
    });
</script>
</html>