<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>寻找金仔</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0，maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--游戏成绩排行-->
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
            box-sizing:border-box;
            padding-top:3.6rem;
        }
        .top-box{
            width:86%;
            background:url("/game/image/scoresRanking/40.png") no-repeat 0 0;
            background-size:100% 100%;
            margin:0 auto;
            box-sizing:border-box;
            padding:0 1rem .9rem;
        }
        .btn-box{
            padding-top:2rem;
            text-align:center;
        }
        .btn-box img{
            width:7rem;
            margin:0 1rem;
        }
        .imgType-1{
            width:7.2rem;
            display:block;
            margin:0 auto;
            transform:translateY(-46%);
        }
        .ranking-list-top-1{
            width:4.2rem;
        }
        .ranking-list-top-2,.ranking-list-top-3{
            width:3.66rem;
        }
        .ranking-list-img-box{
            position:relative;
        }
        .ranking-list-img-box .show-type1,.ranking-list-img-box .show-type2,.ranking-list-img-box .show-type3{
            position:absolute;
            left:50%;
            transform:translateX(-50%);
            background:#fff;
            border-radius:50%;
        }
        .ranking-list-img-box .show-type2{
            width:3.3rem;
            height:3.3rem;
            bottom:.41rem;
        }
        .ranking-list-img-box .show-type3{
            width:3.3rem;
            height:3.3rem;
            bottom:.47rem;
        }
        .ranking-list-img-box .show-type1{
            width:3.8rem;
            height:3.8rem;
            bottom:.4rem;
        }
        .show-type1 img,.show-type2 img,.show-type3 img{
            width:100%;
            height:100%;
            border-radius:50%;
        }
        .ranking-list-bottom{
            background:url("/game/image/scoresRanking/41.png") no-repeat 0 0;
            background-size:100% 100%;
            /*height:20rem;*/
            padding:0 1.4rem;
            margin-top:.6rem;
            height:14rem;
            overflow:auto;
        }
        .list-li{
            border-bottom:1px solid #888;
            padding:.2rem 0;
        }
        .list-li>div:first-child{
            width:6rem;
        }
        .list-li .font-1{
            flex:1;
            margin-right:.2rem;
            overflow:hidden;
            white-space:nowrap;
            text-overflow:ellipsis
        }
        .ranking-list-box>list-li:last-child{
            border:none;
        }
        .opacity{
            opacity: 0;
        }
        .imgType-2{
            width:1.2rem;
            height:1.2rem;
            border-radius:50%;
            margin-right:.3rem;
        }
        .imgType-3{
            width:2rem;
        }
        .num{
            width:2rem;
            color:red;
        }
        .mask{
            display:none;
            position:absolute;
            top:0;
            height:0;
            z-iondex:999;
            width:100%;
            height:100%;
            background:rgba(0,0,0,.7) url("/game/image/scoresRanking/42.png") no-repeat 80% 10%;
            background-size:80% auto;
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="top-box">
        <p><img class="imgType-1" src="/game/image/scoresRanking/28.png" alt=""></p>
        @if(count($Top3) <2)
        <div class="ranking-list-top flex-justify-around">
            <div class="flex-items-end">
                <div>
                    <div class="text-center ranking-list-img-box">
                        <img class="ranking-list-top-2" src="/game/image/scoresRanking/30.png" alt="">
                        <div class="show-type2">
                        </div>
                    </div>
                    <p class="text-center">
                        <span class="font-1"></span>
                        <br/>
                        <span class="font-2"></span>
                    </p>
                </div>
                </a>
            </div>
            @else
                <div class="ranking-list-top flex-justify-around">
                    <div class="flex-items-end">
                        <a href="{{url('/wap/gameInfo',['passport_id'=>$Top3[1]->passport_id,'user_id'=>$passport_id])}}">
                            <div>
                                <div class="text-center ranking-list-img-box">
                                    <img class="ranking-list-top-2" src="/game/image/scoresRanking/30.png" alt="">
                                    <div class="show-type2">
                                        <img src="{{$Top3[1]->headimgurl}}" alt="">
                                    </div>
                                </div>
                                <p class="text-center">
                                    <span class="font-1">{{$Top3[1]->nickname}}</span>
                                    <br/>
                                    <span class="font-2">{{$Top3[1]->total_score}}</span>
                                </p>
                            </div>
                        </a>
                    </div>
            @endif
            <div class="flex-items-end">
                <a href="{{url('/wap/gameInfo',['passport_id'=>$Top3[0]->passport_id,'user_id'=>$passport_id])}}">
                <div>
                    <div class="text-center ranking-list-img-box">
                        <img class="ranking-list-top-1" src="/game/image/scoresRanking/29.png" alt="">
                        <div class="show-type1">
                            <img src="{{$Top3[0]->headimgurl}}" alt="">
                        </div>
                    </div>
                    <p class="text-center">
                        <span class="font-1">{{$Top3[0]->nickname}}</span>
                        <br/>
                        <span class="font-2">{{$Top3[0]->total_score}}</span>
                    </p>
                </div>
                </a>
            </div>
            @if(count($Top3) <3)
            <div class="flex-items-end">
                <div>
                    <div class="text-center ranking-list-img-box">
                        <img class="ranking-list-top-3" src="/game/image/scoresRanking/31.png" alt="">
                        <div class="show-type3">
                        </div>
                    </div>
                    <p class="text-center">
                        <span class="font-1"></span>
                        <br/>
                        <span  class="font-2"></span>
                    </p>
                </div>
                </a>
            </div>
            @else
            <div class="flex-items-end">
                <a href="{{url('/wap/gameInfo',['passport_id'=>$Top3[2]->passport_id,'user_id'=>$passport_id])}}">
                    <div>
                        <div class="text-center ranking-list-img-box">
                            <img class="ranking-list-top-3" src="/game/image/scoresRanking/31.png" alt="">
                            <div class="show-type3">
                                <img src="{{$Top3[2]->headimgurl}}" alt="">
                            </div>
                        </div>
                        <p class="text-center">
                            <span class="font-1">{{$Top3[2]->nickname}}</span>
                            <br/>
                            <span  class="font-2">{{$Top3[2]->total_score}}</span>
                        </p>
                    </div>
                </a>
            </div>
            @endif
        </div>
        <div class="ranking-list-bottom">
            <ul class="ranking-list-box">

            </ul>
        </div>
    </div>
    <div class="btn-box flex-center">
        <a class="imgType-btn flex-center" style="color:#ececec; font-weight:100; width:7rem; margin:0 1rem;" href="{{url('/wap/game',['share_passport_id'=>$share_passport_id,'passport_id'=>$passport_id,])}}">
            <span>返回主頁</span>
        </a>
        <a class="imgType-btn flex-center" href="javascript:;" onclick="Open()" style="color:#ececec; margin:0 1rem; font-family:myFont; font-weight:100; width:7rem;">
            <span>分享到朋友圈</span>
        </a>
    </div>
    <!--小汽车-->
    <div class="car">
        <img src="/game/image/index/10.png" alt="">
    </div>
    <!--分享朋友圈弹出图层-->
    <div class="mask" onclick="Close()"></div>
</div>
</body>
<script src="/admin/js/jquery.min.js"></script>
<script src="/js/jquery.lazyload.min.js"></script>
<script src="/game/js/setRem.js"></script>
<script>
    setRem(18/414);
//    分享朋友圈弹出图层
    var eMask=document.querySelector(".mask");
    function Open(){
        eMask.style.display="block";
    }
    function Close(){
        eMask.style.display="none";
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var pageNumber = 1;
    var pageSize = 5;
    var loading = false;
    var hasMore = true;
    var passport_id = {{$passport_id}}
    function getList(){
        loading = true;
        $.ajax({
            url:"{{url('/wap/game/rank')}}",
            type:"POST",
            data:{pageNumber:pageNumber,pageSize:pageSize,passport_id:passport_id},
            dataType:'html',
            success: function (res) {
                if(res == ""){
                    hasMore = false;
                }else{
                    $(".ranking-list-bottom ul").append(res);
                }
            },
            complete: function (res) {
                loading = false;
            }
        })
    }
    getList();
    //滚动条事件
    $(".ranking-list-bottom").scroll(function(){
        var h = $(this).height();//div可视区域的高度
        var sh = $(this)[0].scrollHeight;//滚动的高度，$(this)指代jQuery对象，而$(this)[0]指代的是dom节点
        var st =$(this)[0].scrollTop;//滚动条的高度，即滚动条的当前位置到div顶部的距离
        if(h+st>=sh-10  && !loading && hasMore){
            //上面的代码是判断滚动条滑到底部的代码
            pageNumber++;
            getList();
        }
    })
    //点击到得分详情页
    function jump(url){
        window.location.href = url;
    }
</script>
</html>