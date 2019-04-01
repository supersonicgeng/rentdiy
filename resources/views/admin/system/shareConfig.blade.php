<!DOCTYPE html>
<html>
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
                    <h5>分享配置</h5>
                </div>
                <div class="ibox-content">
                    <form class="form-horizontal m-t" id="wxSetForm" method="post" action="{{url('manage/shareConfig')}}">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">分享标题：</label>
                            <div class="col-sm-2">
                                <input name="{{\App\Model\Config::$CONFIG_GROUP_SHARE_TITLE}}" class="form-control" type="text" value="{{$info['title']}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">分享描述：</label>
                            <div class="col-sm-2">
                                <input name="{{\App\Model\Config::$CONFIG_GROUP_SHARE_DESC}}" class="form-control" type="text" value="{{$info['desc']}}">

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">分享图片：</label>
                            <div class="col-sm-2">
                                <div id="filePicker"></div>
                                <div id="preview">
                                @if($info['img'])
                                        <img src="{{$info['img']}}" style="width: 100px" alt="">
                                @endif
                                </div>
                                <input name="{{\App\Model\Config::$CONFIG_GROUP_SHARE_IMG}}" class="form-control" type="hidden" value="{{$info['img']}}" id="address">


                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-3">
                                <button class="btn btn-primary" type="submit" onClick="uptext();">提交</button>
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

                                },
                                messages: {

                                }
                            })
                        });
                        var file_index = 0;
                        $("#filePicker").imgUpload('点击上传', 'goods_album', function (file, response) {
                            file_index++;
                            $("#preview").html('<div class="img_box" findex="'+file_index+'"><img src="' + storagePath + '/' + response.img + '" style="width: 100px;"> <a href="javascript:;" class="btn btn-white btn-sm del"><i class="fa fa-close"></i></a></div>');
                            $("#address").val( storagePath + '/' + response.img );

                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
