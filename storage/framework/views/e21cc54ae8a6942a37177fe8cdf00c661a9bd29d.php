


<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                订单管理
                <small>商品订单</small>
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
                                    <div class="input-group">
                                        <div class="input-group-btn">
                                            <button type="button"
                                                    class="btn btn-default dropdown-toggle search-drop-btn"
                                                    data-toggle="dropdown" aria-expanded="false">
                                                <span><?php echo e(Request::input('search_field') ? order_search(Request::input('search_field')):'搜索条件'); ?></span>
                                                <i class="fa fa-caret-down"></i>
                                            </button>
                                            <ul class="dropdown-menu search-ul">
                                                <li><a href="javascript:void(0)" data-field="trade_id">订单ID</a></li>
                                                <li><a href="javascript:void(0)" data-field="num_iid">商品ID</a></li>
                                                <li><a href="javascript:void(0)" data-field="v2">上级ID</a></li>
                                                <li><a href="javascript:void(0)" data-field="v3">上上级ID</a></li>
                                                <li><a href="javascript:void(0)" data-field="v4">SVIPID</a></li>
                                            </ul>
                                        </div>
                                        <input type="hidden" class="search_field" name="search_field"
                                               value="<?php echo e(Request::input('search_field')? Request::input('search_field'):'trade_id'); ?>">
                                        <input type="hidden" name="_sort" value="<?php echo e(Request::input('_sort')); ?>">
                                        <input type="text" name="keyword" class="form-control pull-right"
                                               value="<?php echo e(Request::input('keyword')); ?>"
                                               placeholder="<?php echo e(Request::input('search_field') ? order_search(Request::input('search_field')):'默认搜索订单ID'); ?>">

                                    </div>
                                    <div class="input-group">
                                        <select class="form-control" name="tk_status" style="width: 100%">
                                            <option value="-1">订单状态</option>
                                            <option value="12" <?php if(Request::input('tk_status') == 12): ?> selected <?php endif; ?>>
                                                订单付款
                                            </option>
                                            <option value="14" <?php if(Request::input('tk_status') == 14): ?> selected <?php endif; ?>>
                                                订单成功
                                            </option>
                                            <option value="3" <?php if(Request::input('tk_status') == 3): ?> selected <?php endif; ?>>
                                                订单结算
                                            </option>
                                            <option value="13" <?php if(Request::input('tk_status') == 13): ?> selected <?php endif; ?>>
                                                订单失效
                                            </option>
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <select class="form-control" name="order_type" style="width: 100%">
                                            <option value="-1">商品平台</option>
                                            <option value="淘宝"
                                                    <?php if(Request::input('order_type') == '淘宝'): ?> selected <?php endif; ?>>淘宝
                                            </option>
                                            <option value="天猫"
                                                    <?php if(Request::input('order_type') == '天猫'): ?> selected <?php endif; ?>>天猫
                                            </option>
                                            <option value="聚划算"
                                                    <?php if(Request::input('order_type') == '聚划算'): ?> selected <?php endif; ?>>聚划算
                                            </option>

                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <select class="form-control" name="cate_id" style="width: 120px">
                                            <option value="-1">商品分类</option>
                                            <?php $__currentLoopData = $cates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <optgroup label="<?php echo e($cate->name); ?>">
                                                    <?php $__currentLoopData = $cate->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($c->id); ?>">&nbsp;&nbsp;<?php echo e($c->name); ?></option>
                                                   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </optgroup>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <input class="form-control" id="_time_from" name="start_time"
                                               value="<?php echo e(Request::input('start_time')); ?>"
                                               placeholder="开始日期" type="text" autocomplete="off">
                                        <span class="input-group-addon"><i class="fa fa-chevron-right"></i></span>
                                        <input class="form-control" id="_time_to" name="end_time"
                                               value="<?php echo e(Request::input('end_time')); ?>"
                                               placeholder="结束日期" type="text" autocomplete="off">
                                    </div>
                                    <button type="submit" class="btn btn-default btn-sm">确定</button>
                                    <a href="<?php echo e(route('order.goods.index')); ?>" class="btn btn-default btn-sm">重置</a>
                                </form>
                            </div>

                        </div>

                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>商品平台</th>
                                    <th>订单号</th>
                                    <th>商品ID</th>
                                    <th>平台商品ID</th>
                                    <th>商品标题</th>
                                    <th>所属分类</th>
                                    <th>商品价格<?php echo table_sort('price'); ?></th>
                                    <th>成交金额<?php echo table_sort('alipay_total_price'); ?></th>
                                    <th>成交量<?php echo table_sort('item_num'); ?></th>
                                    <th>佣金比例<?php echo table_sort('total_commission_rate'); ?></th>
                                    <th>佣金金额<?php echo table_sort('pub_share_pre_fee'); ?></th>
                                    <th>用户ID</th>
                                    <th>用户佣金<?php echo table_sort('v1_p'); ?></th>
                                    <th>上一级ID</th>
                                    <th>上级佣金<?php echo table_sort('v2_p'); ?></th>
                                    <th>上二级ID</th>
                                    <th>上上级佣金<?php echo table_sort('v3_p'); ?></th>
                                    <th>SVIPID</th>
                                    <th>SVIP佣金<?php echo table_sort('v4_p'); ?></th>
                                    <th>平台佣金<?php echo table_sort('platform_p'); ?></th>
                                    <th>订单状态</th>
                                    <th>下单时间</th>
                                </tr>
                                <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($order->type_name); ?></td>
                                        <td><?php echo e($order->trade_id); ?></td>
                                        <td><?php echo e($order->num_iid); ?></td>
                                        <td><?php echo e($order->good_id); ?></td>
                                        <td><?php echo e(str_limit($order->item_title,15,'...')); ?></td>
                                        <td><?php echo e($order->cate_name); ?></td>
                                        <td><?php echo e($order->price); ?></td>
                                        <td><?php echo e($order->alipay_total_price); ?></td>
                                        <td><?php echo e($order->item_num); ?></td>
                                        <td><?php echo e($order->total_commission_rate); ?></td>
                                        <td><?php echo e($order->pub_share_pre_fee); ?></td>
                                        <td><?php echo e($order->v1); ?></td>
                                        <td><?php echo e($order->v1_p); ?></td>
                                        <td><?php echo e($order->v2>0 ?$order->v2: ''); ?></td>
                                        <td><?php echo e($order->v2_p>0 ?$order->v2_p : ''); ?></td>
                                        <td><?php echo e($order->v3>0 ? $order->v3: ''); ?></td>
                                        <td><?php echo e($order->v3_p>0 ? $order->v3_p: ''); ?></td>
                                        <td><?php echo e($order->v4>0 ? $order->v4 : ''); ?></td>
                                        <td><?php echo e($order->v4_p>0 ? $order->v4_p: ''); ?></td>
                                        <td><?php echo e($order->platform_p); ?></td>
                                        <td>
                                            <?php if($order->tk_status ==3): ?>
                                                订单结算
                                            <?php elseif($order->tk_status ==12): ?>
                                                订单付款
                                            <?php elseif($order->tk_status ==13): ?>
                                                订单取消
                                            <?php elseif($order->tk_status ==14): ?>
                                                订单成功
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($order->create_time); ?></td>
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

            $("select[name=cate_id]").select2({
                // minimumResultsForSearch: Infinity, // 隐藏搜索框
                // theme: "classic", // 样式
            });

            //时间选择器
            laydate.render({
                elem: '#_time_from'
                , type: 'datetime'
            });

            laydate.render({
                elem: '#_time_to'
                , type: 'datetime'
            });

            $('.dc_excel').click(function () {
                var d = {};
                var t = $('form').serializeArray();
                $.each(t, function() {
                    d[this.name] = this.value;
                });

                location.href='/admin/order/goods/export?search_field='+d.search_field+'&_sort='+d._sort+'&keyword='
                    +d.keyword+'&tk_status='+d.tk_status+'&order_type='+d.order_type+'&cate_id='+d.cate_id+'&start_time='+d.start_time+'&end_time='+d.end_time;
            })
        })
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>