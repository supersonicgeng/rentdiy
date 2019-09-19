

<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                首页管理
                <small>首页banner</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" method="POST" action="<?php echo e(route('home.banner.update',$banner->id)); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
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
                                        <label class="col-sm-2 control-label">banner名称<span
                                                    style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-5">
                                            <input class="form-control" type="text" name="title"
                                                   value="<?php echo e(old('title')?old('title'):$banner->title); ?>">
                                            <input type="hidden" value="1" name="matter_id">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">商品头图<span
                                                    style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-7">
                                            <input id="cover" type="hidden" name="image"
                                                   value="<?php echo e(old('image')?old('image'):$banner->image); ?>">
                                            <input type="file" style="display: none" id="image_upload">
                                            <button type="button" class="btn btn-success btn-sm upload_image">
                                                <i id="loading" class="fa fa-fw fa-cloud-upload"></i>上传封面图
                                            </button>
                                            <small style="color: red">&nbsp请上传750px*400px尺寸图片</small>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"></label>
                                        <div class="col-sm-7">
                                            <img data-action="zoom" id="cover_show"
                                                 src="<?php echo e(old('image')?old('image'):$banner->image); ?>" alt=""
                                                 style="height: 200px;width:200px">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">跳转类型<span
                                                    style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-5">
                                            <select class="form-control" name="type">
                                                <option value="1" <?php if($banner->type ==1): ?> selected <?php endif; ?>>商品详情页</option>
                                                <option value="2" <?php if($banner->type ==2): ?> selected <?php endif; ?>>商品专题页</option>
                                                <option value="3" <?php if($banner->type ==3): ?> selected <?php endif; ?>>URL活动页</option>
                                                <option value="6" <?php if($banner->type ==6): ?> selected <?php endif; ?>>开通会员页面</option>
                                                <option value="7" <?php if($banner->type ==7): ?> selected <?php endif; ?>>端内指定页</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">排序<span style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-5">
                                            <input class="form-control" name="sort" value="<?php echo e($banner->sort); ?>"
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
                                                          readonly><?php echo e($banner->special_id); ?></textarea>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group" id="sp">
                                        <label class="col-sm-2 control-label">指定商品<span
                                                    style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-7">
                                            <div class="margin-bottom">
                                                <button data-url="<?php echo e(route('common.product.single')); ?>" data-url="指定商品"
                                                        type="button"
                                                        class="btn btn-info btn-sm check_model">选择商品
                                                </button>
                                            </div>
                                            <div>

                                                <ul class="mailbox-attachments clearfix">
                                                    <?php if($good !=''): ?>
                                                        <li data-id="<?php echo e($good->id); ?>">
                                                        <span class="mailbox-attachment-icon has-img"><img
                                                                    src="<?php echo e($good->pict_url); ?>"
                                                                    style="width:100%;height: 120px"></span>
                                                            <input type="hidden" name="good_id" value="<?php echo e($good->id); ?>">
                                                            <div class="mailbox-attachment-info">
                                                                <a href="javascript:void(0);"
                                                                   class="mailbox-attachment-name"><?php echo e(str_limit($good->title,50,'...')); ?></a>
                                                                <span class="mailbox-attachment-size">价格: <?php echo e($good->zk_final_price); ?><a
                                                                            href="#"
                                                                            class="btn btn-default btn-xs pull-right goods_del"><i
                                                                                class="fa fa-trash-o text-red"></i></a></span>
                                                            </div>
                                                        </li>

                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group" id="wy" style="display: none">
                                        <label class="col-sm-2 control-label">URL<span style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-5">
                                            <input class="form-control" name="url" value="<?php echo e($banner->url); ?>"
                                                   placeholder="" type="text">

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

        $(document).on('click', '.goods_del', function () {

            $(this).parents('li').remove();
        })

        var a = $('select').val();

        if (a == 1) {
            $('#sp').show();
            $('#zt').hide();
            $('#wy').hide();
        }

        if (a == 2) {
            $('#sp').hide();
            $('#zt').show();
            $('#wy').hide();
        }

        if (a == 3) {
            $('#sp').hide();
            $('#zt').hide();
            $('#wy').show();
        }

        if (a == 6) {
            $('#sp').hide();
            $('#zt').hide();
            $('#wy').hide();
        }

        $('select').change(function () {
            var type = $(this).val();

            if(type ==1){
                $('#sp').show();
                $('#zt').hide();
                $('#wy').hide();
                $('#wy').find('input').val('');
            }

            if(type ==2){
                $('#sp').hide();
                $('#zt').show();
                $('#wy').hide();
                $('#wy').find('input').val('');
            }

            if(type ==3){
                $('#sp').hide();
                $('#zt').hide();
                $('#wy').show();
            }

            if(type ==6){
                $('#sp').hide();
                $('#zt').hide();
                $('#wy').hide();
                $('#wy').find('input').val('');
            }

            if(type ==7){
                $('#sp').hide();
                $('#zt').hide();
                $('#wy').show();
            }
        })
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>