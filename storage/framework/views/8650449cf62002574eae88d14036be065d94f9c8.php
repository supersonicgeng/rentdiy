

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
                                    <label class="col-sm-2 control-label">关键词</label>
                                    <div class="col-sm-5">
                                        <input class="form-control" type="text" name="name" value="<?php echo e($keyword->name); ?>" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">是否置顶</label>
                                    <div class="col-sm-3">
                                        <label class="checkbox-inline">
                                            <input type="radio" name="is_top" value="0" checked> 否
                                        </label>
                                        <label class="checkbox-inline">
                                            <input type="radio" name="is_top" value="1" <?php if($keyword->is_top ==1): ?> checked <?php endif; ?>> 是
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">排序</label>
                                    <div class="col-sm-5">
                                        <input class="form-control" name="sort" value="<?php echo e($keyword->sort); ?>" placeholder="" type="text">

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
                                <div class="btn-group pull-left">
                                    <button type="reset" class="btn btn-warning">撤销</button>
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

            var data =$('form').serialize();



            $.ajax({
                type: 'PUT',
                url: '<?php echo e(route('search.keyword.update',$keyword->id)); ?>',
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



    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.model', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>