

<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                商品管理
                <small>商品专题</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border" style="height:51px;">
                            <div class="pull-left">
                                <a class="btn btn-success btn-sm" href="<?php echo e(route('shop.special.create')); ?>"><i
                                            class="fa fa-save"></i> 新增专题</a>
                            </div>
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            

                        </div>


                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>标题</th>
                                    <th>指定商品数量</th>
                                    <th>是否关联分类</th>
                                    <th>关联的分类</th>
                                    <th>价格区间</th>
                                    <th>操作</th>
                                </tr>
                                <?php $__currentLoopData = $specials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($s->id); ?></td>
                                        <td><?php echo e($s->title); ?></td>
                                        <td>
                                            <?php if($s->goods_id != ''): ?>
                                                <?php echo e(count(explode(',',$s->goods_id))); ?>

                                                <?php else: ?>
                                                0
                                                <?php endif; ?>

                                        </td>
                                        <td>
                                            <?php if($s->is_and ==1 or $s->is_and ==2): ?>
                                                <a href="javasript:;" class="fa fa-check-circle text-green"></a>
                                           <?php else: ?>
                                                        <a href="javasript:;" class="fa fa-times-circle text-red"></a>

                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($s->is_and ==1 or $s->is_and ==2): ?>
                                              <?php echo e(implode(',',$s->cate_names->toArray())); ?>

                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($s->price_min); ?> - <?php echo e($s->price_max); ?></td>
                                        <td>
                                            <a class="btn btn-primary btn-xs" href="<?php echo e(route('shop.special.edit',$s->id)); ?>"><i class="fa fa-edit"></i> 编辑</a>
                                            <a class="btn btn-danger btn-xs delete_genius" href="javascript:void(0);"
                                               data-url="<?php echo e(route('shop.special.destroy',$s->id)); ?>"><i class="fa fa-trash"></i> 删除</a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <div class="pull-right">
                                <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                                    <form>
                                        <div class="input-daterange input-group input-group-sm">
                                            共<?php echo e($specials->total()); ?>条&nbsp
                                        </div>
                                        <div class="input-daterange input-group input-group-sm">
                                            <?php echo e($specials->appends(Request::all())->links()); ?>

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
<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>