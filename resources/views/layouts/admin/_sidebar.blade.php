<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{auth()->user()->avatar ? auth()->user()->avatar : '/avatar.png'}}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{auth()->user()->real_name}}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form (Optional) -->
    {{--<form action="#" method="get" class="sidebar-form">--}}
    {{--<div class="input-group">--}}
    {{--<input type="text" name="q" class="form-control" placeholder="Search...">--}}
    {{--<span class="input-group-btn">--}}
    {{--<button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>--}}
    {{--</button>--}}
    {{--</span>--}}
    {{--</div>--}}
    {{--</form>--}}
    <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">Menu</li>
            <!-- Optionally, you can add icons to the links -->
            <li><a href="/admin"><i class="fa fa-home"></i><span>Backend Index</span></a></li>
            @foreach($menus as $menu)
                <li class="treeview {{$parent_menu == $menu->name ? 'active' : ''}}">
                    <a href="javascript:void(0);">
                        <i class="{{$menu->icon}}"></i><span>{{$menu->label}}</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        @foreach($menu->children as $child)
                            <li @if($children_menu ==$child->name) class="active" @endif>
                                @if(Route::has($child->name))
                                    <a href="{{route($child->name)}}"><i class="{{$child->icon}}"></i>{{$child->label}}</a>
                                @else
                                    <a href="javascript: void 0;" class="error_permission">
                                        <span class="am-icon-close"></span> Permission define error
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </li>
                @endforeach
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
