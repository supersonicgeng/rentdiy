@extends('layouts.admin.list')
@section('header')
@endsection
@section('btn')

@endsection
@section('action')
    {{url('manage/order')}}
@endsection
@section('form_content')
    <div class="col-md-2">
        <div class="input-group">
            <span class="input-group-addon">订单号</span>
            <input type="text" name="order_num" placeholder="请输入订单号" class="input-sm form-control">
        </div>
    </div>
    <div class="col-md-2">
        <div class="input-group">
            <span class="input-group-addon">订单状态</span>
            <input type="text" name="status" placeholder="订单状态" class="input-sm form-control">
        </div>
    </div>
    <div class="col-md-2">
        <div class="input-group">
            <span class="input-group-addon">帐户信息</span>
            <input type="text" name="phone" placeholder="帐户信息" class="input-sm form-control">
        </div>
    </div>
    <div class="col-md-2">
        <div class="input-group">
            <span class="input-group-addon">商品</span>
            <input type="text" name="title" placeholder="商品名称" class="input-sm form-control">
        </div>
    </div>
@endsection
@section('table_head')
    
@endsection