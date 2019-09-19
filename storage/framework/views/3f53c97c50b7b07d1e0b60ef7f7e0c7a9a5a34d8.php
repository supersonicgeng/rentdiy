<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                物料管理
                <small>编辑栏目</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal">
                        <?php echo csrf_field(); ?>
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab1" data-toggle="tab">基本参数</a></li>
                                <li class="pull-right"><a href="javascript:history.back(-1)" class="btn btn-sm"
                                                          style="padding:10px 2px;"><i class="fa fa-list"></i> 返回</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">


                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">栏目名称<span style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-3">
                                            <input class="form-control" name="name" placeholder="名称最多6个字" value="<?php echo e($cate->name); ?>"
                                                   type="text" required>
                                        </div>
                                    </div>

                                    
                                        
                                        
                                            
                                                
                                            
                                            
                                                
                                                       
                                            
                                        
                                    

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">排序</label>
                                        <div class="col-sm-3">
                                            <input class="form-control" name="sort" value="<?php echo e($cate->sort); ?>"
                                                   placeholder="" type="text" required>

                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-7">
                                    <div class="btn-group pull-left">
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
    </div>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
        $(function () {

            //表单提交
            $('.submits').click(function () {

                var data =$('form').serialize();

                $.ajax({
                    type: 'PUT',
                    url: "<?php echo e(route('material.category.update',$cate->id)); ?>",
                    data: data,
                    dataType: 'json',
                    success: function (info) {

                        if (info.status == 1) {

                            toastr.success(info.msg);
                            setTimeout(function(){window.location="<?php echo e(route('material.category.index')); ?>"},800);
                        } else {
                            toastr.error(info.msg);
                        }

                    }

                })
                return false;
            })

        })

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>