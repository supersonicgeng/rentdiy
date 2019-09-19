<?php $__env->startSection('css'); ?>
    <style>
        .rule_node {
            line-height: 34px;
        }

        .rule_node .left1 {
            background: #f9f9f9;
        }

        .rule_node p {
            clear: both;
            margin-bottom: 0px;
        }

        .rule_node .left2 {
            float: left;
            margin-left: 24px;
        }

        .rule_node .left3 {
            margin-left: 0px;
            clear: none;
        }

        .rule_node .p_left {
            float: left;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>





    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal">

                    <div class="nav-tabs-custom">

                        <div class="tab-content">
                            <div>



                                <div class="form-group">
                                    <label class="col-sm-2 control-label">分类列表</label>
                                    <div class="col-sm-7 rule_node">
                                        <?php $__currentLoopData = $cates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <p class="left1">
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" disabled>&nbsp<span class="label label-success"><?php echo e($cate->name); ?></span>
                                                </label>
                                            </p>
                                            <?php $__currentLoopData = $cate->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <p class="left3 p_left">

                                                    &nbsp<input type="checkbox" <?php if(in_array($child->id,$checked)): ?> checked <?php endif; ?> class="cate_id" value="<?php echo e($child->id); ?>" name="cate_id[]">
                                                        &nbsp<span class="label label-info"><?php echo e($child->name); ?></span>


                                                </p>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-7">
                                <div class="btn-group pull-right">
                                    <button type="button" class="btn btn-info pull-left submits sub">提交
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>



<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
        $('.sub').click(function () {
            var length = $('.cate_id:checked').length;


            if (length == 0) {
                layer.msg('请选择一个分类！', {icon: 5});
                return false;
            }

            // var a = $('.checked_id:checked').serialize();
            var id_array=new Array();

            $('.cate_id:checked').each(function(){
                id_array.push($(this).val());//向数组中添加元素
            })

            s = id_array.join(',');
            window.parent.$('#cates').html(s);

            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
            parent.layer.close(index); //再执行关闭
        })
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.model', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>