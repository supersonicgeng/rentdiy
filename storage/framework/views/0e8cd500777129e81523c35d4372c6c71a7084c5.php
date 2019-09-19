
<?php $__env->startSection('css'); ?>
    <style>
        .zoom-img-wrap {
            position: absolute;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                商品管理
                <small>商品入库</small>
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
                                        <input class="form-control" name="num_iid" value="<?php echo e(Request::input('num_iid')); ?>"
                                               placeholder="输入淘宝商品ID精确搜索"
                                               type="text">
                                    </div>

                                    <button type="submit" class="btn btn-default btn-sm">确定</button>

                                </form>
                            </div>

                        </div>


                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>淘宝商品ID</th>
                                    <th>商品标题</th>
                                    <th>商品头图</th>
                                    <th>淘宝现价(元)</th>
                                    <th>卖家店铺</th>
                                    <th>优惠券</th>
                                    <th>优惠券开始时间</th>
                                    <th>优惠截止时间</th>
                                    <th>优惠总量</th>
                                    <th>优惠余量</th>
                                    <th>默认佣金比例</th>
                                    <th>淘宝分类</th>
                                    <th>30天销售量</th>
                                    <th>商品平台</th>
                                    <th>操作</th>
                                </tr>
                                <?php if($good): ?>
                                    <tr>
                                        <td><?php echo e($good->num_iid); ?></td>
                                        <td><?php echo e($good->title); ?></td>
                                        <td><img data-action="zoom" style="height: 50px;width: 50px"
                                                 src="<?php echo e($good->pict_url); ?>" alt=""></td>
                                        <td><?php echo e($good->zk_final_price); ?></td>
                                        <td><?php echo e($good->nick); ?></td>
                                        <td><?php echo e($good->coupon_info ?? ''); ?></td>
                                        <td><?php echo e($good->coupon_start_time ?? ''); ?></td>
                                        <td><?php echo e($good->coupon_end_time ?? ''); ?></td>
                                        <td><?php echo e($good->coupon_total_count ?? ''); ?></td>
                                        <td><?php echo e($good->coupon_remain_count ?? ''); ?></td>
                                        <td><?php echo e($good->commission_rate/100); ?></td>
                                        <td><?php echo e($good->cat_name); ?></td>
                                        <td><?php echo e(display_times($good->volume)); ?></td>
                                        <td>
                                            <?php if($good->user_type ==0): ?>
                                                淘宝
                                            <?php elseif($good->user_type ==1): ?>
                                                天猫
                                            <?php elseif($good->user_type == 2): ?>
                                                京东
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a class="btn btn-success btn-xs"
                                               href="<?php echo e(route('shop.product.warehouse',['num_iid'=>$good->num_iid])); ?>"><i class="fa fa-edit"></i> 编辑入库</a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>

                            
                            
                            
                            
                            
                            
                            
                            
                            

                            
                            

                            
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
                    url: '<?php echo e(route('shop.product.change_attr')); ?>',
                    success: function (data) {
                        if (data.status == 1) {
                            toastr.success(data.msg);
                        } else {
                            toastr.error(data.msg);
                            return false;
                        }
                        setTimeout(function () {
                            window.location.reload();//页面刷新
                        }, 150);
                    }
                })
            })

            //清除人工权重
            $('.clear_weight').click(function () {
                var _this = $(this);
                layer.open({
                    title: '警告',
                    shadeClose: true,
                    content: '您将清除所有人工权重？',
                    yes: function (index, layero) {

                        var url = _this.data('url');//获取删除提交地址

                        $.ajax({
                            type: 'PATCH',
                            url: url,
                            success: function (info) {

                                //删除成功
                                if (info.status == 1) {
                                    layer.msg(info.msg, {
                                        icon: 6,
                                        time: 700
                                    }, function () {
                                        location.href = location.href
                                    });

                                } else {
                                    layer.msg(info.msg, {
                                        icon: 5,
                                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                                    });
                                }
                            }
                        })
                        layer.close(index); //如果设定了yes回调，需进行手工关闭
                    }
                });
            })

        })
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>