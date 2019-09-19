

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


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                角色管理
                <small>编辑角色</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" method="POST" action="<?php echo e(route('system.role.update',$role->id)); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
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
                                        <label class="col-sm-2 control-label">角色名</label>
                                        <div class="col-sm-7">
                                            <input class="form-control" type="text" name="name" value="<?php echo e($role->name); ?>"
                                                   placeholder="节点名称">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">权限列表</label>
                                        <div class="col-sm-7 rule_node">
                                            <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="level1">

                                                <p class="left1">
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="permission1" value="<?php echo e($permission->id); ?>" <?php if($role_permissions->contains($permission->id)): ?>checked <?php endif; ?>
                                                               name="permission_id[]">&nbsp<?php echo e($permission->label); ?>

                                                    </label>
                                                </p>
                                                <?php $__currentLoopData = $permission->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $children): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="level2">
                                                    <p class="left2">
                                                        <label class="checkbox-inline ">
                                                            <input type="checkbox" class="permission2" value="<?php echo e($children->id); ?>" <?php if($role_permissions->contains($children->id)): ?>checked <?php endif; ?>
                                                                   name="permission_id[]">
                                                            &nbsp<span
                                                                    class="label label-info"><?php echo e($children->label); ?></span>

                                                        </label>
                                                    </p>
                                                    <?php $__currentLoopData = $children->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="level3">
                                                        <p class="left3 p_left">
                                                            <label class="checkbox-inline">
                                                                <input type="checkbox" class="permission3" value="<?php echo e($c->id); ?>" name="permission_id[]" <?php if($role_permissions->contains($c->id)): ?>checked <?php endif; ?>>&nbsp<?php echo e($c->label); ?>


                                                            </label>
                                                        </p>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-7">
                                    <div class="btn-group pull-left">
                                        <button type="submit" class="btn btn-info pull-left submits"
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