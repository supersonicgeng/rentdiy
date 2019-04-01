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
                    <form class="form-horizontal m-t" id="wxSetForm" method="post" action="{{url('manage/wishEdit',[$info->id])}}">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">心愿标题：</label>
                            <div class="col-sm-2">
                                <input name="title" class="form-control" type="text" value="{{$info->title}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">展示图(选填)：</label>
                            <div class="col-sm-8">
                                <div id="filePicker"></div>
                                <div id="preview">
                                    @if(!empty($info->pic))
                                    <img src="{{imgShow($info->pic)}}" style="height: 200px;" class="upload_qrcode"/>
                                    @endif
                                </div>
                                <input name="pic" class="form-control" type="hidden" value="{{$info->pic}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">姓名：</label>
                            <div class="col-sm-2">
                                <input name="username" class="form-control" type="text" value="{{$info->username}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">过期时间：</label>
                            <div class="col-sm-2">
                                <input name="expired_at" class="form-control datetimepicker1" type="text" value="{{$info->expired_at}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">心愿描述：</label>
                            <div class="col-sm-9">
                                <textarea name="wish_desc" id="wish_desc">{!! $info->wish_desc !!}</textarea>
                            </div>
                        </div>
                        @include('plugins.cityPicker',['title'=>'收货地址','province'=>'province','city'=>'city','county'=>'county','default_province'=>$info['province'],'default_city'=>$info['city'],'default_county'=>$info['county']])
                        <div class="form-group">
                            <label class="col-sm-3 control-label">详细地址：</label>
                            <div class="col-sm-6">
                                <input name="address" class="form-control" type="text" value="{{$info->address}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">联系方式：</label>
                            <div class="col-sm-2">
                                <input name="phone" class="form-control" type="text" value="{{$info->phone}}">
                            </div>
                        </div>
                        @if(!request()->get('group'))
                            <div class="form-group">
                                <label class="col-sm-3 control-label">审核状态：</label>
                                <div class="col-sm-8">
                                    @foreach(\App\Model\Wish::$STATUS_TEXT as $k=>$v)
                                        <label class="checkbox-inline i-checks"><input type="radio" name="status" value="{{$k}}" @if($info->status == $k) checked @endif />{{$v['text']}}</label>
                                    @endforeach
                                </div>
                            </div>
                        @endif
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
                                    title: "required",
                                    username: "required",
                                    expired_at: "required",
                                    province: "required",
                                    city: "required",
                                    county: "required",
                                    address: "required",
                                    phone: {
                                        required:true,
                                        isMobile: true
                                    }
                                },
                                messages: {
                                    title: e + "请输入心愿标题",
                                    username: e + "请输入姓名",
                                    expired_at: e + "请输入过期时间",
                                    province: e + "请输入地址",
                                    city: e + "请输入地址",
                                    county: e + "请输入地址",
                                    address: e + "请输入地址",
                                    phone: {
                                        required:e + "请输入联系方式",
                                        isMobile:e + "手机号格式不正确",
                                    },
                                }
                            })
                        });
                        $("#filePicker").imgUpload('点击上传', 'goods_brand', function (file, response) {
                            $("#preview").html('<img src="' + storagePath + '/' + response.img + '" style="height: 200px;" class="upload_qrcode"/>');
                            $("[name=pic]").val(response.img);
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
