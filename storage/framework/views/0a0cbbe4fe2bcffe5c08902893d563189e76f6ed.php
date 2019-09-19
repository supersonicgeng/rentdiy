

<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                编辑管理员
                
            </h1>
            
                
                
            
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" method="POST" action="<?php echo e(route('system.user.update',$user->id)); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab1" data-toggle="tab">基本参数</a></li>
                                <li class="pull-right"><a href="javascript:history.back(-1)" class="btn btn-sm" style="padding:10px 2px;"><i class="fa fa-list"></i> 返回</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">用户名</label>
                                        <div class="col-sm-7">
                                            <input class="form-control" type="text" name="username"  value="<?php echo e($user->username); ?>"
                                                   placeholder="请输入用户名" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">真实姓名</label>
                                        <div class="col-sm-7">
                                            <input class="form-control" type="text" name="real_name" value="<?php echo e($user->real_name); ?>"
                                                   placeholder="请输入真实姓名">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">邮箱</label>
                                        <div class="col-sm-7">
                                            <input class="form-control" type="text" name="email" value="<?php echo e($user->email); ?>"
                                                   placeholder="请输入真实邮箱">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">原始密码</label>
                                        <div class="col-sm-7">
                                            <input class="form-control" type="password" name="old_password" value=""
                                                   placeholder="请输入原始密码">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">新密码</label>
                                        <div class="col-sm-7">
                                            <input class="form-control" type="password" name="password" value=""
                                                   placeholder="请输入确认密码">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">确认密码</label>
                                        <div class="col-sm-7">
                                            <input class="form-control" type="password" name="password_confirmation" value=""
                                                   placeholder="请输入确认密码">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">选择角色</label>
                                        <div class="col-sm-7">
                                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" value="<?php echo e($role->id); ?>" name="role_id[]"
                                                           <?php if(old('role_id')): ?>
                                                           <?php if(in_array($role->id, old('role_id'))): ?>
                                                           checked
                                                           <?php endif; ?>
                                                           <?php elseif($user_roles->contains($role->id)): ?>
                                                           checked
                                                            <?php endif; ?>>
                                                    <?php echo e($role->name); ?>

                                                </label>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-7">
                                    <div class="btn-group pull-right">
                                        <button type="submit" class="btn btn-info pull-right"
                                                data-loading-text="<i class='fa fa-spinner fa-spin '></i> 提交">提交
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
    </div>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>