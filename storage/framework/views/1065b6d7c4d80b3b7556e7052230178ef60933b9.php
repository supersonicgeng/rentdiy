<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                商品管理
                <small>商品标签</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border" style="height:51px;">
                            <div class="pull-left">
                                <a class="btn btn-success btn-sm add" href="javascript:;" data-url="<?php echo e(route('shop.tag.create')); ?>"><i class="fa fa-save"></i> 新增</a>
                                <a class="btn btn-danger btn-sm delete_all" href="javascript:;" data-url="<?php echo e(route('shop.tag.create')); ?>"><i class="fa fa-trash"></i> 多选删除</a>
                            </div>
                            <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                                <form>
                                    <div class="input-daterange input-group input-group-sm">
                                        <input class="form-control" name="id" value="<?php echo e(Request::input('id')); ?>" placeholder="输入标签ID搜索"
                                               type="text">
                                    </div>
                                    <div class="input-daterange input-group input-group-sm">
                                        <input class="form-control" name="name" value="<?php echo e(Request::input('name')); ?>" placeholder="输入标签名称搜索"
                                               type="text">
                                    </div>
                                    <button type="submit" class="btn btn-default btn-sm">搜索</button>
                                </form>
                            </div>

                        </div>


                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th><input type="checkbox" class="check_all"></th>
                                    <th>ID</th>
                                    <th>标签名称</th>
                                    <th>包含的商品的数量</th>
                                    <th>操作</th>
                                </tr>
                                <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><input type="checkbox" name="checked_id[]" class="checked_id" value="<?php echo e($tag->id); ?>"></td>
                                    <td><?php echo e($tag->id); ?></td>
                                    <td><?php echo e($tag->name); ?></td>
                                    <td><?php echo e($tag->be_use_time); ?></td>
                                    <td>
                                        <a class="btn btn-info btn-xs" href="/admin/shop/tag/tag_goods?tag_id=<?php echo e($tag->id); ?>&good_num=<?php echo e($tag->be_use_time); ?>" data-url=""><i
                                                    class="fa fa-eye"></i> 查看包含商品</a>

                                        <a class="btn btn-success btn-xs add" href="javascript:;" data-url="<?php echo e(route('shop.tag.edit',$tag->id)); ?>"><i class="fa fa-edit"></i> 编辑</a>
                                        <a class="btn btn-danger btn-xs delete_genius"
                                           href="javascript:void(0);"
                                           data-url="<?php echo e(route('shop.tag.destroy',$tag->id)); ?>"><i class="fa fa-trash"></i> 删除</a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <div class="pull-right">
                                <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                                    <form>
                                        <div class="input-daterange input-group input-group-sm">
                                            共<?php echo e($tags->total()); ?>条&nbsp
                                        </div>
                                        <div class="input-daterange input-group input-group-sm">
                                            <?php echo e($tags->appends(Request::all())->links()); ?>

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
        //编辑模态框
        $('.add').click(function () {
            var url = $(this).data('url');
            // console.log(url);return false;
            top.layer.open({
                title: ' ',
                type: 2,
                shadeClose: true,
                tipsMore: false,
                shade: [0.5, '#393D49'],
                maxmin: true, //开启最大化最小化按钮
                area: ['500px', '40%'],
                content: url

            });
        })

        $('.delete_all').click(function () {
            var length = $('.checked_id:checked').length;
            if (length == 0) {
                layer.msg('至少选择一个标签！', {icon: 5});
                return false;
            }

            var a = $('.checked_id:checked').serialize();

            $.ajax({
                type: 'PATCH',
                url: "<?php echo e(route('shop.tag.delete_all')); ?>",
                data: a,
                success: function (data) {
                    if (data.status == 1) {
                        layer.msg(data.msg, {icon: 6});
                        window.location.reload();
                    } else {
                        layer.msg(data.msg, {icon: 5});
                        return false;
                    }
                }
            });
        })
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>