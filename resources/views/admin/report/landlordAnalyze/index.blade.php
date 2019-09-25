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
                                    <button type="submit" class="btn btn-default btn-sm">确定</button>
                                    <a href="/admin/report/landlordAnalyze" class="btn btn-default btn-sm">重置</a>
                                </form>
                            </div>

                        </div>
                        <div class="box-header with-border">

                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>user nickname</th>
                                    <th>phone</th>
                                    <th>email</th>
                                    <th>house quantity</th>
                                    <th>un rent rate</th>
                                    <th>arrears rate</th>
                                    <th>inspect rate</th>
                                    <th>service fee</th>
                                    <th>short message fee</th>
                                    <th>mail send fee</th>
                                    <th>total income</th>
                                    <th>total arrears</th>

                                </tr>
                                @foreach($res as $item)
                                    <tr>
                                        <td>{{$item->nickname}}</td>
                                        <td>{{$item->phone}}</td>
                                        <td>{{$item->e_mail}}</td>
                                        <td>{{$item->house_num}}</td>
                                        <td>{{$item->empty_rate}}</td>
                                        <td>{{$item->arrears_rate}}</td>
                                        <td>{{$item->inspect_rate}}</td>
                                        <td>{{$item->service_income}}</td>
                                        <td>{{$item->msg_income}}</td>
                                        <td>{{$item->paper_income}}</td>
                                        <td>{{$item->total_income}}</td>
                                        <td>{{$item->total_arrears}}</td>
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
