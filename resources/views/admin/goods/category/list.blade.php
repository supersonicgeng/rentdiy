@extends('layouts.admin.list')
@section('header')
    @include('plugins.datetimepicker')
@endsection
@section('btn')
    <a href="{{url('manage/goodsCategoryAdd')}}" class="btn btn-white btn-sm"><i class="fa fa-plus"></i>新增</a>
@endsection
@section('action')
    {{url('manage/goodsCategory')}}
@endsection
@section('form_content')
    <div class="col-md-2">
        <div class="input-group">
            <span class="input-group-addon">标题</span>
            <input type="text" name="title" placeholder="请输入标题模糊查询" class="input-sm form-control">
        </div>
    </div>
    <div class="col-md-3">
        <div class="input-group">
            <div class="input-daterange input-daterange input-group datetimepicker" id="datepicker">
                <span class="input-group-addon">创建时间</span>
                <input type="text" class="input-sm form-control" name="create_at_start"/>
                <span class="input-group-addon">到</span>
                <input type="text" class="input-sm form-control" name="create_at_end"/>
            </div>
        </div>
    </div>
@endsection
@section('table_head')
    <th>ID</th>
    <th>标题</th>
    <th>创建时间</th>
    <th>操作</th>
@endsection
@section('script')
@endsection
