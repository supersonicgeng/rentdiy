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
                <div class="ibox-title">
                    <h5>支付配置</h5>
                </div>
                <div class="ibox-content">
                    <form class="form-horizontal m-t" id="wxSetForm" method="post" action="{{url('manage/paySet')}}">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">商户号：</label>
                            <div class="col-sm-3">
                                <input name="{{\App\Model\Config::$CONFIG_CODE_MCHID}}" class="form-control" type="text" value="{{$info[\App\Model\Config::$CONFIG_CODE_MCHID]}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">商户密钥：</label>
                            <div class="col-sm-3">
                                <input name="{{\App\Model\Config::$CONFIG_CODE_KEY}}" class="form-control" type="text" value="{{$info[\App\Model\Config::$CONFIG_CODE_KEY]}}">
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
                                    "{{\App\Model\Config::$CONFIG_CODE_MCHID}}": "required",
                                    "{{\App\Model\Config::$CONFIG_CODE_KEY}}": "required",
                                },
                                messages: {
                                    "{{\App\Model\Config::$CONFIG_CODE_MCHID}}": e + "请输入商户号",
                                    "{{\App\Model\Config::$CONFIG_CODE_KEY}}": e + "请输入商户密钥",
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
