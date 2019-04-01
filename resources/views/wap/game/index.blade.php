<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0，maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <title>寻找金仔</title>
    <!--开始游戏首页-->
    <link rel="stylesheet" href="/game/css/style.css">
    <style>
        body{
            width:100vw;
            height:100vh;
            overflow:hidden;
        }
        .wrap{
            background:url("/game/image/loading/031.png") no-repeat center 18rem,url("/game/image/04.png") no-repeat center bottom,url("/game/image/01.png") no-repeat 0 0;
            background-size:42% auto,100% auto,100% 100%;
            width:100%;
            height:100%;
            position:relative;
        }
        .img-wrap{
            position:absolute;
            top:90px;
            left:0;
            width:100%;
        }
        .img-box{
            position:relative;
            border:.1rem #eee dashed;
            border-radius:50%;
            width:10rem;
            height:10rem;
        }
        .canvas-box{
            position:relative;
            border-radius:50%;
            width:90%;
            height:90%;
        }
        #myCanvas{
            display:block;
            width:100%;
            height:100%;
            border-radius:50%;
        }
        @keyframes lookingScrump{
            0%   {transform:translateY(100%);}
            100% {transform:translateY(0);}
        }
        .looking-scrump{
            position:absolute;
            width:100%;
            height:100%;
            left:0;
            top:-.6rem;
            text-align:center;
            overflow:hidden;
        }
        .looking-scrump img{
            width:66%;
            animation-name:lookingScrump;
            animation-duration:3s;
        }
        .cloud{
            position:absolute;
            width:25%;
        }
        .cloud img{
            width:100%;
        }
        @keyframes left{
            0%   {left:-100%;}
            100% {left:-12%;}
        }
        .left{
            left:-12%;
            bottom:40%;
            animation-name:left;
            animation-duration:3s;
        }
        @keyframes right{
            0%   {right:-100%;}
            100% {right:2%;}
        }
        .right{
            right:2%;
            top:12%;
            animation-name:right;
            animation-duration:3s;
        }
        .img-font{
            position:absolute;
            left:50%;
            bottom:-.6rem;
            width:120%;
            transform:translateX(-50%);
        }
        .img-font img{
            width:100%;
        }
        .btnBox{
            position:absolute;
            top:22rem;
            left:0;
            width:100%;
            text-align:center;
        }
        .btnBox a{
            display:inline-block;
            width: 30%;
            margin: 0 35%;
        }
        .btnBox img{
            width:100%;
        }
        .gameShows{
            display:none;
            position:absolute;
            overflow:auto;
            /*background:rgba(0,0,0,.6);*/
            background:#fff;
            width:92%;
            height:96%;
            margin:2% 5%;
            box-sizing:border-box;
            padding:18px;
            /*color:#eee;*/
        }
        .close-box{
            padding:15px;
        }
        .close{
            color:#eee;
            /*position:absolute;*/
            /*bottom:0;*/
            /*left:50%;*/
            /*transform:translateX(-50%);*/

        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="img-wrap flex-center">
        <div class="img-box flex-center">
            <div class="canvas-box">
                <canvas id="myCanvas" width="300" height="300"></canvas>
                <!--小金仔-->
                <div class="looking-scrump">
                    <img src="/game/image/index/08.png" alt="">
                </div>
            </div>
            <!--&lt;!&ndash;左边云朵&ndash;&gt;-->
            <div class="cloud left">
                <img src="/game/image/index/09.png" alt="">
            </div>
            <!--右边云朵-->
            <div class="cloud right">
                <img src="/game/image/index/09.png" alt="">
            </div>
            <div class="img-font">
                <img src="/game/image/index/11.png" alt="">
            </div>
        </div>
    </div>
<!--按钮-->
<div class="btnBox">
    <a href="{{url('wap/game/gamepage',['share_passport_id' => $share_passport_id,'passport_id'=>$passport_id])}}">
        <img src="/game/image/index/06.png" alt="">
    </a>
    <a href="{{url('/wap/game/commit',['score'=>'0','passport_id'=>$passport_id,'share_passport_id'=>$share_passport_id])}}">
        <img src="/game/image/startGame/08.png" alt="">
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
        {!! $game_config['instruction'] !!}
    </div>
    <div class="flex-center close-box">
        <div class="close imgType-btn flex-center" onclick="Close()">
            <span>关闭</span>
        </div>
    </div>
</div>
</div>
</body>
<script src="/admin/js/jquery.min.js"></script>
<script src="/game/js/setRem.js"></script>
<script>
    setRem(18/414);
    (function(){
        var can=document.getElementById("myCanvas"),
            cxt=can.getContext("2d"),
            min=0,
            max=30,
            aColor=["#fff","#f7ee87","#fff","#87d6f4","#fff","#f492a9"];

        function each(sAngle,num){
            min++;
            if(min>max) return;
            var sAngle2=Math.PI+Math.PI/max*min;
            cxt.beginPath();
            cxt.moveTo(150,250);
            cxt.arc(150,250,300,sAngle,sAngle2,false);
            cxt.closePath();
            cxt.fillStyle=aColor[min%6];
            cxt.fill();
            each(sAngle2,min);
        }
        setInterval(function(){
            min=0;
            aColor.reverse();
            each(Math.PI,min);
        },150);
    }());

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
    save("{{$game_config['passScore']}}","{{$game_config['addScore']}}","{{$game_config['level']}}","{{$game_config['time']}}",'');
</script>
</html>