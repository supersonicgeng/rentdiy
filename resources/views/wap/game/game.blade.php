<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>寻找小金仔</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0，maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <!--游戏页面-->
    <link rel="stylesheet" href="/game/css/style.css">
    <style>
        body{
            width:100vw;
            height:100vh;
            overflow:hidden;
        }
        .wrap{
            width:100%;
            height:100%;
            position:relative;
        }
        .wrap1{
            background:url("/game/image/04.png") no-repeat center bottom,url("/game/image/01.png") no-repeat 0 0;
            background-size:100% auto,100% 100%;
            width:100%;
            height:100%;
            position:absolute;
            left:0;
            top:0;
            z-index:100;
        }
        .countDownBox,.btnBox{
            position:relative;
            width:100%;
            left:0;
            text-align:center;
        }
        .countDownBox{
            top:20%;
            color:#fff;
            height:24rem;
            padding-bottom:3rem;
        }
        /*.countDown{*/
        /*font-size:14em;*/
        /*}*/
        .font1{
            font-size:2.4em;
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
        .btnBox img{
            width:30%;
            margin:0 10px;
        }
        /*.car{*/
        /*animation-duration:0s;*/
        /*}*/

    </style>
    <style>
        .wrap2{
            background:url("/game/image/01.png") no-repeat 0 0;
            background-size:100% 100%;
            width:100%;
            height:100%;
            position:absolute;
            left:0;
            top:0;
            z-index:99;
        }
        .box{
            width:100%;
            height:100%;
            background:url("/game/image/game/17.png") no-repeat 0 0;
            background-size:100% 100%;
            box-sizing:border-box;
            padding:0 2rem;
            overflow:hidden;
        }
        .close-num{
            margin:1rem 0 .4rem;
        }
        #passScore,#integral{
            color:#04f;
            font-size:2em;
        }
        .imgType-1{
            width:9rem;
            margin:1rem 0 .4rem;
        }
        .game-box{
            background:url("/game/image/game/18.png") no-repeat 0 0;
            background-size:100% 100%;
            position:relative;
            overflow:hidden;
        }
        .game-box .game{
            position:absolute;
            background:#fff;
            width:100%;
            height: 100%;
            left:0;
            top:0;
            overflow:hidden;
        }
        .game-box .game img{
            position:absolute;
            /*bottom:-10%;*/
            /*left:-10%;*/
            /*transform:scale(1.6);*/
            /*transition:all .1s;*/
        }
        .foot{
            padding:.4rem 0 .8rem;
        }
        .imgType-2{
            width:2.4rem;
            margin-left:2.4rem;
            margin-right:.4rem;
        }
        .link{
            position:absolute;
            transform:translateX(-50%) translateY(-50%);
            border-radius:50%;
            width:6rem;
            height:6rem;
        }
        .visited{
            border:.4rem red solid;
        }
        .options{
            position:absolute;
            left:0;
            top:0;
            width:100%;
            height:100%;
            box-sizing:border-box;
            padding:18px;

            background:rgba(0,0,0,.5);
            color:#fff;
            display:none;
            font-size:1.6em;
        }
    </style>
</head>
<body>
<div class="wrap">
    <div id="load-page" class="wrap1">
        <div class="countDownBox">
            <!--<p class="countDown"></p>-->
            <p class="font1 flex-justify-center">
                <span>游戏加载中&nbsp;</span>
                <span class="countDown"></span>
                <!--<span class="load-font"></span>-->
                <!--<span class="load-font"></span>-->
                <!--<span class="load-font"></span>-->
            </p>
        </div>
        {{--<div class="btnBox">--}}
            {{--<a href="{{url('/wap/game/commit',['score'=>'0','passport_id'=>$passport_id,'share_passport_id'=>$share_passport_id])}}">--}}
                {{--<img src="/game/image/startGame/08.png" alt="">--}}
            {{--</a>--}}
        {{--</div>--}}
        <!--小汽车-->
        <div class="car">
            <img src="/game/image/index/10.png" alt="">
        </div>
    </div>


    <!-- -&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;-->
    <div class="wrap2 box flex-column">
        <div class="close-num flex-items-center text-center">
            <div id="passScore" style="width: 25%;">
            </div>
            <div style="width:50%;" class="flex-center">
                <div id="" class="imgType-btn flex-center">
                    <p class="font-4 flex-justify-around flex-1">第<span id="iteration">1</span>关</p>
                </div>
            </div>

            <div id="integral" style="width: 25%;">

            </div>
        </div>
        <!--游戏界面-->
        <div class="game-box flex-1">
            <div id="game" class="game"></div>
            <div id="radius" class="link"></div>
        </div>
        <div class="foot flex-center">
            <a class="imgType-btn imgType-1 flex-center" style="color:#fff;" href="{{url('/wap/game',['share_passport_id'=>$share_passport_id,'passport_id'=>$passport_id,])}}">
                <span>返回主頁</span>
            </a>
            <div class="flex-center">
                <img class="imgType-2" src="/game/image/game/16.png" alt="">
                <span id="dataTime" class="font-3"></span>
            </div>
        </div>
        <!--游戏结束弹出层-->
        <div class="options flex-center">
            <div>
                <p class="text-center" style="margin-top:1.5rem; font-size:2rem;">恭喜您完成遊戲!</p>
                <p>分享到微信朋友圈或邀請朋友完成遊戲後,</p>
                <p>您更可額外獲得朋友的遊戲分數提高排名!</p>
                <p class="text-center" style="margin-top:1.5rem; font-size:2rem;">獎品</p>
                <p style="font-size:1.4rem;">頭1-5名: Atelier Cologne價值HK$1,400古龍水(100ml)</p>
                <p style="font-size:1.4rem;">頭6-10名: CALVIN KLEIN價值HK$590毛氈</p>
            </div>
        </div>
    </div>
</div>
</body>
<script src="/game/js/setRem.js"></script>
<script>
    setRem(12/414);
    var eGame=document.querySelector("#game"),//获取游戏容器节点
        option=document.querySelector(".options"),//获取游戏结束的弹出层容器
        eGameBox=document.querySelector(".game-box"),
        countDown=document.querySelector(".countDown"),
        eDataTime=document.querySelector("#dataTime"),//获取用于展示当前当游戏倒计时的节点容器
        imgLoad1,
        imgLoad2,
        oGame={  //初始化配置对象
            eGame:eGame,  //获取容器
            outWidth:eGame.offsetWidth,  //获取容器宽度
            outHeight:eGame.offsetHeight, //获取容器高度
            eIteration:document.querySelector("#iteration"),//获取用于展示当前关卡值节点容器
            ePassScore:document.querySelector("#passScore"),//获取用于展示当前关卡通关所需积分值的节点容器
            eIntegral:document.querySelector("#integral"),//获取用于展示当前关卡当前已获取的积分值的节点容器
            eDiv:document.querySelector("#radius"), //选中小金仔后的圆圈特效和控制范围用的元素节点
            imgUrl:"/game/image/game/", //图片路径
            passScore:parseInt(sessionStorage.getItem("passScore")), //获取第一关所需的积分数的初始值
            addScore:parseInt(sessionStorage.getItem("addScore")), //获取每一关的迭代需求积分规则值
            level:parseInt(sessionStorage.getItem("level")), //获取限定最高关卡值
            dataTime:parseInt(sessionStorage.getItem("dataTime")), //获取限制每一关的时限值
            iteration:1, //记录关卡，初始关卡为第一关
            integrals:0, //定义当前关卡通关所需的总积分值
            integral:0, //记录当前游戏目前所获得的总积分
            minTdX:7, //定义图片排布列数,等同于设置关卡的难度系数，初始值为7
            iterationTdX:2, //定义关卡难度系数迭代规则值,目前初始迭代规则是每增加一关图片布局列数便多加一列
            tdX:0,//定义关卡难度系和图片布局列数迭代规则值,最大难度不能超过最高关卡的难度系数
            maxIteration:0,//定义当前关卡难度系数和积分需求的迭代规则值,最大难度不能超过最高关卡的难度系数
            imgWidth:0, //获取每一列图片宽度
            quantity:0, //记录当前关卡图片排布的数量
            aImgList:[], //画布布置完成后记录画布内所有img节点的状态,用做在没有进入新的关卡之前无需为画布进行重新的填充
            bool:true, //第一小金仔现在是否能继续点击
            imgTypeNum:6, //定义图片素材有多少种类
            ratio:1,//定义容器缩放比例
            setIteration(){ //更新当前关卡值
                this.iteration++;
                this.eIteration.innerHTML=this.iteration;
            },
            setIntegrals(){ //更新当前关卡通关所需的总积分值
                this.integrals+=this.passScore+this.maxIteration*this.addScore;
                this.ePassScore.innerHTML=this.integrals;
            },
            setIntegral(){ //更新当前关卡获得的总积分
                this.integral++;
                this.eIntegral.innerHTML=this.integral;
            },
            setDataTime(){ //更新当前游戏倒计时值
                var _this=this;
                setTimeout(function(){
                    _this.dataTime--;
                    eDataTime.innerHTML=_this.dataTime;
                    if(_this.dataTime){
                        _this.setDataTime();
                    }else{
                        _this.callback();
                    }
                },1000);
            },
            callback(){
                option.style.display="flex";
                this.eDiv.onclick=null;
                setTimeout(function(){
                     var url = "{{url('/wap/game/commit',['score'=>'oGame.integral','passport_id'=>$passport_id,'share_passport_id'=>$share_passport_id])}}";
                     url = url.replace("oGame.integral",oGame.integral);
                     window.location.replace(url);
                },3000);
            }
        };
    eDataTime.innerHTML=oGame.dataTime;//倒计时显示
    (function (){
        oGame.tdX=oGame.minTdX+oGame.level*oGame.iterationTdX;//初始化当前关卡图片排布列数,等同于难度系数,当前是最高难度
        oGame.imgWidth=oGame.outWidth/oGame.tdX;//初始化当前关卡每一列图片宽度
        var tdX=oGame.tdX, //定义关卡难度系和图片布局列数迭代规则值,最大难度不能超过最高关卡的难度系数
            aCol=[], //记录当前每一列图片所占高度大小
            min=0,  //更新记录当前布局进度下，所有图片列的最小高度值
            item=0, //更新记录当前布局情况下，所有图片列的最小高度值那一列所处的下标值
            imgWidth=oGame.imgWidth, //获取图片宽度值
            outHeight=oGame.outHeight, //获取容器高度值
            eImg=null, //更新记录当前错操作的图片节点
            quantity=0, //记录图片节点总量值
            aImgList=[], //获取所有图片节点的集合
            index=0, //更新记录当前图片的序号或数量
            imgTypeNum=6, //获取图片素材有多少种类
            aImgHeight=[], //记录每一种类型图片的高度
            imgUrl=oGame.imgUrl; //获取图片路径的公共部分
        img=new Image(); //创建小金仔
        img.src=oGame.imgUrl+"7.png"; //设置小金仔的src路径


        initializeLevelData(); //初始化当前关卡配置，并开始游戏
        createImgType();//每种类型图片素材都创建一个，点并追加到容器，让浏览器渲染出数据
        oGame.setIntegrals();//更新当前关卡通关所需的总积分值
        function initializeLevelData(){ //初始化当前关卡的配置信息
            oGame.maxIteration=(oGame.iteration>oGame.level?oGame.level:oGame.iteration)-1;//初始化当前关卡难度系数和积分需求的迭代规则值
            oGame.eIntegral.innerHTML=oGame.integral;
            oGame.ratio=oGame.tdX/(oGame.minTdX+oGame.maxIteration*oGame.iterationTdX);
            oGame.eGame.style.transform='scale('+oGame.ratio+')';
        }
        //每种类型图片素材都创建一个，点并追加到容器，让浏览器渲染出数据
        function createImgType(){
            var sImg="",
                i=1;

            for(;i<=imgTypeNum;i++){
                sImg+='<img src="'+imgUrl+i+'.png"  onload="imgLoad1(this,'+i+')" style="width:'+imgWidth+'px;"/>';
            }
            oGame.eGame.innerHTML=sImg;
        }
        //图片加载事件-1
        imgLoad1=function(eImg,index){//浏览器渲染出图片后，获取相应的数据
            imgTypeNum--;
            aImgHeight[index]=eImg.offsetHeight; //记录每一种图片素材的高度
            if(!imgTypeNum){ //记录完后，开始虚例化布局游戏地图
                layout();//布局虚例化地图
            }
        }
        //布局虚例化地图
        function layout(){
            var sImg="",
                num=0,
                outHeight=oGame.outHeight;
            function each(){
                num=Math.floor(Math.random()*6+1);//获取1-6的随机数，对应6种图片素材的名称
                if(index<tdX){
                    aCol[index]=0;
                }
                min=Math.min.apply(null,aCol); //获取所占高度最小的那一列的高度值
                for(item=0;min!=aCol[item];item++){
                    //获取所占高度最小的那一列的下标
                }
                aCol[item]+=aImgHeight[num];//更新每一列图片目前所占据的高度
                sImg+='<img src="'+imgUrl+num+'.png" onload="imgLoad2(this,'+index+')"  style="width:'+imgWidth+'px; left:'+item*imgWidth+'px; top:'+min+'px;"/>';
                index++;
                if(min<outHeight){ //当虚例地图没有铺满整个容器时继续对地图补充完善
                    each();
                }
            }
            each();
            oGame.eGame.innerHTML=sImg; //将虚例地图追加到容器
            quantity=index;//获取图片节点的总数量
            index=0; //初始化index
        }
        imgLoad2=function(eImg,i){
            index++;
            if(index==quantity){
                index=0;
                function aaa(){
                    index+=5;
                    setTimeout(function(){
                        countDown.innerHTML=parseInt(index/quantity*100)+"%"; //获取当前的进度百分比
                        if(index>=quantity){
                            aImgList=oGame.eGame.children; //获取图片节点集合，并转化为真数组
                            oGame.aImgList=aImgList;
                            targetCcomposeType(); //对小金仔进行布局
                            document.getElementById("load-page").style.display="none";//关闭加载页面，进入游戏页面
                            oGame.setDataTime(); //游戏开始倒计时
                        }else{
                            aaa();
                        }
                    },1);
                }
                aaa();
            }
            /*countDown.innerHTML=parseInt(index/quantity*100)+"%"; //获取当前的进度百分比
            if(index==quantity){
                setTimeout(function(){
                    oGame.aImgList=aImgList=[...oGame.eGame.children]; //获取图片节点集合，并转化为真数组
                    targetCcomposeType(); //对小金仔进行布局
                    document.querySelector(".wrap1").parentNode.removeChild(document.querySelector(".wrap1"));//关闭加载页面，进入游戏页面
                    oGame.setDataTime(); //游戏开始倒计时
                },1000);

            }*/
        }
        //对小金仔进行布局
        var imgRatio=0;
        function targetCcomposeType(){
            var num = Math.floor(Math.random()*quantity),//随机获取所有img范围内获取任意一个img节点所处的下标位置
                eImg=aImgList[num], //通过下标找到对应的img节点
                imgWidth=oGame.imgWidth,
                widthRation=oGame.outWidth/oGame.ratio,
                heightRatio=oGame.outHeight/oGame.ratio,
                top=eImg.offsetTop,
                left=eImg.offsetLeft,
                colLeft=(left-eImg.offsetHeight*0.6)<(oGame.outWidth-widthRation)/2,
                colRight=(left+imgWidth)>((oGame.outWidth-widthRation)/2+widthRation),
                rowTop=top<(oGame.outHeight-heightRatio)/2,
                rowBottom=(top+eImg.offsetHeight*1.5)>((oGame.outHeight-heightRatio)/2+heightRatio);
            if(colLeft || colRight || rowTop || rowBottom){
                //判断,当小金仔不在容器范围时重新选取,
                targetCcomposeType();
            }else{
                var imgWidth=oGame.imgWidth;
//                var imgWidth=oGame.imgWidth*1.5;
//                if(oGame.iteration==4){
//                    imgRatio=oGame.ratio;
//                }else if(oGame.iteration>4){
//                    imgWidth=imgWidth*(imgRatio/oGame.ratio);
//                }
                var centerX=(left+imgWidth/2-(oGame.outWidth-widthRation)/2)*oGame.ratio,//换算小金仔的中心X坐标
                    centerY=(top+imgWidth/2-(oGame.outHeight-heightRatio)/2)*oGame.ratio;//换算小金仔的中心Y坐标
                img.style.width=imgWidth+"px";
                img.style.left=left+"px";
                img.style.top=top-imgWidth/2+"px";
                oGame.eGame.insertBefore(img,eImg);
                console.log(left,top);
                oGame.eDiv.setAttribute("style","left:"+centerX+"px; top:"+centerY+"px; z-index:999;");//将圆圈的位置移动到小金仔的中心坐标点上面去
                if(oGame.integral%2){
                    eGameBox.style.transform='rotateY(180deg)';
                }else{
                    eGameBox.style.transform='rotateY(0deg)';
                }
                oGame.bool=true;
            }
        }


        //小金仔点击事件函数
        oGame.eDiv.ontouchstart=redraw;
        function redraw(){
            if(!oGame.bool) return;
            oGame.bool=false;
            oGame.setIntegral();//更新当前游戏目前获得的总积分
            oGame.eDiv.classList.toggle("visited");//显示圆圈样式
            setTimeout(function(){
                oGame.eGame.classList.toggle("game1");
                oGame.eDiv.classList.toggle("visited");//清除圆圈样式
                if(oGame.integral==oGame.integrals){
                    nextCustoms();//当满足当前关卡的积分要求后进入下一关
                }
                targetCcomposeType();//从新对小金仔布局
            },5);
        }


        //下一关
        function nextCustoms(){
            oGame.setIteration();//更新当前关卡值
            initializeLevelData(); //初始化当前关卡的配置信息
            oGame.setIntegrals();//更新当前关卡通关所需的总积分值
        }
    }());
</script>
</html>