<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>聚好卖 | Starter</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/dist/admin/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/dist/admin/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/dist/admin/bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/vendor/select2/dist/css/select2.css">

    <link rel="stylesheet" href="/dist/admin/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect. -->


    <link rel="stylesheet" href="/dist/admin/css/skins/skin-blue.min.css">
    <link rel="stylesheet" href="/dist/admin/plugins/iCheck/minimal/blue.css">
    <link rel="stylesheet" type="text/css" href="/dist/admin/login/toastr.min.css">
    <link rel="stylesheet" href="/vendor/zoom/css/zoom.css">
  
    <?php echo $__env->yieldContent('css'); ?>
</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <!-- Main Header -->
   <?php echo $__env->make('layouts.admin._header', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <!-- Left side column. contains the logo and sidebar -->
    <?php echo $__env->make('layouts.admin._sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <!-- Content Wrapper. Contains page content -->
    <!-- /.content-wrapper -->
     <?php echo $__env->yieldContent('content'); ?>
    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
            Anything you want
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; <?php echo e(date('Y',time())); ?> <a href="#">Company</a>.</strong> All rights reserved.
    </footer>

    <div class="control-sidebar-bg"></div>
</div>

<!-- jQuery 3 -->
<script src="/dist/admin/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="/dist/admin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<script src="/vendor/select2/dist/js/select2.js"></script>



<script src="/dist/admin/js/adminlte.min.js"></script>
<script src="/dist/admin/js/nprogress.js"></script>
<script src="/dist/admin/layer/layer.js"></script>
<script src="/dist/admin/plugins/iCheck/icheck.min.js"></script>
<script type="text/javascript" src="/dist/admin/login/toastr.min.js"></script>
<script src="/vendor/zoom/js/zoom.js"></script>

<script src="/vendor/html5-fileupload/jquery.html5-fileupload.js"></script>
<script src="/layDate/laydate.js"></script>
<script src="/dist/admin/js/upload.js"></script>
<script src="/dist/admin/js/common.js"></script>


<script>
    $(document).ready(function(){
        $('input').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue',
            increaseArea: '20%' // optional
        });


    });
</script>
<?php echo $__env->make('layouts.admin._flash', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php echo $__env->yieldContent('js'); ?>
</body>
</html>