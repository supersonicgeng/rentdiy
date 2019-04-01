<!DOCTYPE html>
<html>
<head>
    @include('layouts.admin.header')
    @include('plugins.validate')
    @include('plugins.upload')
    @include('plugins.datetimepicker')
    @include('plugins.ueditor')
    @include('plugins.select2')
    @include('plugins.icheck')
    <style>
        #wish_desc{
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
                    <form class="form-horizontal m-t" id="wxSetForm" method="post" action="{{url('manage/order/edit',[$info->id])}}">
                        <div class="form-group">
                            <label class="col-sm-3 control-label my-common">心愿标题：</label>
                            <div class="col-sm-2">
                                <input class="form-control" type="text" disabled value="{{$info->wishes->title}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label my-common">订单号：</label>
                            <div class="col-sm-2">
                                <input name="order_num" class="form-control" type="text" value="{{$info->order_num}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label my-common">订单状态：</label>
                            <div class="col-sm-2">
                                <select name="order_status" class="form-control">
                                    @foreach(\App\Model\Order::$ORDER_STATUS_TEXT as $k=>$v)
                                        <option {{$info->order_status == $k?'selected':''}} value="{{$k}}">{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label my-common">送货类型：</label>
                            <div class="col-sm-2">
                                <select name="type" class="form-control">
                                    @foreach(\App\Model\Order::$TYPE_TEXT as $k=>$v)
                                        <option {{$info->type == $k?'selected':''}} value="{{$k}}">{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label my-common">物流单号：</label>
                            <div class="col-sm-2">
                                <input name="logistics" class="form-control" type="text" value="{{$info->logistics}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label my-common">联系人：</label>
                            <div class="col-sm-2">
                                <input name="name" class="form-control" type="text" value="{{$info->name}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label my-common">联系电话：</label>
                            <div class="col-sm-2">
                                <input name="phone" class="form-control" type="text" value="{{$info->phone}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-3">
                                <button class="btn btn-primary" type="submit">提交</button>
                            </div>
                        </div>
                    </form>
                    <script>
                        $(".datetimepicker1").datepicker({
                            keyboardNavigation: !1,
                            forceParse: true,
                            autoclose: true,
                            todayHighlight:true,
                            startDate:"0d",
                            endDate:'+7d'
                        });
                        var ue = UE.getEditor('wish_desc',{
                            textarea:'wish_desc',
                            initialFrameWidth:600,
                            initialFrameHeight:200,
                        });
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
                        jQuery.validator.addMethod("isMobile", function(value, element) {
                            var length = value.length;
                            var mobile = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
                            return this.optional(element) || (length == 11 && mobile.test(value));
                        }, "请正确填写您的手机号码");
                        $().ready(function () {
                            var e = "<i class='fa fa-times-circle'></i> ";
                            $("#wxSetForm").validate({
                                rules: {
                                    order_status: "required",
                                    order_num: "required",
                                    type: "required",
                                    name: "required",
                                    phone: {
                                        required:true,
                                        isMobile: true
                                    }
                                },
                                messages: {
                                    order_status: e + "请选择订单状态",
                                    order_num: e + "订单号不能为空",
                                    type: e + "请选择送货类型",
                                    name: e + "联系人不得为空",
                                    phone: {
                                        required:e + "联系方式不得为空",
                                        isMobile:e + "手机号格式不正确",
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

