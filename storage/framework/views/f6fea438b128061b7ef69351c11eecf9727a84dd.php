

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="/vendor/editable/css/bootstrap-editable.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                用户管理
                <small>用户列表</small>
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
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-btn">
                                            <button type="button"
                                                    class="btn btn-default dropdown-toggle search-drop-btn"
                                                    data-toggle="dropdown" aria-expanded="false">
                                                <span><?php echo e(Request::input('search_field')? customer_search(Request::input('search_field')):'搜索条件'); ?></span>
                                                <i class="fa fa-caret-down"></i>
                                            </button>
                                            <ul class="dropdown-menu search-ul">
                                                <li><a href="javascript:void(0)" data-field="id">用户ID</a></li>
                                                <li><a href="javascript:void(0)" data-field="username">用户手机号</a></li>
                                                <li><a href="javascript:void(0)" data-field="parent_id">直属上级ID</a></li>
                                                <li><a href="javascript:void(0)" data-field="grandpa_id">直属上上级ID</a>
                                                </li>
                                                <li><a href="javascript:void(0)" data-field="last_super">SVIP</a></li>
                                                <li><a href="javascript:void(0)" data-field="invit_code">邀请码</a></li>
                                            </ul>
                                        </div>
                                        <input type="hidden" class="search_field" name="search_field"
                                               value="<?php echo e(Request::input('search_field')? Request::input('search_field'):'id'); ?>">
                                        <input type="text" name="keyword" class="form-control pull-right"
                                               value="<?php echo e(Request::input('keyword')); ?>"
                                               placeholder="<?php echo e(Request::input('search_field')? customer_search(Request::input('search_field')):'默认搜索用户ID'); ?>">

                                    </div>

                                    <div class="input-daterange input-group input-group-sm">
                                        <select class="form-control" name="member_type">
                                            <option value="-1">用户等级</option>
                                            <option value="1" <?php if(Request::input('member_type') ==1): ?> selected <?php endif; ?>>
                                                会员
                                            </option>
                                            <option value="2" <?php if(Request::input('member_type') ==2): ?> selected <?php endif; ?>>
                                                VIP
                                            </option>
                                            <option value="3" <?php if(Request::input('member_type') ==3): ?> selected <?php endif; ?>>
                                                超级VIP
                                            </option>
                                        </select>
                                    </div>
                                    <div class="input-daterange input-group input-group-sm">
                                        <select class="form-control" name="status">
                                            <option value="-1">账号状态</option>
                                            <option value="0" <?php if(Request::input('status') =='0'): ?> selected <?php endif; ?>>冻结
                                            </option>
                                            <option value="1" <?php if(Request::input('status') ==1): ?> selected <?php endif; ?>>正常
                                            </option>
                                        </select>
                                    </div>
                                    <div class="input-daterange input-group input-group-sm">
                                        <input class="form-control test" name="start_time"
                                               value="<?php echo e(Request::input('start_time')); ?>" placeholder="开始日期" type="text">
                                        <span class="input-group-addon"><i class="fa fa-chevron-right"></i></span>
                                        <input class="form-control" name="end_time"
                                               value="<?php echo e(Request::input('end_time')); ?>" id="test" placeholder="结束日期"
                                               type="text">
                                    </div>

                                    <button type="submit" class="btn btn-default btn-sm">确定</button>
                                    <a href="<?php echo e(route('platform.customer.index')); ?>" class="btn btn-default btn-sm">重置</a>
                                </form>
                            </div>

                        </div>

                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>用户ID</th>
                                    <th>用户昵称</th>
                                    <th>手机号</th>
                                    <th>微信号</th>
                                    <th>用户标签</th>
                                    <th>会员当前级别</th>
                                    <th>级别期限</th>
                                    <th>邀请码</th>
                                    <th>管理津贴<?php echo table_sort('allowance'); ?></th>
                                    <th>累计预估收益<?php echo table_sort('forecast'); ?></th>
                                    <th>可提现金额<?php echo table_sort('balance'); ?></th>
                                    <th>已提现金额<?php echo table_sort('already_withdrawal'); ?></th>
                                    <th>成交总额<?php echo table_sort('total_price'); ?></th>
                                    <th>邀请人数<?php echo table_sort('invitation_num'); ?></th>
                                    <th>上级ID</th>
                                    <th>上上级ID</th>
                                    <th>SVP ID</th>
                                    <th>账号状态</th>
                                    <th>注册时间</th>
                                    <th>操作</th>
                                </tr>
                                <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><a data-toggle="tooltip" data-placement="top" title="点击查看树形图"
                                               href="<?php echo e(route('platform.customer.treeShow',$customer->id)); ?>"><?php echo e($customer->id); ?></a>
                                        </td>
                                        <td><?php echo e($customer->pname); ?></td>
                                        <td><?php echo e($customer->username); ?></td>
                                        <td><?php echo e($customer->wx_num); ?></td>
                                        <td>
                                            <?php if($customer->identity_names !=''): ?>
                                                <?php echo e(str_limit(implode(',',$customer->identity_names->toArray()),20,'...')); ?>

                                            <?php endif; ?>

                                        </td>
                                        <td>
                                            <?php echo member_level($customer->member_type); ?>

                                        </td>
                                        <td>
                                            <?php if($customer->member_start): ?>
                                                <?php echo e(date('Y-m-d',strtotime($customer->member_start))); ?>

                                                至 <?php echo e(date('Y-m-d',strtotime($customer->member_validity))); ?>

                                            <?php else: ?>
                                                /
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="#" class="username" data-type="text" data-pk="<?php echo e($customer->id); ?>" data-url="<?php echo e(route('platform.customer.changeCode')); ?>" data-title="修改邀请码"><?php echo e($customer->invit_code); ?></a>
                                        </td>
                                        <td><?php echo e($customer->allowance); ?></td>
                                        <td><?php echo e($customer->forecast); ?></td>
                                        <td><?php echo e($customer->balance); ?></td>
                                        <td><?php echo e($customer->already_withdrawal); ?></td>
                                        <td><?php echo e($customer->total_price == ''? 0:$customer->total_price); ?></td>
                                        <td><?php echo e($customer->invitation_num); ?></td>

                                        <td>
                                            <?php echo e($customer->parent_id); ?>

                                        </td>
                                        <td>
                                            <?php if($customer->grandpa_id != 0): ?>
                                                <?php echo e($customer->grandpa_id); ?>

                                            <?php else: ?>
                                                /
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($customer->last_super != 0): ?>
                                                <?php echo e($customer->last_super); ?>

                                            <?php else: ?>
                                                /
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($customer->status ==1): ?>
                                                <small class="label bg-blue">正常</small>
                                            <?php else: ?>
                                                <small class="label bg-red">冻结</small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($customer->created_at); ?></td>
                                        <td>

                                            <a class="btn btn-danger btn-xs check_model"
                                               href="javascript:void(0);"
                                               data-url="<?php echo e(route('platform.customer.supershow',$customer->id)); ?>"
                                               data-title="设置超级Vip"><i
                                                        class="fa fa-edit"></i>
                                                设置超级Vip</a>
                                            <?php if($customer->member_type == 3): ?>
                                                <a class="btn btn-danger btn-xs del_vip" href="javascript:void(0);"
                                                   data-url="<?php echo e(route('platform.customer.del_superVip',$customer->id)); ?>"><i
                                                            class="fa fa-cut"></i> 取消超级Vip</a>
                                            <?php endif; ?>
                                            <a class="btn btn-primary btn-xs"
                                               href="<?php echo e(route('platform.customer.relation',['id'=>$customer->id,'type'=>1])); ?>"><i
                                                        class="fa fa-edit"></i>
                                                用户关系</a>
                                            <?php if($customer->status ==1): ?>
                                                <a class="btn btn-danger btn-xs change" href="javascript:void(0);"
                                                   data-url="<?php echo e(route('platform.customer.changeStatus',['id'=>$customer->id])); ?>"><i
                                                            class="fa fa-cut"></i> 冻结</a>
                                            <?php else: ?>
                                                <a class="btn btn-success btn-xs change" href="javascript:void(0);"
                                                   data-url="<?php echo e(route('platform.customer.changeStatus',['id'=>$customer->id])); ?>"><i
                                                            class="fa fa-hand-pointer-o"></i> 启用</a>
                                            <?php endif; ?>
                                            <?php if($customer->member_type ==1): ?>
                                                <a class="btn btn-primary btn-xs check_model"
                                                   href="javascript:void(0);"
                                                   data-url="<?php echo e(route('platform.customer.setVipView',$customer->id)); ?>"
                                                   data-title="升级Vip"><i
                                                            class="fa fa-edit"></i>
                                                    设置普通Vip</a>
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
                                            共<?php echo e($customers->total()); ?>条&nbsp
                                        </div>
                                        <div class="input-daterange input-group input-group-sm">
                                            <?php echo e($customers->appends(Request::all())->links()); ?>

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
    <script src="/vendor/editable/js/bootstrap-editable.js"></script>
    <script>
        $(document).ready(function() {
            $('.username').editable({
                success: function(response, newValue) {

                    if (response.status == 1) {
                        toastr.success(response.msg);
                    } else {
                        toastr.error(response.msg);
                        return false;
                    }
                    setTimeout(function () {
                        window.location.reload();//页面刷新
                    }, 150);
                }
            });
        });
    </script>
    <script>

        $('[data-toggle="tooltip"]').tooltip()

        //选择条件
        $('.search-ul li').click(function () {
            var field = $(this).find('a').data('field');
            var name = $(this).find('a').html();
            $(this).parent('ul').prev('button').find('span').html(name);
            $('.search_field').val(field);
            $("input[name='keyword']").attr('placeholder', name);
        })


        $('.change').click(function () {
            var url = $(this).data('url');
            $.ajax({
                type: 'PATCH',
                url: url,
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

        //取消vip
        $('.del_vip').click(function () {
            var url = $(this).data('url');
            layer.confirm('您确定要取消超级vip?', {icon: 3, title: '提示'}, function (index) {
                $.ajax({
                    type: 'PATCH',
                    url: url,
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

                layer.close(index);
            });
        })

        //时间选择器
        laydate.render({
            elem: '.test'
            , type: 'datetime'
        });

        laydate.render({
            elem: '#test'
            , type: 'datetime'
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>