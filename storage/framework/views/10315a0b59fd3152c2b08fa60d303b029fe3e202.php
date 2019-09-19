<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Action Log
                <small>Log List</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <div class="pull-right">
                            </div>
                            <div class="search-form-inline form-inline pull-left">
                                <form>
                                    <div class="input-group input-group-sm">


                                        <input type="text" name="userName" class="form-control pull-right"
                                               value="<?php echo e(Request::input('userName')); ?>"
                                               placeholder="Username Search">

                                    </div>

                                    <div class="input-daterange input-group input-group-sm">
                                        <input class="form-control" id="_time_from" name="dateRange"
                                               value="<?php echo e(Request::input('dateRange')); ?>"
                                               placeholder="Date Range Search" type="text" style="width: 200px">

                                        
                                               
                                               
                                    </div>

                                    <button type="submit" class="btn btn-default btn-sm">确定</button>
                                    <a href="<?php echo e(route('action_log.action_log.index')); ?>" class="btn btn-default btn-sm">重置</a>
                                </form>
                            </div>

                        </div>
                        <div class="box-header with-border">

                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>User Name</th>
                                    <th>Action</th>
                                    <th>Describe</th>
                                    <th>Request Param</th>
                                    <th>Request Path</th>
                                    <th>Action Time</th>

                                </tr>
                                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($item->id); ?></td>
                                        <td><?php echo e($item->user_name); ?></td>
                                        <td><?php echo e($item->action); ?></td>
                                        <td><?php echo e($item->router_des); ?></td>
                                        <td><?php echo e($item->req_param); ?></td>
                                        <td><?php echo e($item->req_url); ?></td>
                                        <td><?php echo e($item->created_at); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <div class="pull-right">
                                <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                                    <form>
                                        <div class="input-daterange input-group input-group-sm">
                                            共<?php echo e($items->total()); ?>条&nbsp
                                        </div>
                                        <div class="input-daterange input-group input-group-sm">
                                            <?php echo e($items->appends(Request::all())->links()); ?>

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


            //时间选择器
            laydate.render({
                elem: '#_time_from',
                type: 'datetime',
                range: true,
            });


        })
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>