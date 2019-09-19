<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>聚好卖 | Starter</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/dist/admin/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/dist/admin/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/dist/admin/bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/dist/admin/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect. -->
    <link rel="stylesheet" href="/dist/admin/css/skins/skin-blue.min.css">
    <link rel="stylesheet" href="/dist/admin/plugins/iCheck/minimal/blue.css">
    <link rel="stylesheet" type="text/css" href="/dist/admin/login/toastr.min.css">
    <link rel="stylesheet" href="/vendor/zoom/css/zoom.css">

    @yield('css')
</head>

<body class="hold-transition skin-blue sidebar-mini">


@yield('content')



<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="/dist/admin/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="/dist/admin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="/dist/admin/js/adminlte.min.js"></script>
<script src="/dist/admin/js/nprogress.js"></script>
<script src="/dist/admin/layer/layer.js"></script>
<script src="/dist/admin/plugins/iCheck/icheck.min.js"></script>
<script type="text/javascript" src="/dist/admin/login/toastr.min.js"></script>
<script src="/vendor/zoom/js/zoom.js"></script>
<script src="/dist/admin/js/common.js"></script>
<script src="/layDate/laydate.js"></script>

<script>
    $(document).ready(function(){
        $('input').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
@include('layouts.admin._flash')

@yield('js')
</body>
</html>