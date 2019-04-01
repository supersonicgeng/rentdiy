@extends('layouts.admin.list')
@section('header')
@endsection
@section('btn')
    <a href="{{url('manage/add_level')}}" class="btn btn-white btn-sm"><i class="fa fa-plus"></i> 新增 </a>
@endsection
@section('action')
    {{url('manage/level_list')}}
@endsection
@section('form_content')
    <div class="col-md-2">
        <div class="input-group">
            <span class="input-group-addon">等级名称</span>
            <input type="text" name="level_name" placeholder="输入等级名称模糊搜索" class="input-sm form-control">
        </div>
    </div>
@endsection
@section('table_head')
    <th>ID</th>
    <th>心愿等级</th>
    <th>等级名称</th>
    <th>等级最低积分</th>
    <th>等级最高积分</th>
    <th>操作</th>
@endsection