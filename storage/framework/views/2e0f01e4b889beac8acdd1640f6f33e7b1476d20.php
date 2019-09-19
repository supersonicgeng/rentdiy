<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                商品专题
                <small>编辑专题</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab1" data-toggle="tab">基本信息</a></li>
                                <li class="pull-right"><a href="javascript:history.back(-1)" class="btn btn-sm"
                                                          style="padding:10px 2px;"><i class="fa fa-list"></i> 返回</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">标题</label>
                                        <div class="col-sm-7">
                                            <input class="form-control" type="text" name="title"
                                                   value="<?php echo e($special->title); ?>"
                                                   placeholder="请输入标题" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">是否建立商品库</label>

                                        <label class="radio-inline">
                                            <input type="radio" name="is_good" value="1"  <?php if($special->is_and == 0 or $special->is_and == 2): ?>checked <?php endif; ?>>&nbsp是&nbsp&nbsp
                                            <input type="radio" name="is_good" value="0" <?php if($special->is_and == 1): ?>checked <?php endif; ?>>&nbsp否
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">商品库商品是否分佣</label>

                                        <label class="radio-inline">
                                            <input type="radio" name="is_fy" value="1" <?php if($special->is_fy == 1): ?>checked <?php endif; ?>>&nbsp是&nbsp&nbsp
                                            <input type="radio" name="is_fy" value="0" <?php if($special->is_fy == 0): ?>checked <?php endif; ?>>&nbsp否
                                        </label>
                                        <small class="radio-inline" style="color: red">不分佣时，当前专题商品库选入的商品不进行裂变分佣</small>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">是否关联分类</label>

                                        <label class="radio-inline">
                                            <input type="radio" name="is_cate" value="1"
                                                   <?php if($special->is_and == 1 or $special->is_and == 2): ?>checked <?php endif; ?>>&nbsp是&nbsp&nbsp
                                            <input type="radio" name="is_cate" value="0"
                                                   <?php if($special->is_and == 0): ?>checked <?php endif; ?>>&nbsp否
                                        </label>
                                    </div>

                                    <div class="is_and" <?php if($special->is_and ==0): ?>style="display: none;"<?php endif; ?>>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">选择分类</label>
                                            <div class="col-sm-7">

                                                <a type="button" data-url="<?php echo e(route('common.category.index')); ?>" class="btn btn-success btn-sm check_cate">选择分类</a>
                                                <a type="button"  class="btn btn-danger btn-sm rm_cate">清空分类</a>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label"></label>
                                            <div class="col-sm-7">
                                                <textarea name="cates_id" id="cates" cols="80" rows="5"
                                                          readonly><?php echo e($special->cates_id); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">价格区间</label>
                                            <div class="col-sm-2">
                                                <div class="input-group input-group-sm">
                                                    <input class="form-control" name="price_min"
                                                           value="<?php echo e($special->price_min); ?>" placeholder="起始价格"
                                                           type="text" required>
                                                    <span class="input-group-addon">-</span>
                                                    <input class="form-control" name="price_max"
                                                           value="<?php echo e($special->price_max); ?>" placeholder="" type="text" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-7">
                                    <div class="btn-group pull-left">
                                        <button type="button" class="btn btn-info pull-right submits"
                                                data-loading-text="<i class='fa fa-spinner fa-spin '></i> 提交">提交
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
	<script src="/dist/admin/js/jquery.lazyload.js" type="text/javascript"></script>
    <script>
    	   $("img.lazy").lazyload({effect: "fadeIn"});
           //图片懒加载

        // 先进行一次检查
//         lazyRender();
//         //为了不在滚轮滚动过程中就一直判定，设置个setTimeout,等停止滚动后再去判定是否出现在视野中。
//         var clock; //这里的clock为timeID，
//         $(window).on('scroll',function () {
// //        lazyRender();
//             if (clock) { // 如果在300毫秒内进行scroll的话，都会被clearTimeout掉，setTimeout不会执行。
//                 //如果有300毫秒外的操作，会得到一个新的timeID即clock，会执行一次setTimeout,然后保存这次setTimeout的ID，
//                 //对于300毫秒内的scroll事件，不会生成新的timeID值，所以会一直被clearTimeout掉，不会执行setTimeout.
//                 clearTimeout(clock);
//             }
//             clock = setTimeout(function () {
//                 console.log('运行了一次');
//                 lazyRender();
//             },300)
//         })

//         function lazyRender () {
//             $('.clearfix img').each(function () {
//                 if (checkShow($(this)) && !isLoaded($(this)) ){
//                     loadImg($(this));
//                 }
//             })
//         }

//         function checkShow($img) { // 传入一个img的jq对象
//             var scrollTop = $(window).scrollTop();  //即页面向上滚动的距离
//             var windowHeight = $(window).height(); // 浏览器自身的高度
//             var offsetTop = $img.offset().top;  //目标标签img相对于document顶部的位置

//             if (offsetTop < (scrollTop + windowHeight) && offsetTop > scrollTop) { //在2个临界状态之间的就为出现在视野中的
//                 return true;
//             }
//             return false;
//         }
//         function isLoaded ($img) {
//             return $img.attr('data-src') === $img.attr('src'); //如果data-src和src相等那么就是已经加载过了
//         }
//         function loadImg ($img) {
//             $img.attr('src',$img.attr('data-src')); // 加载就是把自定义属性中存放的真实的src地址赋给src属性
//         }    



        //清空分类
        $('.rm_cate').click(function () {
            $('#cates').val('');
        })

        /***删除商品***/

        $(document).on('click', '.goods_del', function () {

            $(this).parents('li').remove();
        })

        $("input[name='is_cate']").on('ifClicked', function (event) {
            var is_and = $(this).val();
            if (is_and == 1) {
                $('.is_and').show();
            } else {
                $('.is_and').hide();
            }
        })

        /**
         * 选择弹框
         */
        $('.check_product').click(function () {
            var url = $(this).data('url');
            var ids = $("input[name='goods_id[]']").serializeArray();
            var d = new Array();

            $.each(ids, function() {
                d.push(this.value);//向数组中添加元素
            });

            var s = d.join(',');

            layer.open({
                title: '选择商品',
                type: 2,
                shadeClose: true,
                tipsMore: false,
                shade: [0.5, '#393D49'],
                maxmin: true, //开启最大化最小化按钮
                area: ['50%', '80%'],
                // content: url + '?ids=' + s
                content: url

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
                content: url + '?ids=' + ids


            });
        })

        $('.submits').click(function () {
            var data = $('form').serialize();

            $.ajax({
                url: "<?php echo e(route('shop.special.update',$special->id)); ?>",
                type: 'PUT',
                data: data,
                success: function (data) {
                    if (data.status == 0) {
                        toastr.error(data.msg);

                    } else {
                        toastr.success(data.msg);
                        window.setTimeout("location.href =\"<?php echo e(route('shop.special.index')); ?>\"", 1000);

                    }
                }
            })
        })
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>