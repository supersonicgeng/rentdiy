

<?php $__env->startSection('content'); ?>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border" style="height:51px;">
                        
                        
                        
                        <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                            <form>

                                <div class="input-daterange input-group input-group-sm">
                                    <input class="form-control" name="name" value="<?php echo e(Request::input('name')); ?>"
                                           placeholder="输入标签名称" type="text">
                                </div>

                                <button type="submit" class="btn btn-default btn-sm">确定</button>
                            </form>
                        </div>

                    </div>


                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-hover">
                            <tbody>
                            <tr>
                                <th width="100"><input class="check_all" type="checkbox"></th>
                                <th>ID</th>
                                <th>标签名</th>
                                <th>被使用次数</th>

                            </tr>
                            <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td style="display: none"><input type="text" name="tag_id[]" value="<?php echo e($tag->id); ?>"></td>
                                    <td class="del_check"><input class="checked_id" type="checkbox" name="checked_id[]" value="<?php echo e($tag->id); ?>"></td>
                                    <td><?php echo e($tag->id); ?></td>
                                    <td><?php echo e($tag->name); ?></td>
                                    <td><?php echo e($tag->be_use_time); ?></td>
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
        <div class="btn-group pull-right">
            <button type="button" class="btn btn-info pull-right check">确定</button>
        </div>
    </section>
    <!-- /.content -->

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.model', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>