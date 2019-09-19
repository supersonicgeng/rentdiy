<?php $__env->startSection('content'); ?>

    <div class="content-wrapper">

        <section class="content-header">
            <h1>
                专题商品库
                <small><?php echo e($special->title); ?> 的商品库</small>
            </h1>

        </section>

        <section class="content">

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border" style="height:51px;">
                            <div class="pull-right"><a href="javascript:history.back(-1)" class="btn btn-sm"
                                                       style="padding:10px 2px;"><i class="fa fa-list"></i> 返回</a>
                            </div>

                            <div class="pull-left">
                                <a class="btn btn-success btn-sm import" href="javascript:void(0);"><i
                                            class="fa fa-save"></i> 导入商品</a>
                            </div>
                            <div class="search-form-inline form-inline pull-left" style="padding-left: 10px">
                                <form>

                                    <div class="input-daterange input-group input-group-sm">
                                        <input class="form-control" name="num_iid" value="<?php echo e(Request::input('num_iid')); ?>"
                                               placeholder="淘宝商品ID搜索"
                                               type="text">
                                    </div>
                                    <div class="input-daterange input-group input-group-sm">
                                        <input class="form-control" name="title" value="<?php echo e(Request::input('title')); ?>"
                                               placeholder="商品标题搜索"
                                               type="text">
                                    </div>
                                    <div class="input-daterange input-group input-group-sm">
                                        <select class="form-control" name="user_type">
                                            <option value="-1">店铺类型</option>
                                            <option value="0" <?php if(Request::input('user_type') == '0'): ?> selected <?php endif; ?>>
                                                淘宝
                                            </option>
                                            <option value="1" <?php if(Request::input('user_type') == 1): ?> selected <?php endif; ?>>天猫
                                            </option>
                                            <option value="2" <?php if(Request::input('user_type') == 2): ?> selected <?php endif; ?>>京东
                                            </option>
                                        </select>
                                    </div>
                                    <div class="input-daterange input-group input-group-sm">
                                        <select class="form-control" name="is_brand">
                                            <option value="-1">是否品牌</option>
                                            <option value="1" <?php if(Request::input('is_brand') == 1): ?> selected <?php endif; ?>>品牌
                                            </option>
                                            <option value="0" <?php if(Request::input('is_brand') == '0'): ?> selected <?php endif; ?>>
                                                非品牌
                                            </option>
                                        </select>
                                    </div>
                                    <div class="input-daterange input-group input-group-sm">
                                        <select class="form-control" name="is_index_send">
                                            <option value="-1">是否首页配置权重</option>
                                            <option value="1" <?php if(Request::input('is_index_send') == 1): ?> selected <?php endif; ?>>
                                                配置首页权重
                                            </option>
                                            <option value="0"
                                                    <?php if(Request::input('is_index_send') == '0'): ?> selected <?php endif; ?>>未配置首页权重
                                            </option>
                                        </select>
                                    </div>
                                    <div class="input-daterange input-group input-group-sm">
                                        <select class="form-control" name="is_cate_send">
                                            <option value="-1">是否分类配置权重</option>
                                            <option value="1" <?php if(Request::input('is_cate_send') == 1): ?> selected <?php endif; ?>>
                                                配置分类权重
                                            </option>
                                            <option value="0"
                                                    <?php if(Request::input('is_cate_send') == '0'): ?> selected <?php endif; ?>>未配置分类权重
                                            </option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-default btn-sm">搜索</button>
                                </form>
                            </div>

                        </div>


                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>

                                    <th>淘宝商品id</th>
                                    <th>商品标题</th>
                                    <th>商品头图</th>
                                    <th>淘宝折后价(元)<?php echo table_sort('zk_final_price'); ?></th>
                                    <th>券(元)</th>
                                    <th>卷后价</th>
                                    <th>默认佣金比例<?php echo table_sort('commission_rate'); ?></th>
                                    <th>默认佣金(元)<?php echo table_sort('com_price'); ?></th>
                                    <th>调整后佣金比例</th>
                                    <th>调整后佣金(元)</th>
                                    <th>所属一级分类</th>
                                    <th>所属二级分类</th>
                                    <th>销售量<?php echo table_sort('volume'); ?></th>
                                    <th>首页权重</th>
                                    <th>分类权重</th>
                                    <th>是否品牌</th>
                                    <th>是否分佣</th>
                                    <th>上架/下架</th>
                                    <th>商品平台</th>
                                    <th>操作</th>
                                </tr>
                                <?php $__currentLoopData = $goods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $good): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>

                                        <td><?php echo e($good->num_iid); ?></td>
                                        <td><?php echo e($good->title); ?></td>
                                        <td><img data-action="zoom" style="height: 50px;width: 50px"
                                                 src="<?php echo e($good->pict_url); ?>" alt=""></td>
                                        <td><?php echo e($good->zk_final_price); ?></td>
                                        <td><?php echo e($good->coupon_price); ?></td>
                                        <td><?php echo e($good->after_coupon_price); ?></td>
                                        <td><?php echo e($good->commission_rate/10000); ?></td>
                                        <td><?php echo e($good->com_price); ?></td>

                                        <td>
                                            <?php if($good->set_commission_rate ==0): ?>
                                                未调整
                                            <?php else: ?>
                                                <?php echo e($good->commission_rate/10000*(1+$good->set_commission_rate)); ?>

                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <?php if($good->set_commission_rate ==0): ?>
                                                未调整
                                            <?php else: ?>
                                                <?php echo e(($good->commission_rate/10000*(1+$good->set_commission_rate))*$good->zk_final_price); ?>

                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($good->f_cate); ?></td>
                                        <td><?php echo e($good->s_cate); ?></td>
                                        <td><?php echo e(display_times($good->volume)); ?></td>


                                        <td>
                                            <?php echo e($good->index_weight); ?>

                                        </td>
                                        <td>
                                            <?php echo e($good->weight); ?>

                                        </td>
                                        <td>
                                            <?php echo is_something('is_brand',$good); ?>

                                        </td>
                                        <td>
                                            <?php echo is_something('is_need_fy',$good); ?>

                                        </td>
                                        <td>
                                            <?php echo is_something('is_on',$good); ?>

                                        </td>
                                        <td>
                                            <?php if($good->user_type ==0): ?>
                                                淘宝
                                            <?php elseif($good->user_type ==1): ?>
                                                天猫
                                            <?php elseif($good->user_type == 2): ?>
                                                京东
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a class="btn btn-danger btn-xs delete_genius" href="javascript:void(0);"
                                               data-url="<?php echo e(route('shop.special.rm_good',$good->special_id)); ?>"><i
                                                        class="fa fa-trash"></i> 移除</a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <div class="pull-right">
                                <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                                    <form>
                                        <div class="input-daterange input-group input-group-sm">
                                            共<?php echo e($goods->total()); ?>条&nbsp
                                        </div>
                                        <div class="input-daterange input-group input-group-sm">
                                            <?php echo e($goods->appends(Request::all())->links()); ?>

                                        </div>

                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>

    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
        $(function () {
            $('.import').click(function () {
                layer.prompt({
                    formType: 2,
                    value: '',
                    placeholder: '请输入新密码',
                    title: '单个输入淘宝商品id，多个以逗号隔开 例:1,2,3',
                    area: ['500', '200px'] //自定义文本域宽高
                }, function (value, index, elem) {

                    var ids = value;
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo e(route('shop.special.import',$special->id)); ?>',
                        data: {ids: ids},
                        success: function (data) {

                            if (data.status == 1) {
                                toastr.success(data.msg);
                                setTimeout(function () {
                                    parent.location.reload();
                                }, 800);
                            }else{
                                layer.open({
                                    title: '错误'
                                    ,content: data.msg
                                });

                            }
                        }

                    })


                    // layer.close(index);
                });
            })
        })
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>