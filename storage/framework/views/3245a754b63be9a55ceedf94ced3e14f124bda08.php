<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>聚好卖 | Starter</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="/dist/admin/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/dist/admin/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/dist/admin/bower_components/Ionicons/css/ionicons.min.css">

    <link rel="stylesheet" href="/dist/admin/css/AdminLTE.min.css">
    <link rel="stylesheet" type="text/css" href="/dist/admin/login/toastr.min.css">

</head>
<body class="hold-transition lockscreen">
<!-- Automatic element centering -->
<div class="lockscreen-wrapper">
    <div class="lockscreen-logo">
        <a href="../../index2.html"><b>聚好麦</b></a>
    </div>
    <!-- User name -->
    <div class="lockscreen-name"><?php echo e(auth()->user()->real_name); ?></div>

    <!-- START LOCK SCREEN ITEM -->
    <div class="lockscreen-item">
        <!-- lockscreen image -->
        <div class="lockscreen-image">
            <img src="<?php echo e(auth()->user()->avatar ?? '/avatar.png'); ?>" alt="User Image">
        </div>
        <!-- /.lockscreen-image -->

        <!-- lockscreen credentials (contains the form) -->
        <form class="lockscreen-credentials" method="post" action="<?php echo e(route('admin.lock.login')); ?>">
            <?php echo csrf_field(); ?>
            <div class="input-group">
                <input type="password" name="password" class="form-control" placeholder="password">

                <div class="input-group-btn">
                    <button type="submit" class="btn"><i class="fa fa-arrow-right text-muted"></i></button>
                </div>
            </div>
        </form>
        <!-- /.lockscreen credentials -->

    </div>
    <!-- /.lockscreen-item -->
    <div class="help-block text-center">
        请输入你的密码解锁
    </div>
    
        
    
    
        
        
    
</div>
<!-- /.center -->

<!-- jQuery 3 -->
<script src="/dist/admin/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="/dist/admin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/dist/admin/login/toastr.min.js"></script>
<script>
    $(document).ready(function(e) {
        var counter = 0;
        if (window.history && window.history.pushState) {
            $(window).on('popstate', function () {
                window.history.pushState('forward', null, '#');
                window.history.forward(1);
                //alert("不可回退");
                location.replace(document.referrer);//刷新
            });
        }

        window.history.pushState('forward', null, '#'); //在IE中必须得有这两行
        window.history.forward(1);
    });

</script>
<?php echo $__env->make('layouts.admin._flash', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
</body>
</html>
