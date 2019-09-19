@extends('layouts.admin.base')


@section('content')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Coupon
                <small>Issue Coupon</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">


                            <div class="search-form-inline form-inline pull-left">
                                <form>
                                    <div class="input-group">

                                        <input type="text" name="word" class="form-control pull-right"
                                               style="width: 300px"
                                               value="{{Request::input('word')}}"
                                               placeholder="Please Input user E-Mall OR Phone">

                                    </div>


                                    <button type="submit" class="btn btn-default btn-sm">Confirm</button>
                                    <a href="{{route('coupon.issue_coupon.index')}}" class="btn btn-default btn-sm">Reset</a>
                                </form>
                            </div>

                        </div>

                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>Avatar</th>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Join Time</th>
                                    <th>Issue Coupon</th>


                                </tr>
                                {{--@foreach($orders as $order)--}}
                                <tr>
                                    <td>{{$item->id ?? "None" }}</td>
                                    <td><img src="{{$item->head_img ?? "None"}}" style="width: 25px" alt=""></td>
                                    <td>{{$item->username ?? "None"}}</td>
                                    <td>{{$item->e_mail ?? "None"}}</td>
                                    <td>{{$item->phone ?? "None"}}</td>
                                    <td>{{$item->created_at ?? "None"}}</td>
                                    <td>
                                        <a class="btn btn-primary btn-xs"
                                           href="/admin/coupon/issue_coupon/make_coupon/{{$item->id ?? 1}}"><i
                                                    class="fa fa-edit"></i> Issue Coupon</a>

                                    </td>
                                </tr>
                                {{--@endforeach--}}
                                </tbody>
                            </table>
                            <div class="pull-right">
                                <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                                    <form>
                                        <div class="input-daterange input-group input-group-sm">

                                        </div>
                                        <div class="input-daterange input-group input-group-sm">

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

    </script>
@endsection