<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                物料管理
                <small>文章列表</small>
            </h1>
            
            
            
            
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <div class="pull-left">
                                <a class="btn btn-primary btn-sm" href="<?php echo e(route('material.article.create')); ?>"><i
                                            class="fa fa-save"></i> 新增</a>
                            </div>
                            <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                                <form>
                                    <div class="input-daterange input-group input-group-sm">
                                        <input class="form-control" name="name"
                                               value="<?php echo e(Request::input('name')); ?>"
                                               placeholder="关键字搜索"
                                               type="text">
                                    </div>

                                    <div class="input-daterange input-group input-group-sm">
                                        <select class="form-control" name="category">
                                            <option value="-1">栏目</option>
                                            <?php $__currentLoopData = $cates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($cate->id); ?>"
                                                        <?php if($cate->id == Request::input('category')): ?> selected <?php endif; ?>><?php echo e($cate->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-default btn-sm">确定</button>
                                    <a href="<?php echo e(route('material.article.index')); ?>" class="btn btn-default btn-sm">重置</a>
                                </form>
                            </div>

                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>文章标题</th>
                                    <th>所属栏目</th>
                                    <th>封面图</th>
                                    <th>阅览数</th>
                                    <th>排序</th>
                                    <th>是否上架</th>
                                    <th>创建管理员</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                                <?php $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr data-id="<?php echo e($article->id); ?>">
                                        <td><?php echo e($article->id); ?></td>
                                        <td><?php echo e(str_limit($article->title,36)); ?></td>
                                        <td><?php echo e($article->cate->name); ?></td>
                                        <td><img src="<?php echo e($article->cover); ?>" alt="" style="width: 50px;height: 40px">
                                        </td>
                                        <td><?php echo e($article->view_num); ?></td>
                                        <td><?php echo e($article->sort); ?></td>
                                        <td><?php echo is_something('is_on',$article); ?></td>
                                        <td><?php echo e($article->admin->real_name); ?></td>
                                        <td><?php echo e($article->created_at); ?></td>
                                        <td>
                                            <a class="btn btn-primary btn-xs"
                                               href="<?php echo e(route('material.article.edit',$article->id)); ?>"><i
                                                        class="fa fa-edit"></i> 编辑</a>
                                            <a class="btn btn-danger btn-xs delete_genius" href="javascript:void(0);"
                                               data-url="<?php echo e(route('material.article.destroy',$article->id)); ?>"><i
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
                                            共<?php echo e($articles->total()); ?>条&nbsp
                                        </div>
                                        <div class="input-daterange input-group input-group-sm">
                                            <?php echo e($articles->appends(Request::all())->links()); ?>

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
        //改变状态
        $('.change_attr').click(function () {
            var attr = $(this).data('attr');
            var id = $(this).parents('tr').data('id');

            $.ajax({
                type: 'PATCH',
                data: {attr: attr, id: id},
                url: '<?php echo e(route('material.article.change_attr')); ?>',
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
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>