<!DOCTYPE html>
<html>


<!-- Mirrored from www.zi-han.net/theme/hplus/index_v3.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:18:46 GMT -->
<head>
    @include('layouts.admin.header')
    @include('plugins.qrcode')
</head>

<body class="gray-bg">
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-sm-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-primary pull-right">总</span>
                    <h5>用户数</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins" id="acheve_rate">0</h1>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-primary pull-right">单</span>
                    <h5>单局最高得分</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins" id="total_income">0</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="flot-chart">
                <div class="flot-chart-content" id="flot-dashboard-chart" style="height: 500px">
                    <div class="sk-spinner sk-spinner-circle">
                        <div class="sk-circle3 sk-circle"></div>
                        <div class="sk-circle4 sk-circle"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/admin/js/plugins/echarts/echarts-all.js"></script>
<script>
    var myChart = echarts.init(document.getElementById('flot-dashboard-chart'));


    $.ajax({
        url:"{{url('api/adminData')}}",
        type:"GET",
        dataType:"json",
        success: function (response) {
            if(parseInt(response.code) == 0){
                $("#acheve_rate").html(response.data.total_data.user_count);
                $("#total_income").html(response.data.total_data.best_score);
                var option = {
                    title: {
                        text: '游戏总分排行'
                    },
                    tooltip: {
                        trigger: 'axis'
                    },
                    legend: {
                        data:['总分统计']
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    toolbox: {
                        feature: {
                            saveAsImage : {show: true}
                        }
                    },
                    xAxis: {
                        type: 'category',
                        boundaryGap: true,
                        data: response.data.nickname
                    },
                    yAxis: {
                        type: 'value',
                    },
                    series: [
                        {
                            name:'个人总分',
                            type:'bar',
                            stack: '总量',
                            data:response.data.total_score,
                            barCateGoryGap:'20%',
                            barWidth:60,
                        }
                    ]
                };
                myChart.setOption(option);
            }else{
                alertError(response.msg);
            }

        }
    });
</script>
</body>
</html>
