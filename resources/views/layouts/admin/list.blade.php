<!DOCTYPE html>
<html>


<!-- Mirrored from www.zi-han.net/theme/hplus/table_data_tables.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:20:01 GMT -->
<head>
    @include('layouts.admin.header')
    @yield('header')
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="row m-b-sm m-t-sm">
                        <div class="col-md-3">
                            <button type="button" id="loading-example-btn" class="btn btn-white btn-sm"><i class="fa fa-refresh"></i> 刷新</button>
                            @yield('btn')
                        </div>
                        <form id="search_form" method="post" action="@yield('action')">
                            @yield('form_content')
                        </form>
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-sm btn-primary" id="s_btn"> 搜索</button>
                        </span>
                    </div>
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                        <tr>
                            @yield('table_head')
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
    });
    @yield('script')
</script>
</body>
</html>