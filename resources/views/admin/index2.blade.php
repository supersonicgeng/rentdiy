<!DOCTYPE html>
<html>


<!-- Mirrored from www.zi-han.net/theme/hplus/ by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:16:41 GMT -->
<head>
    @include('layouts.admin.header')
</head>

<body class="fixed-sidebar full-height-layout gray-bg" style="overflow:hidden">
<div id="wrapper">
    <!--左侧导航开始-->
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="nav-close"><i class="fa fa-times-circle"></i>
        </div>
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <span><img alt="image" style="width: 50px;height: 50px" class="img-circle" src="{{request()->get('agent')?imgShow(request()->get('agent')->logo,true):'/admin/img/profile_small.jpg'}}"/></span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                               <span class="block m-t-xs"><strong class="font-bold">{{\Illuminate\Support\Facades\Auth::user()->email}}</strong></span>
                                <span class="text-muted text-xs block">{{\Illuminate\Support\Facades\Auth::user()->name}}<b class="caret"></b></span>
                                </span>
                        </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li><a id="editPassword" class="J_menuItem">修改密码</a></li>
                            <li class="divider"></li>
                            <li><a href="{{url('manage/logoutAction')}}">安全退出</a>
                            </li>
                        </ul>
                    </div>
                    <div class="logo-element">HC
                    </div>
                </li>
                <li>
                    <a class="J_menuItem" href="{{url('manage/info')}}"><i class="fa fa-home"></i> <span
                                class="nav-label">主页</span></a>
                </li>
                @foreach($menu as $item)
                    @if($item->pid == \App\Permission::$TOP_CATE_PID)
                        @permission($item['name'])
                        <li>
                            <a href="#">
                                <i class="{{$item->icon}}"></i>
                                <span class="nav-label">{{$item->display_name}}</span>
                                <span class="fa arrow"></span>
                            </a>
                            <ul class="nav nav-second-level">
                                @foreach($menu as $item2)
                                    @if($item2->pid == $item->id)
                                        <li>
                                            <a class="J_menuItem"
                                               href="{{url($item2->name)}}">{{$item2->display_name}}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                        @endpermission
                    @endif
                @endforeach
            </ul>
        </div>
    </nav>
    <!--左侧导航结束-->
    <!--右侧部分开始-->
    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row content-tabs">
            <button class="roll-nav roll-left J_tabLeft navbar-minimalize minimalize-styl-2"><i class="fa fa-backward"></i>
            </button>
            <nav class="page-tabs J_menuTabs">
                <div class="page-tabs-content">
                    <a href="javascript:;" class="active J_menuTab" data-id="{{url('manage/info')}}">首页</a>
                </div>
            </nav>
            <button class="roll-nav roll-right J_tabRight"><i class="fa fa-forward"></i>
            </button>
            <div class="btn-group roll-nav roll-right">
                <button class="dropdown J_tabClose" data-toggle="dropdown">关闭操作<span class="caret"></span>

                </button>
                <ul role="menu" class="dropdown-menu dropdown-menu-right">
                    <li class="J_tabShowActive"><a>定位当前选项卡</a>
                    </li>
                    <li class="divider"></li>
                    <li class="J_tabCloseAll"><a>关闭全部选项卡</a>
                    </li>
                    <li class="J_tabCloseOther"><a>关闭其他选项卡</a>
                    </li>
                </ul>
            </div>
            <a href="{{url('manage/logoutAction')}}" class="roll-nav roll-right J_tabExit"><i
                        class="fa fa fa-sign-out"></i> 退出</a>
        </div>
        <div class="row J_mainContent" id="content-main">
            <iframe class="J_iframe" name="iframe0" width="100%" height="100%" src="{{url('manage/info')}}"
                    frameborder="0" data-id="{{url('manage/info')}}" seamless></iframe>
        </div>
        <div class="footer">
            <div class="pull-right">&copy; 2018-2019 <a href="{{env('APP_URL')}}" target="_blank">IFC</a>
            </div>
        </div>
    </div>
    <!--右侧部分结束-->
</div>
<script src="/admin/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="/admin/js/plugins/layer/layer.min.js"></script>
<script src="/admin/js/hplus.min.js?v=4.1.0"></script>
<script type="text/javascript" src="/admin/js/contabs.min.js"></script>
<script src="/admin/js/plugins/pace/pace.min.js"></script>
<script>
    $('#editPassword').click(function () {
        layer.open({
            type: 2,
            area: ['700px', '450px'],
            fixed: false, //不固定
            maxmin: true,
            content: "{{url('manage/editPassword')}}"
        })
    })
</script>
</body>
</html>
