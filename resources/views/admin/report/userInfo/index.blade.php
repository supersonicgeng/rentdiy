@extends('layouts.admin.base')

@section('content')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Report
                <small>User Inof</small>
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
                                   {{-- <div class="input-group input-group-sm">


                                        <input type="text" name="userName" class="form-control pull-right"
                                               value="{{Request::input('userName')}}"
                                               placeholder="Username Search">

                                    </div>

                                    <div class="input-daterange input-group input-group-sm">
                                        <input class="form-control" id="_time_from" name="dateRange"
                                               value="{{Request::input('dateRange')}}"
                                               placeholder="Date Range Search" type="text" style="width: 200px">

                                        --}}{{--<input class="form-control" id="_time_to" name="end_time"--}}{{--
                                        --}}{{--value="{{Request::input('end_time')}}"--}}{{--
                                        --}}{{--placeholder="End Date" type="text">--}}{{--
                                    </div>
                                    <div class="input-daterange input-group input-group-sm">


                                        <select class="form-control" name="user_role" id="">
                                            <option value="1">Landlord</option>
                                            <option value="2">Provider</option>
                                            <option value="3" selected="selected">All</option>
                                        </select>

                                    </div>
                                    <button type="submit" class="btn btn-default btn-sm">确定</button>
                                    <a href="/admin/report/chargeList" class="btn btn-default btn-sm">重置</a>--}}
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
                                    <th>User quantity</th>
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


                                </tr>

                                <tr>
                                    <td>Landlord</td>
                                    <td>{{$data['landlord_count']}}</td>
                                    <td>{{$data['landlord_total_income']}}</td>
                                    <td>{{$data['landlord_service_income']}}</td>
                                    <td>{{$data['landlord_msg_income']}}</td>
                                    <td>{{$data['landlord_paper_income']}}</td>
                                    <td>{{$data['landlord_vip_income']}}</td>
                                    <td>{{$data['landlord_expense_cost']}}</td>
                                    <td>{{$data['landlord_expense_cost_without_gts']}}</td>
                                    <td>{{$data['landlord_expense_cost_gts']}}</td>
                                    <td>{{$data['landlord_discount']}}</td>
                                    <td>{{$data['landlord_arrears']}}</td>
                                    <td>{{$data['landlord_charge']}}</td>
                                    {{--<td>{{$data['landlord_total_fee']}}</td>--}}
                                    <td>{{$data['landlord_balance']}}</td>
                                </tr>
                                <tr>
                                    <td>Providers</td>
                                    <td>{{$data['provider_count']}}</td>
                                    <td>{{$data['provider_total_income']}}</td>
                                    <td>{{$data['provider_service_income']}}</td>
                                    <td>{{$data['provider_msg_income']}}</td>
                                    <td>{{$data['provider_paper_income']}}</td>
                                    <td>{{$data['provider_vip_income']}}</td>
                                    <td>{{$data['provider_expense_cost']}}</td>
                                    <td>{{$data['provider_expense_cost_without_gts']}}</td>
                                    <td>{{$data['provider_expense_cost_gts']}}</td>
                                    <td>{{$data['provider_discount']}}</td>
                                    <td>{{$data['provider_arrears']}}</td>
                                    <td>{{$data['provider_charge']}}</td>
                                    {{--<td>{{$data['provider_total_fee']}}</td>--}}
                                    <td>{{$data['provider_balance']}}</td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="pull-right">
                                <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                                    <form>
                                       {{-- <div class="input-daterange input-group input-group-sm">
                                            共{{$res->total()}}条&nbsp
                                        </div>
                                        <div class="input-daterange input-group input-group-sm">
                                            {{$res->appends(Request::all())->links()}}
                                        </div>--}}

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
