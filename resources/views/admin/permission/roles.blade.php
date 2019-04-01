@extends('layouts.admin.list')
@section('header')
@endsection
@section('btn')
@endsection
@section('action')
    {{url('manage/roles')}}
@endsection
@section('form_content')
    <div class="col-md-2">
        <div class="input-group">
            <span class="input-group-addon">角色名称</span>
            <input type="text" name="display_name" placeholder="请输入角色名称查询" class="input-sm form-control">
        </div>
    </div>
@endsection
@section('table_head')
    <th>角色编号</th>
    <th>角色名称</th>
    <th>角色描述</th>
    <th>操作</th>
@endsection
