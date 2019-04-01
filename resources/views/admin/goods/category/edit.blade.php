<!DOCTYPE html>
<html>
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
                    <form class="form-horizontal m-t" id="wxSetForm" method="post" action="{{url('manage/goodsCategoryEdit',[$info->id])}}">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">分类名：</label>
                            <div class="col-sm-2">
                                <input name="title" class="form-control" type="text" value="{{$info->title}}">
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
                        $().ready(function () {
                            var e = "<i class='fa fa-times-circle'></i> ";
                            $("#wxSetForm").validate({
                                rules: {
                                    title: "required",
                                },
                                messages: {
                                    title: e + "请输入分类名称",
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
