

<?php $__env->startSection('content'); ?>




        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border" style="height:51px;">

                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            

                        </div>


                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <th>标题</th>
                                    <th>指定商品数量</th>
                                    <th>是否关联分类</th>
                                    <th>关联的分类</th>
                                    <th>价格区间</th>

                                </tr>
                                <?php $__currentLoopData = $specials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><input type="radio" class="checked_id" name="checked_id"
                                                   value="<?php echo e($s->id); ?>"></td>
                                        <td><?php echo e($s->id); ?></td>
                                        <td><?php echo e($s->title); ?></td>
                                        <td><?php echo e(count(explode(',',$s->goods))); ?></td>
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
            <div class="btn-group pull-right">
                <button type="button" class="btn btn-info pull-right sub">确定</button>
            </div>
        </section>
        <!-- /.content -->



<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
    <script>
        $('.sub').click(function () {

            var id = $('input[name=checked_id]:checked').val();

            window.parent.$('#special_id').val(id);

            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
            parent.layer.close(index); //再执行关闭
        })
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.model', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>