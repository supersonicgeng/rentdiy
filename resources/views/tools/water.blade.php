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
                    <h5>图片水印处理</h5>
                </div>
                <div class="ibox-content">
                    <form class="form-horizontal m-t" id="wxSetForm" method="post" action="{{url('water')}}">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">选择要水印的图：</label>
                            <div class="col-sm-8">
                                <div id="filePicker1"></div>
                                <div id="preview1">

                                </div>
                                <input name="background" class="form-control" type="hidden">
                            </div>
                        </div>
                        {{csrf_field()}}
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
                            errorClass: "help-block m-b-none",
                            validClass: "help-block m-b-none"
                        }), $().ready(function() {
                            var e = "<i class='fa fa-times-circle'></i> ";
                            $("#wxSetForm").validate({
                            })
                        });
                        $("#filePicker1").imgUpload('选择','background',function(file,response){
                            $("#preview1").html('<img src="'+storagePath+'/'+response.img+'" style="height: 200px;" class="upload_qrcode"/>');
                            $("[name=background]").val(response.img);
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
