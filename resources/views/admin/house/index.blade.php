@extends('layouts.admin.base')

@section('content')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                house
                <small>house List</small>
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

                                    {{--<div class="input-daterange input-group input-group-sm">
                                        <input class="form-control" id="_time_from" name="dateRange"
                                               value="{{Request::input('dateRange')}}"
                                               placeholder="Date Range Search" type="text" style="width: 200px">

                                        --}}{{--<input class="form-control" id="_time_to" name="end_time"--}}{{--
                                        --}}{{--value="{{Request::input('end_time')}}"--}}{{--
                                        --}}{{--placeholder="End Date" type="text">--}}{{--
                                    </div>--}}

                                    <button type="submit" class="btn btn-default btn-sm">确定</button>
                                    <a href="/admin/house/index" class="btn btn-default btn-sm">重置</a>
                                </form>
                            </div>

                        </div>
                        <div class="box-header with-border">

                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>id</th>
                                    <th>property name</th>
                                    <th>rent category</th>
                                    <th>is banner</th>
                                    <th>banner sort</th>
                                    <th>landlord name</th>
                                </tr>
                                @foreach($res as $item)
                                    <tr data-id="{{$item->id}}">
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->property_name}}</td>
                                        <td>@if($item->rent_category == 1)
                                                
                                            @endif
                                        </td>
                                        <td>{!! is_something('is_banner',$item) !!}</td>
                                        <td>{{$item->banner_sort}}</td>
                                        <td>{{$item->nickname}}</td>
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
        $(function () {
            $('.change_attr').click(function () {
                var attr = $(this).data('attr');
                var id = $(this).parents('tr').data('id');

                $.ajax({
                    type: 'PATCH',
                    data: {attr: attr, id: id},
                    url: '{{route('house.change_attr')}}',
                    success: function (data) {
                        if (data.status == 1) {
                            toastr.success(data.msg);
                        } else {
                            toastr.error(data.msg);
                            return false;
                        }
                        setTimeout(function(){
                            window.location.reload();//页面刷新
                        },150);
                    }
                })
            })





        })
    </script>
@endsection
