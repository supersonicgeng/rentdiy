<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                商品管理
                <small>商品分类</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border" style="height:51px;">
                            
                                
                            
                            <div class="search-form-inline form-inline pull-left">
                                <form>

                                    <div class="input-daterange input-group input-group-sm">
                                        <input class="form-control" name="id" value="<?php echo e(Request::input('id')); ?>" placeholder="输入分类ID搜索" type="text">
                                    </div>
                                    <div class="input-daterange input-group input-group-sm">
                                        <input class="form-control"  name="name" value="<?php echo e(Request::input('name')); ?>" placeholder="输入分类名称搜索" type="text">
                                    </div>

                                    <button type="submit" class="btn btn-default btn-sm">搜索</button>
                                </form>
                            </div>

                        </div>



                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>一级分类</th>
                                    <th>包含的二级分类数量</th>
                                    <th>排序</th>
                                    <th>图片</th>
                                    <th>操作</th>
                                </tr>
                                <?php $__currentLoopData = $cates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr data-id="<?php echo e($cate->id); ?>">
                                    <td><?php echo e($cate->id); ?></td>
                                    <td><?php echo e($cate->name); ?></td>
                                    <td><?php echo e($cate->children_count); ?></td>
                                    <td><?php echo e($cate->sort); ?></td>
                                    <td><img src="<?php echo e($cate->image); ?>" alt="" style="height: 50px;width: 50px"></td>
                                    <td>
                                        <a class="btn btn-info btn-xs" href="<?php echo e(route('shop.category.secondCate',$cate->id)); ?>" data-url=""><i class="fa fa-fw fa-pencil-square"></i> 查看包含二级分类</a>

                                        <a class="btn btn-success btn-xs" href="<?php echo e(route('shop.category.edit',$cate->id)); ?>"><i class="fa fa-edit"></i> 编辑</a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <div class="pull-right">
                                <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                                    <form>
                                        <div class="input-daterange input-group input-group-sm">
                                            共<?php echo e($cates->total()); ?>条&nbsp
                                        </div>
                                        <div class="input-daterange input-group input-group-sm">
                                            <?php echo e($cates->appends(Request::all())->links()); ?>

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
                    url: '<?php echo e(route('shop.category.change_attr')); ?>',
                    success: function (data) {
                        if (data.status == 1) {
                            toastr.success(data.msg);
                        } else {
                            toastr.error(data.msg);
                            return false;
                        }
                        setTimeout(function(){
                            window.location.reload();//页面刷新
                        },200);
                    }
                })
            })

        })
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>