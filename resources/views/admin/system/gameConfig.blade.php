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
                    <h5>游戏配置</h5>
                </div>
                <div class="ibox-content">
                    <form class="form-horizontal m-t" id="wxSetForm" method="post" action="{{url('manage/gameConfig')}}">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">游戏时长：</label>
                            <div class="col-sm-2">
                                <input name="{{\App\Model\Config::$CONFIG_GROUP_GAME_TIME}}" class="form-control" type="text" value="{{$info['time']}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label ">最高难度设置：</label>
                            <div class="col-sm-2">
                                <select  class="selectpicker form-control" data-style="btn-info" name="{{\App\Model\Config::$CONFIG_GROUP_GAME_LEVEL}}" id="">
                                    @for($i=1; $i<=10; $i++)
                                        <option value="{{$i}}" @if($i == $info['level'])selected="selected"@endif>{{$i}}</option>
                                    @endfor
                                </select>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">关卡初始分：</label>
                            <div class="col-sm-2">
                                <input name="{{\App\Model\Config::$CONFIG_GROUP_GAME_PASS_SCORE}}" class="form-control" type="text" value="{{$info['passScore']}}">

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">关卡递增分：</label>
                            <div class="col-sm-2">
                                <input name="{{\App\Model\Config::$CONFIG_GROUP_GAME_ADD_SCORE}}" class="form-control" type="text" value="{{$info['addScore']}}">

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">游戏说明：</label>
                            <div class="col-sm-8">
                                <script id="container" name="{{\App\Model\Config::$CONFIG_GROUP_GAME_INSTRUCTION}}" type="text/plain">{!! $info['instruction'] !!}</script>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-3">
                                <button class="btn btn-primary" type="submit" onClick="uptext();">提交</button>
                            </div>
                        </div>
                    </form>
                    <!-- ueditor配置文件 -->
                    <script type="text/javascript" src="/vendor/ueditor/ueditor.config.js"></script>
                    <!-- 编辑器源码文件 -->
                    <script type="text/javascript" src="/vendor/ueditor/ueditor.all.js"></script>
                    <script>
                        var ue = UE.getEditor('container');


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
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
