

<?php $__env->startSection('content'); ?>



    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                403 Error Page
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="error-page">
                <h2 class="headline text-yellow"> 403</h2>

                <div class="error-content">
                    <h3><i class="fa fa-warning text-yellow"></i> 警告!</h3>

                    <p>
                        您没有访问当前页面的权限！
                        <a href="/admin">return to dashboard</a>
                    </p>

                    
                        
                            

                            
                                
                                
                            
                        
                        
                    
                </div>
                <!-- /.error-content -->
            </div>
            <!-- /.error-page -->
        </section>

        <!-- /.content -->
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>