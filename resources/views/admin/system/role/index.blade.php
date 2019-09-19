@extends('layouts.admin.base')

@section('content')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                系统管理
                <small>角色列表</small>
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
                                <a class="btn btn-primary btn-sm" href="{{route('system.role.create')}}"><i
                                            class="fa fa-save"></i> 新增</a>
                            </div>
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-hover">
                                <tbody>
                                <tr>
                                    <th>编号</th>
                                    <th>角色名</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                                @foreach($roles as $role)
                                    <tr>
                                        <td>{{$role->id}}</td>
                                        <td><span class="label label-success">{{$role->name}}</span></td>
                                        <td>{{$role->created_at}}</td>
                                        <td>
                                            <a class="btn btn-primary btn-xs" href="{{route('system.role.edit',$role->id)}}">
                                                <i class="fa fa-edit"></i> 编辑
                                            </a>
                                            @if($role->name != '超级管理员')
                                            <a class="btn btn-danger btn-xs delete_genius" href="javascript:void(0);" data-url="{{route('system.role.destroy',$role->id)}}"><i class="fa fa-trash"></i> 删除</a>
                                            @endif
                                        </td>
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