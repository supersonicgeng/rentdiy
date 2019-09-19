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
                            <div class="pull-right">
                                <button type="button" class="btn btn-sm btn-danger dc_now" data-toggle="tooltip"
                                        data-placement="left" title="根据左边筛选条件">导出当前表
                                </button>
                                <button type="button" class="btn btn-sm btn-warning dc_excel" data-toggle="tooltip"
                                        data-placement="left" title="根据左边筛选条件">导出提现单表
                                </button>
                            </div>
                            <div class="search-form-inline form-inline pull-left">
                                <form>
                                    <div class="input-daterange input-group input-group-sm">
                                        <input class="form-control" name="username"
                                               value="<?php echo e(Request::input('username')); ?>"
                                               placeholder="输入用户手机号搜索"
                                               type="text">
                                    </div>
                                    <div class="input-daterange input-group input-group-sm">
                                        <input class="form-control" id="start_time" name="start_time"
                                               value="<?php echo e(Request::input('start_time')); ?>"
                                               placeholder="查询月份" type="text" autocomplete="off">
                                        
                                        
                                        
                                        
                                    </div>

                                    <button type="submit" class="btn btn-default btn-sm">确定</button>
                                    <a href="<?php echo e(route('platform.withdraw.index')); ?>" class="btn btn-default btn-sm">重置</a>
                                    <a href="javascript:void (0);" class="btn btn-primary btn-sm audit">一键审核</a>
                                </form>
                            </div>

                        </div>

                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>时间</th>
                                    <th>用户ID</th>
                                    <th>手机号</th>
                                    <th>累计收益</th>
                                    <th>可提现金额</th>
                                    <th>已提现金额</th>
                                    <th>申请提现金额<?php echo table_sort('withdraw_img'); ?></th>
                                    <th>提现单数<?php echo table_sort('withdraw_num'); ?></th>
                                    <th>操作</th>
                                </tr>
                                <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($month); ?></td>
                                        <td><?php echo e($w->id); ?></td>
                                        <td><?php echo e($w->username); ?></td>
                                        <td><?php echo e(round($w->confirm_total,2)); ?></td>
                                        <td><?php echo e(round($w->withdraw_allow,2)); ?></td>
                                        <td><?php echo e(round($w->already_total,2)); ?></td>
                                        <td><?php echo e(round($w->withdraw_img,2)); ?></td>
                                        <td><?php echo e($w->withdraw_num); ?></td>
                                        <td>
                                            <a class="btn btn-success btn-xs"
                                               href="<?php echo e(route('platform.withdraw.record',$w->id)); ?>"><i
                                                        class="fa fa-edit"></i> 查看</a>
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
    <script>
        $(function () {
            //时间选择器
            laydate.render({
                elem: '#start_time'
                , type: 'month'
            });

            function getUrlParam(name) {
                var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
                var r = window.location.search.substr(1).match(reg);  //匹配目标参数
                if (r != null) return unescape(r[2]);
                return null; //返回参数值
            }


            /**
             *审核订单
             */
            $('.dc_now').click(function () {
                var username = '<?php echo e(Request::input('username')); ?>';
                var start_time = '<?php echo e(Request::input('start_time')); ?>';
                var _sort = '<?php echo e(Request::input('_sort')); ?>';
                location.href = '/admin/platform/withdraw/NowExcept?username=' + username + '&start_time=' + start_time + '&_sort=' + _sort;
            })

            /***
             *导出提现订单表
             */
            $('.dc_excel').click(function () {

                var d = {};
                var t = $('form').serializeArray();

                $.each(t, function () {
                    d[this.name] = this.value;
                });

                location.href = '/admin/platform/withdraw/export?username=' + d.username + '&start_time=' + d.start_time;
            })

            /***
             * 一键审核
             */
            $('.audit').click(function () {

                var d = {};
                var t = $('form').serializeArray();

                $.each(t, function () {
                    d[this.name] = this.value;
                });


                
                $.ajax({
                    url:'/admin/platform/withdraw/check?username=' + d.username + '&start_time=' + d.start_time,
                    type:'GET',
                    success:function (data) {


                        layer.open({
                            content: '审核总金额:'+data.data.withdraw_total+'</br>总单数:'+data.data.withdraw_num+'</br>总人数:'+data.data.withdraw_people,
                            btn: ['确定', '取消'],
                            yes: function(index, layero){

                                location.href = '/admin/platform/withdraw/OneKey?username=' + d.username + '&start_time=' + d.start_time;
                                layer.close(index); //如果设定了yes回调，需进行手工关闭
                            },btn2: function(index, layero){

                            }
                        });

                    }
                })
                


            })
        })
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>