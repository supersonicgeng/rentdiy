<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                物料管理
                <small>栏目列表</small>
            </h1>
            
            
            
            
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <div class="pull-left">
                                <a class="btn btn-primary btn-sm" href="<?php echo e(route('material.category.create')); ?>"><i
                                            class="fa fa-save"></i> 新增</a>
                            </div>
                            <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                                <form>
                                    <div class="input-daterange input-group input-group-sm">
                                        <input class="form-control" name="name"
                                               value="<?php echo e(Request::input('name')); ?>"
                                               placeholder="关键字搜索"
                                               type="text">
                                    </div>

                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    

                                    <button type="submit" class="btn btn-default btn-sm">确定</button>
                                    <a href="<?php echo e(route('material.category.index')); ?>" class="btn btn-default btn-sm">重置</a>
                                </form>
                            </div>

                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>栏目名称</th>
                                    <th>排序</th>
                                    <th>创建管理员</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                                <?php $__currentLoopData = $cates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr data-id="<?php echo e($cate->id); ?>">
                                        <td><?php echo e($cate->id); ?></td>
                                        <td><?php echo e($cate->name); ?></td>
                                        <td><?php echo e($cate->sort); ?></td>
                                        <td><?php echo e($cate->admin->real_name); ?></td>
                                        <td><?php echo e($cate->created_at); ?></td>
                                        <td>
                                            <a class="btn btn-primary btn-xs"
                                               href="<?php echo e(route('material.category.edit',$cate->id)); ?>"><i
                                                        class="fa fa-edit"></i> 编辑</a>
                                            <a class="btn btn-danger btn-xs delete_genius" href="javascript:void(0);"
                                               data-url="<?php echo e(route('material.category.destroy',$cate->id)); ?>"><i
                                                        class="fa fa-trash"></i> 删除</a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <div class="pull-right">
                                <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                                    <form>
                                        <div class="input-daterange input-group input-group-sm">
                                            共<?php echo e($cates->total()); ?>条&nbsp
                                        </div>
                                        <div class="input-daterange input-group input-group-sm">
                                            <?php echo e($cates->appends(Request::all())->links()); ?>

                                        </div>

                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>


<?php $__env->stopSection(); ?>


    
        
        
            
            

            
                
                
                
                
                    
                        
                    
                        
                        
                    
                    
                        
                    
                
            
        
    

<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>