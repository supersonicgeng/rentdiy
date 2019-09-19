

<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                首页配置
                <small>快捷入口</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <div class="pull-left">
                                <a class="btn btn-primary btn-sm" href="<?php echo e(route('home.intry.create')); ?>"><i class="fa fa-save"></i> 新增</a>
                            </div>
                            <div class="search-form-inline form-inline pull-right">
                             <small style="color: red;">注释说明:没有配置任何内容，默认隐藏快捷入口区域,最多同时上架10个</small>
                            </div>

                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>标题</th>
                                    
                                    <th>图标</th>
                                    <th>排序</th>
                                    <th>上架/下架</th>
                                    <th>操作</th>
                                </tr>
                                <?php $__currentLoopData = $intrys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $intry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr data-id="<?php echo e($intry->id); ?>">
                                    <td><?php echo e($intry->id); ?></td>
                                    <td><?php echo e($intry->title); ?></td>
                                    <td><img src="<?php echo e($intry->image); ?>" alt="" style="height: 50px;width: 50px"></td>
                                    <td><?php echo e($intry->sort); ?></td>
                                    <td><?php echo is_something('is_on',$intry); ?></td>

                                    <td>
                                        <a class="btn btn-primary btn-xs" href="<?php echo e(route('home.intry.edit',$intry->id)); ?>"><i class="fa fa-edit"></i> 编辑</a>
                                        <a class="btn btn-danger btn-xs delete_genius" href="javascript:void(0);" data-url="<?php echo e(route('home.intry.destroy',$intry->id)); ?>"><i class="fa fa-trash"></i> 删除</a>
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
                    url: '<?php echo e(route('home.intry.change_attr')); ?>',
                    success: function (data) {
                        if (data.status == 1) {
                            toastr.success(data.msg);
                        } else {
                            toastr.error(data.msg);
                            return false;
                        }
                        setTimeout(function(){
                            window.location.reload();//页面刷新
                        },150);
                    }
                })
            })

        })
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>