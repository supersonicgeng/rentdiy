@extends('layouts.admin.list')
@section('header')
    @include('plugins.datetimepicker')
@endsection
@section('action')
    {{url('manage/finance/financeList')}}
@endsection
@section('form_content')
    <div class="col-md-2">
        <div class="input-group">
            <span class="input-group-addon">姓名</span>
            <input type="text" name="username" placeholder="请输入姓名模糊查询" class="input-sm form-control">
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
    <th>申请人</th>
    <th>账号类型</th>
    <th>账号</th>
    <th>真实姓名</th>
    <th>申请金额</th>
    <th>手续费</th>
    <th>到账金额</th>
    <th>申请时间</th>
    <th>审核时间</th>
    <th>审核状态</th>
    <th>操作</th>
@endsection
@section('script')
@endsection
