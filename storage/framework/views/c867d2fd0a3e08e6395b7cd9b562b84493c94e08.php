

<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                物料管理
                <small>角色列表</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <div class="pull-left">
                                <a class="btn btn-primary btn-sm" href="<?php echo e(route('material.person.create')); ?>"><i
                                            class="fa fa-save"></i> 新增</a>
                            </div>
                            <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                                <form>
                                    <div class="input-daterange input-group input-group-sm">
                                        <input class="form-control" name="mid" value="<?php echo e(Request::input('mid')); ?>"
                                               placeholder="输入角色ID搜索"
                                               type="text">
                                    </div>
                                    <div class="input-daterange input-group input-group-sm">
                                        <input class="form-control" name="mname" value="<?php echo e(Request::input('mname')); ?>"
                                               placeholder="输入角色名称搜索"
                                               type="text">
                                    </div>
                                    <button type="submit" class="btn btn-default btn-sm">搜索</button>
                                    <a href="<?php echo e(route('material.person.index')); ?>" class="btn btn-default btn-sm">重置</a>
                                </form>
                            </div>

                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>名称</th>
                                    <th>头像</th>
                                    <th>角色title</th>
                                    <th>角色分类</th>
                                    <th>标签</th>
                                    <th>排序</th>
                                    <th>置顶</th>
                                    <th>操作</th>
                                </tr>
                                <?php $__currentLoopData = $matusers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr data-id="<?php echo e($m->mid); ?>">
                                        <td><?php echo e($m->mid); ?></td>
                                        <td><?php echo e($m->mname); ?></td>
                                        <td>
                                            <img src="<?php echo e($m->imageurl); ?>" alt="" style="height: 50px;width: 50px">
                                        </td>
                                        <td><?php echo e($m->title); ?></td>
                                        <td>
                                            <?php echo e($m->cat_name); ?>

                                        </td>
                                        <td><?php echo e($m->tag_name); ?></td>
                                        <td><?php echo e($m->sort); ?></td>
                                        <td>
                                          <?php echo is_something('toptag',$m); ?>

                                        </td>
                                        <td>
                                            <a class="btn btn-primary btn-xs"
                                               href="<?php echo e(route('material.person.edit',$m->mid)); ?>"><i
                                                        class="fa fa-edit"></i> 编辑</a>
                                            <a class="btn btn-danger btn-xs delete_genius" href="javascript:void(0);"
                                               data-url="<?php echo e(route('material.person.destroy',$m->mid)); ?>"><i
                                                        class="fa fa-trash"></i> 删除</a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <div class="pull-right">
                                <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                                    <form>
                                        <div class="input-daterange input-group input-group-sm">
                                            共<?php echo e($matusers->total()); ?>条&nbsp
                                        </div>
                                        <div class="input-daterange input-group input-group-sm">
                                            <?php echo e($matusers->appends(Request::all())->links()); ?>

                                        </div>

                                    </form>
                                </div>

                            </div>
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
            $('.change_attr').click(function () {
                var attr = $(this).data('attr');
                var id = $(this).parents('tr').data('id');

                $.ajax({
                    type: 'PATCH',
                    data: {attr: attr, id: id},
                    url: '<?php echo e(route('material.person.change_attr')); ?>',
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