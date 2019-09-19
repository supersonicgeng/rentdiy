

<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                系统管理
                <small>角色列表</small>
            </h1>
            
            
            
            
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border" style="height:51px;">
                            <div class="pull-left">
                                <a class="btn btn-primary btn-sm" href="<?php echo e(route('system.role.create')); ?>"><i
                                            class="fa fa-save"></i> 新增</a>
                            </div>
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-hover">
                                <tbody>
                                <tr>
                                    <th>编号</th>
                                    <th>角色名</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($role->id); ?></td>
                                        <td><span class="label label-success"><?php echo e($role->name); ?></span></td>
                                        <td><?php echo e($role->created_at); ?></td>
                                        <td>
                                            <a class="btn btn-primary btn-xs" href="<?php echo e(route('system.role.edit',$role->id)); ?>">
                                                <i class="fa fa-edit"></i> 编辑
                                            </a>
                                            <?php if($role->name != '超级管理员'): ?>
                                            <a class="btn btn-danger btn-xs delete_genius" href="javascript:void(0);" data-url="<?php echo e(route('system.role.destroy',$role->id)); ?>"><i class="fa fa-trash"></i> 删除</a>
                                            <?php endif; ?>
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
<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>