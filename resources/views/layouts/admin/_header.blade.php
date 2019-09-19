<header class="main-header">

    <!-- Logo -->
    <a href="index2.html" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>A</b>LT</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Rental Platform</b></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Messages: style can be found in dropdown.less-->
                {{--<li class="dropdown messages-menu">--}}
                    {{--<!-- Menu toggle button -->--}}
                    {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
                        {{--<i class="fa fa-envelope-o"></i>--}}
                        {{--<span class="label label-success">4</span>--}}
                    {{--</a>--}}
                    {{--<ul class="dropdown-menu">--}}
                        {{--<li class="header">You have 4 messages</li>--}}
                        {{--<li>--}}
                            {{--<!-- inner menu: contains the messages -->--}}
                            {{--<ul class="menu">--}}
                                {{--<li><!-- start message -->--}}
                                    {{--<a href="#">--}}
                                        {{--<div class="pull-left">--}}
                                            {{--<!-- User Image -->--}}
                                            {{--<img src="/dis/admin//user2-160x160.jpg" class="img-circle" alt="User Image">--}}
                                        {{--</div>--}}
                                        {{--<!-- Message title and timestamp -->--}}
                                        {{--<h4>--}}
                                            {{--Support Team--}}
                                            {{--<small><i class="fa fa-clock-o"></i> 5 mins</small>--}}
                                        {{--</h4>--}}
                                        {{--<!-- The message -->--}}
                                        {{--<p>Why not buy a new awesome theme?</p>--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                                {{--<!-- end message -->--}}
                            {{--</ul>--}}
                            {{--<!-- /.menu -->--}}
                        {{--</li>--}}
                        {{--<li class="footer"><a href="#">See All Messages</a></li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
                <!-- /.messages-menu -->

                <!-- Notifications Menu -->
                {{--<li class="dropdown notifications-menu">--}}
                    {{--<!-- Menu toggle button -->--}}
                    {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
                        {{--<i class="fa fa-bell-o"></i>--}}
                        {{--<span class="label label-warning">10</span>--}}
                    {{--</a>--}}
                    {{--<ul class="dropdown-menu">--}}
                        {{--<li class="header">You have 10 notifications</li>--}}
                        {{--<li>--}}
                            {{--<!-- Inner Menu: contains the notifications -->--}}
                            {{--<ul class="menu">--}}
                                {{--<li><!-- start notification -->--}}
                                    {{--<a href="#">--}}
                                        {{--<i class="fa fa-users text-aqua"></i> 5 new members joined today--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                                {{--<!-- end notification -->--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                        {{--<li class="footer"><a href="#">View all</a></li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
                <!-- Tasks Menu -->
                <li class="dropdown tasks-menu">
                    <!-- Menu Toggle Button -->
                    <a href="{{route('admin.lock')}}">
                        <i class="fa fa-desktop"></i> Lock Screen
                    </a>

                </li>
                <li class="dropdown tasks-menu">
                    <!-- Menu Toggle Button -->
                    <a href="{{route('admin.clear')}}">
                        <i class="fa fa-paint-brush"></i> Clear Cache
                    </a>
                </li>
                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <img src="{{auth()->user()->avatar ? auth()->user()->avatar : '/avatar.png'}}" class="user-image" alt="User Image">
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">{{auth()->user()->real_name}}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <img src="{{auth()->user()->avatar ? auth()->user()->avatar : '/avatar.png'}}" class="img-circle" alt="User Image">

                            <p>
                                {{auth()->user()->real_name}}
                            </p>
                        </li>

                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{route('system.user.person')}}" class="btn btn-default btn-flat">个人设置</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="btn btn-default btn-flat">退出</a>
                            </div>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                {{--<li>--}}
                {{--<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>--}}
                {{--</li>--}}
            </ul>
        </div>
    </nav>
</header>
