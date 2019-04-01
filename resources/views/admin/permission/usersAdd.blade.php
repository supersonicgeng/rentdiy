<!DOCTYPE html>
<html>


<!-- Mirrored from www.zi-han.net/theme/hplus/form_validate.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:19:15 GMT -->
<head>
    @include('layouts.admin.header')
    @include('plugins.validate')
    @include('plugins.select2')
</head>

<body class="gray-bg">

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <form class="form-horizontal m-t" id="wxSetForm" method="post" action="{{url('manage/usersAdd')}}">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">员工姓名：</label>
                            <div class="col-sm-2">
                                <input name="name" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">邮箱：</label>
                            <div class="col-sm-2">
                                <input name="email" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">手机号：</label>
                            <div class="col-sm-2">
                                <input name="phone" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">密码：</label>
                            <div class="col-sm-2">
                                <input name="password" class="form-control" type="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">绑定微信：</label>
                            <div class="col-sm-2">
                                <select id="passport" name="passport_id">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">选择角色：</label>
                            <div class="col-sm-2">
                                <select class="form-control" name="role_id">
                                    @foreach($roles as $role)
                                        <option value="{{$role->id}}">{{$role->display_name}}</option>
                                    @endforeach
                                </select>
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
                        });
                        jQuery.validator.addMethod("isMobile", function(value, element) {
                            var length = value.length;
                            var mobile = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
                            return this.optional(element) || (length == 11 && mobile.test(value));
                        }, "请正确填写您的手机号码");
                        $().ready(function() {
                            var e = "<i class='fa fa-times-circle'></i> ";
                            $("#wxSetForm").validate({
                                rules: {
                                    name: {
                                        required:true,
                                        maxlength:10
                                    },
                                    email: {
                                        required:true,
                                        email:true
                                    },
                                    phone: {
                                        required:true,
                                        isMobile: true
                                    },
                                    password: "required",
                                },
                                messages: {
                                    name: {
                                        required:e + "请输入车牌号码",
                                        maxlength:e + "姓名太长"
                                    },
                                    email:{
                                        required:e + "请输入邮箱",
                                        email:e + "邮箱格式不对"
                                    },
                                    phone: {
                                        required:e + "请输入手机号",
                                        isMobile: e + "手机号格式不对"
                                    },
                                    password:e + "请输入密码",
                                }
                            })
                        });
                        $('#passport').select2({
                            width: '280px',
                            ajax: {
                                url: "{{url('getAllPassport')}}",
                                dataType: 'json',
                                data: function (params) {
                                    return {
                                        search: params.term,
                                        pageNumber: params.page || 1,
                                        pageSize: 30
                                    };
                                },
                                processResults: function (data, params) {
                                    var ndata = [];
                                    data.items.forEach(function (v) {
                                        ndata.push({
                                            id: v.passport_id,
                                            nickname: v.nickname,
                                            headimgurl: v.headimgurl
                                        });
                                    });
                                    params.page = params.page || 1;
                                    return {
                                        results: ndata,
                                        pagination: {
                                            more: data.incomplete_results
                                        }
                                    }
                                }
                            },
                            templateResult: function (repo) {
                                if (repo.loading) {
                                    return repo.text;
                                }
                                var node = $(
                                        '<div><img style="width: 20px;height: 20px;" src="' + repo.headimgurl + '" class="img-flag" /> ' + repo.nickname + '</div>'
                                );
                                return node;
                            },
                            templateSelection: function (repo) {
                                if (!repo.id) {
                                    return repo.text;
                                }
                                return repo.nickname;
                            },
                            placeholder: '请选择'
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
