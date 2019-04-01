<!DOCTYPE html>
<html>


<!-- Mirrored from www.zi-han.net/theme/hplus/form_validate.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:19:15 GMT -->
<head>
    @include('layouts.admin.header')
    @include('plugins.icheck')
</head>

<body class="gray-bg">

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <form class="form-horizontal m-t" id="wxSetForm" method="post" action="{{url('manage/rolesPermission',[$id])}}">
                        @foreach($permissions as $permission)
                        <div class="form-group">
                            <label class="col-sm-3 control-label"><input type="checkbox" value="{{$permission['id']}}" name="permissions[]" class="i-checks main"  @if(in_array($permission['id'],$role_permission)) checked @endif >{{$permission['display_name']}}：</label>
                            @if(!empty($permission['_child']))
                            <div class="col-sm-6">
                                @foreach($permission['_child'] as $permission2)
                                <label class="checkbox-inline"><input type="checkbox" value="{{$permission2['id']}}" name="permissions[]" class="i-checks childp"  @if(in_array($permission2['id'],$role_permission)) checked @endif >{{$permission2['display_name']}}</label>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endforeach
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-3">
                                <button class="btn btn-primary" type="button" id="submit">提交</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    $('.main').on('ifChecked', function(event){ //ifCreated 事件应该在插件初始化之前绑定
        $(this).parent().parent().parent().find('.childp').iCheck('check');
    });
    $('.main').on('ifUnchecked', function(event){ //ifCreated 事件应该在插件初始化之前绑定
        $(this).parent().parent().parent().find('.childp').iCheck('uncheck');
    });
    $("#submit").click(function(){
        $("#wxSetForm").ajaxSubmit({
            beforeSubmit: beforeAjax,
            dataType : "json",
            success : ajaxCallback,
            error:errorCallback
        });
    })
</script>
</html>
