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
                        <div class="col-md-2">
                            <button type="button" id="loading-example-btn" class="btn btn-white btn-sm"><i class="fa fa-refresh"></i> 刷新</button>
                            <a href="{{url('manage/updateWxMenu')}}" class="btn btn-white btn-sm" target="dialog" shade='0.1' height="500px"
                               width="600px" btn="确定:doUpdate,取消" title="新增微信菜单"><i class="fa fa-plus"></i>新增菜单</a>
                            <a href="{{url('manage/refreshWxMenu')}}" class="btn btn-white btn-sm layer-get" title="更新到微信？"><i class="fa fa-cloud-upload"></i>更新到微信</a>
                        </div>
                    </div>
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                        <tr>
                            <th>菜单名称</th>
                            <th>菜单类型</th>
                            <th>值</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($menus['button']))
                        @foreach($menus['button'] as $k=>$item)
                            <tr>
                                <td>{{$item['name']}}</td>
                                <td>{{@$item['type']?\App\Model\Config::$WX_MENU_TYPE[$item['type']]:'顶级菜单'}}</td>
                                <td>{{@$item['url']?:''}}</td>
                                <td>
                                    <a href="{{url('manage/delWxMenu',['key1'=>$k,'key2'=>-1])}}" class="btn btn-white btn-sm layer-get" title="是否删除?"><i class="fa fa-close"></i> 删除 </a>
                                    <a href="{{url('manage/editWxMenu',['key1'=>$k,'key2'=>-1])}}" class="btn btn-white btn-sm" target="dialog" target="dialog" shade='0.1' height="500px"
                                       width="600px" btn="确定:doUpdate,取消" title="编辑微信菜单"><i class="fa fa-pencil"></i> 编辑 </a>
                                </td>
                            </tr>
                            @if(!empty($item['sub_button']))
                                @foreach($item['sub_button'] as $k1=>$item1)
                                    <tr>
                                        <td>--{{$item1['name']}}</td>
                                        <td>{{@\App\Model\Config::$WX_MENU_TYPE[$item1['type']]}}</td>
                                        <td>{{@$item1['url']?:''}}</td>
                                        <td>
                                            <a href="{{url('manage/delWxMenu',['key1'=>$k,'key2'=>$k1])}}" class="btn btn-white btn-sm layer-get" title="是否删除?"><i class="fa fa-close"></i> 删除 </a>
                                            <a href="{{url('manage/editWxMenu',['key1'=>$k,'key2'=>$k1])}}" class="btn btn-white btn-sm" target="dialog" target="dialog" shade='0.1' height="500px"
                                               width="600px" btn="确定:doUpdate,取消" title="编辑微信菜单"><i class="fa fa-pencil"></i> 编辑 </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    function doUpdate(){
        var child = layer.getChildFrame('body');
        loadingShow();
        child.find("#wxSetForm").ajaxSubmit({
            dataType : "json",
            success : ajaxCallback,
            error:errorCallback
        })
    }
</script>
</html>