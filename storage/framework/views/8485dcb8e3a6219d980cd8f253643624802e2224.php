<?php $__env->startSection('content'); ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                财务管理
                <small>订单报表</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="box box-primary">
                    <div class="box-header with-border">

                        <div class="search-form-inline form-inline pull-left">
                            <form>

                                <div class="input-daterange input-group input-group-sm">
                                    <input class="form-control" id="start_time" name="start_time"
                                           value="<?php echo e(Request::input('start_time')); ?>"
                                           placeholder="开始日期" type="text" autocomplete="off">
                                    <span class="input-group-addon"><i class="fa fa-chevron-right"></i></span>
                                    <input class="form-control" id="end_time" name="end_time"
                                           value="<?php echo e(Request::input('end_time')); ?>"
                                           placeholder="结束日期" type="text" autocomplete="off">
                                </div>

                                <button type="submit" class="btn btn-default btn-sm">确定</button>

                            </form>
                        </div>

                    </div>

                    <div id="statistics" style="width: 100%;height:400px;"></div>
                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-hover">
                            <tbody>
                            <tr>

                                <th>提现订单号</th>
                                <th>用户ID</th>
                                <th>用户昵称</th>
                                <th>手机号</th>
                                <th>提现账号</th>
                                <th>提现金额</th>
                                <th>申请时间</th>
                                <th>到账时间</th>
                                <th>审核状态</th>
                                <th>审核人</th>
                                <th>审核时间</th>
                                
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                        
                            
                                
                                    
                                        
                                    
                                    
                                        
                                    
                                
                            

                        
                    </div>

                </div>




            </div>
        </section>
        <!-- /.content -->
    </div>


<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
    <script src="/vendor/echarts/echarts.min.js"></script>
    <script src="/vendor/echarts/macarons.js"></script>
    <script type="text/javascript">
        var res = {"goods_arr":[6999,0,0,0,1116.9,712,2181.02,926.2,0,0,970.18,746.06,1583.9,138349.11,14747.26,3398,0],"cost_arr":[0,0,0,0,0,0,0,0,0,0,800,16,90,357,878,2000,0],"shipping_arr":[0,0,0,0,0,0,19,20,0,0,65,22,9,62,100,0,0],"coupon_arr":[0,0,0,0,20,100,0,0,0,0,0,0,10,160,10,0,0],"time":["2018-04-13","2018-04-14","2018-04-15","2018-04-16","2018-04-17","2018-04-18","2018-04-19","2018-04-20","2018-04-21","2018-04-22","2018-04-23","2018-04-24","2018-04-25","2018-04-26","2018-04-27","2018-04-28","2018-04-29"]};
        var myChart = echarts.init(document.getElementById('statistics'),'macarons');
        option = {
            tooltip : {
                trigger: 'axis'
            },
            toolbox: {
                show : true,
                feature : {
                    mark : {show: true},
                    dataView : {show: true, readOnly: false},
                    magicType: {show: true, type: ['line', 'bar']},
                    restore : {show: true},
                    saveAsImage : {show: true}
                }
            },
            calculable : true,
            legend: {
                data:['商品总额','优惠金额','商品成本','物流费用']
            },
            xAxis : [
                {
                    type : 'category',
                    data : res.time
                }
            ],
            yAxis : [
                {
                    type : 'value',
                    name : '商品总额',
                    axisLabel : {
                        formatter: '{value} ￥'
                    }
                }
                ,
                {
                    type : 'value',
                    name : '',
                    axisLabel : {
                        formatter: '{value} ￥'
                    }
                }
            ],
            series : [
                {
                    name:'商品总额',
                    type:'bar',
                    data:res.goods_arr
                },
                {
                    name:'优惠金额',
                    type:'bar',
                    data:res.coupon_arr
                },
                {
                    name:'商品成本',
                    type:'bar',
                    data:res.cost_arr
                },
                {
                    name:'物流费用',
                    type:'line',
                    yAxisIndex: 1,
                    data:res.shipping_arr
                }
            ]
        };

        myChart.setOption(option);
        $(document).ready(function(){
            // 表格行点击选中切换
            $('#flexigrid > table>tbody >tr').click(function(){
                $(this).toggleClass('trSelected');
            });

            // 点击刷新数据
            $('.fa-refresh').click(function(){
                location.href = location.href;
            });

            // 起始位置日历控件
            $('#start_time').layDate();
            $('#end_time').layDate();

        });

        function check_form(){
            var start_time = $.trim($('#start_time').val());
            var end_time =  $.trim($('#end_time').val());
            if(start_time == '' ^ end_time == ''){
                layer.alert('请选择完整的时间间隔', {icon: 2});
                return false;
            }
            return true;
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>