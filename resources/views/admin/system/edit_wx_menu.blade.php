<!DOCTYPE html>
<html>
<head>
    @include('layouts.admin.header')
    @include('layouts.admin.dialog')
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-8">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <form class="form-horizontal m-t" id="wxSetForm" method="post" action="{{url('manage/editWxMenu',['key1'=>$key1,'key2'=>$key2])}}">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">菜单名称：</label>
                            <div class="col-sm-6">
                                <input name="name" class="form-control" type="text" value="{{$info['name']}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">父级菜单：</label>
                            <div class="col-sm-6">
                                <select class="form-control" name="parent" disabled>
                                    <option value="-1" @if($key2 == -1) selected @endif>顶级菜单</option>
                                    @if(!empty($top_menu['button']))
                                        @foreach($top_menu['button'] as $k=>$top)
                                            <option value="{{$k}}" @if($key2 != -1 && $k == $key1) selected @endif>{{$top['name']}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">菜单类型：</label>
                            <div class="col-sm-6">
                                <select class="form-control m-b" name="type">
                                    <option value="view" @if(@$info['type'] == 'view') selected @endif>打开网页</option>
                                    <option value="scancode_push" @if(@$info['type'] == 'scancode_push') selected @endif>扫一扫</option>
                                    <option value="expand" @if(empty($info['type'])) selected @endif>展开二级</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">链接URL：</label>
                            <div class="col-sm-8">
                                <input name="url" class="form-control" type="text" value="{{@$info['url']}}">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
