<!DOCTYPE html>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>登陆</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" type="text/css" href="/dist/admin/login/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/dist/admin/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/dist/admin/login/AdminLTE.min.css">

</head>
<body class="hold-transition login-page" style="height:auto;">

<div class="cover">
    <div id="particles-after-filter">

        <canvas class="particles-js-canvas-el" width="1903" height="468" style="width: 100%; height: 100%;"></canvas>
    </div>
    <div id="particles">

        <canvas class="particles-js-canvas-el" width="1903" height="468" style="width: 100%; height: 100%;"></canvas>
    </div>
</div>

<div class="login-box">
    <div class="login-logo"><a href="javascript:;">Rental platform</a></div>
    <div class="login-box-body">
        <p class="login-box-msg">Please input your username and password</p>
        <form action="<?php echo e(route('login')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="username" value="<?php echo e(old('username')); ?>" placeholder="UserName">
                <span class="glyphicon form-control-feedback fa fa-user fa-lg"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="password" placeholder="PassWord">
                <span class="glyphicon form-control-feedback fa fa-lock fa-lg"></span>
            </div>
            <div class="row form-group">
                <div class="col-xs-6"><input class="form-control" name="captcha" placeholder="VerifyCode"></div>
                <div class="col-xs-4">
                    <img src="<?php echo e(captcha_src()); ?>" id="code" alt="captcha" onclick="this.src='<?php echo e(captcha_src()); ?>&'+Math.random()">
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-danger btn-block"
                            data-loading-text="&lt;i class=&#39;fa fa-spinner fa-spin &#39;&gt;&lt;/i&gt; 登录">Login
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="/dist/admin/login/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="/dist/admin/login/bootstrap.min.js"></script>
<script type="text/javascript" src="/dist/admin/login/jquery.form.js"></script>
<link rel="stylesheet" type="text/css" href="/dist/admin/login/toastr.min.css">
<script type="text/javascript" src="/dist/admin/login/toastr.min.js"></script>
<link rel="stylesheet" type="text/css" href="/dist/admin/login/particles.css">
<script type="text/javascript" src="/dist/admin/login/particles.min.js"></script>
<script type="text/javascript" src="/dist/admin/login/login.js"></script>

<!--错误提示信息-->
<?php if(count($errors) > 0): ?>
    <script type="text/javascript">
        toastr.options = {
            closeButton: true,                  //是否显示关闭按钮
            debug: false,                       //是否使用debug模式
            progressBar: true,                  //是否显示进度条
            positionClass: "toast-top-center",   //弹出窗的位置
            showDuration: "300",                //显示动作时间
            preventDuplicates: true,            //提示框只出现一次
            hideDuration: "300",                //隐藏动作时间
            timeOut: "3000",                    //自动关闭超时时间
            extendedTimeOut: "1000",            ////加长展示时间
            showEasing: "swing",                //显示时的动画缓冲方式
            hideEasing: "linear",               //消失时的动画缓冲方式
            showMethod: "fadeIn",               //显示时的动画方式
            hideMethod: "fadeOut"               //消失时的动画方式
        };

        toastr.warning('<?php echo e($errors->first()); ?>');
    </script>
<?php endif; ?>

<?php if(session('alert')): ?>
    <script type="text/javascript">

        toastr.options = {
            closeButton: true,                  //是否显示关闭按钮
            debug: false,                       //是否使用debug模式
            progressBar: true,                  //是否显示进度条
            positionClass: "toast-top-center",   //弹出窗的位置
            showDuration: "300",                //显示动作时间
            preventDuplicates: true,            //提示框只出现一次
            hideDuration: "300",                //隐藏动作时间
            timeOut: "3000",                    //自动关闭超时时间
            extendedTimeOut: "1000",            ////加长展示时间
            showEasing: "swing",                //显示时的动画缓冲方式
            hideEasing: "linear",               //消失时的动画缓冲方式
            showMethod: "fadeIn",               //显示时的动画方式
            hideMethod: "fadeOut"               //消失时的动画方式
        };
        toastr.error('<?php echo e(session('alert')); ?>');
    </script>
<?php endif; ?>
</body>
</html>