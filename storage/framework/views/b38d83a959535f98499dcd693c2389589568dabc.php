

<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                首页配置
                <small>专题列表</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <div class="pull-left">
                                <a class="btn btn-primary btn-sm" href="<?php echo e(route('home.project.create')); ?>"><i
                                            class="fa fa-save"></i> 新增</a>
                            </div>
                            <div class="search-form-inline form-inline pull-right">
                                <small style="color: red;">注释说明:没有配置任何内容，默认隐藏专题区域</small>
                            </div>

                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>标题</th>
                                    <th>专题样式</th>
                                    <th>排序</th>
                                    <th>上架/下架</th>
                                    <th>操作</th>
                                </tr>
                                <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr data-id="<?php echo e($pro->id); ?>">
                                        <td><?php echo e($pro->id); ?></td>
                                        <td><?php echo e($pro->title); ?></td>
                                        <td>
                                            <?php if($pro->matter_id ==3): ?>
                                                专题-图
                                            <?php else: ?>
                                                专题-榜单
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($pro->sort); ?></td>
                                        <td><?php echo is_something('is_on',$pro); ?></td>
                                        <td>
                                            <a class="btn btn-primary btn-xs" href="<?php echo e(route('home.project.edit',$pro->id)); ?>"><i class="fa fa-edit"></i> 编辑</a>
                                            <a class="btn btn-danger btn-xs delete_genius" href="javascript:void(0);"
                                               data-url="<?php echo e(route('home.project.destroy',$pro->id)); ?>"><i class="fa fa-trash"></i> 删除</a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>


<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
    <script>
        $(function () {
            //改变状态
            $('.change_attr').click(function () {
                var attr = $(this).data('attr');
                var id = $(this).parents('tr').data('id');

                $.ajax({
                    type: 'PATCH',
                    data: {attr: attr, id: id},
                    url: '<?php echo e(route('home.project.change_attr')); ?>',
                    success: function (data) {
                        if (data.status == 1) {
                            toastr.success(data.msg);
                        } else {
                            toastr.error(data.msg);
                            return false;
                        }
                        setTimeout(function () {
                            window.location.reload();//页面刷新
                        }, 150);
                    }
                })
            })

        })
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>