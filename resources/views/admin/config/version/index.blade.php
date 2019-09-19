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
                <small>版本控制列表</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">

                        <div class="box-header with-border">
                            <div class="pull-left">
                                <a class="btn btn-primary btn-sm" href="{{route('profit.version.create')}}"><i
                                            class="fa fa-save"></i> 新增</a>
                            </div>
                        </div>


                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>app二维码</th>
                                    <th>版本号</th>
                                    <th>渠道号</th>
                                    <th>渠道号描述</th>
                                    <th>是否强更</th>
                                    <th>是否维护</th>
                                    <th>维护开始时间</th>
                                    <th>维护结束时间</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                                @foreach($apps as $app)
                                    <tr data-id="{{$app->id}}">
                                        <td>{{$app->id}}</td>
                                        <td>
                                            <img src="{{$app->image}}" alt="" style="width: 50px;height: 50px"
                                                 data-action="zoom">
                                        </td>
                                        <td>{{$app->version}}</td>
                                        <td>{{$app->channel->channel ?? ''}}</td>
                                        <td>{{$app->des}}</td>

                                        <td>{!! is_something('compel',$app) !!}</td>
                                        <td>{!! is_something('is_maintain',$app) !!}</td>
                                        <td>{{$app->main_created}}</td>
                                        <td>{{$app->main_end}}</td>
                                        <td>{{$app->created_at}}</td>
                                        <td>
                                            <a class="btn btn-primary btn-xs"
                                               href="{{route('profit.version.edit',$app->id)}}"><i
                                                        class="fa fa-edit"></i>编辑</a>

                                            <a class="btn btn-danger btn-xs delete_genius" href="javascript:void(0);"
                                               data-url="{{route('profit.version.destroy',$app->id)}}"><i
                                                        class="fa fa-trash"></i> 删除</a></td>


                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pull-right">
                                <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">

                                    <div class="input-daterange input-group input-group-sm">
                                        共{{$apps->total()}}条&nbsp
                                    </div>
                                    <div class="input-daterange input-group input-group-sm">
                                        {{$apps->appends(Request::all())->links()}}
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
@section('js')

    <script>
        // $('.change').click(function () {
        //     var url = $(this).data('url');
        //
        //
        //     $.ajax({
        //         type: 'PATCH',
        //         url: url,
        //         success: function (data) {
        //             if (data.status == 1) {
        //                 toastr.success(data.msg);
        //             } else {
        //                 toastr.error(data.msg);
        //                 return false;
        //             }
        //             setTimeout(function () {
        //                 window.location.reload();//页面刷新
        //             }, 150);
        //         }
        //     })
        // })


        //时间选择器
        laydate.render({
            elem: '.test'
            , type: 'datetime'
        });

        laydate.render({
            elem: '#test'
            , type: 'datetime'
        });
    </script>
@endsection