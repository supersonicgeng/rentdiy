@extends('layouts.admin.base')

@section('content')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                配置管理
                <small>编辑开屏广告</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" method="POST" action="{{route('profit.ad.update',$ad->id)}}">
                        @csrf
                        @method('PUT')
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
                                        <label class="col-sm-2 control-label">广告名称<span
                                                    style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-5">
                                            <input class="form-control" type="text" name="title"
                                                   value="{{old('title')?old('title'):$ad->title}}" placeholder="广告名称"
                                                   required>
                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">广告图16:9</label>
                                        <div class="col-sm-7">
                                            <input id="cover" type="hidden" name="image1"
                                                   value="{{old('image1')?old('image1'):$ad->image1}}">
                                            <input type="file" style="display: none" id="image_upload">
                                            <button type="button" class="btn btn-success btn-sm upload_image">
                                                <i id="loading" class="fa fa-fw fa-cloud-upload"></i>更改图片
                                            </button>
                                            {{--<small style="color: red">&nbsp;建议上传100px*100px尺寸图片</small>--}}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"></label>
                                        <div class="col-sm-7">
                                            <img id="cover_show" data-action="zoom"
                                                 src="{{old('image1') ? old('image1'):$ad->image1}}" alt=""
                                                 style="height: 320px;width:180px">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">广告图19:9</label>
                                        <div class="col-sm-7">
                                            <input id="cover1" type="hidden" name="image2"
                                                   value="{{old('image2')?old('image2'):$ad->image2}}">
                                            <input type="file" style="display: none" id="image_upload1">
                                            <button type="button" class="btn btn-success btn-sm upload_image1">
                                                <i id="loading1" class="fa fa-fw fa-cloud-upload"></i>更改图片
                                            </button>
                                            {{--<small style="color: red">&nbsp;建议上传100px*100px尺寸图片</small>--}}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"></label>
                                        <div class="col-sm-7">
                                            <img id="cover_show1" data-action="zoom"
                                                 src="{{old('image2')?old('image2'):$ad->image2}}" alt=""
                                                 style="height: 380px;width:180px">
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">跳转类型<span
                                                    style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-5">
                                            <select class="form-control" name="type">
                                                <option value="1" @if($ad->type ==1)selected @endif>商品详情页</option>
                                                <option value="2" @if($ad->type ==2)selected @endif>商品专题页</option>
                                                <option value="3" @if($ad->type ==3)selected @endif>活动页</option>
                                                {{--<option value="5">淘宝猜你喜欢</option>--}}
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">排序</label>
                                        <div class="col-sm-5">
                                            <input class="form-control" name="sort"
                                                   value="{{old('sort')?old('sort'):$ad->sort}}" placeholder="值越大排序越靠前"
                                                   type="text">

                                        </div>
                                    </div>
                                    <div class="form-group" id="zt" style="display: none">
                                        <label class="col-sm-2 control-label">指定专题<span
                                                    style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-7">
                                            <div class="margin-bottom">
                                                <button data-url="{{route('common.special.index')}}" data-title="选择专题"
                                                        type="button"
                                                        class="btn btn-info btn-sm check_model">选择专题
                                                </button>
                                            </div>
                                            <div>
                                                <textarea name="special_id" id="special_id" cols="80" rows="5"
                                                          readonly>{{old('special_id')?old('special_id'):$ad->special_id}}</textarea>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group" id="sp">
                                        <label class="col-sm-2 control-label">指定商品<span
                                                    style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-7">
                                            <div class="margin-bottom">
                                                <button data-url="{{route('common.product.single')}}" data-url="指定商品"
                                                        type="button"
                                                        class="btn btn-info btn-sm check_model">选择商品
                                                </button>
                                            </div>
                                            <div>
                                                <ul class="mailbox-attachments clearfix">

                                                </ul>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group" id="wy" style="display: none">
                                        <label class="col-sm-2 control-label">URL<span style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-5">
                                            <input class="form-control" name="url"
                                                   value="{{old('url')?old('url'):$ad->url}}" placeholder=""
                                                   type="text">

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">生效起始时间<span
                                                    style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-5">
                                            <input class="form-control" id="_time_from" name="start_at"
                                                   value="{{old('start_at')?old('start_at'):$ad->start_at}}" placeholder="" type="text" required>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">生效结束时间<span
                                                    style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-5">
                                            <input class="form-control" id="_time_to" name="stop_at"
                                                   value="{{old('stop_at') ? old('stop_at') :$ad->stop_at}}" placeholder="" type="text" required>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">上下架<span style="color: red;">&nbsp*</span></label>
                                        <label class="radio-inline">
                                            <input type="radio" name="is_on" value="1" checked>&nbsp是
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="is_on" value="0" @if($ad->is_on == 0)checked @endif>&nbsp否&nbsp&nbsp
                                        </label>
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
                                    {{--<div class="btn-group pull-left">--}}
                                    {{--<button type="reset" class="btn btn-warning">撤销</button>--}}
                                    {{--</div>--}}
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
        $(document).on('click', '.goods_del', function () {

            $(this).parents('li').remove();
        })

        $('select').change(function () {
            var type = $(this).val();

            if (type == 1) {
                $('#sp').show();
                $('#zt').hide();
                $('#wy').hide();
            }

            if (type == 2) {
                $('#sp').hide();
                $('#zt').show();
                $('#wy').hide();
            }

            if (type == 3) {
                $('#sp').hide();
                $('#zt').hide();
                $('#wy').show();
            }

            if (type == 5) {
                $('#sp').hide();
                $('#zt').hide();
                $('#wy').hide();
            }
        })


        //19:9上传配置
        var opts2 = {
            url: "/admin/photo",
            type: "POST",
            beforeSend: function () {
                $("#loading1").attr("class", "fa fa-spinner fa-spin");
            },
            success: function (result, status, xhr) {

                if (result.status == "0") {
                    alert(result.msg);
                    $("#loading1").attr("class", "fa fa-fw fa-cloud-upload");
                    return false;
                }

                $("#cover1").val(result.image);
                $("#cover_show1").attr('src', result.image);
                $("#loading1").attr("class", "fa fa-fw fa-cloud-upload");

                layer.msg('上传成功', {icon: 6, time: 1500});
            },
            error: function (result, status, errorThrown) {

                layer.alert('上传失败', {
                    skin: 'layui-layer-lan'
                    , title: '错误'
                    , closeBtn: 0
                    , anim: 4 //动画类型
                });

                $("#loading1").attr("class", "fa fa-fw fa-cloud-upload");
            }
        }

        $('#image_upload1').fileUpload(opts2);
        $('.upload_image1').click(function () {

            $('#image_upload1').click();
        })

        //时间选择器
        laydate.render({
            elem: '#_time_from'
            , type: 'datetime'
        });

        laydate.render({
            elem: '#_time_to'
            , type: 'datetime'
        });

    </script>
@endsection

