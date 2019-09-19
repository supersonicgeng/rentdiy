<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                用户管理
                <small>用户关系</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li <?php if(Request::input('type') ==1): ?> class="active" <?php endif; ?>><a
                                        href="<?php echo e(route('platform.customer.relation',['id'=>Request::input('id'),'type'=>1])); ?>">直属上级</a>
                            </li>
                            <li <?php if(Request::input('type') ==2): ?> class="active" <?php endif; ?>><a
                                        href="<?php echo e(route('platform.customer.relation',['id'=>Request::input('id'),'type'=>2])); ?>">一级市场</a>
                            </li>
                            <li <?php if(Request::input('type') ==3): ?> class="active" <?php endif; ?>><a
                                        href="<?php echo e(route('platform.customer.relation',['id'=>Request::input('id'),'type'=>3])); ?>">二级市场</a>
                            </li>
                            <li class="pull-right"><a href="javascript:history.back(-1)" class="btn btn-sm"
                                                      style="padding:10px 2px;"><i class="fa fa-list"></i> 返回</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <?php if(Request::input('type') ==1): ?>
                                <div class="tab-pane active" id="tab1">
                                    <div class="box-body table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <tbody>
                                            <tr>
                                                <th>关系</th>
                                                <th>用户ID</th>
                                                <th>用户名</th>
                                                <th>等级</th>
                                                <th>邀请码</th>
                                                <th>注册时间</th>
                                            </tr>
                                            <?php if($members): ?>
                                            <tr>
                                                <td>直属上级</td>
                                                <td><?php echo e($members->id); ?></td>
                                                <td><?php echo e($members->username); ?></td>
                                                <td>
                                                    <?php echo member_level($members->member_type); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($members->invit_code); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($members->created_at); ?>

                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            <?php endif; ?>
                            <?php if(Request::input('type') ==2): ?>
                                <div class="tab-pane active" id="tab2">
                                    <div class="box-body table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <tbody>
                                            <tr>
                                                <th>用户ID</th>
                                                <th>用户名</th>
                                                <th>等级</th>
                                                <th>邀请码</th>
                                                <th>邀请的人数</th>
                                                <th>上月预估收益</th>
                                                <th>累计预估收益</th>
                                                <th>注册时间</th>
                                            </tr>
                                            <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($m->id); ?></td>
                                                <td><?php echo e($m->username); ?></td>
                                                <td><?php echo member_level($m->member_type); ?></td>
                                                <td><?php echo e($m->invit_code); ?></td>
                                                <td><?php echo e($m->invitation_num); ?></td>
                                                <td><?php echo e($m->month_forecast); ?></td>
                                                <td><?php echo e($m->total_forecast); ?></td>
                                                <td><?php echo e($m->created_at); ?></td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                        <div class="pull-right">
                                            <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                                                <form>
                                                    <div class="input-daterange input-group input-group-sm">
                                                        共<?php echo e($members->total()); ?>条&nbsp
                                                    </div>
                                                    <div class="input-daterange input-group input-group-sm">
                                                        <?php echo e($members->appends(Request::all())->links()); ?>

                                                    </div>

                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if(Request::input('type') ==3): ?>
                                <div class="tab-pane active" id="tab3">
                                    <div class="box-body table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <tbody>
                                            <tr>
                                                <th>用户ID</th>
                                                <th>用户名</th>
                                                <th>等级</th>
                                                <th>邀请码</th>
                                                <th>邀请的人数</th>
                                                <th>上月预估收益</th>
                                                <th>累计预估收益</th>
                                                <th>注册时间</th>
                                            </tr>
                                            <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($m->id); ?></td>
                                                    <td><?php echo e($m->username); ?></td>
                                                    <td><?php echo member_level($m->member_type); ?></td>
                                                    <td><?php echo e($m->invit_code); ?></td>
                                                    <td><?php echo e($m->invitation_num); ?></td>
                                                    <td><?php echo e($m->month_forecast); ?></td>
                                                    <td><?php echo e($m->total_forecast); ?></td>
                                                    <td><?php echo e($m->created_at); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                        <div class="pull-right">
                                            <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                                                <form>
                                                    <div class="input-daterange input-group input-group-sm">
                                                        共<?php echo e($members->total()); ?>条&nbsp
                                                    </div>
                                                    <div class="input-daterange input-group input-group-sm">
                                                        <?php echo e($members->appends(Request::all())->links()); ?>

                                                    </div>

                                                </form>
                                            </div>

                                        </div>
                                    </div>

                                </div>

                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>