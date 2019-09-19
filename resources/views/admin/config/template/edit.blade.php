@extends('layouts.admin.base')


@section('content')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                配置管理
                <small>编辑模板</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal">
                        @csrf
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
                                        <label class="col-sm-2 control-label">模板图片<span style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-7">
                                            <input id="cover" type="hidden" name="image" value="{{old('image')?old('image'):$template->image}}">
                                            <input type="file" style="display: none" id="image_upload">
                                            <button type="button" class="btn btn-success btn-sm upload_image">
                                                <i id="loading" class="fa fa-fw fa-cloud-upload"></i>上传图片
                                            </button>
                                            {{--<small style="color: red">&nbsp建议上传108px*108px尺寸图片</small>--}}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"></label>
                                        <div class="col-sm-7">
                                            <img  id="cover_show"
                                                 src="{{old('image')?old('image'):$template->image}}" alt=""
                                                 style="height: 500px;width:300px">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">URL<span style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-3">
                                            <input class="form-control" name="url" placeholder="" value="{{old('url')?old('url'):$template->url}}"
                                                   type="text" required>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">排序</label>
                                        <div class="col-sm-3">
                                            <input class="form-control" name="sort" value="{{old('sort')?old('sort'):$template->sort}}"
                                                   placeholder="" type="text">

                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-7">
                                    <div class="btn-group pull-right">
                                        <button type="button" class="btn btn-info pull-right submits">提交
                                        </button>
                                    </div>
                                    <div class="btn-group pull-left">
                                        <button type="reset" class="btn btn-warning">撤销</button>
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
    <script src="/vendor/html5-fileupload/jquery.html5-fileupload.js"></script>

    <script>
        $(function () {

            //表单提交
            $('.submits').click(function () {

                var data =$('form').serialize();

                $.ajax({
                    type: 'PUT',
                    url: "{{route('profit.template.update',$template->id)}}",
                    data: data,
                    dataType: 'json',
                    success: function (info) {

                        if (info.status == 1) {

                            toastr.success(info.msg);
                            setTimeout(function(){window.location="{{route('profit.template.index')}}"},800);
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
