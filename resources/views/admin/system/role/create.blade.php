@extends('layouts.admin.base')

@section('css')
    <style>
        .rule_node {
            line-height: 34px;
        }

        .rule_node .left1 {
            background: #f9f9f9;
        }

        .rule_node p {
            clear: both;
            margin-bottom: 0px;
        }

        .rule_node .left2 {
            float: left;
            margin-left: 24px;
        }

        .rule_node .left3 {
            margin-left: 0px;
            clear: none;
        }

        .rule_node .p_left {
            float: left;
        }
    </style>
@endsection

@section('content')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                角色管理
                <small>新增角色</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" method="POST" action="{{route('system.role.store')}}">
                        @csrf
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab1" data-toggle="tab">基本参数</a></li>
                                <li class="pull-right"><a href="{{route('system.role.index')}}" class="btn btn-sm"
                                                          style="padding:10px 2px;"><i class="fa fa-list"></i> 返回</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">


                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">角色名</label>
                                        <div class="col-sm-7">
                                            <input class="form-control" type="text" name="name" value=""
                                                   placeholder="节点名称">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">权限列表</label>
                                        <div class="col-sm-7 rule_node">
                                            @foreach($permissions as $permission)
                                                <div class="level1">
                                                <p class="left1">
                                                    <label class="checkbox-inline">
                                                        <input class="permission1" type="checkbox" value="{{$permission->id}}"
                                                               name="permission_id[]">&nbsp{{$permission->label}}
                                                    </label>
                                                </p>

                                                @foreach($permission->children as $children)
                                                    <div class="level2">
                                                        <p class="left2">
                                                            <label class="checkbox-inline ">
                                                            <input class="permission2" type="checkbox" value="{{$children->id}}"
                                                                   name="permission_id[]">
                                                            &nbsp<span
                                                                    class="label label-info">{{$children->label}}</span>

                                                        </label>
                                                    </p>

                                                        <div class="level3">
                                                            @foreach($children->children as $c)
                                                        <p class="left3 p_left">
                                                            <label class="checkbox-inline">
                                                                <input type="checkbox" class="permission3" value="{{$c->id}}" name="permission_id[]">&nbsp{{$c->label}}

                                                            </label>
                                                        </p>
                                                            @endforeach
                                                        </div>

                                                    </div>
                                                @endforeach
                                                </div>
                                            @endforeach


                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-7">
                                    <div class="btn-group pull-left">
                                        <button type="submit" class="btn btn-info pull-left submits"
                                                data-loading-text="<i class='fa fa-spinner fa-spin '></i> 提交">提交
                                        </button>
                                    </div>
                                    {{--<div class="btn-group pull-left">--}}
                                    {{--<button type="reset" class="btn btn-warning">撤销</button>--}}
                                    {{--</div>--}}
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>


@endsection