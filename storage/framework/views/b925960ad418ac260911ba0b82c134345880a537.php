

<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                用户管理
                <small>用户树形图</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-body table-responsive">
                            <div id="treeShow" style="width:100%;height:700px;">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>


<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
    <script src="/vendor/echarts/echarts.min.js"></script>
    <script type="text/javascript">
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('treeShow'));

        myChart.showLoading();


        $.ajax({
            type: 'GET',
            url: "<?php echo e(route('platform.customer.treeData',$id)); ?>",
            dataType: 'json',
            success: function (data) {
                myChart.hideLoading();

                myChart.setOption(option = {
                    title: {
                        text: data.value+'的树形图',
                        x: 'center'
                    },
                    tooltip: {
                        trigger: 'item',
                        triggerOn: 'mousemove'
                    },
                    series: [
                        {
                            type: 'tree',

                            data: [data],

                            left: '2%',
                            right: '2%',
                            top: '8%',
                            bottom: '20%',

                            symbol: 'emptyCircle',

                            orient: 'vertical',

                            expandAndCollapse: true,
                            initialTreeDepth:100,
                            label: {
                                normal: {
                                    position: 'top',
                                    rotate: 360,
                                    verticalAlign: 'middle',
                                    align: 'right',
                                    fontSize: 12
                                }
                            },

                            leaves: {
                                label: {
                                    normal: {
                                        position: 'bottom',
                                        rotate: -90,
                                        verticalAlign: 'middle',
                                        align: 'left'
                                    }
                                }
                            },

                            animationDurationUpdate: 750
                        }
                    ]
                });
            }
        })


        window.addEventListener("resize", () => {
            this.myChart.resize();
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>