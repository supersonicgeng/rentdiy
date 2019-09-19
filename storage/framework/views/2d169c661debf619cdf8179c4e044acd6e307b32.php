<header class="main-header">

    <!-- Logo -->
    <a href="index2.html" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>A</b>LT</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Rental Platform</b></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Messages: style can be found in dropdown.less-->
                
                    
                    
                        
                        
                    
                    
                        
                        
                            
                            
                                
                                    
                                        
                                            
                                            
                                        
                                        
                                        
                                            
                                            
                                        
                                        
                                        
                                    
                                
                                
                            
                            
                        
                        
                    
                
                <!-- /.messages-menu -->

                <!-- Notifications Menu -->
                
                    
                    
                        
                        
                    
                    
                        
                        
                            
                            
                                
                                    
                                        
                                    
                                
                                
                            
                        
                        
                    
                
                <!-- Tasks Menu -->
                <li class="dropdown tasks-menu">
                    <!-- Menu Toggle Button -->
                    <a href="<?php echo e(route('admin.lock')); ?>">
                        <i class="fa fa-desktop"></i> Lock Screen
                    </a>

                </li>
                <li class="dropdown tasks-menu">
                    <!-- Menu Toggle Button -->
                    <a href="<?php echo e(route('admin.clear')); ?>">
                        <i class="fa fa-paint-brush"></i> Clear Cache
                    </a>
                </li>
                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <img src="<?php echo e(auth()->user()->avatar ? auth()->user()->avatar : '/avatar.png'); ?>" class="user-image" alt="User Image">
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs"><?php echo e(auth()->user()->real_name); ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <img src="<?php echo e(auth()->user()->avatar ? auth()->user()->avatar : '/avatar.png'); ?>" class="img-circle" alt="User Image">

                            <p>
                                <?php echo e(auth()->user()->real_name); ?>

                            </p>
                        </li>

                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?php echo e(route('system.user.person')); ?>" class="btn btn-default btn-flat">个人设置</a>
                            </div>
                            <div class="pull-right">
                                <a href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="btn btn-default btn-flat">退出</a>
                            </div>
                            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                <?php echo csrf_field(); ?>
                            </form>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                
                
                
            </ul>
        </div>
    </nav>
</header>
