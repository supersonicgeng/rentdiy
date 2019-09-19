<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                运营管理
                <small>新增内容</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" method="POST" action="<?php echo e(route('operate.subject.store')); ?>">
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
                                        <label class="col-sm-2 control-label">所在运营位<span
                                                    style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-5">
                                            <select class="form-control" name="matter_id">
                                                <?php $__currentLoopData = $matters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $matter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($matter->id); ?>" <?php if($matter->id == $matter_id): ?> selected <?php endif; ?>><?php echo e($matter->m_title); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">名称<span style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-5">
                                            <input class="form-control" type="text" name="title"
                                                   value="<?php echo e(old('title')); ?>" required>
                                            <input type="hidden" value="1" name="matter_id">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">图片<span
                                                    style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-7">
                                            <input id="cover" type="hidden" name="image" value="<?php echo e(old('image')); ?>">
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
                                                 src="<?php echo e(old('image') ?old('image'):'/avatar.png'); ?>" alt=""
                                                 style="height: 200px;width:200px">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">跳转类型<span
                                                    style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-5">
                                            <select class="form-control" name="type">
                                                <option value="1">商品详情页</option>
                                                <option value="2" <?php if(old('type') == 2): ?> selected <?php endif; ?>>商品专题页</option>
                                                <option value="3" <?php if(old('type') == 3): ?> selected <?php endif; ?>>URL活动页</option>
                                                <option value="4" <?php if(old('type') == 4): ?> selected <?php endif; ?>>榜单列表</option>
                                                <option value="6" <?php if(old('type') == 6): ?> selected <?php endif; ?>>会员开通页面</option>
                                                <option value="7" <?php if(old('type') == 7): ?> selected <?php endif; ?>>端内指定页面</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">排序</label>
                                        <div class="col-sm-5">
                                            <input class="form-control" name="sort" value="<?php echo e(old('sort')); ?>"
                                                   placeholder="值越大排序越靠前" type="text">

                                        </div>
                                    </div>
                                    <div class="form-group" id="zt" style="display: none">
                                        <label class="col-sm-2 control-label">指定专题<span
                                                    style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-7">
                                            <div class="margin-bottom">
                                                <button data-url="<?php echo e(route('common.special.index')); ?>" data-title="选择专题"
                                                        type="button"
                                                        class="btn btn-info btn-sm check_model">选择专题
                                                </button>
                                            </div>
                                            <div>
                                                <textarea name="special_id" id="special_id" cols="80" rows="5"
                                                          readonly></textarea>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group" id="sp">
                                        <label class="col-sm-2 control-label">指定商品<span
                                                    style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-10">
                                            <div class="margin-bottom">
                                                <button data-url="<?php echo e(route('common.product.single')); ?>" data-url="指定商品"
                                                        type="button"
                                                        class="btn btn-info btn-sm check_model">选择商品
                                                </button>
                                            </div>
                                            <ul class="mailbox-attachments clearfix">

                                            </ul>
                                        </div>
                                    </div>

                                    <div class="form-group" id="wy" style="display: none">
                                        <label class="col-sm-2 control-label">URL<span style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-5">
                                            <input class="form-control" name="url" value="" placeholder="" type="text">

                                        </div>
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


<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
        /***删除商品***/

        $(document).on('click', '.goods_del', function () {
            $(this).parents('li').remove();
        })

        $('select').change(function () {
            var type = $(this).val();

            if (type == 1) {
                $('#sp').show();
                $('#zt').hide();
                $('#wy').hide();
                $('#wy').find('input').val('');
            }

            if (type == 2 || type==4) {
                $('#sp').hide();
                $('#zt').show();
                $('#wy').hide();
                $('#wy').find('input').val('');
            }

            if (type == 3) {
                $('#sp').hide();
                $('#zt').hide();
                $('#wy').show();
            }

            if (type == 6) {
                $('#sp').hide();
                $('#zt').hide();
                $('#wy').hide();
                $('#wy').find('input').val('');
            }

            if (type == 7) {
                $('#sp').hide();
                $('#zt').hide();
                $('#wy').show();
            }
        })
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>