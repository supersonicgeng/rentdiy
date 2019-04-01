<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="format-detection" content="telephone=no, email=no">
    <title>{{@$title?:'商城'}}</title>
    <link rel="stylesheet" href="/index/css/style.css">
    <script src="/index/js/jquery.min.js"></script>
    <style>
        body,html {
            background-color: #F0F0F0;
        }
        .container{
            width: 100%;
        }
        a{
            text-decoration: none;
            color: #000000;
        }
    </style>
    @yield('style')
</head>
<body>
@yield('container')
</body>
<script src="/admin/js/plugins/layer/layer.min.js"></script>
<script src="/index/js/main.js"></script>
<script>
    layer.config({extend: 'extend/layer.ext.js'});
</script>
@yield('script')
</html>
