@extends('layouts.admin.base')

@section('content')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                配置管理
                <small>新增联系人</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" method="POST" action="{{route('profit.linkman.store')}}">
                        @csrf
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab1" data-toggle="tab">基本参数</a></li>
                                <li class="pull-right"><a href="javascript:history.back(-1)" class="btn btn-sm"
                                                          style="padding:10px 2px;"><i class="fa fa-list"></i> 返回</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">微信号<span style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-5">
                                            <input class="form-control" type="text" name="wechat" value="{{old('wechat')}}" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">手机号<span style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-5">
                                            <input class="form-control" type="text" name="username" value="{{old('username')}}" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">展示位置</label>
                                        <div class="col-sm-5">
                                            <select class="form-control" name="type">
                                                <option value="-1">页面展示位置</option>
                                                <option value="1">升级合伙人页面的</option>
                                                <option value="2">合伙人弹框显示的</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-7">
                                    <div class="btn-group pull-left">
                                        <button type="submit" class="btn btn-info pull-right submits"
                                                data-loading-text="<i class='fa fa-spinner fa-spin '></i> 提交">提交
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>


@endsection


