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
                    <form class="form-horizontal m-t" id="wxSetForm" method="post" action="{{url('manage/member/qrCode')}}">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">设置客服二维码：</label>
                            <div class="col-sm-8">
                                <div id="filePicker"></div>
                                <div id="preview">
                                    @if(!empty($qrcode))
                                        <img src="{{imgShow($qrcode)}}" style="height: 200px;" class="upload_qrcode"/>
                                    @endif
                                </div>
                                <input name="qrcode" class="form-control" type="hidden" value="{{$qrcode}}">
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
                                    qrcode: "required",
                                },
                                messages: {
                                    qrcode: e + "请上传客服二维码",
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
