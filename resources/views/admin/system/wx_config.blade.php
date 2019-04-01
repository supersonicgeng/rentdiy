<!DOCTYPE html>
<html>


<!-- Mirrored from www.zi-han.net/theme/hplus/form_validate.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:19:15 GMT -->
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
                    <h5>微信公众号配置</h5>
                </div>
                <div class="ibox-content">
                    <form class="form-horizontal m-t" id="wxSetForm" method="post" action="{{url('manage/wxConfig')}}">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">URL：</label>
                            <div class="col-sm-8">
                                <input name="url" class="form-control" type="text" value="{{$config['url']?:''}}" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">token：</label>
                            <div class="col-sm-8">
                                <input name="token" class="form-control" type="text" value="{{$config['token']}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">公众号名称：</label>
                            <div class="col-sm-8">
                                <input name="name" class="form-control" type="text" value="{{$config['name']}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">encodingaeskey：</label>
                            <div class="col-sm-8">
                                <input name="encodingaeskey" class="form-control" type="text" value="{{$config['encodingaeskey']}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">appid：</label>
                            <div class="col-sm-8">
                                <input name="appid" class="form-control" type="text" value="{{$config['appid']}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">secret：</label>
                            <div class="col-sm-8">
                                <input name="secret" class="form-control" type="text" value="{{$config['secret']}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">二维码：</label>
                            <div class="col-sm-8">
                                <div id="filePicker"></div>
                                <div id="preview">
                                    @if(!empty($config['qrcode']))
                                        <img src="{{imgShow($config['qrcode'])}}" style="height: 200px;" class="upload_qrcode"/>
                                    @endif
                                </div>
                                <input name="qrcode" class="form-control" type="hidden" value="{{$config['qrcode']}}">
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
                                    beforeSubmit: beforeAjax,
                                    dataType : "json",
                                    success : ajaxCallback,
                                    error:errorCallback
                                });

                            },
                            errorClass: "help-block m-b-none",
                            validClass: "help-block m-b-none"
                        }), $().ready(function() {
                            var e = "<i class='fa fa-times-circle'></i> ";
                            $("#wxSetForm").validate({
                                rules: {
                                    url: "required",
                                    token: "required",
                                    name: "required",
                                    wx_num: "required",
                                    original_id: "required",
                                    encodingaeskey: "required",
                                    appid: "required",
                                    secret: "required",
                                    qrcode: "required",
                                },
                                messages: {
                                    url: e + "请输入url",
                                    token: e + "请输入token",
                                    name: e + "请输入公众号名字",
                                    wx_num: e + "请输入微信号",
                                    original_id: e + "请输入original_id",
                                    encodingaeskey: e + "请输入encodingaeskey",
                                    appid: e + "请输入appid",
                                    secret: e + "请输入secret",
                                    qrcode: e + "请上传公众号二维码",
                                }
                            })
                        });
                        function uploadSuccess(file,response){
                            $("#preview").html('<img src="'+storagePath+'/'+response.img+'" style="height: 200px;" class="upload_qrcode"/>');
                            $("[name=qrcode]").val(response.img);
                        }
                        $("#filePicker").imgUpload('点击选择图片','qrcode',uploadSuccess);
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
