

<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                商品列表
                <small>商品入库编辑</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" method="POST" action="<?php echo e(route('shop.product.Putwarehouse')); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab1" data-toggle="tab">基本信息</a></li>
                                
                                                          
                                
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">选择平台分类</label>
                                        <div class="col-sm-5">
                                            <select class="form-control" name="cate_id">
                                                <option value="-1">商品分类</option>
                                                <?php $__currentLoopData = $cates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <optgroup label="<?php echo e($cate->name); ?>">
                                                        <?php $__currentLoopData = $cate->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($c->id); ?>">&nbsp;&nbsp;<?php echo e($c->name); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </optgroup>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">商品头图</label>
                                        <div class="col-sm-5">
                                            <input id="cover" type="hidden" name="pict_url" value="<?php echo e($good->pict_url); ?>">
                                            <input type="file" style="display: none" id="image_upload">
                                            <button type="button" class="btn btn-success btn-sm upload_image">
                                                <i id="loading" class="fa fa-fw fa-cloud-upload"></i>更改图片
                                            </button>
                                            </button> <small style="color: red">&nbsp请上传340px*340px尺寸图片</small>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"></label>
                                        <div class="col-sm-7">
                                            <img data-action="zoom" id="cover_show" src="<?php echo e($good->pict_url); ?>" alt=""
                                                 style="height: 200px;width:300px">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">默认佣金比例</label>
                                        <div class="col-sm-3">
                                            <input class="form-control" type="text"
                                                   value="<?php echo e($good->commission_rate); ?>%" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">调整佣金比例</label>
                                        <div class="col-sm-7">
                                            <div class="row">
                                                <div class="col-md-1"><input type="radio" value="-1" name="check_radio">
                                                    扣减
                                                </div>
                                                <div class="col-md-5"><input class="form-control" id="down" name="down" type="text" value="" placeholder="输入扣减百分比，例：1% 输入0.01"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"></label>

                                        <div class="col-sm-7">
                                            <div class="row">
                                                <div class="col-md-1"><input type="radio" value="1" name="check_radio">
                                                    增加
                                                </div>
                                                <div class="col-md-5"><input class="form-control" id="up" name="up" type="text" value="" placeholder="输入增加百分比，例：1% 输入0.01">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">是否上架</label>
                                        <div class="col-sm-3">
                                            <input type="radio" value="1" name="is_on" checked>&nbsp是&nbsp&nbsp&nbsp
                                            <input type="radio" value="0" name="is_on">&nbsp否

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">是否品牌</label>
                                        <div class="col-sm-3">
                                            <input type="radio" value="1" name="is_brand">&nbsp是&nbsp&nbsp&nbsp
                                            <input type="radio" value="0" name="is_brand" checked>&nbsp否

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">首页权重</label>
                                        <div class="col-sm-3">
                                            <input class="form-control" name="index_weight" type="number" min="0" value="0" placeholder="数值越大首页排序越靠前" required>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">分类权重</label>
                                        <div class="col-sm-3">
                                            <input class="form-control" type="number" min="0" value="0"
                                                   name="weight" placeholder="数值越大分类排序越靠前" required>
                                        </div>
                                    </div>
                                    <input type="hidden" name="collect" value="<?php echo e(json_encode($good)); ?>">
                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-7">
                                    <div class="btn-group pull-left">
                                        <button type="submit" class="btn btn-info pull-right">提交
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

<?php $__env->startSection('js'); ?>
    <script>
        $(function () {
            $("#up").click(function () {

                $(this).parent('div').prev().find('input').iCheck('check');
            })

            $("#down").click(function () {


                $(this).parent('div').prev().find('input').iCheck('check');
            })


        })
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>