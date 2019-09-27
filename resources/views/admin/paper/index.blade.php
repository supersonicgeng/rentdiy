@extends('layouts.admin.base')

@section('content')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                paper
                <small>paper send</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <div class="pull-right">
                            </div>
                            <div class="search-form-inline form-inline pull-left">
                                <form>
                                    <div class="input-group input-group-sm">


                                        <input type="text" name="userName" class="form-control pull-right"
                                               value="{{Request::input('userName')}}"
                                               placeholder="Username Search">

                                    </div>
                                    <div class="input-group input-group-sm">


                                        <input type="text" name="property_name" class="form-control pull-right"
                                               value="{{Request::input('property_name')}}"
                                               placeholder="property  Search">

                                    </div>

                                    {{--<div class="input-daterange input-group input-group-sm">
                                        <input class="form-control" id="_time_from" name="dateRange"
                                               value="{{Request::input('dateRange')}}"
                                               placeholder="Date Range Search" type="text" style="width: 200px">

                                        --}}{{--<input class="form-control" id="_time_to" name="end_time"--}}{{--
                                        --}}{{--value="{{Request::input('end_time')}}"--}}{{--
                                        --}}{{--placeholder="End Date" type="text">--}}{{--
                                    </div>--}}

                                    <button type="submit" class="btn btn-default btn-sm">确定</button>
                                    <a href="/admin/paper/index" class="btn btn-default btn-sm">重置</a>
                                    <a class="btn btn-success btn-sm delete_all" href="javascript:;" data-url=""><i class="fa fa-envelope"></i> 多选打印</a>
                                </form>
                            </div>

                        </div>
                        <div class="box-header with-border">

                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th></th>
                                    <th>id</th>
                                    <th>send name</th>
                                    <th>send address</th>
                                    <th>send mail code</th>
                                    <th>receive name</th>
                                    <th>receive address</th>
                                    <th>receive mail code</th>
                                    <th>is send</th>
                                </tr>
                                @foreach($res as $item)
                                    <tr data-id="{{$item->id}}">
                                        <td>
                                            <input type="checkbox" name="checked_id[]" class="checked_id" value="{{$item->id}}">
                                        </td>
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->send_name}}</td>
                                        <td>{{$item->send_address}}</td>
                                        <td>{{$item->send_code}}</td>
                                        <td>{{$item->receive_name}}</td>
                                        <td>{{$item->receive_address}}</td>
                                        <td>{{$item->receive_code}}</td>
                                        <td> {!! is_something('is_send',$item) !!}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pull-right">
                                <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                                    <form>
                                        <div class="input-daterange input-group input-group-sm">
                                            共{{$res->total()}}条&nbsp
                                        </div>
                                        <div class="input-daterange input-group input-group-sm">
                                            {{$res->appends(Request::all())->links()}}
                                        </div>

                                    </form>
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
        $('.delete_all').click(function () {
            var length = $('.checked_id:checked').length;
            if (length == 0) {
                layer.msg('至少选择一个邮件！', {icon: 5});
                return false;
            }

            var a = $('.checked_id:checked').serialize();

            $.ajax({
                type: 'PATCH',
                url: "{{route('paper.paperPrint')}}",
                data: a,
                success: function (data) {
                    if (data.status == 1) {
                        toastr.success(data.message);

                        setTimeout(function(){
                            window.location.reload();//刷新当前页面.
                        },2000)
                    } else {
                        toastr.error('邮件发送失败');
                    }
                }
            });
        })
    </script>
@endsection
