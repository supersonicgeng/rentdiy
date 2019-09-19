@extends('layouts.admin.base')
@section('css')
    <style>
        .zoom-img-wrap{
            position: absolute;
        }
    </style>
@endsection

@section('content')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                配置管理
                <small>云控列表</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">

                        <div class="box-header with-border">
                            <div class="pull-left">
                                <a class="btn btn-primary btn-sm" href="{{route('profit.iosc.create')}}"><i
                                            class="fa fa-save"></i> 新增</a>
                            </div>
                        </div>


                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>模板ID</th>
                                    <th>云控版本</th>
                                    <th>是否启用</th>
                                    <th>创建管理员</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                                @foreach($controllers as $controller)
                                    <tr data-id="{{$controller->id}}">
                                        <td>{{$controller->id}}</td>
                                        <td>{{$controller->version}}</td>
                                        <td>{!! is_something('is_on',$controller) !!}</td>
                                        <td>{{$controller->real_name}}</td>
                                        <td>{{$controller->created_at}}</td>
                                        <td>
                                            <a class="btn btn-primary btn-xs"
                                               href="{{route('profit.iosc.edit',$controller->id)}}"><i
                                                        class="fa fa-edit"></i>编辑</a>

                                            <a class="btn btn-danger btn-xs delete_genius" href="javascript:void(0);"
                                               data-url="{{route('profit.iosc.destroy',$controller->id)}}"><i
                                                        class="fa fa-trash"></i> 删除</a></td>


                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pull-right">
                                <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">

                                    <div class="input-daterange input-group input-group-sm">
                                        共{{$controllers->total()}}条&nbsp
                                    </div>
                                    <div class="input-daterange input-group input-group-sm">
                                        {{$controllers->appends(Request::all())->links()}}
                                    </div>


                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>


@endsection
