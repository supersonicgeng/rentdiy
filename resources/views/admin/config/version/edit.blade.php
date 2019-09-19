@extends('layouts.admin.base')


@section('content')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                配置管理
                <small>编辑版本</small>
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
                                        <label class="col-sm-2 control-label">app二维码<span style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-7">
                                            <input id="cover" type="hidden" name="image" value="{{$app->image}}">
                                            <input type="file" style="display: none" id="image_upload">
                                            <button type="button" class="btn btn-success btn-sm upload_image">
                                                <i id="loading" class="fa fa-fw fa-cloud-upload"></i>上传二维码
                                            </button>
                                            {{--<small style="color: red">&nbsp建议上传108px*108px尺寸图片</small>--}}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"></label>
                                        <div class="col-sm-7">
                                            <img id="cover_show"
                                                 src="{{$app->image}}" alt=""
                                                 style="height: 100px;width:100px">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">渠道号</label>
                                        <div class="col-sm-7">
                                            <select class="form-control" name="channel_id">
                                                <option value="0">渠道号</option>
                                                @foreach($channels as $c)
                                                    <option value="{{$c->id}}" @if($app->channel_id == $c->id)selected @endif>
                                                        {{$c->channel}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">版本号<span style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-3">
                                            <input class="form-control" name="version" placeholder="" value="{{$app->version}}"
                                                   type="text" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">安卓apk地址<span style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-3">
                                            <input class="form-control" name="android_apk" placeholder="" value="{{$app->android_apk}}"
                                                   type="text" required>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">ios商店地址<span style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-3">
                                            <input class="form-control" name="ios_apk" placeholder="" value="{{$app->ios_apk}}"
                                                   type="text" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">ios企业地址<span style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-3">
                                            <input class="form-control" name="ios_store_apk" placeholder="" value="{{$app->ios_store_apk}}"
                                                   type="text" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">是否强更<span style="color: red;">&nbsp*</span></label>
                                        <label class="radio-inline">
                                            <input type="radio" name="compel" value="0" checked>&nbsp否
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="compel" value="1" @if($app->compel ==1)checked @endif>&nbsp是&nbsp&nbsp
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">版本描述<span style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-3">
                                            <textarea name="des" placeholder="介绍不超过150个字" cols="80" rows="5">{{$app->des}}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">是否维护<span style="color: red;">&nbsp*</span></label>
                                        <label class="radio-inline">
                                            <input type="radio" name="is_maintain" value="0" checked>&nbsp否
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="is_maintain" value="1" @if($app->is_maintain ==1)checked @endif>&nbsp是&nbsp&nbsp
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">维护开始时间</label>
                                        <div class="col-sm-3">
                                            <input class="form-control" name="main_created" id="_time_from" placeholder="不维护可不填写" value="{{$app->main_created}}"
                                                   type="text">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">维护结束时间</label>
                                        <div class="col-sm-3">
                                            <input class="form-control" name="main_end" id="_time_to" placeholder="不维护可不填写" value="{{$app->main_end}}"
                                                   type="text">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">维护描述<span style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-3">
                                            <textarea name="maintain_des" placeholder="不维护可不填写" cols="80" rows="5">{{$app->maintain_des}}</textarea>

                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-7">
                                    <div class="btn-group pull-left">
                                        <button type="button" class="btn btn-info pull-right submits">提交
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
    <script src="/vendor/html5-fileupload/jquery.html5-fileupload.js"></script>

    <script>
        $(function () {

            //时间选择器
            laydate.render({
                elem: '#_time_from'
                , type: 'datetime'
            });

            laydate.render({
                elem: '#_time_to'
                , type: 'datetime'
            });

            //表单提交
            $('.submits').click(function () {

                var data =$('form').serialize();

                $.ajax({
                    type: 'PUT',
                    url: "{{route('profit.version.update',$app->id)}}",
                    data: data,
                    dataType: 'json',
                    success: function (info) {

                        if (info.status == 1) {

                            toastr.success(info.msg);
                            setTimeout(function(){window.location="{{route('profit.version.index')}}"},800);
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
