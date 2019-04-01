@extends('layouts.admin.list')
@section('header')
@endsection
@section('btn')
@endsection
@section('action')
    {{url('manage/noValidate')}}
@endsection
@section('form_content')
    <div class="col-md-2">
        <div class="input-group">
            <span class="input-group-addon">手机号查询</span>
            <input type="text" name="phone" placeholder="请输入手机号查询" class="input-sm form-control">
        </div>
    </div>
@endsection
@section('table_head')
    <th>ID</th>
    <th>用户昵称</th>
    <th>头像</th>
    <th>性别</th>
    <th>是否关注</th>
    <th>免验证手机号</th>
    <th>操作</th>
@endsection
