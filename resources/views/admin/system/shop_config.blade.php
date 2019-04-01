<!DOCTYPE html>
<html>
<head>
    @include('layouts.admin.header')
    @include('plugins.validate')
    @include('plugins.upload')
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>基本配置</h5>
                </div>
                <div class="ibox-content">
                    <form class="form-horizontal m-t" id="wxSetForm" method="post" action="{{url('manage/shopSet')}}">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">网站名称：</label>
                            <div class="col-sm-8">
                                <input name="{{\App\Model\Config::$CONFIG_CODE_SHOP_NAME}}" class="form-control" type="text" value="{{$config[\App\Model\Config::$CONFIG_CODE_SHOP_NAME]}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">网站描述：</label>
                            <div class="col-sm-8">
                                <input name="{{\App\Model\Config::$CONFIG_CODE_SHOP_DESC}}" class="form-control" type="text" value="{{$config[\App\Model\Config::$CONFIG_CODE_SHOP_DESC]}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">网站关键字：（用逗号隔开）</label>
                            <div class="col-sm-8">
                                <input name="{{\App\Model\Config::$CONFIG_CODE_SHOP_KEYWORDS}}" class="form-control" type="text" value="{{$config[\App\Model\Config::$CONFIG_CODE_SHOP_KEYWORDS]}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">站点logo：</label>
                            <div class="col-sm-8">
                                <div id="filePicker"></div>
                                <div id="preview">
                                    @if(!empty($config[\App\Model\Config::$CONFIG_CODE_SHOP_LOGO]))
                                        <img src="{{imgShow($config[\App\Model\Config::$CONFIG_CODE_SHOP_LOGO],true)}}" style="height: 100px;" class="upload_qrcode"/>
                                    @endif
                                </div>
                                <input name="{{\App\Model\Config::$CONFIG_CODE_SHOP_LOGO}}" class="form-control" type="hidden" value="{{$config[\App\Model\Config::$CONFIG_CODE_SHOP_LOGO]}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-3">
                                <button class="btn btn-primary" type="submit">提交</button>
                            </div>
                        </div>
                    </form>
                    <script>
                        $.validator.setDefaults({
                            highlight: function(e) {
                                $(e).closest(".form-group").removeClass("has-success").addClass("has-error")
                            },
                            success: function(e) {
                                e.closest(".form-group").removeClass("has-error").addClass("has-success")
                            },
                            errorElement: "span",
                            errorPlacement: function(e, r) {
                                e.appendTo(r.is(":radio") || r.is(":checkbox") ? r.parent().parent().parent() : r.parent())
                            },
                            submitHandler: function() {
                                $("#wxSetForm").ajaxSubmit({
                                    dataType : "json",
                                    success : ajaxCallback,
                                    error: errorCallback
                                });

                            },
                            errorClass: "help-block m-b-none",
                            validClass: "help-block m-b-none"
                        }), $().ready(function() {
                            var e = "<i class='fa fa-times-circle'></i> ";
                            $("#wxSetForm").validate({
                                rules: {
                                    "{{\App\Model\Config::$CONFIG_CODE_SHOP_NAME}}": "required",
                                    "{{\App\Model\Config::$CONFIG_CODE_SHOP_DESC}}": "required",
                                    "{{\App\Model\Config::$CONFIG_CODE_SHOP_KEYWORDS}}": "required",
                                    "{{\App\Model\Config::$CONFIG_CODE_SHOP_LOGO}}": "required"
                                },
                                messages: {
                                    "{{\App\Model\Config::$CONFIG_CODE_SHOP_NAME}}": e + "请输入站点名称",
                                    "{{\App\Model\Config::$CONFIG_CODE_SHOP_DESC}}": e + "请输入网站描述",
                                    "{{\App\Model\Config::$CONFIG_CODE_SHOP_KEYWORDS}}": e + "请输入关键字",
                                    "{{\App\Model\Config::$CONFIG_CODE_SHOP_LOGO}}": e + "请上传网站logo"
                                }
                            })
                        });
                        function uploadSuccess(file,response){
                            $("#preview").html('<img src="'+storagePath+'/'+response.img_thumb+'" style="height: 100px;" class="upload_qrcode"/>');
                            $("[name={{\App\Model\Config::$CONFIG_CODE_SHOP_LOGO}}]").val(response.img);
                        }
                        $("#filePicker").imgUpload('点击选择','logo',uploadSuccess);
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
