<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                运营管理
                <small>运营位列表</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <div class="pull-left">
                                <a class="btn btn-primary btn-sm" href="<?php echo e(route('operate.locate.create')); ?>"><i
                                            class="fa fa-save"></i> 新增</a>
                            </div>
                            <div class="search-form-inline form-inline pull-right">

                            </div>

                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>运营位名称</th>
                                    <th>描述</th>
                                    <th>是否启用</th>
                                    <th>最大内容数量</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                                <?php $__currentLoopData = $matters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $matter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr data-id="<?php echo e($matter->id); ?>">
                                        <td><?php echo e($matter->id); ?></td>
                                        <td><?php echo e($matter->m_title); ?></td>
                                        <td><?php echo e($matter->describe); ?></td>
                                        <td><?php echo is_something('is_on',$matter); ?></td>
                                        <td><?php echo e($matter->subject_num); ?></td>
                                        <td><?php echo e($matter->created_at); ?></td>
                                        <td>
                                            <a class="btn btn-primary btn-xs"
                                               href="<?php echo e(route('operate.locate.edit',$matter->id)); ?>"><i
                                                        class="fa fa-edit"></i> 编辑</a>
                                            <a class="btn btn-danger btn-xs delete_genius" href="javascript:void(0);"
                                               data-url="<?php echo e(route('operate.locate.destroy',$matter->id)); ?>"><i
                                                        class="fa fa-trash"></i> 删除</a>
                                            <a class="btn btn-primary btn-xs"
                                               href="<?php echo e(route('operate.subject.index',$matter->id)); ?>"><i
                                                        class="fa fa-search"></i> 运营内容</a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <div class="pull-right">
                                <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                                    <form>
                                        <div class="input-daterange input-group input-group-sm">
                                            共<?php echo e($matters->total()); ?>条&nbsp
                                        </div>
                                        <div class="input-daterange input-group input-group-sm">
                                            <?php echo e($matters->appends(Request::all())->links()); ?>

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



            //改变状态
            $('.change_attr').click(function () {
                var attr = $(this).data('attr');
                var id = $(this).parents('tr').data('id');

                $.ajax({
                    type: 'PATCH',
                    data: {attr: attr, id: id},
                    url: '<?php echo e(route('home.banner.change_attr')); ?>',
                    success: function (data) {
                        if (data.status == 1) {
                            toastr.success(data.msg);
                        } else {
                            toastr.error(data.msg);
                            return false;
                        }
                        setTimeout(function(){
                            window.location.reload();//页面刷新
                        },150);
                    }
                })
            })

        })
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>