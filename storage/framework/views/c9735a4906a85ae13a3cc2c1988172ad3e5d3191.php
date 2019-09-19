<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?php echo e(auth()->user()->avatar ? auth()->user()->avatar : '/avatar.png'); ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?php echo e(auth()->user()->real_name); ?></p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> 在线</a>
            </div>
        </div>

        <!-- search form (Optional) -->
    
    
    
    
    
    
    
    
    
    <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">菜单</li>
            <!-- Optionally, you can add icons to the links -->
            <li><a href="/admin"><i class="fa fa-home"></i><span>后台首页</span></a></li>
            <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="treeview <?php echo e($parent_menu == $menu->name ? 'active' : ''); ?>">
                    <a href="javascript:void(0);">
                        <i class="<?php echo e($menu->icon); ?>"></i><span><?php echo e($menu->label); ?></span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <?php $__currentLoopData = $menu->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li <?php if($children_menu ==$child->name): ?> class="active" <?php endif; ?>>
                                <?php if(Route::has($child->name)): ?>
                                    <a href="<?php echo e(route($child->name)); ?>"><i class="<?php echo e($child->icon); ?>"></i><?php echo e($child->label); ?></a>
                                <?php else: ?>
                                    <a href="javascript: void 0;" class="error_permission">
                                        <span class="am-icon-close"></span> 权限定义错误
                                    </a>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
