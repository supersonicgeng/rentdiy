@extends('layouts.admin.list')
@section('btn')
    <a href="{{url('manage/usersAdd')}}" class="btn btn-white btn-sm"><i class="fa fa-plus"></i>新增</a>
@endsection
@section('action')
    {{url('manage/users')}}
@endsection
@section('form_content')
    <div class="col-md-2">
        <div class="input-group">
            <span class="input-group-addon">账号名称</span>
            <input type="text" name="name" placeholder="请输入账号名称查询" class="input-sm form-control">
        </div>
    </div>
    <div class="col-md-2">
        <div class="input-group">
            <span class="input-group-addon">手机号</span>
            <input type="text" name="phone" placeholder="请输入手机号查询" class="input-sm form-control">
        </div>
    </div>
@endsection
@section('table_head')
    <th>账号名称</th>
    <th>登陆账号</th>
    <th>手机号</th>
    <th>角色</th>
    <th>操作</th>
@endsection