<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>rentDIY</title>
<meta name="keywords" content="rentDIY">
<meta name="description" content="rentDIY">
<script>
    /*全局变量定义*/
    var uploadPath = "{{url('api/imageUploader')}}";  //图片上传地址
    @if(config('upload.driver') == 'local')
        var storagePath = "{{asset('storage')}}";     //资源存放地址
    @elseif(config('upload.driver') == 'oss')
        var storagePath = "{{config('upload.oss.imgUrl')}}";     //资源存放地址
    @endif

</script>
<link rel="shortcut icon" href="favicon.ico">
<link href="/admin/css/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
<link href="/admin/css/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
<link href="/admin/css/animate.min.css" rel="stylesheet">
<link href="/admin/css/style.min862f.css?v=4.1.0" rel="stylesheet">
<script src="/admin/js/jquery.min.js?v=2.1.4"></script>
<script src="/admin/js/bootstrap.min.js?v=3.3.6"></script>
<script src="/admin/js/content.min.js?v=1.0.0"></script>
<script src="/admin/js/plugins/jquery-ui/jquery-ui.min.js"></script>
<link href="/admin/css/plugins/toastr/toastr.min.css" rel="stylesheet">
<script src="/admin/js/plugins/toastr/toastr.min.js"></script>
<script src="/admin/js/jquery_form.js"></script>
<link href="/admin/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
<script src="/admin/js/plugins/dataTables/jquery.dataTables.js"></script>
<script src="/admin/js/plugins/dataTables/dataTables.bootstrap.js"></script>
<script src="/admin/js/plugins/layer/layer.min.js"></script>
<script src="/admin/js/jquery.pagination.min.js"></script>
<script src="/admin/js/admin_common.js"></script>
<link href="/admin/css/admin_common.css" rel="stylesheet">