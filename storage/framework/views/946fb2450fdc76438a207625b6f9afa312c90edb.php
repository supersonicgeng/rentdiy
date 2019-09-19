

<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                商品列表
                <small>商品打标签</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" method="POST" action="<?php echo e(route('shop.product.updateTag',$goods->id)); ?>">
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
                                        <label class="col-sm-2 control-label">商品名称</label>
                                        <div class="col-sm-5">
                                            <input class="form-control" type="text" value="<?php echo e($goods->title); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">关联标签</label>
                                        <div class="col-sm-7">
                                            <div class="box-body table-responsive">
                                                <table class="table table-bordered table-hover">
                                                    <tbody>
                                                    <tr>
                                                        <th>标签ID</th>
                                                        <th>标签名称</th>
                                                        <th>被使用次数</th>
                                                        <th>操作</th>
                                                    </tr>
                                                    <?php if($goods->tags->count()> 0): ?>
                                                        <?php $__currentLoopData = $goods->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr>
                                                                <td style="display: none">
                                                                    <input type="text" name="tag_id[]" value="<?php echo e($tag->id); ?>">
                                                                </td>
                                                                <td><?php echo e($tag->id); ?></td>
                                                                <td><?php echo e($tag->name); ?></td>
                                                                <td><?php echo e($tag->be_use_time); ?></td>
                                                                <td>
                                                                    <a class="btn btn-danger btn-xs remove_tag" href="javascript:void(0);" data-url=""><i class="fa fa-trash"></i> 移除</a>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"></label>

                                        <a class="btn btn-app check_tag" href="javascript:;">
                                            <i class="fa fa-plus text-blue"></i> 添加标签
                                        </a>

                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-7">
                                    <div class="btn-group pull-left">
                                        <button type="submit" class="btn btn-info pull-right submits"
                                                data-loading-text="<i class='fa fa-spinner fa-spin '></i> 提交">保存
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
        $(function () {
            //移除标签
            $(document).on('click', '.remove_tag', function () {
                $(this).parents('tr').remove();
            })

            $('.check_tag').click(function () {

                var ids = $("input[name='tag_id[]']").serialize();

                layer.open({
                    title: '选择标签',
                    type: 2,
                    shadeClose: true,
                    tipsMore: false,
                    shade: [0.5, '#393D49'],
                    maxmin: true, //开启最大化最小化按钮
                    area: ['50%', '70%'],
                    content: '/admin/common/tag?' + ids

                });
            })
        })
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>