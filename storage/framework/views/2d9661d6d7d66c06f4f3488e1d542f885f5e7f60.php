

<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                公告和反馈
                <small>反馈列表</small>
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
                                        <input class="form-control" name="nickname" value="<?php echo e(Request::input('nickname')); ?>"
                                               placeholder="反馈人昵称"
                                               type="text">
                                    </div>
                                    <div class="input-daterange input-group input-group-sm">
                                        <select class="form-control" name="is_read">
                                            <option value="-1">查看状态</option>
                                            <option value="0" <?php if(Request::input('is_read') == '0'): ?> selected <?php endif; ?>>未查看</option>
                                            <option value="1" <?php if(Request::input('is_read') == 1): ?> selected <?php endif; ?>>已查看</option>

                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-default btn-sm">确定</button>
                                    <a href="<?php echo e(route('notice.feedback.index')); ?>" class="btn btn-default btn-sm">重置</a>
                                </form>
                            </div>
                        </div>

                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>昵称</th>
                                    <th>联系方式</th>
                                    <th>联系号码</th>
                                    
                                    <th>创建时间</th>
                                    <th>查看状态</th>
                                    <th>查看人</th>
                                    <th>操作</th>
                                </tr>
                                <?php $__currentLoopData = $feeds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $word): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($word->id); ?></td>
                                        <td><?php echo e($word->nickname); ?></td>
                                        <td>
                                            <?php if($word->type ==1): ?>
                                                微信
                                            <?php elseif($word->type ==2): ?>
                                                QQ
                                            <?php else: ?>
                                                手机号
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($word->contact_way); ?></td>
                                        
                                        <td><?php echo e($word->create_time); ?></td>
                                        <td>
                                            <?php if($word->is_read ==0): ?>
                                                待查看
                                            <?php else: ?>
                                                已查看
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($word->real_name); ?></td>
                                        <td>
                                            <a class="btn btn-primary btn-xs check_model" href="javascript:;"
                                               data-title="反馈详情" data-url="<?php echo e(route('notice.feedback.show',$word->id)); ?>"><i
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
                                            共<?php echo e($feeds->total()); ?>条&nbsp
                                        </div>
                                        <div class="input-daterange input-group input-group-sm">
                                            <?php echo e($feeds->appends(Request::all())->links()); ?>

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

            $('.check_model').click(function () {
                $(this).parent().prev().prev().html('已查看');
                var real_name = $(this).parent().prev('td');
                if (real_name.html() == '') {

                    real_name.html("<?php echo e(auth()->user()->real_name); ?>")
                }
            })

            //改变状态
            $('.change_attr').click(function () {
                var attr = $(this).data('attr');
                var id = $(this).parents('tr').data('id');

                $.ajax({
                    type: 'PATCH',
                    data: {attr: attr, id: id},
                    url: '<?php echo e(route('search.keyword.change_attr')); ?>',
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

        })
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>