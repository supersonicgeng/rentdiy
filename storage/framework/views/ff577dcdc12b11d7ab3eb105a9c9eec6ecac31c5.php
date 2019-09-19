

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="/vendor/webupload/style.css"/>
    <link rel="stylesheet" href="/vendor/webupload/dist/webuploader.css"/>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                配置管理
                <small>App启动图</small>
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
                                        <label class="col-sm-2 control-label">启动闪图16:9</label>
                                        <div class="col-sm-7">
                                            <input id="cover" type="hidden" name="figure1" value="<?php echo e($figure->figure1); ?>">
                                            <input type="file" style="display: none" id="image_upload">
                                            <button type="button" class="btn btn-success btn-sm upload_image">
                                                <i id="loading" class="fa fa-fw fa-cloud-upload"></i>更改图片
                                            </button>
                                            
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"></label>
                                        <div class="col-sm-7">
                                            <img id="cover_show" data-action="zoom" src="<?php echo e($figure->figure1); ?>" alt=""
                                                 style="height: 320px;width:180px">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">启动闪图19:9</label>
                                        <div class="col-sm-7">
                                            <input id="cover1" type="hidden" name="figure2"
                                                   value="<?php echo e($figure->figure2); ?>">
                                            <input type="file" style="display: none" id="image_upload1">
                                            <button type="button" class="btn btn-success btn-sm upload_image1">
                                                <i id="loading1" class="fa fa-fw fa-cloud-upload"></i>更改图片
                                            </button>
                                            
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"></label>
                                        <div class="col-sm-7">
                                            <img id="cover_show1" data-action="zoom" src="<?php echo e($figure->figure2); ?>" alt=""
                                                 style="height: 380px;width:180px">
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

                                            <ul class="mailbox-attachments clearfix1">
                                                <?php $__currentLoopData = explode(',',$figure->imgs1); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li>
                                                    <span class="mailbox-attachment-icon has-img"><img class="img_g"
                                                                                                       src="<?php echo e($img); ?>"
                                                                                                       alt="Attachment"></span>
                                                        <input type="hidden" name="imgs1[]" value="<?php echo e($img); ?>">
                                                        <div class="mailbox-attachment-info">
                                                            <a href="javacript:void(0);"
                                                               class="mailbox-attachment-name del_son"><i
                                                                        class="fa fa-close"></i></a>
                                                        </div>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                            <ul class="mailbox-attachments clearfix2">
                                                <?php $__currentLoopData = explode(',',$figure->imgs2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li>
                                                    <span class="mailbox-attachment-icon has-img"><img class="img_g"
                                                                                                       src="<?php echo e($img); ?>"
                                                                                                       alt="Attachment"></span>
                                                        <input type="hidden" name="imgs2[]" value="<?php echo e($img); ?>">
                                                        <div class="mailbox-attachment-info">
                                                            <a href="javacript:void(0);"
                                                               class="mailbox-attachment-name del_son"><i
                                                                        class="fa fa-close"></i></a>
                                                        </div>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
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



<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script src="/vendor/html5-fileupload/jquery.html5-fileupload.js"></script>
    <script type="text/javascript" src="/vendor/webupload/dist/webuploader.js"></script>
    <script type="text/javascript" src="/vendor/webupload/upload.js"></script>
    <script>
        $(function () {
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

            //表单提交
            $('.submits').click(function () {

                var data = $('form').serialize();

                $.ajax({
                    type: 'POST',
                    url: "<?php echo e(route('profit.start.store')); ?>",
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>