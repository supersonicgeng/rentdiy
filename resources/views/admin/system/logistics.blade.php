<!DOCTYPE html>
<html>


<!-- Mirrored from www.zi-han.net/theme/hplus/table_data_tables.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:20:01 GMT -->
<head>
    @include('layouts.admin.header')
</head>

<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="row m-b-sm m-t-sm">
                        <div class="col-md-1">
                            <button type="button" id="loading-example-btn" class="btn btn-white btn-sm"><i class="fa fa-refresh"></i> 刷新</button>
                        </div>
                        <form id="search_form" method="post" action="{{url('manage/logistics')}}">
                            <div class="col-md-2">
                                <div class="input-group">
                                    <span class="input-group-addon">公司名称</span>
                                    <input type="text" name="name" placeholder="请输入公司名称" class="input-sm form-control">
                                </div>
                            </div>
                        </form>
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-sm btn-primary" id="s_btn"> 搜索</button>
                        </span>
                    </div>
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>物流公司</th>
                            <th>公司编码</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    pageInit($("#search_form"));
    $("#loading-example-btn,#s_btn").click(function () {
        pageInit($("#search_form"));
    })
</script>
</body>
</html>
