<!DOCTYPE html>
<html>
<head>
    @include('layouts.admin.header')
    @include('plugins.validate')
    <style>
        #wish_desc {
            width: 400px;
            height: 300px;
        }
    </style>
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <form class="form-horizontal m-t" id="edit_form" method="post" action="{{url('manage/add_level')}}">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">等级级别：</label>
                            <div class="col-sm-2">
                                <input name="level" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">等级名称：</label>
                            <div class="col-sm-2">
                                <input name="level_name" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">最低积分：</label>
                            <div class="col-sm-2">
                                <input name="min_score" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">最高积分：</label>
                            <div class="col-sm-2">
                                <input name="max_score" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-3">
                                <a class="btn btn-white"  href="{{url('manage/level_list')}}">返回</a>
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
                                $("#edit_form").ajaxSubmit({
                                    beforeSubmit: beforeAjax,
                                    dataType: "json",
                                    success: ajaxCallback,
                                    error: errorCallback
                                });

                            },
                            errorClass: "help-block m-b-none",
                            validClass: "help-block m-b-none"
                        });
                        $().ready(function () {
                            var e = "<i class='fa fa-times-circle'></i> ";
                            $("#edit_form").validate({
                                rules: {
                                    level: {
                                        required: true,
                                        number: true
                                    },
                                    level_name: {
                                        required: true,
                                        maxlength: 10
                                    },
                                    min_score: {
                                        required: true,
                                        digits: true
                                    },
                                    max_score: {
                                        required: true,
                                        digits: true
                                    }
                                },
                                messages: {
                                    level: {
                                        required: e + '请输入等级',
                                        number: '请输入整数'
                                    },
                                    level_name: {
                                        required: e + '请输入等级名称',
                                        maxlength: '名字最长不得超过10个字符'
                                    },
                                    min_score: {
                                        required: e + '请输入最低分数',
                                        digits: '请输入正整数'
                                    },
                                    max_score: {
                                        required: e + '请输入最高分数',
                                        digits: '请输入正整数'
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
</div>
</body>
</html>
