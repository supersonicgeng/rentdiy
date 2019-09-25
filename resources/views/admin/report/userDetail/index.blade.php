@extends('layouts.admin.base')

@section('content')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Report
                <small>Report List</small>
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
                                    <div class="input-daterange input-group input-group-sm">


                                        <select class="form-control" name="user_role" id="">
                                            <option value="1" selected="selected">Landlord</option>
                                            <option value="2">Provider</option>
                                        </select>

                                    </div>
                                    <button type="submit" class="btn btn-default btn-sm">确定</button>
                                    <a href="/admin/report/userDetail" class="btn btn-default btn-sm">重置</a>
                                </form>
                            </div>

                        </div>
                        <div class="box-header with-border">

                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>user type</th>
                                    <th>user nickname</th>
                                    <th>phone</th>
                                    <th>email</th>
                                    <th>total income</th>
                                    <th>service fee</th>
                                    <th>short message fee</th>
                                    <th>send mail fee</th>
                                    <th>other fee</th>
                                    <th>actual receive</th>
                                    <th>actual receive (without gts)</th>
                                    <th>actual receive (gts)</th>
                                    <th>discount</th>
                                    <th>arrears</th>
                                    <th>charge fee</th>
                                    <th>balance</th>
                                    <th>register time</th>
                                </tr>
                                @foreach($res as $item)
                                    <tr>
                                        <td>@if($item->user_role ==1 ||  $item->user_role == 5)
                                                Landlord
                                            @elseif($item->user_role ==2 || $item->user_role == 6)
                                                Providers
                                            @elseif($item->user_role ==3 || $item->user_role == 7)
                                                Landlord + Providers
                                            @endif</td>
                                        <td>{{$item->nickname}}</td>
                                        <td>{{$item->phone}}</td>
                                        <td>{{$item->e_mail}}</td>
                                        <td>{{$item->total_income}}</td>
                                        <td>{{$item->service_income}}</td>
                                        <td>{{$item->msg_income}}</td>
                                        <td>{{$item->paper_income}}</td>
                                        <td>{{$item->vip_income}}</td>
                                        <td>{{$item->expense_cost}}</td>
                                        <td>{{$item->expense_cost_without_gts}}</td>
                                        <td>{{$item->expense_cost_gts}}</td>
                                        <td>{{$item->discount}}</td>
                                        <td>{{$item->arrears}}</td>
                                        <td>{{$item->charge}}</td>
                                        {{--<td>{{$data['landlord_total_fee']}}</td>--}}
                                        <td>{{$item->balance}}</td>
                                        <td>{{$item->created_at}}</td>
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


            //时间选择器
            laydate.render({
                elem: '#_time_from',
                type: 'datetime',
                range: true,
            });


        })
    </script>
@endsection
