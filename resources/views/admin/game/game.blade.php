<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>寻找小金仔</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--游戏页面-->
    <link rel="stylesheet" href="/game/css/style.css">
    <style>
        body{
            width:100vw;
            height:100vh;
            overflow:hidden;
        }
        .wrap{
            background:url("/game/image/01.png") no-repeat 0 0;
            background-size:100% 100%;
            width:100%;
            height:100%;
            position:relative;
        }
        .box{
            width:100%;
            height:100%;
            background:url("/game/image/game/17.png") no-repeat 0 0;
            background-size:100% 100%;
            box-sizing:border-box;
            padding:0 2rem;
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
            width:100%;
            height: 100%;
            overflow:hidden;
        }
        .game-box .game img{
            position:absolute;
            bottom:50%;
            left:-10%;
            /*transform:scale(1.4);*/
            transition:all .3s;
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
            border:.6rem red solid;
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="box flex-column">
        <div class="close-num flex-items-center flex-justify-around">
            <div id="passScore">

            </div>
            <div id="" class="imgType-btn flex-center">
                <p class="font-4 flex-justify-around flex-1">第<span id="iteration">1</span>关</p>
            </div>
            <div id="integral">

            </div>
        </div>
        <!--游戏界面-->
        <div class="game-box flex-1">
            <div id="game" class="game"></div>
            <div id="radius" class="link"></div>
        </div>
        <div class="foot flex-center">
            <a href="javascript:;">
                <img class="imgType-1" src="/game/image/game/15.png" alt="">
            </a>
            <div class="flex-center">
                <img class="imgType-2" src="/game/image/game/16.png" alt="">
                <span id="dataTime" class="font-3"></span>
            </div>
        </div>
    </div>
</div>

</body>
<script src="/game/js/setRem.js"></script>
<script>
    setRem(12/414);
    var eGame=document.querySelector("#game"),//获取游戏容器节点
        oGame={  //初始化配置对象
            eGame:eGame,  //获取容器
            outWidth:eGame.offsetWidth,  //获取容器宽度
            outHeight:eGame.offsetHeight, //获取容器高度
            eIteration:document.querySelector("#iteration"),//获取用于展示当前关卡值节点容器
            ePassScore:document.querySelector("#passScore"),//获取用于展示当前关卡通关所需积分值的节点容器
            eIntegral:document.querySelector("#integral"),//获取用于展示当前关卡当前已获取的积分值的节点容器
            eDataTime:document.querySelector("#dataTime"),//获取用于展示当前当游戏倒计时的节点容器
            eDiv:document.querySelector("#radius"), //选中小金仔后的圆圈特效和控制范围用的元素节点
            imgUrl:"/game/image/game/", //图片路径
            passScore:parseInt(sessionStorage.getItem("passScore")), //获取第一关所需的积分数的初始值
            addScore:parseInt(sessionStorage.getItem("addScore")), //获取每一关的迭代需求积分规则值
            level:parseInt(sessionStorage.getItem("level")), //获取限定最高关卡值
            dataTime:parseInt(sessionStorage.getItem("dataTime")), //获取限制每一关的时限值
            iteration:1, //记录关卡，初始关卡为第一关
            integrals:0, //定义当前关卡通关所需的总积分值
            integral:0, //记录当前游戏目前所获得的总积分
            aCol:[], //记录当前排布的每一列的所占位置的高度
            minTdX:10, //定义图片排布列数,等同于设置关卡的难度系数，初始值为10
            iterationTdX:1, //定义关卡难度系数迭代规则值,目前初始迭代规则是每增加一关图片布局列数便多加一列
            tdX:0,//定义关卡难度系和图片布局列数迭代规则值,最大难度不能超过最高关卡的难度系数
            maxIteration:0,//定义当前关卡难度系数和积分需求的迭代规则值,最大难度不能超过最高关卡的难度系数
            imgWidth:0, //获取每一列图片宽度
            min:0, //记录每一列的最小高度
            item:0,//记录每一列最小高度位于所有列中的下标
            quantity:0, //记录当前关卡图片排布的数量
            onTime:0, //定义每一关的点击所需点击次数，等同于每一关的积分需求
            aGameImg:[], //画布布置完成后记录画布内所有img节点的状态,用做在没有进入新的关卡之前无需为画布进行重新的填充
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
                    _this.eDataTime.innerHTML=_this.dataTime;
                    if(_this.dataTime){
                        _this.setDataTime();
                    }else{
                        _this.callback();
                    }
                },1000);
            },
            callback(){
                var url = "{{url('game/commit',['score'=>'oGame.integral','passport_id'=>$passport_id,'share_passport_id'=>$share_passport_id])}}";
                url = url.replace("oGame.integral",oGame.integral)
                window.location.href= url;
            }
        };
    oGame.eDataTime.innerHTML=oGame.dataTime;
    (function (){
        console.log(oGame)

        layout(); //初始化当前关卡配置，并开始游戏
        addCreateImg();//填充画布
        oGame.setIntegrals();//更新当前关卡通关所需的总积分值
        oGame.setDataTime(); //游戏开始倒计时
        function layout(){ //初始化当前关卡配置，并开始游戏
            oGame.maxIteration=(oGame.iteration>oGame.level?oGame.level:oGame.iteration)-1;//初始化当前关卡难度系数和积分需求的迭代规则值
            oGame.eIntegral.innerHTML=oGame.integral;
            oGame.tdX=oGame.minTdX+oGame.maxIteration*oGame.iterationTdX;//初始化当前关卡图片排布列数,等同于难度系数
            oGame.aCol=[];//初始化当前关卡每一列的高度
            oGame.min=0;//初始化当前关卡每一列的最小高度
            oGame.imgWidth=oGame.outWidth/oGame.tdX;//初始化当前关卡每一列图片宽度
            oGame.quantity=0;//初始化当前关卡图片排布的数量
        }
        //创建图片节点并追加到容器
        function addCreateImg(){
            var num=Math.floor(Math.random()*6+1),//获取1-6的随机数，对应6种素材图片名称
                eImg=new Image();//常见img节点
            eImg.style.width=oGame.imgWidth+"px";//设置图片宽度
            eImg.src=oGame.imgUrl+num+".png";//设置图片路径
            eGame.appendChild(eImg);//追加到容器内底部
            eImg.onload=imgLoad;
        }
        function imgLoad(){
            this.onload=null;
            oGame.quantity++;//每创建一个图片便自加一次
            oGame.aGameImg.push(this);
            imgComposeType(this);//对最新追加的图片进行布局
            composeType();//判断是否继续追加新的图片
        }
        //判断是否继续追加新的图片
        function composeType(){
            if(oGame.min>=oGame.outHeight || oGame.iteration>oGame.level){//当图片列最小的高度那一列超出容器范围的时候停止追加新的图片
                setTimeout(targetCcomposeType,300);;//当停止追加新障碍图片时，从障碍图片中随机抽取一张图片更换为小金仔图片
            }else{
                addCreateImg();//继续追加新图片
            }
        }
        //对当前图片进行排版布局
        function imgComposeType(eImg){
            if(oGame.quantity<=oGame.tdX){
                oGame.aCol[oGame.quantity-1]=0;
            }
            oGame.min=Math.min.apply(null,oGame.aCol); //获取所占高度最小的那一列的高度值
            oGame.aCol.forEach(function(value,index){ //获取所占高度最小的那一列的下标
                if(value==oGame.min) oGame.item=index;
            });
            oGame.aCol[oGame.item]+=eImg.offsetHeight;//更新每一列图片目前所占据的高度
            eImg.style.top=oGame.min+"px"; //当图片非第一行的时候图片的top值位于容器中图片列所占高度最小的那一列下面
            eImg.style.left=oGame.item*oGame.imgWidth+"px";//规划改图片处于那一列的位置
        }
        //对小金仔进行布局
        function targetCcomposeType(){
            console.log(oGame.quantity)
            var num = Math.floor(Math.random()*oGame.quantity),//随机获取所有img范围内获取任意一个img节点所处的下标位置
                eImg=oGame.aGameImg[num], //通过下标找到对应的img节点
                rowNum=Math.floor(eImg.offsetTop+eImg.offsetHeight)>oGame.outHeight,
                colNum=Math.floor(eImg.offsetLeft/oGame.imgWidth); //获取img节点所处列的位置下标
            if((num<oGame.tdX*2) || rowNum || (colNum>=(oGame.tdX-2)) || (colNum<=1)){
                //判断
                //1:当该图片处于最前两行之内的话将重新选取
                //2:单该图片处于容器底部之外将重新选取
                //3:当该图片处于左边两列之内将重新选取
                //4:当该图片处于右边两列之内将重新选取
                targetCcomposeType();
            }else{
                eImg.src=oGame.imgUrl+"7.png";  //当该图片位置符合要求变更改路径为小金仔
                var left=eImg.offsetLeft,//获取小金仔的X坐标点
                    top=eImg.offsetTop,//获取小金仔的Y坐标点
                    centerX=left+oGame.imgWidth/2,//换算小金仔的中心X坐标
                    centerY=top+oGame.imgWidth/2;//换算小金仔的中心Y坐标
                oGame.eDiv.style.left=centerX+"px";//将圆圈的位置移动到小金仔的中心坐标点上面去
                oGame.eDiv.style.top=centerY+"px";
            }
        }
        //小金仔点击事件函数
        oGame.eDiv.onclick=redraw;
        function redraw(){
            oGame.setIntegral();//更新当前游戏目前获得的总积分
            oGame.eDiv.classList.toggle("visited");//显示圆圈样式
            setTimeout(function(){
                oGame.eDiv.classList.toggle("visited");//清除圆圈样式
                if(oGame.integral==oGame.integrals){
                    nextCustoms();//当满足当前关卡的积分要求后进入下一关
                }else{
                    relayout(); //当没有达到当前关卡的积分要求时对画布里面的图片进行从新排序布局
                }
            },500)
        }
        //刷新当前关卡
        function relayout(){
            var quantity=oGame.quantity-1;
            layout(); //从新填充画布
            repImgsUrl(function(eImg,index){
                eImg.style.width=oGame.imgWidth+"px";//设置图片宽度
                if(index==quantity){
                    composeType();
                }
            })
        }
        //对图片集合进行随机更换不同的url路径,并重新进行排序布局
        function repImgsUrl(callback){
            oGame.aCol=[];
            oGame.quantity=0;//初始化当前关卡图片排布的数量
            oGame.aGameImg.forEach(function(eImg,index){ //对所有img节点随机更换src路径
                if(callback) callback(eImg,index);
                var num=Math.floor(Math.random()*6+1);
                eImg.src=oGame.imgUrl+num+".png";
                eImg.onload=function(){
                    this.onload=null;
                    oGame.quantity++;
                    imgComposeType(eImg);
                };
            });
        }
        //下一关
        function nextCustoms(){
            oGame.setIteration();//更新当前关卡值
            relayout();
            oGame.setIntegrals();//更新当前关卡通关所需的总积分值
        }
    }());
</script>
</html>
