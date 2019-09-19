@extends('layouts.admin.base')


@section('content')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                配置管理
                <small>App强更提示语</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal">

                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab1" data-toggle="tab">基本参数</a></li>
                                <li class="pull-right"><a href="javascript:history.back(-1)" class="btn btn-sm"
                                                          style="padding:10px 2px;"><i class="fa fa-list"></i> 返回</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">提示语</label>
                                        <div class="col-sm-7">

                                            <textarea name="msg" id="" cols="100" rows="10" required>{{$instruction->msg ?? ''}}</textarea>
                                            {{--<small style="color: red">&nbsp;建议上传100px*100px尺寸图片</small>--}}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">操作管理员</label>
                                        <div class="col-sm-5">
                                            <input class="form-control" value="{{$admin->real_name}}" type="text" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">操作时间</label>
                                        <div class="col-sm-5">
                                            <input class="form-control" value="{{$instruction->created_at}}" type="text" disabled>

                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-7">
                                    <div class="btn-group pull-left">
                                        <button type="button" class="btn btn-info pull-right submits">提交</button>
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
            //表单提交
            $('.submits').click(function () {

                var data = $('form').serialize();

                $.ajax({
                    type: 'POST',
                    url: "{{route('profit.instruction.store')}}",
                    data: data,
                    dataType: 'json',
                    success: function (info) {

                        if (info.status == 1) {

                            toastr.success(info.msg);
                            setTimeout(function () {
                                window.location = window.location;
                            }, 800);
                        } else {
                            toastr.error(info.msg);
                        }

                    }

                })
                return false;
            })


        })
    </script>
@endsection