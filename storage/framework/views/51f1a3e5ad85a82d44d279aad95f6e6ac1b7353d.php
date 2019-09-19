

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="/vendor/webupload/style.css"/>
    <link rel="stylesheet" href="/vendor/webupload/dist/webuploader.css"/>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                物料管理
                <small>新增物料</small>
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
                                        <label class="col-sm-2 control-label">选择角色<span
                                                    style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-7">
                                            <button data-url="<?php echo e(route('common.role.index')); ?>" data-title="选择角色"
                                                    type="button"
                                                    class="btn btn-info btn-sm check_model">选择角色
                                            </button>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"></label>
                                        <div class="col-sm-7">
                                            <table class="table table-bordered table-hover">

                                            </table>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">选择位置<span
                                                    style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-3">
                                            <select class="form-control" name="position">
                                                <option value="-1">页面展示位置</option>
                                                <option value="1">找乐子</option>
                                                <option value="2">找麦子</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">文案介绍<span
                                                    style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-7">
                                            <textarea name="introduce" id="" cols="80" rows="5"
                                                      placeholder="介绍最多不超过150个字"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">内容类型<span
                                                    style="color: red;">&nbsp*</span></label>

                                        <label class="radio-inline">
                                            <input type="radio" class="image_vedio" name="type" value="1" checked>&nbsp图片&nbsp&nbsp
                                            <input type="radio" class="image_vedio" name="type" value="2">&nbsp视频
                                        </label>
                                    </div>
                                    <div class="form-group" id="images">
                                        <label class="col-sm-2 control-label"></label>
                                        <div class="col-sm-7">

                                            <div id="uploader">
                                                <div class="queueList">
                                                    <div id="dndArea" class="placeholder">
                                                        <div id="filePicker"></div>
                                                        <p>或将照片拖到这里，最多上传9张图</p>
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
                                        </div>

                                    </div>
                                    <div class="form-group" style="display: none" id="video">
                                        <label class="col-sm-2 control-label"></label>
                                        <div class="col-sm-7">
                                            <video height="400" width="550" src="" controls="controls"></video>
                                            <input type="hidden" name="video">
                                            <input type="file" id="video_upload" style="display:none;">
                                            <div>
                                                <button type="button" class="btn btn-info btn-sm scsp"><i id="loading_v"
                                                                                                          class="fa fa-fw fa-cloud-upload"></i>上传视频
                                                </button>
                                                <span style="color: red;">视频大小不超过30M</span>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-group" style="display: none">
                                        <label class="col-sm-2 control-label">是否<span style="color: red;">&nbsp*</span></label>
                                        <label class="radio-inline">
                                            <input type="radio" name="is_rel_pro" value="1" checked>&nbsp是&nbsp&nbsp
                                        </label>

                                    </div>
                                    <div class="form-group goods_ids">
                                        <label class="col-sm-2 control-label">关联商品</label>
                                        <div class="col-sm-7">
                                            <div class="margin-bottom">
                                                <button data-url="<?php echo e(route('common.product.single')); ?>" type="button"
                                                        data-title="选择商品"
                                                        class="btn btn-info btn-sm check_product">选择商品
                                                </button>
                                            </div>
                                            <div>
                                                <ul class="mailbox-attachments clearfix">

                                                </ul>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">分享量</label>
                                        <div class="col-sm-3">
                                            <input class="form-control" name="share_amt" value="0" placeholder=""
                                                   type="text">

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">下载量</label>
                                        <div class="col-sm-3">
                                            <input class="form-control" name="down_amt" value="0" placeholder=""
                                                   type="text">

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">发布时间<span
                                                    style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-3">
                                            <input class="form-control" id="test" AUTOCOMPLETE="off" name="publish_time"
                                                   value="<?php echo e(Timeformat(time())); ?>" placeholder="" type="text">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-7">
                                    <div class="btn-group pull-right">
                                        <button type="button" class="btn btn-info pull-right submits">提交</button>
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


<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script src="/vendor/html5-fileupload/jquery.html5-fileupload.js"></script>
    <script type="text/javascript" src="/vendor/webupload/dist/webuploader.js"></script>
    <script type="text/javascript" src="/vendor/webupload/upload.js"></script>
    <script>
        $(function () {

            $(document).on('click', '.goods_del', function () {

                $(this).parents('li').remove();
            })

            //表单提交
            $('.submits').click(function () {

                var data = $('form').serialize();

                $.ajax({
                    type: 'POST',
                    url: "<?php echo e(route('material.supply.store')); ?>",
                    data: data,
                    dataType: 'json',
                    success: function (info) {

                        if (info.status == 1) {

                            toastr.success(info.msg);
                            setTimeout(function () {
                                window.location = "<?php echo e(route('material.supply.index')); ?>"
                            }, 800);
                        } else {
                            toastr.error(info.msg);
                        }

                    }

                })
                return false;
            })


            $("input[name='is_rel_pro']").on('ifClicked', function (event) {


                var is_and = $(this).val();
                if (is_and == 1) {
                    $('.goods_ids').show();
                } else {
                    $('.goods_ids').hide();
                }
            })


            $('.check_product').click(function () {

                var url = $(this).data('url');


                layer.open({
                    title: '选择商品',
                    type: 2,
                    shadeClose: true,
                    tipsMore: false,
                    shade: [0.5, '#393D49'],
                    maxmin: true, //开启最大化最小化按钮
                    area: ['85%', '90%'],
                    content: url

                });
            })

            $('.image_vedio').on('ifChecked', function (event) {
                var a = $(this).val();//图片视频上传方式

                //选择图片
                if (a == 1) {
                    $('#images').show();
                    $('#video').hide();
                }
                //选择视频
                if (a == 2) {
                    $('#images').hide();
                    $('#video').show();
                }

            });

            $('.scsp').click(function () {
                $("#video_upload").click();
            })

            //时间选择器
            laydate.render({
                elem: '#test'
                , type: 'datetime'
            });
        })
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>