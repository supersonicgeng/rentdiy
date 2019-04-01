
<!DOCTYPE html><html lang="en"><head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>寻找金仔</title>
    <link rel="stylesheet" href="/game/style/css/game.css">
    <link rel="stylesheet" href="/game/style/css/over.css">
    <link rel="stylesheet" href="/game/style/css/rank.css">
    <link rel="stylesheet" href="/game/style/css/style.css">
</head>
<body>
{{--{if $arr['share_img']}<div style="display:none"><img src="{media $arr['share_img']}"/></div>{/if}--}}
<div class="start-img" style="opacity: 0; display: none;">

</div>

<div class="attention" id="register" style="display:none">

    <div class="attention-con attention-con2">
        <p class="attention-title">完善信息</p>
        <p class="attention-text" id="dm-attention">
            <input name="realname" id="realname" class="deam-input" type="text" placeholder="请输入姓名"/>
            <input name="telphone" id="telphone" class="deam-input" type="tel" placeholder="请输入手机号"/>
        </p>
        <div class="attention-button">
            <button type="button" id="deam-submit" class="btn btn-submit">提交</button>
        </div>
    </div>
</div>
<div class="attention" id="deamrule" style="display:none">

    <div class="attention-con attention-con2">
        <p class="attention-title">游戏规则</p>
        <div class="attention-text" id="dm-attention">
            {{--{$arr['content']}--}}
        </div>
        <div class="attention-button">
            <button type="button" id="deam-submit" class="btn btn-close">关闭</button>
        </div>
    </div>
</div>
<footer class="foot" style="display:none">
    <div class="bg"></div>
</footer>

<script src="/game/style/js/zepto.js"></script>
<script src="/game/style/js/phaser.js"></script>
<link rel="stylesheet" type="text/css" href="/game/style/css/main.css">

<div id="game-div" class="game-div">
</div>
<!-- 游戏界面 -->
<div id="circle" class="circle"></div><!-- 圆圈 -->
<div id="startButton" class="start-button" style="display: none; left: 288.5px; top: 619.5px;"></div><!-- 开始按钮 -->
<div class="deam-menu" style="display:none">
    <span class="ruleBtn"><img src="/game/style/images/rule.png" alt=""></span>
    <span class="rankBtn"><img src="/game/style/images/rank.png" alt=""></span>
</div>
<p class="copyright">{{--{$arr['copyright']}--}}</p>
<div id="pauseButton" class="pause-button"></div><!-- 暂停按钮 -->
<div id="pause-div" class="pause-div">
    <div class="pause-container">
        <img id="pauseHead" class="pause-head" src="">
        <div class="pause-text-container">
            <div id="pauseText" class="pause-text"></div>
        </div>
        <div class="pause-button-container">
            <div id="pauseReplay" class="replay-button"></div>
            <div id="pauseResume" class="resume-button"></div>
        </div>
    </div>
</div><!-- 暂停界面 -->
<div id="rotate-div" class="rotate-div">
    <div class="rotate-img"></div>
</div><!-- 旋转提示 -->
{{--{if $arr['is_jssdk']}--}}
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript" >
    wx.config({
        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: "{{$config['appId']}}", // 必填，公众号的唯一标识
        timestamp:"{{$config['timestamp']}}" , // 必填，生成签名的时间戳
        nonceStr: "{{$config['nonceStr']}}", // 必填，生成签名的随机串
        signature: "{{$config['signature']}}",// 必填，签名
        jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage'] // 必填，需要使用的JS接口列表
    });
</script>
{{--{/if}--}}
<script type="text/javascript">
    (function () {
        // 屏幕数据
        var windowInnerWidth = document.body.offsetWidth > document.body.scrollHeight ? document.body.scrollHeight : document.body.offsetWidth;
        var windowInnerHeight = document.body.offsetWidth > document.body.scrollHeight ? document.body.offsetWidth : document.body.scrollHeight;
        var ratio = windowInnerWidth/windowInnerHeight;
        //alert(windowInnerWidth);
        // 设备类型
        var device = (navigator.userAgent.indexOf("iPad") > -1) ? 'pad' : 'mobile';

        // 背景宽高比
        var bgRatio_mobile = 828/1344;
        var bgRatio_pad = 1408/2048;

        // 暂停的话和狗头
        var pauseResource = [
            {"text": "别再说自己是单身狗了，你这个年纪，狗已经死了。", "pic": "/game/style/images/assets/pause-1.png"},
            {"text": "单身狗也是狗，秀恩爱属于虐狗行为。", "pic": "/game/style/images/assets/pause-2.png"},
            {"text": "“你喜欢猫呢还是狗呢?”<br/>“喜欢狗”<br/>“汪汪~”", "pic": "/game/style/images/assets/pause-3.png"},
            {"text": "跑到喜欢的女生面前躺下对她说“同学，你男朋友掉地上了。”", "pic": "/game/style/images/assets/pause-4.png"},
            {"text": "“诶同学，我怎么越看你越像我下一任女友耶。”", "pic": "/game/style/images/assets/pause-5.png"}
        ];

        // 游戏状态
        var playing = false;

        // 人群
        var persons;

        // 单身狗
        var single;

        // 计时器
        var timer;

        // 音频
        var audioWin;
        var audioBg;

        // 游戏顶部icon
        var iconDoge;
        var iconCountDown;
        var iconScore;
        var dogeText;
        var countDownText;
        var bestScore = 18;
        var bestScoreText;

        // 游戏数值
        var level = 1; // 难度级别
        var scale = 1.0; // 缩放级别
        var hatTag = false; // 戴帽子标记
        var countDown = 60; // 计时
        var score = 0; // 得分
        var offsetRange = 60; // 点击允许偏移范围
        // 单身狗位置
        var singleLocation = {
            x: 0,
            y: 0,
            offsetX: 0,
            offsetY: 0
        };
        // 单体体积
        var singleSize = {
            width: 120,
            height: 120
        };
        // 单身狗中心
        var singleCenter = {
            x: 0,
            y: 0
        };
        // 情侣个数
        var personsNum = {
            x: 0,
            y: 0,
            total: 0
        };
        // 游戏界面画布
        var playground = {
            x: 0,
            y: 0,
            width: windowInnerWidth*2,
            height: windowInnerHeight*2
        };

        // 开始游戏
        function gameStart() {
            hiddenLabel();
            audioWin.play();
            $("#startButton").css("-webkit-transform", "scale(0.9, 0.9)");
            setTimeout(function() {
                $("#startButton").css("-webkit-transform", "scale(1.0, 1.0)");
                setTimeout(function() {
                    $("#startButton,.copyright,.deam-menu").css("display", "none");
                    game.state.start('play');
                    setTimeout(function() {
                        playing = true;
                    }, 100);
                }, 100);
            }, 100);
        }

        // 游戏结束
        function gameEnd() {
            timer.pause();

            playing = false;

            // 隐藏暂停按钮
            $("#pauseButton").css("display", "none");

            // 提交分数和奖牌
            setScore(score, getBadge());
        }

        // 暂停游戏
        function gamePause() {
            if (!timer.paused) {
                timer.pause();
                var pause_random = Math.floor(Math.random()*5);
                $("#pauseHead").attr("src", pauseResource[pause_random]["pic"]);
                $("#pauseText").html(pauseResource[pause_random]["text"]);
                $("#pause-div").css("display", "-webkit-box");
                playing = false;
            }
        }

        // 继续游戏
        function gameResume() {
            if (timer.paused) {
                setTimeout(function() {
                    playing = true;
                }, 100);
                $("#pause-div").css("display", "none");
                timer.resume();
            }
        }

        // 重玩游戏
        function gameReplay() {
            // 重置游戏数据
            gameReset();
            game.state.start('play');
            $("#pauseButton").css("display", "none");
            $("#pause-div").css("display", "none");
            setTimeout(function() {
                playing = true;
            }, 100);
        }

        // 游戏重置
        function gameReset() {
            // 重设数值
            level = 1;
            scale = 1.0;
            countDown = 60;
            hatTag = false;
            score = 0;
            countDownText.text = countDown;
            dogeText.text = score;
            // 清空场景
            persons.destroy(true, true);
        }

        // 计算情侣个数
        function setPersonsNum() {
            personsNum.x = parseInt(Math.ceil(playground.width/singleSize.width/scale));
            personsNum.y = parseInt(Math.ceil(playground.height/singleSize.height/scale));
            personsNum.total = parseInt(personsNum.x * personsNum.y);
        }

        // 设置单身狗位置
        function setSingleLocation() {
            singleLocation.offsetX = parseInt(Math.random() * (personsNum.x-4))+2;
            singleLocation.offsetY = parseInt(Math.random() * (personsNum.y-4))+2;
        }

        // 平铺人群
        function fillStage() {
            var random_arr = getRandomArr();
            for (var j = 0; j < personsNum.y; j++) {
                for (var i = 0; i < personsNum.x; i++) {
                    // 偏移值
                    var offsetX = getRandomOffset(30, false);
                    var offsetY = (j == 0) ? getRandomOffset(10, true) : offsetX;
                    // 坐标
                    var x = i * singleSize.width + offsetX - singleSize.width/3;
                    var y = j * singleSize.height + offsetY;
                    // 判断创建的是否为单身狗
                    if (i == singleLocation.offsetX && j == singleLocation.offsetY) {
                        // 是否戴帽子
                        var random_face = hatTag ? 1 : 0;
                        // 单身狗的坐标
                        singleLocation.x = x * scale;
                        singleLocation.y = y * scale;
                        // 单身狗的中心
                        singleCenter.x = (singleLocation.x + 150/2*scale);
                        singleCenter.y = (singleLocation.y + 300/2*scale);
                        // 创建单身狗
                        single = persons.create(x, y, 'persons', random_face);
                        single.animations.add('found', [0, 1, 0, 1], 10, false);
                    } else {
                        // 随机抽取一个情侣
                        if (random_arr.length == 0) random_arr = getRandomArr();
                        var ramdom_index = Math.floor(Math.random()*random_arr.length);
                        var random_face = random_arr[ramdom_index];
                        random_arr.splice(ramdom_index, 1);
                        // 如果是单身狗的下面一个情侣，移动到单身狗下面
                        if (i == singleLocation.offsetX && j == singleLocation.offsetY+1) {
                            var d = (device == "mobile") ? 80 : 120;
                            x = singleLocation.x/scale + getRandomOffset(10, false);
                            y = singleLocation.y/scale + d + getRandomOffset(5, true);
                        }
                        // 是否反转
                        var flip = Math.random() > 0.5 ? 1 : -1;
                        x = (flip == 1) ? x : (x+singleSize.width);
                        // 创建情侣
                        var person = persons.create(x, y, 'persons', random_face);
                        person.scale.x = flip;
                    }
                }
            }
            persons.scale.set(scale, scale);
        }

        // 获取情侣数组
        function getRandomArr() {
            var arr = [];
            for (var i = 0; i < 16; i++) {
                arr[i] = i+2;
            }
            return arr;
        }

        // 获取随机偏移值
        function getRandomOffset(offset, tag) {
            var x = Math.random()*offset;
            var y = Math.random() > 0.5 ? 1 : -1;
            var result = x * y;
            if (tag) {
                result = x;
            }
            return result;
        }

        // 重置人群
        function resetPersons() {
            persons.destroy(true, true);
            initPersons();
        }

        // 初始化人群
        function initPersons() {
            // 计算人群数量
            setPersonsNum();
            // 计算单身狗位置
            setSingleLocation();
            // 填充画布
            fillStage();
        }

        // 点击事件回调
        function onTap(pointer, doubleTap) {
            if (playing) {
                var x = pointer.x * 2;
                var y = pointer.y * 2;
                if (Math.abs(x-singleCenter.x) <= offsetRange && Math.abs(y-singleCenter.y) <= offsetRange) {
                    scoreCallback();
                }
            }
        }

        // 得分回调
        function scoreCallback() {
            // 播放动画和声效
            single.animations.play('found');
            audioWin.play();
            // 显示分数
            score += 1;
            dogeText.text = score;
            if (score == 10) {
                dogeText.x -= 19;
            }
            // 级别提升
            levelUp();
            // 显示圆圈
            showCircle();
            // 得分以后跳动
            game.add.tween(dogeText).to( { width: dogeText.width*1.2, height:dogeText.height*1.2 }, 100, Phaser.Easing.Linear.None, true, 0, 0, true);
            game.add.tween(iconDoge).to( { width: iconDoge.width*1.2, height:iconDoge.height*1.2 }, 100, Phaser.Easing.Linear.None, true, 0, 0, true);
            // 500毫秒以后到下一关
            setTimeout(function() {
                $("#circle").css("display", "none");
                resetPersons();
            }, 500);
        }

        // 显示圈圈
        function showCircle() {
            var circleSize = (device == "mobile") ? 50 : 50;
            var top = singleCenter.y/2 - circleSize;
            var left = singleCenter.x/2 - circleSize;
            $("#circle").css("top", top).css("left", left).css("display", "block");
        }

        // 等级提升
        function levelUp() {
            level++;
            if (level <= 2) { // 1-2
                scale = 1.0;
            } else if (level <= 3) { // 3
                scale = 0.9;
            } else if (level <= 5) { // 4-5
                scale = 0.8;
            } else if (level <= 7) { // 6-7
                scale = 0.7;
            } else if (level <= 9) { // 8-9
                scale = 0.6;
            } else if (level <= 20) { // 10-20
                scale = 0.6-0.22*(level-11)/11;
            } else { // 20以后
                scale = 0.38;
            }
            if (level >= 15) {
                hatTag = true;
            }
            console.log(scale);
        }

        // 计算奖牌
        function getBadge() {
            if (score < 8) {
                return 0;
            } else if (score < 17) {
                return 3;
            } else if (score < 21) {
                return 2;
            } else {
                return 1;
            }
        }
        function remaind(msg, position, duration) {
            if(!msg){
                var m=document.getElementById('core_show_div');
                var d = 0.2;
                m.style.webkitTransition = '-webkit-transform ' + d + 's ease-in, opacity ' + d + 's ease-in';
                m.style.opacity = '0';
                setTimeout(function() {
                    document.body.removeChild(m)
                }, d * 1000);
                return;
            }
            if(position!='bottom' && position!='middle' && position!='top'){
                position ='bottom';
            }

            duration = isNaN(duration) ? 1000 : duration;
            var m = document.createElement('div');
            m.id = 'core_show_div';
            m.innerHTML = msg;
            var css = "width:60%; font-size:14px;min-width:150px; background:#000; opacity:0.7; min-height:35px; overflow:hidden; color:#fff; line-height:35px; text-align:center; border-radius:5px; position:fixed; left:20%; z-index:999999;box-shadow:3px 3px 4px #d9d9d9;-webkit-box-shadow: 3px 3px 4px #d9d9d9;-moz-box-shadow: 3px 3px 4px #d9d9d9;";
            if(position=='top'){
                css+="top:10%; ";
            } else if(position=='bottom'){
                css+="bottom:10%; ";
            } else if(position=='middle'){
                css+="top:50%;margin-top:-18px;";
            }
            m.style.cssText = css;
            document.body.appendChild(m);
            if(duration!=0){
                setTimeout(function() {
                    var d = 0.2;
                    m.style.webkitTransition = '-webkit-transform ' + d + 's ease-in, opacity ' + d + 's ease-in';
                    m.style.opacity = '0';
                    setTimeout(function() {
                        document.body.removeChild(m)
                    }, d * 1000);
                }, duration);
            }

        }


        //检测手机号码是否合法
        function checkMobile(s){
            var regu =/^[1][3|8|4|5|7][0-9]{9}$/;
            var re = new RegExp(regu);
            if (re.test(s)) {
                return true;
            }else{
                return false;
            }
        }

        /*------------------------------------------------

         游戏的不同状态

         ------------------------------------------------*/

        // 创建游戏
        var game = new Phaser.Game(playground.width, playground.height, Phaser.AUTO, 'game-div');

        game.States = {};

        game.States.boot = function () {
            this.preload = function () {
                // 设置重玩函数
                setFunction(gameReplay);
                // 设置最高分数
                bestScore = max_score;

                // 停止监听页面可见性事件
                game.stage.disableVisibilityChange = true;

                // 加载加载时的素材
                game.load.image('progress-empty', '/game/style/images/assets/'+device+'/progress-empty.png');
                game.load.image('progress-fill', '/game/style/images/assets/'+device+'/progress-fill.png');
                game.load.image('pic-start', '/game/style/images/assets/'+device+'/pic-start.png');

                // 设置画布大小
                $(game.canvas).css("width", windowInnerWidth);
                $(game.canvas).css("height", windowInnerHeight);

                // ipad处理
                if (device == "pad") {
                    singleSize.width = 180;
                    singleSize.height = 180;
                    offsetRange = 90;
                }
            };
            this.create = function() {
                // 隐藏画面
                setTimeout(function() {
                    hiddenImg();
                }, 1500);
                game.state.start('preload');
            };
        }

        game.States.preload = function () {

            this.preload = function () {
                // 添加开始图片
                var pic_start = game.add.image(0, 0, 'pic-start');
                var ratio_temp = (device == "mobile") ? bgRatio_mobile : bgRatio_pad;
                if (ratio >= ratio_temp) {
                    pic_start.width = playground.width;
                    pic_start.height = playground.width/ratio_temp;
                } else {
                    pic_start.width = playground.height*ratio_temp;
                    pic_start.height = playground.height;
                    pic_start.x = -(pic_start.width - playground.width)/2;
                }
                console.log(pic_start.width, pic_start.height);

                // 显示进度条
                var progress_fill, progress_empty;
                if (device == "mobile") {
                    game.add.text((playground.width-96)/2, (playground.height-32)/2, '加载中', { fontSize: '32px', fill: '#000' });
                    progress_empty = game.add.sprite((playground.width-326)/2, (playground.height-70)/2+40, 'progress-empty');
                    progress_empty.width = 326;
                    progress_empty.height = 70;
                    progress_fill = game.add.sprite((playground.width-320)/2, (playground.height-60)/2+40, 'progress-fill');
                    progress_fill.width = 320;
                    progress_fill.height = 60;
                } else {
                    game.add.text((playground.width-144)/2, (playground.height-48)/2, '加载中', { fontSize: '48px', fill: '#000' });
                    progress_empty = game.add.sprite((playground.width-486)/2, (playground.height-110)/2+60, 'progress-empty');
                    progress_empty.width = 486;
                    progress_empty.height = 110;
                    progress_fill = game.add.sprite((playground.width-480)/2, (playground.height-90)/2+60, 'progress-fill');
                    progress_fill.width = 480;
                    progress_fill.height = 90;
                }

                game.load.setPreloadSprite(progress_fill);

                // 加载其他资源
                game.load.audio('sound_win', '/game/style/images/assets/audio/win.wav');
                game.load.audio('sound_bg', '/game/style/images/assets/audio/bg.mp3');
                game.load.image('bg-start', '/game/style/images/assets/'+device+'/bg-start.png');
                game.load.image('icon-doge', '/game/style/images/assets/icon-doge.png');
                game.load.image('icon-countDown', '/game/style/images/assets/'+device+'/bg-countDown.png');
                game.load.image('bg-score', '/game/style/images/assets/'+device+'/bg-score.png');
                if (device == "mobile") {
                    game.load.spritesheet('persons', '/game/style/images/assets/mobile/persons.png', 150, 300);
                } else {
                    game.load.spritesheet('persons', '/game/style/images/assets/pad/persons.png', 225, 450);
                }
            };

            this.create = function() {
                game.state.start('create');
            };
        }

        game.States.create = function () {
            this.create = function () {
                // 设置背景颜色
                game.stage.backgroundColor = "#336666";

                // 添加声效素材
                if (typeof(audioBg) == "undefined") {
                    audioBg = game.add.audio('sound_bg');
                }
                audioWin = game.add.audio('sound_win');

                // 添加背景
                var bg_start = game.add.image(0, 0, 'bg-start');
                var ratio_temp = (device == "mobile") ? bgRatio_mobile : bgRatio_pad;
                if (ratio >= ratio_temp) {
                    bg_start.width = playground.width;
                    bg_start.height = playground.width/ratio_temp;
                } else {
                    bg_start.width = playground.height*ratio_temp;
                    bg_start.height = playground.height;
                    bg_start.x = -(bg_start.width - playground.width)/2;
                }

                // 开始按钮
                $("#startButton").css("display", "block").css("left", (windowInnerWidth-180)/2).css("top", windowInnerHeight*4/6);
                $(".deam-menu").show();
                $("#startButton").on("tap", function(e) {
                    $.ajax({
                        url: '{php echo $this->createMobileUrl("index",array("id"=>$id))}',
                        dataType:"json",
                        data: {'op': 'checkgamedata'},
                        type: "post",
                        success:function(data) {
                            if(data.status == 1){
                                e.preventDefault();
                                e.stopPropagation();
                                gameStart();
                            }else if(data.status == 201 || data.status == 202){
                                $('#register').show();
                                return false;
                            }else{
                                remaind(data.result,'middle');
                            }
                        },
                        error: function(err) {
                            console.log('error:' + err);
                        }
                    });

                });
                //提交数据
                $('#deam-submit').on('tap',function(e){
                    var phone = $('#telphone').val();
                    var realname = $('#realname').val();
                    if(realname == ''){
                        remaind('姓名必填','middle');
                        return false;
                    }
                    if (phone == ''|| !checkMobile(phone)) {
                        remaind('请填写有效的号码','middle');
                        return false;
                    }
                    $.ajax({
                        url: '{php echo $this->createMobileUrl("index",array("id"=>$id))}',
                        dataType:"json",
                        data: {'op': 'register','telphone':phone,'realname':realname},
                        type: "post",
                        success:function(data) {
                            if(data.status){
                                $('#register').hide();
                                remaind('信息提交成功！','middle');
                                e.preventDefault();
                                e.stopPropagation();
                                gameStart();
                            }else{
                                remaind(data.result,'middle');
                                return false;
                            }
                        },
                        error: function(err) {
                            console.log('error:' + err);
                        }
                    });

                });
                // 暂停按钮
                $("#pauseButton").on("tap", function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    gamePause();
                });

                // 重玩按钮
                $("#pauseReplay").on("tap", function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    gameReplay();
                });

                // 继续按钮
                $("#pauseResume").on("tap", function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    gameResume();
                });


                $("#rotate-div").on("touchmove", function(e) {
                    e.preventDefault();
                });
            };
        }

        game.States.play = function () {
            this.create = function () {
                // 显示暂停按钮
                $("#pauseButton").css("display", "block");

                // 播放背景音乐
                if (!audioBg.isPlaying) audioBg.loopFull();

                // 创建人群
                persons = game.add.group();
                initPersons();

                if (device == "mobile") {
                    // 找到的单身狗个数
                    var bgScore = game.add.image(10, 20, 'bg-score');
                    iconDoge = game.add.image(28, 24, 'icon-doge');
                    iconDoge.width = 38;
                    iconDoge.height = 38;
                    dogeText = game.add.text(100, 24, '0', {fontSize: '38px', fill:"#FFFFFF"});
                    bestScoreText = game.add.text(90, 64, bestScore, {fontSize: '20px', fill:"#FFFFFF"});

                    // 剩余时间
                    iconCountDown = game.add.image((playground.width-60)/2, 20, 'icon-countDown');
                    iconCountDown.width = 60;
                    iconCountDown.height = 60;
                    countDownText = game.add.text((playground.width-40)/2, 28, '60', {fontSize: "40px", fill:"#FFFFFF"});
                    countDownText.setTextBounds(0, 0, 40, 40);
                } else {
                    // 找到的单身狗个数
                    var bgScore = game.add.image(15, 30, 'bg-score');
                    iconDoge = game.add.image(42, 36, 'icon-doge');
                    iconDoge.width = 57;
                    iconDoge.height = 57;
                    dogeText = game.add.text(150, 36, '0', {fontSize: '57px', fill:"#FFFFFF"});
                    bestScoreText = game.add.text(135, 96, bestScore, {fontSize: '30px', fill:"#FFFFFF"});

                    // 剩余时间
                    iconCountDown = game.add.image((playground.width-90)/2, 30, 'icon-countDown');
                    iconCountDown.width = 90;
                    iconCountDown.height = 90;
                    countDownText = game.add.text((playground.width-60)/2, 42, '60', {fontSize: "60px", fill:"#FFFFFF"});
                    countDownText.setTextBounds(0, 0, 60, 60);
                }
                countDownText.boundsAlignH = 'center';


                // 绑定点击事件
                game.input.onTap.add(onTap, this);
                game.input.onHold.add(onTap, this);

                // 启动计时器
                timer = game.time.create(false);
                timer.loop(1000, function() {
                    countDown--;
                    countDownText.text = countDown;
                    if (countDown == 0) {
                        // game.state.start('end');
                        gameEnd();
                    } else if (countDown == 10) {
                        if (device == "mobile") {
                            game.add.tween(iconCountDown).to( { width: 70, height: 70, x: (playground.width-70)/2, y:15 }, 300, Phaser.Easing.Linear.None, true, 0, Number.MAX_VALUE, true);
                        } else {
                            game.add.tween(iconCountDown).to( { width: 100, height: 100, x: (playground.width-100)/2, y:30 }, 300, Phaser.Easing.Linear.None, true, 0, Number.MAX_VALUE, true);
                        }
                    }
                }, this);
                timer.start();
            };
        }
        game.state.add('boot',game.States.boot);
        game.state.add('preload',game.States.preload);
        game.state.add('create',game.States.create);
        game.state.add('play',game.States.play);
        game.state.start('boot');

        /*------------------------------------------------

         监听横竖屏变化

         ------------------------------------------------*/

        window.addEventListener("orientationchange", orientationChanged, false);

        function orientationChanged() {
            if(window.orientation==180 || window.orientation==0){ // 竖屏状态
                $(game.canvas).css("width", windowInnerWidth);
                $(game.canvas).css("height", windowInnerHeight);
                $("#rotate-div").css("display", "none");
            } else if (window.orientation==90 || window.orientation==-90) {
                $("#rotate-div").css("display", "-webkit-box");
                gamePause();
            }
        }
    })();
</script>

<!-- 结束页面 -->
<div class="over-box">

    <div class="data-box">
        <div class="pic-box">
            <img class="over-bg" src="/game/style/images/over-box.png" alt="">
            <img class="badge" src="/game/style/images/badge_1.png">
            <div class="left-box">
                <span class="badge-text"></span>
            </div>
            <div class="right-box">
                <div class="label-score">得分</div>
                <div class="score"></div>
                <!-- <div class="label-max-score">最高分</div> -->
                <!-- <div class="max-score"></div> -->
            </div>
            <span class="data-text"></span>
        </div>

    </div>
    <div class="menu">
        <span class="play-again"><img src="/game/style/images/playagain.png" alt=""></span>
        <span class="rankBtn"><img src="/game/style/images/rank.png" alt=""></span>
    </div>
</div>

<!-- 背景蒙版 -->
<div class="background-box"></div>
<!-- 排行榜组件 -->
<div class="rank-box">
    <div class="rank" style="height: 756px; overflow-y: auto;">
        <div class="close"></div>
        <div class="myself">
            <h3>我的排名</h3>
            <div class="data-box">
                <span class="rank-num"></span>
               {{-- <span class="headImg"><img src="{media $memberinfo['avatar']}" alt=""></span>--}}
                <div class="data">
                    <span class="name"></span>
                    <span class="score"></span>
                    <span class="win"></span>
                </div>
            </div>
        </div>
        <div class="player-list">
            <h3 class="player-num"></h3>
            <ol id="player-box"></ol>
        </div>
    </div>
</div>

<!-- 分享文案 -->
<div class="share-text">
    点击右上角
</div>
<script type="text/javascript">

    var gameId = '1';
    var user_result = '{"rank":257,"persent":72,"score":"15"}';
    var max_score = '{$maxScore}';
    var max_badge = '3';
    var borderColor = '#000';
    if(max_badge == 1) {
        borderColor = '#f8e71c';
    } else if(max_badge == 2) {
        borderColor = '#DCDFE3';
    } else  if(max_badge == 3) {
        borderColor = '#BA6E40';
    }
    $(function() {
        /*var game_time = '11.10 周二';
         function init() {
         var str = '';
         if(user_result != 'null') {
         //存在历史记录
         user_result = JSON.parse(user_result);
         max_score = user_result['score'];
         str += '<span class="foot-headImg" style="background-image: url(' + headImgUrl + ');border-color:' + borderColor +'"></span>';
         str += '<span class="max-score">最高分:' + max_score + '</span>';
         if(user_result['persent'] != 0) {
         str += '<span class="win">打败了' + user_result['persent'] + '%的人</span>';
         }
         $('.foot-box').append(str);
         } else {
         //不存在历史记录
         $('.foot').css('display','none');
         // $('.center-box .right-box').css({'right':'50%','transform':'translateX(50%)'});
         }
         }
         init();*/



        //排行榜出现与隐藏交互
        var winH = window.innerHeight;
        $('.rank-box .rank').css({"height":winH-70,"overflow-y":"auto"});
        //排行榜隐藏
        $('.close').on('tap',function() {

            $('.rank-box').animate({"margin-top":"20px","opacity":0},200,'ease-in',function(){
                var bgbox = $('.background-box');
                if($('.over-box').css('display') == 'block') {
                    bgbox.css('z-index','65');
                } else {
                    bgbox.css({'display':'none','z-index':'65'});
                }
                $('.rank-box').css('display','none');
            });
        });
        //排行榜出现
        $('.rankBtn').on('tap',function() {
            addRankBox(gameId);
            // $('.rank-box').css('display','block').removeClass('hide-box').addClass('appear-box');
            $('.background-box').css({'z-index':$('.rank-box').css('z-index')-1,'display':'block'});
            $('.rank-box').css('display','block').animate({"margin-top":"40px","opacity":1},200,'ease-in');
        });
        $('.ruleBtn').on('tap',function() {
            $('#deamrule').css('display','block');
        });
        $('.btn-close').on('tap',function() {
            $('.attention').css('display','none');
        });
        //排行榜ajax接口
        function addRankBox(gameId) {
            $.ajax({
                url: '{php echo $this->createMobileUrl("index",array("id"=>$id))}',
                dataType:"json",
                data: {'op': 'getrank'},
                type: "post",
                success:function(data) {
                    if (data.status) {
                        data = data.result;
                        var user_result = data.user_result;
                        var rank_list = data.rank_list;
                        user_result.avatar = user_result.avatar ? user_result.avatar : '/game/style/images/getheadimg.jpg';
                        user_result.nickname = user_result.nickname ? user_result.nickname : user_result.telphone;
                        $('.player-num').text('共' + user_result.total_count + '名玩家');
                        //我的排名
                        $('.myself .rank-num').text(user_result.rank);
                        $('.myself .headImg img').attr('src',user_result.avatar);
                        $('.myself .name').text(user_result.nickname);
                        $('.myself .score').text(user_result.maxscore).css('top','40%');
                        //$('.myself .win').text('前' + (100-parseInt(user_result.percent)) + '%').css('bottom','20%');
                        //玩家排名列表
                        var str = '';
                        for(var i=0,len=rank_list.length;i<len;i++) {
                            var obj = rank_list[i];
                            obj.avatar = obj.avatar ? obj.avatar : '/game/style/images/getheadimg.jpg';
                            obj.nickname = obj.nickname ? obj.nickname : obj.telphone;
                            str += '<li><div class="data-box"><span class="rank-num">' + (i+1) + '</span>';
                            str += '<span class="headImg"><img src="' + obj.avatar + '" alt=""></span>';
                            str += '<div class="data"><span class="name">' + obj.nickname + '</span>';
                            str += '<span class="score">' + obj.maxscore +'</span></div></div></div>';
                        }
                        $('.player-list ol').empty().append(str);

                    }
                },
                error: function(err) {
                    console.log('error:' + err);
                }
            });
        }
        //分享引导
        //分享出现
        $('.share').on('tap',function(){
            $('.background-box').css({'z-index':$('.share-text').css('z-index')-1,'display':'block'});
            $('.share-text').css('display','block');
        });
        //分享隐藏
        $('.share-text').on('tap', function(){
            $(this).css('display','none');
            $('.background-box').css({'z-index':'65'});
        });

        //再玩一次事件触发
        $('.play-again').on('tap',function(){
            againFun && againFun();
            $('.background-box').hide();
            $('.over-box').hide();
        });
    });
    //开始游戏隐藏上下标签的接口
    function hiddenLabel() {
        $('.foot').animate({'bottom':-$('.foot').height()},500,'ease-in');
    }
    //微信分享
    function wxReady(text){
        wx.ready(function () {
            wx.onMenuShareTimeline({
                title: text, // 分享标题
                link: '', // 分享链接
                imgUrl: "{media $arr['share_img']}", // 分享图标
                desc: "{$arr['share_desc']}",
                success: function () {
                    // 用户确认分享后执行的回调函数
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                }
            });
            //分享到朋友
            wx.onMenuShareAppMessage({
                title: text, // 分享标题
                desc: "{$arr['share_desc']}", // 分享描述
                link: '', // 分享链接
                imgUrl: "{media $arr['share_img']}", // 分享图标
                type: '', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success: function () {
                    // 用户确认分享后执行的回调函数
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                }
            });
        });
    }
    {if $arr['is_jssdk']}
    wxReady("{$shareTitle}");
    {/if}
    //再玩一次游戏接口
    var againFun;
    function setFunction(obj) {
        againFun = obj || null;
    }

    //提交分数ajax接口
    var currentScore = 0;
    var tips =  [
        "别再说自己是单身狗了，你这个年纪，狗已经死了。",
        "单身狗也是狗，秀恩爱属于虐狗行为。",
        "“你喜欢猫呢还是狗呢?”<br/>“喜欢狗”<br/>“汪汪~”",
        "跑到喜欢的女生面前躺下对她说“同学，你男朋友掉地上了。”",
        "“诶同学，我怎么越看你越像我下一任女友耶。”"
    ];

    function setScore(score,badge) {
        $('.over-box .data-text').html(tips[Math.floor(Math.random()*5)]);
        currentScore = score;
        {if $arr['is_jssdk']}
        var textNone = "{$arr['share_title']}";
        if(textNone.indexOf("{SCORE}")>0){
            var text = textNone.split("\{SCORE\}")[0] + currentScore +textNone.split("\{SCORE\}")[1];
        }else{
            var text = textNone;
        }
        {/if}
        if(currentScore > max_score){//当前分数大于历史最高分

            $.ajax({
                url: '{php echo $this->createMobileUrl("index",array("id"=>$id))}',
                dataType:"json",
                data: {'op': 'submitscore','score':currentScore},
                type: "post",
                success:function(data) {

                },
                error: function(err) {
                    console.log('error:' + err);
                }
            });
            {if $arr['is_jssdk']}
            wxReady(text);
            {/if}
        }


        //if(parseInt(score) == 0) {
//				var text = '快来聚集单身狗力量，寻找节日主角！';
//			} else {
//				var text = '我找到了' + currentScore +'只单身狗，快来聚集单身狗力量，寻找节日主角！'
//			}

        var borderColor = '#000';
        if(badge != 0) {
            $('.over-box .topbox').prepend('<img src="/game/style/images/badge_' + badge + '.png">');
        }

        //将狗头根据分数修改，将
        if(badge == 1) {
            borderColor = '#f8e71c';
            $('.badge-text').text('金牌');
            $('.over-box .badge').attr('src','/game/style/images/badge_1.png');
        } else if(badge == 2) {
            borderColor = '#DCDFE3';
            $('.badge-text').text('银牌');
            $('.over-box .badge').attr('src','/game/style/images/badge_2.png');
        } else  if(badge == 3) {
            borderColor = '#BA6E40';
            $('.badge-text').text('铜牌');
            $('.over-box .badge').attr('src','/game/style/images/badge_3.png');
        } else {
            $('.over-box .badge').attr('src','/game/style/images/badge_3.png');
        }
        $('.over-box .score').text(score);
        $('.background-box').show();
        $('.over-box').show();

    }


    function hiddenImg() {
        $('.start-img').animate({'opacity':0},500,'ease-in',function(){
            console.log('11');
            $('.start-img').css('display','none');
        });
    }

    $('.more-game').on('tap',function(){

    });
</script>

</body>
</html>
