<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                运营位管理
                <small>新增运营位</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" method="POST" action="<?php echo e(route('operate.locate.store')); ?>">
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
                                        <label class="col-sm-2 control-label">运营位名称<span style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-5">
                                            <input class="form-control" type="text" name="m_title" value="<?php echo e(old('m_title')); ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">最大运营内容数量<span style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-5">
                                            <input class="form-control" type="text" name="subject_num" value="<?php echo e(old('subject_num')); ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">运营位描述</label>
                                        <div class="col-sm-5">
                                            <textarea name="describe" cols="80" rows="10"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">是否启用</label>

                                        <label class="radio-inline">
                                            <input type="radio" name="is_on" value="1" checked>&nbsp是&nbsp&nbsp
                                            <input type="radio" name="is_on" value="0" >&nbsp否
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-7">
                                    <div class="btn-group pull-left">
                                        <button type="submit" class="btn btn-info pull-right submits"
                                                data-loading-text="<i class='fa fa-spinner fa-spin '></i> 提交">提交
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



<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>