@extends('layouts.admin.base')

@section('content')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                配置管理
                <small>渠道号列表</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border" style="height:51px;">
                            <div class="pull-left">
                                <a class="btn btn-success btn-sm add" href="javascript:;" data-url="{{route('profit.channel.create')}}"><i class="fa fa-save"></i> 新增</a>
                                {{--<a class="btn btn-danger btn-sm delete_all" href="javascript:;" data-url=""><i class="fa fa-trash"></i> 多选删除</a>--}}
                            </div>
                            {{--<div class="search-form-inline form-inline pull-left" style="margin-left:10px;">--}}
                                {{--<form>--}}
                                    {{--<div class="input-daterange input-group input-group-sm">--}}
                                        {{--<input class="form-control" name="id" value="{{Request::input('id')}}" placeholder="输入标签ID搜索"--}}
                                               {{--type="text">--}}
                                    {{--</div>--}}
                                    {{--<div class="input-daterange input-group input-group-sm">--}}
                                        {{--<input class="form-control" name="name" value="{{Request::input('name')}}" placeholder="输入标签名称搜索"--}}
                                               {{--type="text">--}}
                                    {{--</div>--}}
                                    {{--<button type="submit" class="btn btn-default btn-sm">搜索</button>--}}
                                    {{--<a href="{{ route('platform.identity.index') }}" class="btn btn-default btn-sm">重置</a>--}}
                                {{--</form>--}}
                            {{--</div>--}}

                        </div>


                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>渠道ID</th>
                                    <th>渠道名称</th>
                                    <th>创建人</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                                @foreach($channels as $channel)
                                    <tr>
                                        <td>{{$channel->id}}</td>
                                        <td>{{$channel->channel}}</td>
                                        <td>{{$channel->admin->real_name ?? ''}}</td>
                                        <td>{{$channel->created_at}}</td>
                                        <td>
                                            <a class="btn btn-success btn-xs add" href="javascript:;" data-url="{{route('profit.channel.edit',$channel->id)}}"><i class="fa fa-edit"></i> 编辑</a>
                                            <a class="btn btn-danger btn-xs delete_genius"
                                               href="javascript:void(0);"
                                               data-url="{{route('profit.channel.destroy',$channel->id)}}"><i class="fa fa-trash"></i> 删除</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{--<div class="pull-right">--}}
                                {{--<div class="search-form-inline form-inline pull-left" style="margin-left:10px;">--}}
                                    {{--<form>--}}
                                        {{--<div class="input-daterange input-group input-group-sm">--}}
                                            {{--共{{$channels->total()}}条&nbsp--}}
                                        {{--</div>--}}
                                        {{--<div class="input-daterange input-group input-group-sm">--}}
                                            {{--{{$channels->appends(Request::all())->links()}}--}}
                                        {{--</div>--}}

                                    {{--</form>--}}
                                {{--</div>--}}

                            {{--</div>--}}
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
        //编辑模态框
        $('.add').click(function () {
            var url = $(this).data('url');
            // console.log(url);return false;
            top.layer.open({
                title: ' ',
                type: 2,
                shadeClose: true,
                tipsMore: false,
                shade: [0.5, '#393D49'],
                maxmin: true, //开启最大化最小化按钮
                area: ['500px', '40%'],
                content: url

            });
        })

        {{--$('.delete_all').click(function () {--}}
            {{--var length = $('.checked_id:checked').length;--}}
            {{--if (length == 0) {--}}
                {{--layer.msg('至少选择一个身份！', {icon: 5});--}}
                {{--return false;--}}
            {{--}--}}

            {{--var a = $('.checked_id:checked').serialize();--}}

            {{--$.ajax({--}}
                {{--type: 'PATCH',--}}
                {{--url: "{{route('platform.identity.delete_all')}}",--}}
                {{--data: a,--}}
                {{--success: function (data) {--}}
                    {{--if (data.status == 1) {--}}
                        {{--layer.msg(data.msg, {icon: 6});--}}
                        {{--window.location.reload();--}}
                    {{--} else {--}}
                        {{--layer.msg(data.msg, {icon: 5});--}}
                        {{--return false;--}}
                    {{--}--}}
                {{--}--}}
            {{--});--}}
        {{--})--}}
    </script>
@endsection