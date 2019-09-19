

<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                用户管理
                <small>提现审核</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            
                            
                            
                            <div class="search-form-inline form-inline pull-left">
                                <form>
                                    <div class="input-daterange input-group input-group-sm">
                                        <input class="form-control" name="order_sn"
                                               value="<?php echo e(Request::input('order_sn')); ?>"
                                               placeholder="输入用户订单号搜索"
                                               type="text">
                                    </div>
                                    <div class="input-daterange input-group input-group-sm">
                                        <input class="form-control" name="username"
                                               value="<?php echo e(Request::input('username')); ?>"
                                               placeholder="输入用户手机号搜索"
                                               type="text">
                                    </div>
                                    <div class="input-daterange input-group input-group-sm">
                                        <select class="form-control" name="pass_flag">
                                            <option value="">审核状态</option>
                                            <option value="0" <?php if(Request::input('pass_flag') == '0'): ?> selected <?php endif; ?>>
                                                待审核
                                            </option>
                                            <option value="1" <?php if(Request::input('pass_flag') == '1'): ?> selected <?php endif; ?>>
                                                审核通过
                                            </option>
                                            <option value="-1" <?php if(Request::input('pass_flag') == '-1'): ?> selected <?php endif; ?>>
                                                审核不通过
                                            </option>
                                        </select>
                                    </div>

                                    <div class="input-daterange input-group input-group-sm">
                                        <input class="form-control" id="start_time" name="start_time"
                                               value="<?php echo e(Request::input('start_time')); ?>"
                                               placeholder="开始日期" type="text" autocomplete="off">
                                        <span class="input-group-addon"><i class="fa fa-chevron-right"></i></span>
                                        <input class="form-control" id="end_time" name="end_time"
                                               value="<?php echo e(Request::input('end_time')); ?>"
                                               placeholder="结束日期" type="text" autocomplete="off">
                                    </div>

                                    <button type="submit" class="btn btn-default btn-sm">确定</button>
                                    <a href="<?php echo e(route('platform.withdraw.index')); ?>" class="btn btn-default btn-sm">重置</a>
                                </form>
                            </div>

                        </div>

                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>提现订单号</th>
                                    <th>用户名</th>
                                    <th>手机号</th>
                                    <th>微信号</th>
                                    <th>会员级别</th>
                                    <th>余额</th>
                                    <th>提现金额</th>
                                    <th>申请时间</th>
                                    <th>审核状态</th>
                                    <th>审核人</th>
                                    <th>审核时间</th>
                                    <th>操作</th>
                                </tr>
                                <?php $__currentLoopData = $withdraws; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $withdraw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($withdraw->order_sn); ?></td>
                                        <td><?php echo e($withdraw->username); ?></td>
                                        <td><?php echo e($withdraw->username); ?></td>
                                        <td><?php echo e($withdraw->wx_num); ?></td>
                                        <td>
                                            <?php echo member_level($withdraw->member_type); ?>

                                        </td>
                                        <td><?php echo e($withdraw->balance); ?></td>
                                        <td>
                                            <?php echo e($withdraw->amount); ?>

                                        </td>
                                        <td><?php echo e($withdraw->created_at); ?></td>
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

                                        <td>
                                            <?php if($withdraw->pass_flag !=0): ?>
                                                <a class="btn btn-primary btn-xs" href="javascript:;"> 已审核</a>
                                            <?php endif; ?>
                                            <?php if($withdraw->pass_flag ==0): ?>
                                                <a data-url="<?php echo e(route('platform.withdraw.update',$withdraw->id)); ?>"
                                                   data-pass="1" class="btn btn-success btn-xs audit"
                                                   href="javascript:;"><i
                                                            class="fa fa-check"></i>通过</a>
                                            <?php endif; ?>
                                            <?php if($withdraw->pass_flag ==0): ?>
                                                <a data-url="<?php echo e(route('platform.withdraw.update',$withdraw->id)); ?>"
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

            $('.audit').click(function () {

                var url = $(this).data('url');
                var pass_flag = $(this).data('pass');


                layer.confirm('您确定吗?', {icon: 3, title: '审核'}, function (index) {

                    $.ajax({
                        type: 'PUT',
                        url: url,
                        data: {pass_flag: pass_flag},
                        success: function (data) {
                            if (data.status == 0) {

                                layer.msg(data.msg, {icon: 5});

                            } else {

                                layer.msg('审核成功', {
                                    icon: 6,
                                    time: 800 //2秒关闭（如果不配置，默认是3秒）
                                }, function(){
                                    parent.location.reload();
                                });

                            }
                        }
                    });

                    layer.close(index);
                });
            })

        })
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>