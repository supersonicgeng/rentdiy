<!DOCTYPE html>
<html>


<!-- Mirrored from www.zi-han.net/theme/hplus/form_validate.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:19:15 GMT -->
<head>
    @include('layouts.admin.header')
    @include('plugins.validate')
</head>

<body class="gray-bg">

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <form class="form-horizontal m-t" id="wxSetForm" method="post"
                          action="{{url('manage/edit_management',[$info->group_id])}}">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">组织名称：</label>
                            <div class="col-sm-2">
                                <input name="name" class="form-control" type="text" value="{{$info->users->name}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">登录账号：</label>
                            <div class="col-sm-2">
                                <input name="email" class="form-control" type="text" value="{{$info->users->email}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">手机号：</label>
                            <div class="col-sm-2">
                                <input name="phone" class="form-control" type="text" value="{{$info->users->phone}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">密码：</label>
                            <div class="col-sm-2">
                                <input name="password" class="form-control" type="password">
                            </div>
                        </div>
                        <div class="form-group flex-align-items-center">
                            <label class="col-sm-3 control-label my-common">账号状态：</label>
                            <div class="col-sm-9">
                                @foreach(\App\User::$IS_FORBIDDEN_TEXT as $k=>$v)
                                    <label class="checkbox-inline i-checks"><input
                                                {{$info->users->is_forbidden == $k?'checked':''}} type="radio"
                                                name="is_forbidden"
                                                value="{{$k}}">{{$v}}</label>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-3">
                                <a class="btn btn-white"  href="{{url('manage/management')}}">返回</a>
                                <button style="margin-left: 20px;" class="btn btn-primary" type="submit">提交</button>
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
                                    beforeSubmit: beforeAjax,
                                    dataType: "json",
                                    success: ajaxCallback,
                                    error: errorCallback
                                });

                            },
                            errorClass: "help-block m-b-none",
                            validClass: "help-block m-b-none"
                        });
                        jQuery.validator.addMethod("isMobile", function (value, element) {
                            var length = value.length;
                            var mobile = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
                            return this.optional(element) || (length == 11 && mobile.test(value));
                        }, "请正确填写您的手机号码");
                        $().ready(function () {
                            var e = "<i class='fa fa-times-circle'></i> ";
                            $("#wxSetForm").validate({
                                rules: {
                                    name: {
                                        required: true,
                                        maxlength: 10
                                    },
                                    email: {
                                        required: true,
                                    },
                                    phone: {
                                        required: true,
                                        isMobile: true
                                    },
                                    password: "required",
                                    is_forbidden: 'required'
                                },
                                messages: {
                                    name: {
                                        required: e + "请输入车牌号码",
                                        maxlength: e + "姓名太长"
                                    },
                                    email: {
                                        required: e + "请输入邮箱",
                                    },
                                    phone: {
                                        required: e + "请输入手机号",
                                        isMobile: e + "手机号格式不对"
                                    },
                                    password: e + "请输入密码",
                                    is_forbidden: e + "请输选择"
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
</html>
