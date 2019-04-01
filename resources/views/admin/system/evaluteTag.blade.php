@extends('layouts.admin.list')
@section('btn')
    <a href="{{url('manage/evaluteTagAdd')}}" class="btn btn-white btn-sm" target="dialog" shade='0.1' height="400px" width="500px" btn="确定:doUpdate,取消" title="新增评价标签"><i class="fa fa-plus"></i>新增</a>
@endsection
@section('action')
    {{url('manage/evaluteTag')}}
@endsection
@section('form_content')
    <div class="col-md-2">
        <div class="input-group">
            <span class="input-group-addon">标签</span>
            <input type="text" name="tag" placeholder="请输入标签模糊查询" class="input-sm form-control">
        </div>
    </div>
    <div class="col-md-2">
        <div class="input-group">
            <span class="input-group-addon">标签类型</span>
            <select class="form-control" name="type">
                <option value="">全部</option>
                @foreach(\App\Model\Evaluate::$TYPE as $k=>$v)
                    <option value="{{$k}}">{{$v}}</option>
                @endforeach
            </select>
        </div>
    </div>
@endsection
@section('table_head')
    <th>ID</th>
    <th>标签</th>
    <th>类型</th>
    <th>删除</th>
@endsection
@section('script')
    function doUpdate(){
        var child = layer.getChildFrame('body');
        child.find("#wxSetForm").ajaxSubmit({
            beforeSubmit: beforeAjax,
            dataType : "json",
            success : ajaxCallback,
            error:errorCallback
        })
    }
@endsection