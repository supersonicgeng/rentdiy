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


                                        <input type="text" name="login_time" class="form-control pull-right"
                                               value="{{Request::input('login_time')}}"
                                               placeholder="un login time Search">

                                    </div>
                                    <div class="input-daterange input-group input-group-sm">


                                        <select class="form-control" name="opeartor_method" id="">
                                            <option value="" selected="selected">All</option>
                                            <option value="1">add house</option>
                                            <option value="2">add contract</option>
                                            <option value="3">inspect</option>
                                            <option value="4">add fee list</option>
                                            <option value="5">bank checke</option>
                                            <option value="6">cash pay</option>
                                            <option value="7">stop contract</option>

                                        </select>

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
                                    <th>last operate</th>
                                    <th>last login time</th>
                                    <th>un login day</th>
                                    <th>house number</th>


                                </tr>
                                @foreach($res as $item)
                                    <tr>
                                        <td>{{$item->nickname}}</td>
                                        <td>{{$item->phone}}</td>
                                        <td>{{$item->e_mail}}</td>
                                        <td>@if($item->opeartor_method == 1)
                                                add house
                                            @elseif($item->opeartor_method == 2)
                                                add contract
                                            @elseif($item->opeartor_method == 3)
                                                inspect
                                            @elseif($item->opeartor_method == 4)
                                                add fee list
                                            @elseif($item->opeartor_method == 5)
                                                bank check
                                            @elseif($item->opeartor_method == 6)
                                                cash pay
                                            @elseif($item->opeartor_method == 7)
                                                stop contract
                                            @else
                                                null
                                            @endif
                                        </td>
                                        <td>{{$item->login_expire_time}}</td>
                                        <td>{{$item->un_logint_day}}</td>
                                        <td>{{$item->house_num}}</td>

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
