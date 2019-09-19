

<?php $__env->startSection('css'); ?>
    <style>
        .editable-click {
            border-bottom: dashed 1px #0088cc
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                系统管理
                <small>菜单与权限</small>
            </h1>
            
            
            
            
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border" style="height:51px;">
                            <div class="pull-left">
                                <a class="btn btn-primary btn-sm add" href="javascrip:;"><i
                                            class="fa fa-save"></i> 新增</a>
                            </div>
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>权限名称</th>
                                    <th>节点地址</th>
                                    <th>图标</th>
                                    <th>排序</th>
                                    <th>创建时间</th>
                                    <th width="124">操作</th>
                                </tr>
                                <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <span class="editable editable-click"><?php echo e($permission->label); ?></span>
                                        </td>
                                        <td>
                                            <span><?php echo e($permission->name); ?></span>
                                        </td>
                                        <td align="center">
                                            <i class="<?php echo e($permission->icon); ?>"></i>
                                        </td>

                                        <td>
                                            <?php echo e($permission->sort_order); ?>

                                        </td>
                                        <td>
                                            <?php echo e($permission->created_at); ?>

                                        </td>
                                        <td>
                                            <a class="btn btn-primary btn-xs edit"
                                               href="javascript:;"
                                               data-url="<?php echo e(route('system.permission.edit',$permission->id)); ?>"><i
                                                        class="fa fa-edit"></i> 编辑</a>
                                            <a class="btn btn-danger btn-xs delete_genius" href="javascript:void(0);"
                                               data-url="<?php echo e(route('system.permission.destroy',$permission->id)); ?>"><i
                                                        class="fa fa-trash"></i> 删除</a>
                                        </td>
                                    </tr>
                                    <?php $__currentLoopData = $permission->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>

                                                　　　├ <span class="editable editable-click"><?php echo e($child->label); ?></span>
                                            </td>
                                            <td>
                                                <span><?php echo e($child->name); ?></span>
                                            </td>
                                            <td align="center">
                                                <i class="<?php echo e($child->icon); ?>"></i>
                                            </td>

                                            <td>
                                                <?php echo e($child->sort_order); ?>

                                            </td>
                                            <td>
                                                <?php echo e($child->created_at); ?>

                                            </td>
                                            <td>
                                                <a class="btn btn-primary btn-xs edit"
                                                   href="javascript:;"
                                                   data-url="<?php echo e(route('system.permission.edit',$child->id)); ?>"><i
                                                            class="fa fa-edit"></i> 编辑</a>
                                                <a class="btn btn-danger btn-xs delete_genius"
                                                   href="javascript:void(0);"
                                                   data-url="<?php echo e(route('system.permission.destroy',$child->id)); ?>"><i
                                                            class="fa fa-trash"></i> 删除</a>
                                            </td>
                                        </tr>
                                        <?php $__currentLoopData = $child->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td>

                                                    　　　│　　　├ <span class="editable editable-click"><?php echo e($c->label); ?></span>
                                                </td>
                                                <td>
                                                    <span><?php echo e($c->name); ?></span>
                                                </td>
                                                <td align="center">
                                                    <i class="<?php echo e($c->icon); ?>"></i>
                                                </td>

                                                <td>
                                                    <?php echo e($c->sort_order); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($c->created_at); ?>

                                                </td>
                                                <td>
                                                    <a class="btn btn-primary btn-xs edit"
                                                       href="javascript:;"
                                                       data-url="<?php echo e(route('system.permission.edit',$c->id)); ?>"><i
                                                                class="fa fa-edit"></i> 编辑</a>
                                                    <a class="btn btn-danger btn-xs delete_genius"
                                                       href="javascript:void(0);"
                                                       data-url="<?php echo e(route('system.permission.destroy',$c->id)); ?>"><i
                                                                class="fa fa-trash"></i> 删除</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


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




            //新增模态框
            $('.add').click(function () {
                top.layer.open({
                    title: '  ',
                    type: 2,
                    shadeClose: true,
                    tipsMore: false,
                    shade: [0.5, '#393D49'],
                    maxmin: true, //开启最大化最小化按钮
                    area: ['500px', '550px'],
                    content: '<?php echo e(route('system.permission.create')); ?>'

                });
            })

            //编辑模态框
            $('.edit').click(function () {
                var url = $(this).data('url');
                // console.log(url);return false;
                top.layer.open({
                    title: '  ',
                    type: 2,
                    shadeClose: true,
                    tipsMore: false,
                    shade: [0.5, '#393D49'],
                    maxmin: true, //开启最大化最小化按钮
                    area: ['500px', '550px'],
                    content: url

                });
            })


        })
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>