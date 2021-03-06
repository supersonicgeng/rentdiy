@extends('layouts.admin.base')

@section('content')



    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                403 Error Page
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="error-page">
                <h2 class="headline text-yellow"> 403</h2>

                <div class="error-content">
                    <h3><i class="fa fa-warning text-yellow"></i> 警告!</h3>

                    <p>
                        您没有访问当前页面的权限！
                        <a href="/admin">return to dashboard</a>
                    </p>

                    {{--<form class="search-form">--}}
                        {{--<div class="input-group">--}}
                            {{--<input type="text" name="search" class="form-control" placeholder="Search">--}}

                            {{--<div class="input-group-btn">--}}
                                {{--<button type="submit" name="submit" class="btn btn-warning btn-flat"><i class="fa fa-search"></i>--}}
                                {{--</button>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<!-- /.input-group -->--}}
                    {{--</form>--}}
                </div>
                <!-- /.error-content -->
            </div>
            <!-- /.error-page -->
        </section>

        <!-- /.content -->
    </div>

@endsection