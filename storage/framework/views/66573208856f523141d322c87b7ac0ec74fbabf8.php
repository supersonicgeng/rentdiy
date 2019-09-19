


<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                物料管理
                <small>人物列表</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal">

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
                                        <label class="col-sm-2 control-label">角色头像<span style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-2">
                                            <input id="cover" type="hidden" name="imageurl" value="<?php echo e($matuser->imageurl); ?>">
                                            <input type="file" style="display: none" id="image_upload">
                                            <button type="button" class="btn btn-success btn-sm upload_image">
                                                <i id="loading" class="fa fa-fw fa-cloud-upload"></i>上传头像
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"></label>
                                        <div class="col-sm-7">
                                            <img class="img-circle"  id="cover_show" src="<?php echo e($matuser->imageurl); ?>" alt="" style="height: 100px;width:100px">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">角色名称<span style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-3">
                                            <input class="form-control" name="mname" placeholder="名称最多6个字" value="<?php echo e($matuser->mname); ?>" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">角色title<span style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-3">
                                            <input class="form-control" name="title" placeholder="title最多6个字" value="<?php echo e($matuser->title); ?>" type="text">
                                        </div>
                                    </div>
                                    
                                        
                                        
                                            
                                        
                                    
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">是否置顶</label>
                                        <div class="col-sm-3">
                                            <label class="checkbox-inline">
                                                <input type="radio" name="toptag" value="0" checked> 否
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="radio" name="toptag" value="1" <?php if($matuser->toptag ==1): ?>checked <?php endif; ?>> 是
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">角色分类</label>
                                        <div class="col-sm-7">
                                            <div class="margin-bottom">
                                                <button data-url="<?php echo e(route('common.category.index')); ?>" type="button"
                                                        class="btn btn-info btn-sm check_cate">选择分类
                                                </button>
                                            </div>
                                            <div>
                                                <textarea name="cats" id="cates" cols="80" rows="5" readonly><?php echo e($matuser->cats); ?></textarea>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">角色标签</label>
                                        <div class="col-sm-7">
                                            <div class="margin-bottom">
                                                <button data-url="<?php echo e(route('common.tag.index')); ?>" type="button"
                                                        class="btn btn-info btn-sm check_tag">选择标签
                                                </button>
                                            </div>
                                            <div>
                                                <div class="box-body table-responsive">
                                                    <table class="table table-bordered table-hover">
                                                        <tbody>
                                                        <tr>
                                                            <th>标签ID</th>
                                                            <th>标签名称</th>
                                                            <th>被使用次数</th>
                                                            <th>操作</th>
                                                        </tr>
                                                        <?php if($tags->count()> 0): ?>
                                                            <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <tr>
                                                                    <td style="display: none">
                                                                        <input type="text" name="tag_id[]" value="<?php echo e($tag->id); ?>">
                                                                    </td>
                                                                    <td><?php echo e($tag->id); ?></td>
                                                                    <td><?php echo e($tag->name); ?></td>
                                                                    <td><?php echo e($tag->be_use_time); ?></td>
                                                                    <td>
                                                                        <a class="btn btn-danger btn-xs remove_tag" href="javascript:void(0);" data-url=""><i class="fa fa-trash"></i> 移除</a>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php endif; ?>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>



                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">排序</label>
                                        <div class="col-sm-3">
                                            <input class="form-control" name="sort" value="<?php echo e($matuser->sort); ?>" placeholder="" type="text">

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">角色介绍<span style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-3">
                                            <textarea name="desc" cols="80" rows="5" placeholder="介绍不超过150个字"><?php echo e($matuser->desc); ?></textarea>


                                        </div>
                                    </div>
                                
                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-7">
                                    <div class="btn-group pull-right">
                                        <button type="button" class="btn btn-info pull-right submits"
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
    <script src="/vendor/html5-fileupload/jquery.html5-fileupload.js"></script>
    <script>
        $(function () {
            //表单提交
            $('.submits').click(function () {

                var data =$('form').serialize();

                $.ajax({
                    type: 'PUT',
                    url: "<?php echo e(route('material.person.update',$matuser->mid)); ?>",
                    data: data,
                    dataType: 'json',
                    success: function (info) {

                        if (info.status == 1) {

                            toastr.success(info.msg);
                            setTimeout(function(){window.location="<?php echo e(route('material.person.index')); ?>"},800);
                        } else {
                            toastr.error(info.msg);
                        }

                    }

                })
                return false;
            })



            //移除标签
            $(document).on('click', '.remove_tag', function () {
                $(this).parents('tr').remove();
            })

            $('.check_tag').click(function () {

                var ids = $("input[name='tag_id[]']").serialize();
                console.log(ids);
                layer.open({
                    title: '选择标签',
                    type: 2,
                    shadeClose: true,
                    tipsMore: false,
                    shade: [0.5, '#393D49'],
                    maxmin: true, //开启最大化最小化按钮
                    area: ['50%', '70%'],
                    content: '/admin/common/tag?' + ids

                });
            })

            $('.check_cate').click(function () {

                var url = $(this).data('url');
                var ids = $('#cates').val();

                layer.open({
                    title: '选择分类',
                    type: 2,
                    shadeClose: true,
                    tipsMore: false,
                    shade: [0.5, '#393D49'],
                    maxmin: true, //开启最大化最小化按钮
                    area: ['50%', '80%'],
                    content: url+'?ids='+ids

                });
            })
        })

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>