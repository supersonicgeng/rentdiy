<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>新增权限</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/layui/css/layui.css" media="all">

</head>
<body>

<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
    <legend>编辑菜单与权限</legend>
</fieldset>


<form class="layui-form" action="">
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">上级菜单</label>
            <div class="layui-input-inline">
                <select name="parent_id">
                    <option value="0">无上级</option>
                    @foreach($permissions as $permisson)
                        <option value="{{$permisson->id}}" @if($per->parent_id == $permisson->id) selected @endif>{{$permisson->label}}</option>

                        @foreach($permisson->children as $child)
                            <option value="{{$child->id}}" @if($child->id == $per->parent_id) selected @endif>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp|— {{$child->label}}</option>

                        @endforeach
                    @endforeach

                </select>
            </div>
        </div>

    </div>


    <div class="layui-form-item layui-col-xs8">
        <label class="layui-form-label">权限名称</label>
        <div class="layui-input-block">
            <input type="text" name="label" value="{{$per->label}}" lay-verify="label" autocomplete="off"
                   placeholder="请输入权限名称"
                   class="layui-input">
        </div>
    </div>
    <div class="layui-form-item layui-col-xs8">
        <label class="layui-form-label">路由地址</label>
        <div class="layui-input-block">
            <input type="text" name="name" value="{{$per->name}}" lay-verify="name" autocomplete="off"
                   placeholder="请输入路由名称"
                   class="layui-input">
        </div>
    </div>
    <div class="layui-form-item layui-col-xs8">
        <label class="layui-form-label">图标icon</label>
        <div class="layui-input-block">
            <input type="text" name="icon" value="{{$per->icon}}" lay-verify="icon" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item layui-col-xs8">
        <label class="layui-form-label">排序</label>
        <div class="layui-input-block">
            <input type="text" name="sort_order" value="{{$per->sort_order}}" lay-verify="required" autocomplete="off"
                   class="layui-input">
        </div>
    </div>




    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>

<script src="/layui/layui.js" charset="utf-8"></script>
<script src="/dist/admin/js/jquery-3.3.1.min.js"></script>

<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    layui.use(['form', 'layedit', 'laydate'], function () {
        var form = layui.form
            , layer = layui.layer
            , layedit = layui.layedit
            , laydate = layui.laydate;

        //日期
        laydate.render({
            elem: '#date'
        });
        laydate.render({
            elem: '#date1'
        });


        //自定义验证规则
        form.verify({
            label: function (value) {
                if (value.length == 0) {
                    return '名称必须填写';
                }
            },
            name: function (value) {
                if (value.length == 0) {
                    return '权限路由地址必须填写';
                }
            },

            // ,pass: [/(.+){6,12}$/, '密码必须6到12位']
            // ,content: function(value){
            //     layedit.sync(editIndex);
            // }
        });

        //监听提交
        form.on('submit(demo1)', function (data) {

            $.ajax({
                type: 'PUT',
                url: '{{route('system.permission.update',$per->id)}}',
                data: data.field,
                dataType:'json',
                success: function (info) {
                    if (info.status == 1) {
                        layer.msg(info.msg, {
                            icon: 6,
                            time: 2000 //2秒关闭（如果不配置，默认是3秒）
                        });
                        top.location.reload();
                    } else {
                        layer.msg(info.msg, {
                            icon: 5,
                            time: 2500 //2.5秒关闭（如果不配置，默认是3秒）
                        });
                    }

                }

            })
            return false;
        });


    });
</script>

</body>
</html>