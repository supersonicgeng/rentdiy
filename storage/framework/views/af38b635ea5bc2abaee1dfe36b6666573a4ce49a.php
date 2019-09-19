

<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                商品专题
                <small>编辑专题</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab1" data-toggle="tab">基本信息</a></li>
                                <li class="pull-right"><a href="javascript:history.back(-1)" class="btn btn-sm"
                                                          style="padding:10px 2px;"><i class="fa fa-list"></i> 返回</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">标题</label>
                                        <div class="col-sm-7">
                                            <input class="form-control" type="text" name="title"
                                                   value="<?php echo e($special->title); ?>"
                                                   placeholder="请输入标题" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">选择商品</label>
                                        <div class="col-sm-7">

                                            <a type="button" data-url="<?php echo e(route('common.product.index')); ?>"
                                               class="btn btn-success btn-sm check_product">选择商品</a>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"></label>
                                        <div class="col-sm-10">
                                            <ul class="mailbox-attachments clearfix">
                                                <?php if($goods !=''): ?>
                                                    <?php $__currentLoopData = $goods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $good): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li data-id="<?php echo e($good->id); ?>">
                                                        <span class="mailbox-attachment-icon has-img"><img
                                                                    src="<?php echo e($good->pict_url); ?>"
                                                                    style="width:100%;height: 120px"></span>
                                                            <input type="hidden" name="goods_id[]" value="<?php echo e($good->id); ?>">
                                                            <div class="mailbox-attachment-info">
                                                                <a href="javascript:void(0);"
                                                                   class="mailbox-attachment-name"><?php echo e(str_limit($good->title,40,'...')); ?></a>
                                                                <span class="mailbox-attachment-size">价格: <?php echo e($good->zk_final_price); ?><a href="#"
                                                                                                            class="btn btn-default btn-xs pull-right goods_del"><i
                                                                                class="fa fa-trash-o text-red"></i></a></span>
                                                            </div>
                                                        </li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </ul>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">是否关联分类</label>

                                        <label class="radio-inline">
                                            <input type="radio" name="is_and" value="1"
                                                   <?php if($special->is_and == 1 or $special->is_and == 2): ?>checked <?php endif; ?>>&nbsp是&nbsp&nbsp
                                            <input type="radio" name="is_and" value="0"
                                                   <?php if($special->is_and == 0): ?>checked <?php endif; ?>>&nbsp否
                                        </label>
                                    </div>

                                    <div class="is_and" <?php if($special->is_and ==0): ?>style="display: none;"<?php endif; ?>>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">选择分类</label>
                                            <div class="col-sm-7">

                                                <a type="button" data-url="<?php echo e(route('common.category.index')); ?>" class="btn btn-success btn-sm check_cate">选择分类</a>
                                                <a type="button"  class="btn btn-danger btn-sm rm_cate">清空分类</a>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label"></label>
                                            <div class="col-sm-7">
                                                <textarea name="cates_id" id="cates" cols="80" rows="5"
                                                          readonly><?php echo e($special->cates_id); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">价格区间</label>
                                            <div class="col-sm-2">
                                                <div class="input-group input-group-sm">
                                                    <input class="form-control" name="price_min"
                                                           value="<?php echo e($special->price_min); ?>" placeholder="起始价格"
                                                           type="text" required>
                                                    <span class="input-group-addon">-</span>
                                                    <input class="form-control" name="price_max"
                                                           value="<?php echo e($special->price_max); ?>" placeholder="" type="text" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-7">
                                    <div class="btn-group pull-left">
                                        <button type="button" class="btn btn-info pull-right submits"
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
        //清空分类
        $('.rm_cate').click(function () {
            $('#cates').val('');
        })

        /***删除商品***/

        $(document).on('click', '.goods_del', function () {

            $(this).parents('li').remove();
        })

        $("input[name='is_and']").on('ifClicked', function (event) {
            var is_and = $(this).val();
            if (is_and == 1) {
                $('.is_and').show();
            } else {
                $('.is_and').hide();
            }
        })

        /**
         * 选择弹框
         */
        $('.check_product').click(function () {
            var url = $(this).data('url');
            var ids = $("input[name='goods_id[]']").serializeArray();
            var d = new Array();

            $.each(ids, function() {
                d.push(this.value);//向数组中添加元素
            });

            var s = d.join(',');

            layer.open({
                title: '选择分类',
                type: 2,
                shadeClose: true,
                tipsMore: false,
                shade: [0.5, '#393D49'],
                maxmin: true, //开启最大化最小化按钮
                area: ['50%', '80%'],
                content: url + '?ids=' + d

            });
        })

        $('.check_cate').click(function () {

            var url = $(this).data('url');
            var ids = $('#cates').val();

            layer.open({
                title: '选择分类',
                type: 2,
                shadeClose: true,
                tipsMore: false,
                shade: [0.5, '#393D49'],
                maxmin: true, //开启最大化最小化按钮
                area: ['50%', '80%'],
                content: url + '?ids=' + ids

            });
        })

        $('.submits').click(function () {
            var data = $('form').serialize();

            $.ajax({
                url: "<?php echo e(route('shop.special.update',$special->id)); ?>",
                type: 'PUT',
                data: data,
                success: function (data) {
                    if (data.status == 0) {
                        toastr.error(data.msg);

                    } else {
                        toastr.success(data.msg);
                        window.setTimeout("location.href =\"<?php echo e(route('shop.special.index')); ?>\"", 1000);

                    }
                }
            })
        })
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>