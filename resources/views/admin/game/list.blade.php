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
@section('action')
    {{url('manage/gameList')}}
@endsection
@section('table_head')
    <th>用户昵称</th>
    <th>头像</th>
    <th>性别</th>
    <th>总积分</th>
    <th>名次</th>
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