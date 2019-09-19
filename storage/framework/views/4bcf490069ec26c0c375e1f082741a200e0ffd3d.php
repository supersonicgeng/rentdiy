<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                用户管理
                <small>用户提现记录</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <div class="pull-left">
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <th>用户ID</th>
                                        <th>手机号</th>
                                        <th>累计收益</th>
                                        <th>可提金额</th>
                                        <th>已提金额</th>
                                        <th>申请提现金额</th>
                                    </tr>
                                    <tr>
                                        <td><?php echo e($customer_info->id); ?></td>
                                        <td><?php echo e($customer_info->username ?? ''); ?></td>
                                        <td><?php echo e($customer_info->incomeInfo->confirmed_income); ?></td>
                                        <td><?php echo e($customer_info->incomeInfo->balance); ?></td>
                                        <td><?php echo e($withdraw_already); ?></td>
                                        <td><?php echo e($withdraw_ing); ?></td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>

                        </div>

                        <div class="box-body table-responsive">
                            <div class="pull-left" style="padding-top: 5px;padding-bottom: 10px">
                                <a class="btn btn-primary btn-sm yj" data-url="<?php echo e(route('platform.withdraw.update')); ?>"
                                   data-pass="1" href="javascript:void(0);"><i class="fa fa-check"></i> 一键通过</a>
                                <a class="btn btn-danger btn-sm yj" data-url="<?php echo e(route('platform.withdraw.update')); ?>"
                                   data-pass="-1" href="javascript:void(0);"><i class="fa fa-ban"></i> 一键拒绝</a>
                            </div>
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>提现订单号</th>
                                    <th>提现金额</th>
                                    <th>提现账号</th>
                                    <th>申请时间</th>
                                    <th>到账时间</th>
                                    <th>审核状态</th>
                                    <th>审核人</th>
                                    <th>审核时间</th>
                                    <th>原因</th>
                                    <th>操作</th>
                                </tr>
                                <?php $__currentLoopData = $withdraws; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $withdraw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($withdraw->order_sn); ?></td>
                                        <td>
                                            <?php echo e($withdraw->amount); ?>

                                        </td>
                                        <td><?php echo e($withdraw->ali_num); ?></td>
                                        <td><?php echo e($withdraw->created_at); ?></td>
                                        <td><?php echo e($withdraw->arrive_time); ?></td>
                                        <td>
                                            <?php if($withdraw->pass_flag == -1): ?>
                                                <small class="label bg-red">不通过</small>
                                            <?php elseif($withdraw->pass_flag ==0): ?>
                                                <small class="label bg-blue">待审核</small>
                                            <?php elseif($withdraw->pass_flag ==1): ?>
                                                <small class="label bg-yellow">通过</small>

                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo e($withdraw->real_name); ?>

                                        </td>
                                        <td>
                                            <?php echo e($withdraw->check_time); ?>

                                        </td>
                                        <td><?php echo e($withdraw->reason); ?></td>
                                        <td>
                                            <?php if($withdraw->pass_flag !=0): ?>
                                                /
                                            <?php endif; ?>
                                            <?php if($withdraw->pass_flag ==0): ?>
                                                <a data-url="<?php echo e(route('platform.withdraw.single',$withdraw->id)); ?>"
                                                   data-pass="1" class="btn btn-success btn-xs audit"
                                                   href="javascript:;"><i
                                                            class="fa fa-check"></i>通过</a>
                                            <?php endif; ?>
                                            <?php if($withdraw->pass_flag ==0): ?>
                                                <a data-url="<?php echo e(route('platform.withdraw.single',$withdraw->id)); ?>"
                                                   data-pass="-1" class="btn btn-danger btn-xs audit"
                                                   href="javascript:;"><i
                                                            class="fa fa-close (alias)"></i>不通过</a>
                                            <?php endif; ?>
                                        </td>

                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>

                            <div class="pull-right">
                                <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                                    <form>
                                        <div class="input-daterange input-group input-group-sm">
                                            共<?php echo e($withdraws->total()); ?>条&nbsp
                                        </div>
                                        <div class="input-daterange input-group input-group-sm">
                                            <?php echo e($withdraws->appends(Request::all())->links()); ?>

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
                elem: '#start_time'
                , type: 'datetime'
            });

            laydate.render({
                elem: '#end_time'
                , type: 'datetime'
            });


            /***
             * 一键审核
             */
            $('.yj').click(function () {
                var url = $(this).data('url');
                var pass_flag = $(this).data('pass');
                var id = '<?php echo e($customer_info->id); ?>';

                if (pass_flag == -1) {

                    layer.prompt({
                        formType: 2,
                        title: '请输入不通过原因',
                    }, function (value, index, elem) {

                        $.ajax({
                            type: 'PATCH',
                            url: url,
                            data: {pass_flag: pass_flag, reason: value,id:id},
                            success: function (data) {

                                if (data.status == 0) {
                                    layer.msg(data.msg, {icon: 5});
                                } else {
                                    layer.msg('操作成功', {
                                        icon: 6,
                                        time: 800 //2秒关闭（如果不配置，默认是3秒）
                                    }, function () {
                                        parent.location.reload();
                                    });
                                }
                            }
                        });

                        layer.close(index);
                    });

                    return false;
                }

                $.ajax({
                    type: 'PATCH',
                    url: url,
                    data: {pass_flag: pass_flag,id:id},
                    success: function (data) {


                        if (data.status == 0) {
                            layer.msg(data.msg, {icon: 5});
                        } else {
                            layer.msg('审核成功', {
                                icon: 6,
                                time: 800 //2秒关闭（如果不配置，默认是3秒）
                            }, function () {
                                parent.location.reload();
                            });
                        }
                    }
                });
            })

            /**
             * 单个审核
             */
            $('.audit').click(function () {

                var url = $(this).data('url');
                var pass_flag = $(this).data('pass');


                if (pass_flag == -1) {

                    layer.prompt({
                        formType: 2,
                        title: '请输入不通过原因',
                    }, function (value, index, elem) {

                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: {pass_flag: pass_flag, reason: value},
                            success: function (data) {

                                if (data.status == 0) {
                                    layer.msg(data.msg, {icon: 5});
                                } else {
                                    layer.msg('操作成功', {
                                        icon: 6,
                                        time: 800 //2秒关闭（如果不配置，默认是3秒）
                                    }, function () {
                                        parent.location.reload();
                                    });
                                }
                            }
                        });

                        layer.close(index);
                    });

                    return false;
                }

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {pass_flag: pass_flag},
                    success: function (data) {


                        if (data.status == 0) {
                            layer.msg(data.msg, {icon: 5});
                        } else {
                            layer.msg('审核成功', {
                                icon: 6,
                                time: 800 //2秒关闭（如果不配置，默认是3秒）
                            }, function () {
                                parent.location.reload();
                            });
                        }
                    }
                });

            })

        })
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>