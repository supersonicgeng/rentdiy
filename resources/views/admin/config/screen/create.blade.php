@extends('layouts.admin.base')

@section('css')
    <link rel="stylesheet" href="/vendor/webupload/style.css"/>
    <link rel="stylesheet" href="/vendor/webupload/dist/webuploader.css"/>

@endsection

@section('content')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                配置管理
                <small>新增滑图</small>
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
                                        <label class="col-sm-2 control-label">版本号</label>
                                        <div class="col-sm-3">
                                            <input class="form-control" name="share_amt" value="" placeholder="" type="text" required>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">启动滑图16:9</label>
                                        <div class="col-sm-7">
                                            <button type="button" class="btn btn-primary many_upload1"
                                                    data-toggle="modal"
                                                    data-target=".bs-example-modal-lg">
                                                <i id="loading1" class="fa fa-fw fa-cloud-upload"></i>选择图片
                                            </button>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"></label>
                                        <div class="col-sm-7">

                                            <ul class="mailbox-attachments clearfix1" id="items">

                                            </ul>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">启动滑图19:9</label>
                                        <div class="col-sm-7">
                                            <button type="button" class="btn btn-primary many_upload2"
                                                    data-toggle="modal"
                                                    data-target=".bs-example-modal-lg">
                                                <i id="loading1" class="fa fa-fw fa-cloud-upload"></i>选择图片
                                            </button>

                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"></label>
                                        <div class="col-sm-7">
                                            <ul class="mailbox-attachments clearfix2" id="items1">

                                            </ul>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">上下架<span style="color: red;">&nbsp*</span></label>
                                        <label class="radio-inline">
                                            <input type="radio" name="is_on" value="1" checked>&nbsp是
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="is_on" value="0">&nbsp否&nbsp&nbsp
                                        </label>
                                    </div>

                                    {{--<div class="form-group">--}}
                                    {{--<label class="col-sm-2 control-label">发布时间<span--}}
                                    {{--style="color: red;">&nbsp*</span></label>--}}
                                    {{--<div class="col-sm-3">--}}
                                    {{--<input class="form-control" id="test" AUTOCOMPLETE="off" name="publish_time"--}}
                                    {{--value="{{Timeformat(time())}}" placeholder="" type="text">--}}

                                    {{--</div>--}}
                                    {{--</div>--}}
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

        <div id="model" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog"
             aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">多图上传</h4>
                    </div>

                    <div id="uploader">
                        <div class="queueList">
                            <div id="dndArea" class="placeholder">
                                <div id="filePicker"></div>
                                <p>或将照片拖到这里</p>
                            </div>
                        </div>
                        <div class="statusBar" style="display:none;">
                            <div class="progress">
                                <span class="text">0%</span>
                                <span class="percentage"></span>
                            </div>
                            <div class="info"></div>
                            <div class="btns">
                                <div id="filePicker2"></div>
                                <div class="uploadBtn">开始上传</div>
                            </div>
                        </div>
                        <div id="imgs"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <button type="button" class="btn btn-primary image_submit">确定</button>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('js')
    <script src="/vendor/html5-fileupload/jquery.html5-fileupload.js"></script>
    <script type="text/javascript" src="/vendor/webupload/dist/webuploader.js"></script>
    <script type="text/javascript" src="/vendor/webupload/upload.js"></script>
    <script src="/vendor/Sortable/Sortable.min.js"></script>
    <script>
        $(function () {

            var el = document.getElementById('items');
            var sortable = Sortable.create(el,{
                animation: 300,
                delay: 10,
            });

            var ell = document.getElementById('items1');
            var sortable = Sortable.create(ell,{
                animation: 300,
                delay: 10,
            });

            var image_num = 1;
            //赋值全局变量，判断哪个上传
            $('.many_upload1').click(function () {
                image_num = 1;
            });

            $('.many_upload2').click(function () {
                image_num = 2;
            })

            $('#model').on("shown.bs.modal", function () {
                $(window).trigger("resize");
            })

            $('.image_submit').click(function () {

                var imgsArr = new Array();
                var nihao = $("#imgs input[type=hidden]");

                if (nihao.serializeArray().length == 0) {
                    layer.msg('请上传一张！', {icon: 5});
                    return false;
                }

                nihao.each(function () {
                    imgsArr.push($(this).val());
                });


                var html = '';
                $.each(imgsArr, function (index, item) {
                    html += '<li>' +
                        '<span class="mailbox-attachment-icon has-img"><img class="img_g" src="' + item + '" alt="Attachment"></span>' +
                        '<div class="mailbox-attachment-info">' +
                        '<input type="hidden" name="imgs' + image_num + '[]" value="' + item + '">' +
                        '<a href="javacript:void(0);" class="mailbox-attachment-name del_son"><i class="fa fa-close"></i></a>' +
                        '</div>' +
                        '</li>';
                })
                if (image_num == 1) {
                    $('ul.clearfix1').append(html);
                }

                if (image_num == 2) {
                    $('ul.clearfix2').append(html);
                }


                //关闭模态框
                $('#model').modal('hide')
            })


            $('#model').on('hidden.bs.modal', function (e) {

                for (var i = 0; i < uploader.getFiles().length; i++) {
                    uploader.removeFile(uploader.getFiles()[i]);
                }
                uploader.reset();
                $('#imgs').html('');

            })

            //删除图片
            $(document).on('click', '.del_son', function () {

                $(this).parents('li').remove();
            })



            //表单提交
            $('.submits').click(function () {

                var data = $('form').serialize();

                $.ajax({
                    type: 'POST',
                    url: "{{route('profit.screen.store')}}",
                    data: data,
                    dataType: 'json',
                    success: function (info) {

                        if (info.status == 1) {

                            toastr.success(info.msg);
                            setTimeout(function () {
                                window.location = "{{route('profit.screen.index')}}";
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