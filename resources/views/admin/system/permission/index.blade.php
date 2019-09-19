@extends('layouts.admin.base')

@section('css')
    <style>
        .editable-click {
            border-bottom: dashed 1px #0088cc
        }
    </style>
@endsection

@section('content')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                系统管理
                <small>菜单与权限</small>
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
                                <a class="btn btn-primary btn-sm add" href="javascrip:;"><i
                                            class="fa fa-save"></i> 新增</a>
                            </div>
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>权限名称</th>
                                    <th>节点地址</th>
                                    <th>图标</th>
                                    <th>排序</th>
                                    <th>创建时间</th>
                                    <th width="124">操作</th>
                                </tr>
                                @foreach($permissions as $permission)
                                    <tr>
                                        <td>
                                            <span class="editable editable-click">{{$permission->label}}</span>
                                        </td>
                                        <td>
                                            <span>{{$permission->name}}</span>
                                        </td>
                                        <td align="center">
                                            <i class="{{$permission->icon}}"></i>
                                        </td>

                                        <td>
                                            {{$permission->sort_order}}
                                        </td>
                                        <td>
                                            {{$permission->created_at}}
                                        </td>
                                        <td>
                                            <a class="btn btn-primary btn-xs edit"
                                               href="javascript:;"
                                               data-url="{{route('system.permission.edit',$permission->id)}}"><i
                                                        class="fa fa-edit"></i> 编辑</a>
                                            <a class="btn btn-danger btn-xs delete_genius" href="javascript:void(0);"
                                               data-url="{{route('system.permission.destroy',$permission->id)}}"><i
                                                        class="fa fa-trash"></i> 删除</a>
                                        </td>
                                    </tr>
                                    @foreach($permission->children as $child)
                                        <tr>
                                            <td>

                                                　　　├ <span class="editable editable-click">{{$child->label}}</span>
                                            </td>
                                            <td>
                                                <span>{{$child->name}}</span>
                                            </td>
                                            <td align="center">
                                                <i class="{{$child->icon}}"></i>
                                            </td>

                                            <td>
                                                {{$child->sort_order}}
                                            </td>
                                            <td>
                                                {{$child->created_at}}
                                            </td>
                                            <td>
                                                <a class="btn btn-primary btn-xs edit"
                                                   href="javascript:;"
                                                   data-url="{{route('system.permission.edit',$child->id)}}"><i
                                                            class="fa fa-edit"></i> 编辑</a>
                                                <a class="btn btn-danger btn-xs delete_genius"
                                                   href="javascript:void(0);"
                                                   data-url="{{route('system.permission.destroy',$child->id)}}"><i
                                                            class="fa fa-trash"></i> 删除</a>
                                            </td>
                                        </tr>
                                        @foreach($child->children as $c)
                                            <tr>
                                                <td>

                                                    　　　│　　　├ <span class="editable editable-click">{{$c->label}}</span>
                                                </td>
                                                <td>
                                                    <span>{{$c->name}}</span>
                                                </td>
                                                <td align="center">
                                                    <i class="{{$c->icon}}"></i>
                                                </td>

                                                <td>
                                                    {{$c->sort_order}}
                                                </td>
                                                <td>
                                                    {{$c->created_at}}
                                                </td>
                                                <td>
                                                    <a class="btn btn-primary btn-xs edit"
                                                       href="javascript:;"
                                                       data-url="{{route('system.permission.edit',$c->id)}}"><i
                                                                class="fa fa-edit"></i> 编辑</a>
                                                    <a class="btn btn-danger btn-xs delete_genius"
                                                       href="javascript:void(0);"
                                                       data-url="{{route('system.permission.destroy',$c->id)}}"><i
                                                                class="fa fa-trash"></i> 删除</a>
                                                </td>
                                            </tr>
                                        @endforeach


                                    @endforeach


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

@section('js')
    <script>
        $(function () {




            //新增模态框
            $('.add').click(function () {
                top.layer.open({
                    title: '  ',
                    type: 2,
                    shadeClose: true,
                    tipsMore: false,
                    shade: [0.5, '#393D49'],
                    maxmin: true, //开启最大化最小化按钮
                    area: ['500px', '550px'],
                    content: '{{route('system.permission.create')}}'

                });
            })

            //编辑模态框
            $('.edit').click(function () {
                var url = $(this).data('url');
                // console.log(url);return false;
                top.layer.open({
                    title: '  ',
                    type: 2,
                    shadeClose: true,
                    tipsMore: false,
                    shade: [0.5, '#393D49'],
                    maxmin: true, //开启最大化最小化按钮
                    area: ['500px', '550px'],
                    content: url

                });
            })


        })
    </script>
@endsection