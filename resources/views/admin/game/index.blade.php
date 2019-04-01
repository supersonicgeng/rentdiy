<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>寻找小金仔</title>
    <!--开始游戏首页-->
    <link rel="stylesheet" href="/game/css/style.css">
    <style>
        body{
            width:100vw;
            height:100vh;
            overflow:hidden;
        }
        .wrap{
            background:url("/game/image/index/05.png") no-repeat center 20%,url("/game/image/loading/03.png") no-repeat center 48%,url("/game/image/04.png") no-repeat center bottom,url("/game/image/01.png") no-repeat 0 0;
            background-size:50% auto,42% auto,100% auto,100% 100%;
            width:100%;
            height:100%;
            position:relative;
        }
        .looking-scrump{
            position:absolute;
            top:17%;
            left:50%;
            transform:translateX(-50%);
            overflow:hidden;
            height:6.6rem;
        }
        @keyframes name1
        {
            0%   {transform:translateY(100%);}
            100% {transform:translateY(0);}
        }
        .looking-scrump img{
            width:6rem;
            transform:translateY(0%);
            animation-name:name1;
            animation-duration:3s;
        }
        .cloud{
            position:absolute;
        }
        .right img,.left img{
            width:3.2rem;
        }
        @keyframes name2{
            0%   {left:100%;}
            100% {left:14rem;}
        }
        .right{
            top:18%;
            left:14rem;
            animation-name:name2;
            animation-duration:3s;
        }
        @keyframes name3{
            0%   {left:-4rem;}
            100% {left:5.8rem;}
        }
        .left{
            top:26%;
            left:5.8rem;
            transform:scaleX(-1) translateX(0);
            animation-name:name3;
            animation-duration:3s;
        }
        .btnBox{
            position:absolute;
            bottom:40%;
            left:0;
            width:100%;
            text-align:center;
        }
        .btnBox img{
            width:30%;
            margin:0 10px;
        }
        .gameShows{
            display:none;
            position:absolute;
            overflow:auto;
            background:rgba(0,0,0,.6);
            width:90%;
            height:96%;
            margin:2% 5%;
            box-sizing:border-box;
            padding:10px;
            color:#eee;
        }
        .close{
            position:absolute;
            bottom:0;
            left:50%;
            transform:translateX(-50%);
        }
    </style>
</head>
<body>
<div class="wrap">
<!--左边云朵-->
<div class="cloud left">
    <img src="/game/image/index/09.png" alt="">
</div>
<!--右边云朵-->
<div class="cloud right">
    <img src="/game/image/index/09.png" alt="">
</div>
<!--小金仔-->
<div class="looking-scrump">
    <img src="/game/image/index/08.png" alt="">
</div>
<!--按钮-->
<div class="btnBox">
    <a href="{{url('game/gameCount',['share_passport_id' => $share_passport_id,'passport_id'=>$passport_id])}}">
        <img src="/game/image/index/06.png" alt="">
    </a>
    <a href="javascript:;" onclick="Open()">
        <img src="/game/image/index/07.png" alt="">
    </a>
</div>
<!--小汽车-->
<div class="car">
    <img src="/game/image/index/10.png" alt="">
</div>
<!--游戏说明-->
<div class="gameShows">
    <div class="text">
        {!! $config['instruction'] !!}
    </div>
    <div class="close imgType-btn flex-center" onclick="Close()">
        <span>关闭</span>
    </div>
</div>
</div>
</body>
<script src="/admin/js/jquery.min.js"></script>
<script src="/game/js/setRem.js"></script>

<script>
    setRem(18/414);
    var gameShows=document.querySelector(".gameShows"),
        eInstall=gameShows.children[0];
    function Open(){
        gameShows.style.display="block";
    }
    function Close(){
        gameShows.style.display="none";
    }

    function save(passScore,addScore,level,dataTime,install){
        sessionStorage.setItem("passScore",passScore);//保存第一关所需的积分数的初始值
        sessionStorage.setItem("addScore",addScore);//保存每一关的迭代需求积分规则值
        sessionStorage.setItem("level",level);//保存限定最高关卡值
        sessionStorage.setItem("dataTime",dataTime);//保存限制每一关的时限值
        sessionStorage.setItem("install",install);//保存游戏说明文本
    }
    save("{{$config['passScore']}}","{{$config['addScore']}}","{{$config['level']}}","{{$config['time']}}",'');
    window.onload=function(){


           console.log({{$config['passScore']}},{{$config['addScore']}},{{$config['level']}},{{$config['time']}})

//           eInstall.innerHTML=sessionStorage.getItem("install");
    }

</script>
</html>