

<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                搜索管理
                <small>关键字管理</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <div class="pull-left">
                                <a class="btn btn-primary btn-sm check_model" href="javascript:;" data-title="新增搜索词"
                                   data-url="<?php echo e(route('search.keyword.create')); ?>"><i class="fa fa-save"></i> 新增</a>
                            </div>
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>关键字</th>
                                    <th>排序</th>
                                    <th>置顶</th>
                                    <th>操作</th>
                                </tr>
                                <?php $__currentLoopData = $words; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $word): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr data-id="<?php echo e($word->id); ?>">
                                    <td><?php echo e($word->id); ?></td>
                                    <td><?php echo e($word->name); ?></td>
                                    <td><?php echo e($word->sort); ?></td>
                                    <td>
                                        <?php echo is_something('is_top',$word); ?>

                                    </td>
                                    <td>
                                        <a class="btn btn-primary btn-xs check_model" href="javascript:;" data-title="编辑关键字" data-url="<?php echo e(route('search.keyword.edit',$word->id)); ?>"><i class="fa fa-edit"></i> 编辑</a>
                                        <a class="btn btn-danger btn-xs delete_genius" href="javascript:void(0);"
                                           data-url="<?php echo e(route('search.keyword.destroy',$word->id)); ?>"><i class="fa fa-trash"></i> 删除</a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <div class="pull-right">
                                <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                                    <form>
                                        <div class="input-daterange input-group input-group-sm">
                                            共<?php echo e($words->total()); ?>条&nbsp
                                        </div>
                                        <div class="input-daterange input-group input-group-sm">
                                            <?php echo e($words->appends(Request::all())->links()); ?>

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



            //改变状态
            $('.change_attr').click(function () {
                var attr = $(this).data('attr');
                var id = $(this).parents('tr').data('id');

                $.ajax({
                    type: 'PATCH',
                    data: {attr: attr, id: id},
                    url: '<?php echo e(route('search.keyword.change_attr')); ?>',
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