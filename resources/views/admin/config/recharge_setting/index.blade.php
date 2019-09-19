@extends('layouts.admin.base')

@section('content')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Setting
                <small> Recharge Setting</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" method="POST" action="{{route('config.rechargeSetting.store')}}">
                        @csrf
                        <div class="nav-tabs-custom">

                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">






                                        <div class="box-body table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <tbody>
                                                <tr>
                                                    <th  style="width: 100px">ID</th>
                                                    <th>Recharge Fee</th>
                                                    <th >Free Balance</th>
                                                    <th style="width: 100px">Enable/Disable</th>
                                                    <th>Sort</th>

                                                </tr>
                                                @foreach($items as $item)
                                                    <tr data-id="{{$item->id}}">
                                                        <td >
                                                            <input class="form-control" type="number"
                                                                   name="id[]" value="{{$item->id}}" readonly=”readonly”  >
                                                        </td>
                                                        <td>
                                                            <input class="form-control" type="number" min="0" max="1000000"
                                                                   step="0.1"
                                                                   name="charge_fee[]" value="{{$item->charge_fee}}" >
                                                        </td>
                                                        <td>
                                                            <input class="form-control" type="number" min="0" max="1000000"
                                                                   step="0.1"
                                                                   name="free_balance[]" value="{{$item->free_balance}}" >
                                                        </td>
                                                        <td>
                                                            {!! is_something('is_use',$item) !!}
                                                        </td>

                                                        <td>
                                                            <input class="form-control" type="number" min="0" max="1000000"
                                                                   step="1"
                                                                   name="sort[]" value="{{$item->sort}}" >
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>

                                        </div>






                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-7">
                                    <div class="btn-group pull-left">
                                        <button type="submit" class="btn btn-info pull-right submits"
                                                data-loading-text="<i class='fa fa-spinner fa-spin '></i> 提交">提交
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
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
                    url: '{{route('config.rechargeSetting.change_attr')}}',
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

