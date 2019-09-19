<?php $__env->startSection('css'); ?>
    <style type="text/css">
        .toolbar {
            border: 1px solid #ccc;

        }

        .text {
            border: 1px solid #ccc;
            min-height: 400px;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                物料管理
                <small>编辑文章</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal">
                        <?php echo csrf_field(); ?>
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
                                        <label class="col-sm-2 control-label">选择栏目<span
                                                    style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-3">
                                            <select class="form-control" name="category">
                                                <option value="-1">页面展示位置</option>
                                                <?php $__currentLoopData = $cates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($cate->id); ?>" <?php if($cate->id == $article->category): ?> selected <?php endif; ?>><?php echo e($cate->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">文章标题<span
                                                    style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-3">
                                            <input class="form-control" name="title" placeholder="名称最多6个字"
                                                   value="<?php echo e($article->title); ?>"
                                                   type="text" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">封面图<span
                                                    style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-7">
                                            <input id="cover" type="hidden" name="cover" value="<?php echo e($article->cover); ?>">
                                            <input type="file" style="display: none" id="image_upload">
                                            <button type="button" class="btn btn-success btn-sm upload_image">
                                                <i id="loading" class="fa fa-fw fa-cloud-upload"></i>上传图片
                                            </button>
                                            
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"></label>
                                        <div class="col-sm-7">
                                            <img data-action="zoom" id="cover_show"
                                                 src="<?php echo e($article->cover); ?>" alt=""
                                                 style="height: 200px;width:200px">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">是否上架</label>
                                        <div class="col-sm-3">
                                            <label class="checkbox-inline">
                                                <input type="radio" name="is_on" value="1" <?php if($article->is_on ==1): ?> checked <?php endif; ?>> 是
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="radio" name="is_on" value="0" checked> 否
                                            </label>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">文章内容</label>
                                        <div class="col-sm-10">
                                            <div id="div1" class="toolbar">
                                            </div>
                                            <div style="padding: 3px 0; color: #ccc"></div>
                                            <div id="div2" class="text"> <!--可使用 min-height 实现编辑区域自动增加高度-->
                                              <?php echo $article->content; ?>

                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">排序</label>
                                        <div class="col-sm-3">
                                            <input class="form-control" name="sort"
                                                   value="<?php echo e($article->sort); ?>"
                                                   placeholder="" type="text" required>

                                        </div>
                                    </div>
                                    <input type="hidden" name="content">

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


<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script src="/vendor/wangEditor/release/wangEditor.min.js"></script>
    <script>
        $(function () {

            var E = window.wangEditor
            var editor = new E('#div1', '#div2');
            // 限制一次最多上传 5 张图片
            editor.customConfig.uploadImgMaxLength = 5;
            // 将图片大小限制为 6M
            editor.customConfig.uploadImgMaxSize = 6 * 1024 * 1024;
            // 配置服务器端地址
            editor.customConfig.uploadImgServer = '/admin/editorUpload';
            editor.customConfig.uploadFileName = 'files[]'
            editor.customConfig.customAlert = function (info) {
                // info 是需要提示的内容
                alert('自定义提示：' + info)
            };
            editor.create();


            //表单提交
            $('.submits').click(function () {

                var content = editor.txt.html();
                $('input[name=content]').val(content);

                var data = $('form').serialize();

                $.ajax({
                    type: 'PUT',
                    url: "<?php echo e(route('material.article.update',$article->id)); ?>",
                    data: data,
                    dataType: 'json',
                    success: function (info) {

                        if (info.status == 1) {

                            toastr.success(info.msg);
                            setTimeout(function () {
                                window.location = "<?php echo e(route('material.article.index')); ?>"
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