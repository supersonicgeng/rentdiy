

<?php $__env->startSection('content'); ?>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal">

                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab1" data-toggle="tab">基本参数</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab1">

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">用户id</label>
                                    <div class="col-sm-5">
                                        <input class="form-control" type="text" value="<?php echo e($customer->id); ?>" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">用户名</label>
                                    <div class="col-sm-5">
                                        <input class="form-control" value="<?php echo e($customer->username); ?>" type="text"
                                               disabled>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">当前等级</label>
                                    <div class="col-sm-5">
                                        <input class="form-control"
                                               value="<?php if($customer->member_type ==1): ?> 会员 <?php elseif($customer->member_type ==2): ?> VIP <?php elseif($customer->member_type==3): ?> 超级Vip <?php endif; ?>"
                                               type="text" disabled>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Vip开始时间</label>
                                    <div class="col-sm-5">
                                        <input class="form-control" id="start_time" name="start_time"
                                               value="<?php echo e($customer->member_start ?? ''); ?>" placeholder="请选择开始时间" type="text"
                                               readonly>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Vip结束时间</label>
                                    <div class="col-sm-5">
                                        <input class="form-control" id="end_time" name="end_time"
                                               value="<?php echo e($customer->member_validity ?? ''); ?>" placeholder="请选择结束时间" type="text"
                                               readonly>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-7">
                                <div class="btn-group pull-right">
                                    <button type="button" class="btn btn-info pull-right submits">提交
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- /.content -->



<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>

        $('.submits').click(function () {

            var data = $('form').serialize();


            $.ajax({
                type: 'PUT',
                url: '<?php echo e(route('platform.customer.setVip',$customer->id)); ?>',
                data: data,
                dataType: 'json',
                success: function (info) {

                    if (info.status == 1) {
                        layer.msg(info.msg, {
                            icon: 6,
                            time: 2000 //2秒关闭（如果不配置，默认是3秒）
                        });
                        top.location.reload();
                    } else {
                        layer.msg(info.msg, {
                            icon: 5,
                            time: 2500 //2.5秒关闭（如果不配置，默认是3秒）
                        });
                    }

                }

            })
            return false;
        })

        //时间选择器
        laydate.render({
            elem: '#start_time'
            , type: 'datetime'
        });

        laydate.render({
            elem: '#end_time'
            , type: 'datetime'
        });

    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.model', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>