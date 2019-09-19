@extends('layouts.admin.base')

@section('content')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                系统管理
                <small>管理员列表</small>
            </h1>
            {{--<ol class="breadcrumb">--}}
            {{--<li><a href="#"><i class="fa fa-dashboard"></i> 系统管理</a></li>--}}
            {{--<li class="active">菜单与权限</li>--}}
            {{--</ol>--}}
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border" style="height:51px;">
                            <div class="pull-left">
                                <a class="btn btn-primary btn-sm" href="{{route('system.user.create')}}"><i class="fa fa-save"></i> 新增</a>
                            </div>
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>编号</th>
                                    <th>头像</th>
                                    <th>用户名</th>
                                    <th>真实姓名</th>
                                    <th>所属用户组</th>
                                    <th>邮箱</th>
                                    <th>创建时间</th>
                                    <th width="124">操作</th>
                                </tr>
                                @foreach($users as $user)
                                    <tr>
                                        <td style="vertical-align:middle">{{$user->id}}</td>
                                        <td style="vertical-align:middle"><img style="width:50px;border-radius:50%;" src="{{$user->avatar?$user->avatar:'/avatar.png'}}" alt=""></td>
                                        <td style="vertical-align:middle">{{$user->username}}</td>
                                        <td style="vertical-align:middle">{{$user->real_name}}</td>
                                        <td style="vertical-align:middle">{{$user->roles->implode('name', ', ')}}</td>
                                        <td style="vertical-align:middle">{{$user->email}}</td>
                                        <td style="vertical-align:middle">{{$user->created_at}}</td>
                                        <td style="vertical-align:middle">
                                            <a class="btn btn-primary btn-xs" href="{{route('system.user.edit',$user->id)}}"><i class="fa fa-edit"></i> 编辑</a>
                                            <a class="btn btn-danger btn-xs delete_genius" href="javascript:void(0);"
                                               data-url="{{route('system.user.destroy',$user->id)}}"><i class="fa fa-trash"></i> 删除</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>


@endsection