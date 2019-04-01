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
                    <h5>提现配置</h5>
                </div>
                <div class="ibox-content">
                    <form class="form-horizontal m-t" id="wxSetForm" method="post" action="{{url('manage/finance/financeSet')}}">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">最低提现额度：</label>
                            <div class="col-sm-3">
                                <input name="{{\App\Model\Config::$CONFIG_CODE_FI}}" class="form-control" type="text" value="{{$info[\App\Model\Config::$CONFIG_CODE_FI]}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">提现频率：</label>
                            <div class="col-sm-3">
                                <input name="{{\App\Model\Config::$CONFIG_CODE_FI_T}}" class="form-control" type="text" value="{{$info[\App\Model\Config::$CONFIG_CODE_FI_T]}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">提现手续费(%)：</label>
                            <div class="col-sm-3">
                                <input name="{{\App\Model\Config::$CONFIG_CODE_FI_RATE}}" class="form-control" type="text" value="{{$info[\App\Model\Config::$CONFIG_CODE_FI_RATE]}}">
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
                                    "{{\App\Model\Config::$CONFIG_CODE_FI}}": "required",
                                    "{{\App\Model\Config::$CONFIG_CODE_FI_T}}": "required",
                                    "{{\App\Model\Config::$CONFIG_CODE_FI_RATE}}": {
                                        required:true,
                                        digits:true,
                                        range:[0,100]
                                    },
                                },
                                messages: {
                                    "{{\App\Model\Config::$CONFIG_CODE_FI}}": e + "请输入最低提现额度",
                                    "{{\App\Model\Config::$CONFIG_CODE_FI_T}}": e + "请输入提现频率",
                                    "{{\App\Model\Config::$CONFIG_CODE_FI_RATE}}": {
                                        required:e + "请输入提现手续费",
                                        digits:e + "提现手续费必须为整数",
                                        range:e + "提现手续费必须在0到100之间",
                                    },
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
