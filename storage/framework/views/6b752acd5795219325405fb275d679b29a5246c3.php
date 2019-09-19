

<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                订单管理
                <small>开通会员订单</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <div class="pull-right">
                                <button type="button" class="btn btn-sm btn-warning dc_excel" data-toggle="tooltip"
                                        data-placement="left" title="根据左边筛选条件">导出Excel
                                </button>
                            </div>
                            <div class="search-form-inline form-inline pull-left">
                                <form>
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-btn">
                                            <button type="button"
                                                    class="btn btn-default dropdown-toggle search-drop-btn"
                                                    data-toggle="dropdown" aria-expanded="false">
                                                <span><?php echo e(Request::input('search_field') ? member_order(Request::input('search_field')):'搜索条件'); ?></span>
                                                <i class="fa fa-caret-down"></i>
                                            </button>
                                            <ul class="dropdown-menu search-ul">
                                                <li><a href="javascript:void(0)" data-field="username">用户名</a></li>
                                                <li><a href="javascript:void(0)" data-field="trade_id">订单号</a></li>
                                                <li><a href="javascript:void(0)" data-field="c.id">用户ID</a></li>
                                                <li><a href="javascript:void(0)" data-field="v2">上级ID</a></li>
                                                <li><a href="javascript:void(0)" data-field="v3">上上级ID</a></li>
                                                <li><a href="javascript:void(0)" data-field="v4">SVIP一ID</a></li>
                                                <li><a href="javascript:void(0)" data-field="v5">SVIP二ID</a></li>
                                            </ul>
                                        </div>
                                        <input type="hidden" class="search_field" name="search_field"
                                               value="<?php echo e(Request::input('search_field')? Request::input('search_field'):'trade_id'); ?>">
                                        <input type="hidden" name="_sort" value="<?php echo e(Request::input('_sort')); ?>">
                                        <input type="text" name="keyword" class="form-control pull-right"
                                               value="<?php echo e(Request::input('keyword')); ?>"
                                               placeholder="<?php echo e(Request::input('search_field') ? member_order(Request::input('search_field')):'默认搜索用户名'); ?>">

                                    </div>
                                    <div class="input-group input-group-sm">
                                        <select class="form-control" name="status">
                                            <option value="-1">订单状态</option>
                                            <option value="0" <?php if(Request::input('status') =='0'): ?> selected <?php endif; ?>>待返佣
                                            </option>
                                            <option value="1" <?php if(Request::input('status') ==1): ?> selected <?php endif; ?>>已到账
                                            </option>
                                        </select>
                                    </div>
                                    <div class="input-daterange input-group input-group-sm">
                                        <input class="form-control" id="_time_from" name="start_time"
                                               value="<?php echo e(Request::input('start_time')); ?>"
                                               placeholder="开始日期" type="text">
                                        <span class="input-group-addon"><i class="fa fa-chevron-right"></i></span>
                                        <input class="form-control" id="_time_to" name="end_time"
                                               value="<?php echo e(Request::input('end_time')); ?>"
                                               placeholder="结束日期" type="text">
                                    </div>

                                    <button type="submit" class="btn btn-default btn-sm">确定</button>
                                    <a href="<?php echo e(route('order.member.index')); ?>" class="btn btn-default btn-sm">重置</a>
                                </form>
                            </div>

                        </div>

                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>订单号</th>
                                    <th>用户名</th>
                                    <th>用户ID</th>
                                    <th>升级前等级</th>
                                    <th>充值等级</th>
                                    <th>付款(元)<?php echo table_sort('price'); ?></th>
                                    <th>上级ID</th>
                                    <th>上级津贴<?php echo table_sort('v2_p'); ?></th>
                                    <th>上上级ID</th>
                                    <th>上上级津贴<?php echo table_sort('v3_p'); ?></th>
                                    <th>SVIP-ID</th>
                                    <th>SVIP-津贴<?php echo table_sort('v4_p'); ?></th>
                                    <th>SVIP二ID</th>
                                    <th>SVIP二津贴<?php echo table_sort('v5_p'); ?></th>
                                    <th>平台津贴<?php echo table_sort('platform_p'); ?></th>
                                    <th>津贴状态</th>
                                    <th>开通时间</th>
                                </tr>
                                <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($order->trade_id); ?></td>
                                        <td><?php echo e($order->username); ?></td>
                                        <td><?php echo e($order->customer_id); ?></td>
                                        <td><?php echo member_level($order->b_level); ?></td>
                                        <td><?php echo member_level($order->c_level); ?></td>
                                        <td><?php echo e($order->price); ?></td>
                                        <td><?php echo e($order->v2); ?></td>
                                        <td><?php echo e($order->v2_p); ?></td>
                                        <td><?php echo e($order->v3); ?></td>
                                        <td><?php echo e($order->v3_p); ?></td>
                                        <td><?php echo e($order->v4); ?></td>
                                        <td><?php echo e($order->v4_p); ?></td>
                                        <td><?php echo e($order->v5); ?></td>
                                        <td><?php echo e($order->v5_p); ?></td>
                                        <td><?php echo e($order->platform_p); ?></td>
                                        <td>
                                            <?php if($order->order_status ==1): ?>
                                                已返佣
                                            <?php else: ?>
                                                待返佣
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($order->created_at); ?></td>
                                        
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <div class="pull-right">
                                <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                                    <form>
                                        <div class="input-daterange input-group input-group-sm">
                                            共<?php echo e($orders->total()); ?>条&nbsp
                                        </div>
                                        <div class="input-daterange input-group input-group-sm">
                                            <?php echo e($orders->appends(Request::all())->links()); ?>

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
            //选择条件
            $('.search-ul li').click(function () {
                var field = $(this).find('a').data('field');
                var name = $(this).find('a').html();

                $(this).parent('ul').prev('button').find('span').html(name);
                $('.search_field').val(field);
                $("input[name='keyword']").attr('placeholder', name);
            })

            //导出excel
            $('.dc_excel').click(function () {
                var d = {};
                var t = $('form').serializeArray();
                $.each(t, function() {
                    d[this.name] = this.value;
                });

                location.href='/admin/order/member/export?search_field='+d.search_field+'&_sort='+d._sort+'&keyword='
                    +d.keyword+'&status='+d.status+'&start_time='+d.start_time+'&end_time='+d.end_time;
            })

            //时间选择器
            laydate.render({
                elem: '#_time_from'
                , type: 'datetime'
            });

            laydate.render({
                elem: '#_time_to'
                , type: 'datetime'
            });
        })
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>