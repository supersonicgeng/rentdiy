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
                <div class="ibox-content">
                    <form class="form-horizontal m-t" id="wxSetForm" method="post"
                          action="{{url('manage/editPwdAction')}}">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">原密码1122：</label>
                            <div class="col-sm-8">
                                <input name="opassword" class="form-control" type="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">新密码：</label>
                            <div class="col-sm-8">
                                <input name="npassword" id="npassword" class="form-control" type="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">确认密码：</label>
                            <div class="col-sm-8">
                                <input name="cpassword" class="form-control" type="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-3">
                                <button class="btn btn-primary" type="submit">提交</button>
                                <button onclick="close_edit()" class="btn btn-warning" type="button">关闭</button>
                            </div>

                        </div>
                    </form>
                    <script>
                        $.validator.setDefaults({
                            highlight: function (e) {
                                $(e).closest(".form-group").removeClass("has-success").addClass("has-error")
                            },
                            success: function (e) {
                                e.closest(".form-group").removeClass("has-error").addClass("has-success")
                            },
                            errorElement: "span",
                            errorPlacement: function (e, r) {
                                e.appendTo(r.is(":radio") || r.is(":checkbox") ? r.parent().parent().parent() : r.parent())
                            },
                            submitHandler: function () {
                                $("#wxSetForm").ajaxSubmit({
                                    dataType: "json",
                                    success: function (res) {
                                        console.log(res);
                                        var index = parent.layer.getFrameIndex(window.name);
                                        if (res.code == 0) {
                                            layer.msg(res.msg, {time: 1000}, function () {
                                                parent.layer.close(index);
                                            });
                                        }
                                    },
                                    error: errorCallback
                                });

                            },
                            errorClass: "help-block m-b-none",
                            validClass: "help-block m-b-none"
                        }), $().ready(function () {
                            var e = "<i class='fa fa-times-circle'></i> ";
                            $("#wxSetForm").validate({
                                rules: {
                                    opassword: "required",
                                    npassword: "required",
                                    cpassword: {
                                        required: true,
                                        equalTo: "#npassword"
                                    }
                                },
                                messages: {
                                    opassword: e + "请输入旧密码",
                                    npassword: e + "请输入新密码",
                                    cpassword: {
                                        required: '请确认新密码',
                                        equalTo: '两次密码输入不一致'
                                    }
                                }
                            })
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    function close_edit() {
        var index = parent.layer.getFrameIndex(window.name);
        parent.layer.close(index);
    }
</script>
</html>
