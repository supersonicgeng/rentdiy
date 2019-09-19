<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                首页管理
                <small>图片专题和榜单</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" method="POST" action="<?php echo e(route('home.project.store')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab1" data-toggle="tab">专题1</a></li>
                                
                                
                                <li class="pull-right"><a href="javascript:history.back(-1)" class="btn btn-sm"
                                                          style="padding:10px 2px;"><i class="fa fa-list"></i> 返回</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">专题名称<span style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-5">
                                            <input class="form-control" placeholder="最多4个字" type="text" name="title" required>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">样式选择<span style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-5">
                                            <select class="form-control" name="matter_id" id="matter_id">
                                                <option value="3">专题-图片</option>
                                                <option value="4">专题-榜单</option>
                                            </select>
                                        </div>
                                    </div>
                                  <div id="matter">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">专题图片<span style="color: red;">&nbsp*</span></label>
                                            <div class="col-sm-7">
                                                <input id="cover" type="hidden" name="image" value="">
                                                <input type="file" style="display: none" id="image_upload">
                                                <button type="button" class="btn btn-success btn-sm upload_image">
                                                    <i id="loading" class="fa fa-fw fa-cloud-upload"></i>上传封面图
                                                </button><small style="color: red">&nbsp建议上传300px*300px或380px*140px尺寸图片</small>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label"></label>
                                            <div class="col-sm-7">
                                                <img data-action="zoom" id="cover_show" src="/avatar.png" alt=""
                                                     style="height: 200px;width:200px">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">跳转类型<span style="color: red;">&nbsp*</span></label>
                                            <div class="col-sm-5">
                                                <select class="form-control" name="type">
                                                    <option value="1">商品详情页</option>
                                                    <option value="2">商品专题页</option>
                                                    <option value="3">活动页</option>
                                                    <option value="5">淘宝猜你喜欢</option>
                                                </select>
                                            </div>
                                        </div>
                                      <div class="form-group" id="sp">
                                          <label class="col-sm-2 control-label">指定商品<span style="color: red;">&nbsp*</span></label>
                                          <div class="col-sm-7">
                                              <div class="margin-bottom">
                                                  <button data-url="<?php echo e(route('common.product.single')); ?>"
                                                          data-url="指定商品"
                                                          type="button"
                                                          class="btn btn-info btn-sm check_model">选择商品
                                                  </button>
                                              </div>
                                              <div>
                                                  <ul class="mailbox-attachments clearfix">

                                                  </ul>

                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                        <div class="form-group" id="zt" style="display: none">
                                            <label class="col-sm-2 control-label">指定专题<span style="color: red;">&nbsp*</span></label>
                                            <div class="col-sm-7">
                                                <div class="margin-bottom">
                                                    <button data-url="<?php echo e(route('common.special.index')); ?>"
                                                            data-title="选择专题" type="button"
                                                            class="btn btn-info btn-sm check_model">选择专题
                                                    </button>
                                                </div>
                                                <div>
                                                    <textarea name="special_id" id="special_id" cols="80" rows="5"
                                                              readonly></textarea>

                                                </div>
                                            </div>
                                        </div>




                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">排序</label>
                                        <div class="col-sm-5">
                                            <input class="form-control" name="sort" value="" placeholder="" type="text">

                                        </div>
                                    </div>

                                    <div class="form-group" id="wy" style="display: none">
                                        <label class="col-sm-2 control-label">URL</label>
                                        <div class="col-sm-5">
                                            <input class="form-control" name="url" value="" placeholder="" type="text">

                                        </div>
                                    </div>


                                </div>
                                
                                    
                                        
                                        
                                            
                                        
                                    
                                    
                                        
                                        
                                            
                                                
                                                
                                            
                                        
                                    
                                    
                                        
                                            
                                            
                                                
                                                
                                                
                                                    
                                                
                                            
                                        
                                        
                                            
                                            
                                                
                                                     
                                            
                                        
                                        
                                            
                                            
                                                
                                                    
                                                    
                                                    
                                                
                                            
                                        
                                        
                                            
                                            
                                                
                                                    
                                                            
                                                            
                                                            
                                                    
                                                
                                                
                                                    
                                                           

                                                
                                            
                                        
                                    
                                    
                                        
                                        
                                            
                                                
                                                        
                                                        
                                                
                                            
                                            
                                                    
                                                              

                                            
                                        
                                    




                                    
                                        
                                        
                                            

                                        
                                    

                                    
                                        
                                        
                                            

                                        
                                    


                                
                                
                                    
                                        
                                        
                                            
                                        
                                    
                                    
                                        
                                        
                                            
                                                
                                                
                                            
                                        
                                    
                                    
                                        
                                            
                                            
                                                
                                                
                                                
                                                    
                                                
                                            
                                        
                                        
                                            
                                            
                                                
                                                     
                                            
                                        
                                        
                                            
                                            
                                                
                                                    
                                                    
                                                    
                                                
                                            
                                        
                                        
                                            
                                            
                                                
                                                    
                                                            
                                                            
                                                            
                                                    
                                                
                                                
                                                    
                                                           

                                                
                                            
                                        
                                    
                                    
                                        
                                        
                                            
                                                
                                                        
                                                        
                                                
                                            
                                            
                                                    
                                                              

                                            
                                        
                                    




                                    
                                        
                                        
                                            

                                        
                                    

                                    
                                        
                                        
                                            

                                        
                                    


                                
                            </div>
                            <div class="box-footer">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-7">
                                    <div class="btn-group pull-right">
                                        <button type="submit" class="btn btn-info pull-right submits"
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

<?php $__env->startSection('js'); ?>
    <script>
        $(document).on('click', '.goods_del', function () {

            $(this).parents('li').remove();
        })

        $('select[name=type]').change(function () {
            var type = $(this).val();

            if (type == 1) {
                $('#sp').show();
                $('#zt').hide();
                $('#wy').hide();
            }

            if (type == 2) {
                $('#sp').hide();
                $('#zt').show();
                $('#wy').hide();
            }

            if (type == 3) {
                $('#sp').hide();
                $('#zt').hide();
                $('#wy').show();
            }
        })

        $('select[name=matter_id]').change(function () {
            var matter_id = $(this).val();

            if(matter_id == 3){
                $('#matter').show();

                var type = $('select[name=type]').val();

                if (type == 1) {
                    $('#sp').show();
                    $('#zt').hide();
                    $('#wy').hide();
                }

                if (type == 2) {
                    $('#sp').hide();
                    $('#zt').show();
                    $('#wy').hide();
                }

                if (type == 3) {
                    $('#sp').hide();
                    $('#zt').hide();
                    $('#wy').show();
                }
            }

            if(matter_id ==4){
                $('#matter').hide();
                $('#zt').show();
            }
        })
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>