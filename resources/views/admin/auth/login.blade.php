<!DOCTYPE html>
<html>


<!-- Mirrored from www.zi-han.net/theme/hplus/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:18:23 GMT -->
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>rentDIY - login</title>
    <meta name="keywords" content="rentDIY">
    <meta name="description" content="rentDIY">

    <link rel="shortcut icon" href="favicon.ico">
    <link href="/admin/css/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
    <link href="/admin/css/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">

    <link href="/admin/css/animate.min.css" rel="stylesheet">
    <link href="/admin/css/style.min862f.css?v=4.1.0" rel="stylesheet">
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html"/>
    <![endif]-->
    <script>if (window.top !== window.self) {
            window.top.location = window.location;
        }</script>
</head>

<body class="gray-bg">

<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div>

            {{--<h2 class="logo-name">rentDIY</h2>--}}

        </div>
        <h3>welcome rentDIY</h3>

        <form id="login_form" class="m-t" role="form" action="{{ url('manage/login') }}">
            {{ csrf_field() }}
            <div class="form-group">
                <input type="text" name="email" class="form-control" placeholder="username" required="">
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="password" required="">
            </div>
            <button type="submit" class="btn btn-primary block full-width m-b">login</button>


            {{--<p class="text-muted text-center"><a href="{{ url('password.request') }}">--}}
                    {{--<small>忘记密码了？</small>--}}
                {{--</a> | <a href="{{ url('register') }}">注册一个新账号</a>--}}
            {{--</p>--}}

        </form>
    </div>
</div>
<script src="/admin/js/jquery.min.js?v=2.1.4"></script>
<script src="/admin/js/bootstrap.min.js?v=3.3.6"></script>
<script src="/admin/js/plugins/layer/layer.min.js"></script>
<script>
    $("#login_form").submit(function () {
        var index = layer.load(2, {
            shade: [0.1, '#000'] //0.1透明度的白色背景
        });
        $.ajax({
            url: "{{url('manage/loginAction')}}",
            data: $("#login_form").serialize(),
            dataType: 'json',
            type: 'post',
            success: function (result) {
                layer.close(index);
                if(result.Success){
                    layer.msg(result.Message,{icon:6,time:1000},function(){
                        location.href = result.re_dir_url;
                    });
                }else{
                    layer.msg(result.Message, {icon: 5});
                }
            },
            error: function (error) {
                layer.close(index);
                console.log(error);
            }
        });
        return false;
    })
</script>
</body>

<!-- Mirrored from www.zi-han.net/theme/hplus/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:18:23 GMT -->
</html>
