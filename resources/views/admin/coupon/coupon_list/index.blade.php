@extends('layouts.admin.base')

@section('content')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Coupon
                <small>Coupon List</small>
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

                                    <div class="input-daterange input-group input-group-sm">
                                        <input class="form-control" id="_time_from" name="dateRange"
                                               value="{{Request::input('dateRange')}}"
                                               placeholder="Date Range Search" type="text" style="width: 200px">

                                        {{--<input class="form-control" id="_time_to" name="end_time"--}}
                                        {{--value="{{Request::input('end_time')}}"--}}
                                        {{--placeholder="End Date" type="text">--}}
                                    </div>

                                    <button type="submit" class="btn btn-default btn-sm">确定</button>
                                    <a href="/admin/coupon/coupon_list" class="btn btn-default btn-sm">重置</a>
                                </form>
                            </div>

                        </div>
                        <div class="box-header with-border">

                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>Coupon SN</th>
                                    <th>Coupon Type</th>
                                    <th>Discount &nbsp&nbsp&nbsp&nbsp%</th>
                                    <th>Deductions &nbsp&nbsp&nbsp&nbsp$</th>
                                    <th>Is Activated</th>
                                    <th>Issue Time</th>
                                    <th>Activated Time</th>

                                    <th></th>

                                </tr>
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->coupon_sn}}</td>

                                        <td>
                                            @if($item->coupon_type ==1)
                                                Discount Coupon
                                            @elseif($item->coupon_type ==2)
                                                Deductions Coupon
                                            @endif
                                        </td>

                                        <td>{{$item->discount }} </td>
                                        <td>{{$item->deductions }}</td>
                                        {{--<td>{{$item->is_activated}}</td>--}}
                                        <td>
                                            {!! is_something('is_activated',$item) !!}
                                        </td>

                                        <td>{{$item->created_at}}</td>
                                        <td>{{$item->activated_at ?? "/"}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pull-right">
                                <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                                    <form>
                                        <div class="input-daterange input-group input-group-sm">
                                            共{{$items->total()}}条&nbsp
                                        </div>
                                        <div class="input-daterange input-group input-group-sm">
                                            {{$items->appends(Request::all())->links()}}
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


            //时间选择器
            laydate.render({
                elem: '#_time_from',
                type: 'datetime',
                range: true,
            });


        })
    </script>
@endsection
