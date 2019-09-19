@extends('layouts.admin.base')

@section('content')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                新增管理员
                {{--<small>Control panel</small>--}}
            </h1>
            {{--<ol class="breadcrumb">--}}
                {{--<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>--}}
                {{--<li class="active">Dashboard</li>--}}
            {{--</ol>--}}
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" method="POST" action="{{route('system.user.store')}}">
                        @csrf
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab1" data-toggle="tab">基本参数</a></li>
                                <li class="pull-right"><a href="javascript:history.back(-1)" class="btn btn-sm" style="padding:10px 2px;"><i class="fa fa-list"></i> 返回</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">用户名</label>
                                        <div class="col-sm-7">
                                            <input class="form-control" type="text" name="username" value=""
                                                   placeholder="请输入用户名">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">真实姓名</label>
                                        <div class="col-sm-7">
                                            <input class="form-control" type="text" name="real_name" value=""
                                                   placeholder="请输入真实姓名">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">邮箱</label>
                                        <div class="col-sm-7">
                                            <input class="form-control" type="text" name="email" value=""
                                                   placeholder="请输入真实邮箱">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">密码</label>
                                        <div class="col-sm-7">
                                            <input class="form-control" type="password" name="password" value=""
                                                   placeholder="请输入密码">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">确认密码</label>
                                        <div class="col-sm-7">
                                            <input class="form-control" type="password" name="password_confirmation" value=""
                                                   placeholder="请输入确认密码">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">选择角色</label>
                                        <div class="col-sm-7">
                                            @foreach ($roles as $role)
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" value="{{$role->id}}" name="role_id[]"
                                                           @if(old('role_id') && in_array($role->id, old('role_id'))) checked @endif>
                                                    {{$role->name}}
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-7">
                                    <div class="btn-group pull-right">
                                        <button type="submit" class="btn btn-info pull-right"
                                                data-loading-text="<i class='fa fa-spinner fa-spin '></i> 提交">提交
                                        </button>
                                    </div>
                                    <div class="btn-group pull-left">
                                        <button type="reset" class="btn btn-warning">撤销</button>
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