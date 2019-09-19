@extends('layouts.admin.base')

@section('content')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Customer
                <small>Customer List</small>
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
                                               placeholder="Input Email Or Phone ">

                                    </div>


                                    <button type="submit" class="btn btn-default btn-sm">Confirm</button>
                                    <a href="{{route('action_log.action_log.index')}}" class="btn btn-default btn-sm">Reset</a>
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
                                    <th>Avatar</th>
                                    <th>User Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Join Date</th>
                                    <th>Request Path</th>
                                    <th>Action Time</th>

                                </tr>
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->user_name}}</td>
                                        <td>{{$item->action}}</td>
                                        <td>{{$item->router_des}}</td>
                                        <td>{{$item->req_param}}</td>
                                        <td>{{$item->req_url}}</td>
                                        <td>{{$item->created_at}}</td>
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
