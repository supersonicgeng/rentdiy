

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
                    <form class="form-horizontal" method="POST" action="<?php echo e(route('home.project.update',$project->id)); ?>">
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
                                        <label class="col-sm-2 control-label">专题名称<span style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-5">
                                            <input class="form-control" type="text" placeholder="最多4个字" value="<?php echo e($project->title); ?>" name="title" required>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">样式选择<span style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-5">
                                            <select class="form-control" name="matter_id" id="matter_id">
                                                <option value="3" <?php if($project->matter_id == 3): ?> selected <?php endif; ?>>专题-图片</option>
                                                <option value="4" <?php if($project->matter_id == 4): ?> selected <?php endif; ?>>专题-榜单</option>
                                            </select>
                                        </div>
                                    </div>
                                  <div id="matter">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">商品头图<span style="color: red;">&nbsp*</span></label>
                                            <div class="col-sm-7">
                                                <input id="cover" type="hidden" name="image" value="<?php echo e($project->image); ?>">
                                                <input type="file" style="display: none" id="image_upload">
                                                <button type="button" class="btn btn-success btn-sm upload_image">
                                                    <i id="loading" class="fa fa-fw fa-cloud-upload"></i>上传封面图
                                                </button><small style="color: red">&nbsp建议上传300px*300px或380px*140px尺寸图片</small>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label"></label>
                                            <div class="col-sm-7">
                                                <img data-action="zoom" id="cover_show" src="<?php echo e($project->image); ?>" alt=""
                                                     style="height: 200px;width:200px">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">跳转类型<span style="color: red;">&nbsp*</span></label>
                                            <div class="col-sm-5">
                                                <select class="form-control" name="type">
                                                    <option value="1" <?php if($project->type ==1): ?> selected <?php endif; ?>>商品详情页</option>
                                                    <option value="2" <?php if($project->type ==2): ?> selected <?php endif; ?>>商品专题页</option>
                                                    <option value="3" <?php if($project->type ==3): ?> selected <?php endif; ?>>活动页</option>
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
                                                      <?php if($good !=''): ?>
                                                          <li data-id="<?php echo e($good->id); ?>">
                                                        <span class="mailbox-attachment-icon has-img"><img
                                                                    src="<?php echo e($good->pict_url); ?>"
                                                                    style="width:100%;height: 120px"></span>
                                                              <input type="hidden" name="good_id" value="<?php echo e($good->id); ?>">
                                                              <div class="mailbox-attachment-info">
                                                                  <a href="javascript:void(0);"
                                                                     class="mailbox-attachment-name"><?php echo e(str_limit($good->title,50,'...')); ?></a>
                                                                  <span class="mailbox-attachment-size">价格: <?php echo e($good->zk_final_price); ?><a
                                                                              href="#"
                                                                              class="btn btn-default btn-xs pull-right goods_del"><i
                                                                                  class="fa fa-trash-o text-red"></i></a></span>
                                                              </div>
                                                          </li>

                                                      <?php endif; ?>
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
                                                              readonly><?php echo e($project->special_id); ?></textarea>

                                                </div>
                                            </div>
                                        </div>




                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">排序</label>
                                        <div class="col-sm-5">
                                            <input class="form-control" name="sort" value="<?php echo e($project->sort); ?>" placeholder="" type="text">

                                        </div>
                                    </div>

                                    <div class="form-group" id="wy" style="display: none">
                                        <label class="col-sm-2 control-label">URL<span style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-5">
                                            <input class="form-control" name="url" value="<?php echo e($project->url); ?>" placeholder="" type="text">

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

        var a = $('select[name=matter_id]').val();

        var b = $('select[name=type]').val();

        if(a == 3){
            $('#matter').show();

            if (b == 1) {
                $('#sp').show();
                $('#zt').hide();
                $('#wy').hide();
            }

            if (b == 2) {
                $('#sp').hide();
                $('#zt').show();
                $('#wy').hide();
            }

            if (b == 3) {
                $('#sp').hide();
                $('#zt').hide();
                $('#wy').show();
            }
        }

        if(a ==4){
            $('#matter').hide();
            $('#zt').show();
        }

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