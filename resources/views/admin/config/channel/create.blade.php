@extends('layouts.admin.model')

@section('content')



    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" method="POST" action="">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab1">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">渠道名称</label>
                                    <div class="col-sm-7">
                                        <input class="form-control" type="text" name="channel" value=""
                                               placeholder="请输入渠道名称" required>
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
@endsection

@section('js')
    <script>
        $(function () {
            $('.submits').click(function () {
                var identity = $("input[name='channel']").val();

                if (identity == '') {
                    toastr.error('请输入名称'); return false;
                }

                $.ajax({
                    type: 'POST',
                    url: "{{route('profit.channel.store')}}",
                    data: {channel: identity},
                    success: function (data) {
                        if (data.status == 1) {
                            layer.msg(data.msg, {icon: 6});
                            window.parent.location.reload();
                        } else {
                            layer.msg(data.msg, {icon: 5});
                            return false;
                        }
                    }
                });
            })


        })
    </script>
@endsection