<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0，maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <title>寻找金仔</title>
    <!--加载游戏-->
</head>
<link rel="stylesheet" href="/game/css/style.css">
<style>
    body{
        width:100vw;
        height:100vh;
        overflow:hidden;
    }
    .wrap{
        background:url("/game/image/loading/031.png") no-repeat center 30%,url("/game/image/01.png") no-repeat 0 0;
        background-size:70% auto,100% 100%;
        width:100%;
        height:100%;
        position:relative;
    }
    .loading-box{
        width:26rem;
        height:6rem;
        position:absolute;
        top:74%;
        left:50%;
        transform:translateX(-50%);
    }
    .imgType-1{
        width:100%;
        height:100%;
    }
    .loading-font{
        position:absolute;
        left:0;
        top:0;
        color:#eee;
        width:100%;
        font-size:2rem;
        transform:translateY(-70%);

    }
    @keyframes load-font{
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
        display:inline-block;
        align-self:flex-end;
        background:#fff;
        width:.3rem;
        height:.3rem;
        border-radius:50%;
        animation-name:load-font;
        animation-duration:1s;
        animation-delay:0s;
        animation-iteration-count:infinite;
    }
    .loading-img{
        position:absolute;
        bottom:0;
        left:50%;
        height:4.8rem;
        width:22.1rem;
        transform:translateX(-50%);
    }
    .imgType-2{
        height:78%;
        width:.8rem;
    }
</style>
<body>
<div class="wrap">
<div class="loading-box">
    <img class="imgType-1" src="/game/image/loading/04.png" alt="">
    <div class="loading-font text-center">
        <span>loading</span>
        <span class="load-font"></span>
        <span class="load-font"></span>
        <span class="load-font"></span>
    </div>
    <div class="loading-img flex-justify-between flex-items-center">

    </div>
</div>
</div>
</body>
<script src="/game/js/setRem.js"></script>
<script src="/admin/js/jquery.min.js"></script>
<script>
    setRem(12/414);

    function load(time,backcall){
        var loadTag=document.querySelector(".loading-img"),
            html="";
        //动态添加加载竖条
        for(var i=0,l=20;i<l;i++){
            html+='<img class="imgType-2" src="/game/image/loading/06.png" alt="">';
        }
        loadTag.innerHTML=html;

        //获取加载竖条节点数组集合
        var aImg=loadTag.children,
            leng=aImg.length,
            time=time/leng,
            j=0,
            t;

        t = setInterval(function(){
            j++;
            if(j>leng){
                clearInterval(t);
                if(backcall) backcall();
            }else{
                aImg[j-1].setAttribute("src","/game/image/loading/05.png")
            }
        },time)


    }
    load(2000,function(){
        window.location.replace("{{url('/wap/game',['share_passport_id'=>$share_passport_id,'passport_id'=>$passport_id])}}");
    });
</script>
</html>