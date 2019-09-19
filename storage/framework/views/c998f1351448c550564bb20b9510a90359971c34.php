<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                物料管理
                <small>物料列表</small>
            </h1>
            
            
            
            
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <div class="pull-left">
                                <a class="btn btn-primary btn-sm" href="<?php echo e(route('material.supply.create')); ?>"><i
                                            class="fa fa-save"></i> 新增</a>
                            </div>
                            <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                                <form>
                                    <div class="input-daterange input-group input-group-sm">
                                        <input class="form-control" name="introduce"
                                               value="<?php echo e(Request::input('introduce')); ?>"
                                               placeholder="关键字搜索"
                                               type="text">
                                    </div>

                                    <div class="input-daterange input-group input-group-sm">
                                        <select class="form-control" name="position">
                                            <option value="-1">显示位置</option>
                                            <option value="1" <?php if(Request::input('position') == 1): ?> selected <?php endif; ?>>找乐子
                                            </option>
                                            <option value="2" <?php if(Request::input('position') == 2): ?> selected <?php endif; ?>>找麦子
                                            </option>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-default btn-sm">确定</button>
                                    <a href="<?php echo e(route('material.supply.index')); ?>" class="btn btn-default btn-sm">重置</a>
                                </form>
                            </div>

                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>发布人</th>
                                    <th>关联商品</th>
                                    <th>文案介绍</th>
                                    <th>图片/视频</th>
                                    <th>是否关联商品</th>
                                    <th>页面展现位置</th>
                                    <th>分享量<?php echo table_sort('share_amt'); ?></th>
                                    <th>下载量<?php echo table_sort('down_amt'); ?></th>
                                    <th>发布时间<?php echo table_sort('publish_time'); ?></th>
                                    <th>上架/下架</th>
                                    <th>操作</th>
                                </tr>
                                <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr data-id="<?php echo e($m->matid); ?>">
                                        <td><?php echo e($m->matid); ?></td>
                                        <td><?php echo e($m->matuser->mname); ?></td>
                                        <td><?php echo e($m->rel_pro_id); ?></td>
                                        <td><?php echo e(str_limit($m->introduce,'20','...')); ?></td>
                                        <td>
                                            <?php if($m->type ==1): ?>
                                                图片
                                            <?php else: ?>
                                                视频
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($m->is_rel_pro ==1): ?>
                                                <a href="javasript:;" class="fa fa-check-circle text-green"></a>

                                            <?php else: ?>
                                                <a href="javasript:;" class="fa fa-times-circle text-red"></a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($m->position ==1): ?>
                                                乐子
                                            <?php else: ?>
                                                麦子
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($m->share_amt); ?></td>
                                        <td><?php echo e($m->down_amt); ?></td>
                                        <td><?php echo e($m->publish_time); ?></td>
                                        <td>

                                              <?php echo is_something('isputaway',$m); ?>



                                        </td>

                                        <td>
                                            <a class="btn btn-primary btn-xs" href="<?php echo e(route('material.supply.edit',$m->matid)); ?>"><i class="fa fa-edit"></i> 编辑</a>
                                            <a class="btn btn-danger btn-xs delete_genius" href="javascript:void(0);"
                                               data-url="<?php echo e(route('material.supply.destroy',$m->matid)); ?>"><i class="fa fa-trash"></i> 删除</a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <div class="pull-right">
                                <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                                    <form>
                                        <div class="input-daterange input-group input-group-sm">
                                            共<?php echo e($materials->total()); ?>条&nbsp
                                        </div>
                                        <div class="input-daterange input-group input-group-sm">
                                            <?php echo e($materials->appends(Request::all())->links()); ?>

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
                url: '<?php echo e(route('material.supply.change_attr')); ?>',
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
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>