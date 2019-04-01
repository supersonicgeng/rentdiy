@extends('layouts.admin.list')
@section('header')
    @include('plugins.jquery-enlargement')
    @include('plugins.datetimepicker')
    <style>
        td {
            text-align: center;
        }

        th {
            text-align: center;
        }

        .zoomify-shadow {
            background: rgba(0, 0, 0, 0);
        }
    </style>
@endsection
@section('btn')
@endsection
@section('action')
    {{url('manage/passportList')}}
@endsection
@section('form_content')
    <div class="col-md-2">
        <div class="input-group">
            <span class="input-group-addon">昵称</span>
            <input type="text" name="nickname" placeholder="输入昵称模糊搜索" class="input-sm form-control">
        </div>
    </div>
    <div class="col-md-3">
        <div class="input-group">
            <div class="input-daterange input-group datetimepicker">
                <span class="input-group-addon">关注时间</span>
                <input type="text" class="input-sm form-control" name="create_at_start"/>
                <span class="input-group-addon">到</span>
                <input type="text" class="input-sm form-control" name="create_at_end"/>
            </div>
        </div>
    </div>
@endsection
@section('table_head')
    <th>ID</th>
    <th>用户昵称</th>
    <th>头像</th>
    <th>性别</th>
    <th>地址</th>
    <th>注册时间</th>
    <th>分享次数</th>
    <th>个人游戏积分</th>
    <th>分享游戏积分</th>
    <th>总积分</th>
    <th style="width: 215px;">操作</th>
@endsection
@section('script')
    function submit() {
        var child = layer.getChildFrame('body');
        child.find("#edit_form").ajaxSubmit({
            beforeSubmit: beforeAjax,
            dataType : "json",
            success : ajaxCallback,
            error:errorCallback
        })
    }
@endsection