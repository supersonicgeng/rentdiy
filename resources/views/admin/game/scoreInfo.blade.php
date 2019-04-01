<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>寻找小金仔</title>
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
        .box{
            width:92%;
            height:86%;
            background:url("/game/image/integralRanking/39.png") no-repeat 0 0;
            background-size:100% 100%;
        }
        .box ul{
            width:80%;
            height:100%;
        }
        .rankingList{
            border-bottom:2px red dashed;
        }
        .rankingList:last-child{
            border:none;
            margin-bottom:1rem;
            overflow:auto;
        }
        .rankingList>p:first-child img{
            width:12rem;
        }
        .lateY{
            transform:translateY(-46%);
        }
        .imgType1{
            width:6rem;
            border-radius:50%;
        }
        .imgType2{
            width:4rem;
        }
        .imgType3{
            width:10rem;
        }
        .imgType4{
            width:2rem;
            border-radius:50%;
            border:.1rem solid red;
            margin-right:.1rem;
        }
        .imgType4:last-child{
            margin-right:0;
        }
        .imgType5{
            width:4.6rem;
        }
        .nickname{
            display:block;
            margin:.8rem 0 1rem;
            text-align:center;
        }
        .opacity{
            opacity: 0;
        }
        .marginTop{
            margin-top:1rem;
        }
        .box .over-box{
            width:100%;
            height:100%;
            overflow:auto;
        }
        .num-bg{
            color:#fff;
            /*background:#dd7b7b;*/
            margin-bottom:.6rem;
            height:2rem;
            width:11rem;
            border-radius:2rem;
            background:-webkit-linear-gradient(#ff4141, #fff); /* Safari 5.1 - 6.0 */
            background: -o-linear-gradient(#ff4141, #fff); /* Opera 11.1 - 12.0 */
            background: -moz-linear-gradient(#ff4141, #fff); /* Firefox 3.6 - 15 */
            background: linear-gradient(#ff4141, #fff); /* 标准的语法 */;
        }
    </style>
</head>
<body>
<div class="wrap flex-center">
    <div class="box flex-center">
        <ul class="flex-column">
            <li class="rankingList">
                <p class="flex-center">
                    <img class="lateY" src="/game/image/integralRanking/19.png" alt="">
                </p>
                <div class="flex-justify-between flex-items-center marginTop">
                    <div>
                        <p><img class="imgType1" src="{{$passportInfo->headimgurl}}" alt=""></p>
                        <span class="nickname">{{$passportInfo->nickname}}</span>
                    </div>
                    <div class="flex-items-center">
                        <img class="imgType2" src="/game/image/integralRanking/21.png" alt="">
                        <span>{{$passportInfo->total_score}}</span>
                    </div>
                    <div>
                        <p class="flex-center num-bg">线人所有量：{{count($sharedInfo)}}名</p>
                        <p style="text-align:right;">
                            @if(count($sharedInfo) < 4)
                                @for($i =0 ; $i< count($sharedInfo) ; $i++)
                                    <img class="imgType4" src="{{$sharedInfo[$i]->headimgurl}}" alt="">
                                @endfor
                            @else
                                @for($i =0 ; $i< 4 ; $i++)
                                    <img class="imgType4" src="{{$sharedInfo[$i]->headimgurl}}" alt="">
                                @endfor
                            @endif
                        </p>
                    </div>
                </div>
            </li>
            <li class="rankingList flex-grow-1">
                <p class="flex-center marginTop">
                    <img src="/game/image/integralRanking/23.png" alt="">
                </p>
                <ul class="over-box">
                    {{--@foreach($sharedInfo as $k => $share)
                        <li class="rankingList">
                            <div class="flex-justify-between flex-items-center marginTop">
                                <div>
                                    <p><img class="imgType1" src="{{$share->headimgurl}}" alt=""></p>
                                    <span class="nickname">{{$share->nickname}}</span>
                                </div>
                                <div class="flex-items-center">
                                    <img class="imgType2" src="/game/image/integralRanking/21.png" alt="">
                                    <span>{{$share->shared_score}}</span>
                                </div>
                                <div>
                                    <img class="imgType5" src="/game/image/integralRanking/2{{$k+4}}.png" alt="">
                                </div>
                            </div>
                        </li>
                    @endforeach
                    @foreach($sharedInfo as $k => $share)
                        <li class="rankingList">
                            <div class="flex-justify-between flex-items-center marginTop">
                                <div>
                                    <p><img class="imgType1" src="{{$share->headimgurl}}" alt=""></p>
                                    <span class="nickname">{{$share->nickname}}</span>
                                </div>
                                <div class="flex-items-center">
                                    <img class="imgType2" src="/game/image/integralRanking/21.png" alt="">
                                    <span>{{$share->shared_score}}</span>
                                </div>
                                <div>
                                    <img class="imgType5" src="/game/image/integralRanking/2{{$k+4}}.png" alt="">
                                </div>
                            </div>
                        </li>
                    @endforeach--}}
                </ul>
            </li>
        </ul>
    </div>
</div>

</body>
<script src="/admin/js/jquery.min.js"></script>
<script src="/js/jquery.lazyload.min.js"></script>
<script src="/game/js/setRem.js"></script>
<script>
    setRem(12/414);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var pageNumber = 1;
    var pageSize = 4;
    var loading = false;
    var hasMore = true;
    var passport_id = {{$passportInfo->passport_id}};
    var u = "{{url('/gameInfo',['passport_id' =>'passportId'])}}";
    u = u.replace('passportId',passport_id);
    function getList(){
    loading = true;
    $.ajax({
        url:u,
        type:"POST",
        data:{pageNumber:pageNumber,pageSize:pageSize,passport_id:passport_id},
        dataType:'html',
        success: function (res) {
            if(res == ""){
                hasMore = false;
            }else{
                $(".over-box").append(res);
            }
        },
        complete: function (res) {
            loading = false;
        }
    })
    }
    getList();
    //滚动条事件
    $(".rankingList").scroll(function(){
        var h = $(this).height();//div可视区域的高度
        var sh = $(this)[0].scrollHeight;//滚动的高度，$(this)指代jQuery对象，而$(this)[0]指代的是dom节点
        var st =$(this)[0].scrollTop;//滚动条的高度，即滚动条的当前位置到div顶部的距离
        if(h+st>=sh-10  && !loading && hasMore){
            //上面的代码是判断滚动条滑到底部的代码
            pageNumber++;
            getList();
        }
    })
</script>
</html>
