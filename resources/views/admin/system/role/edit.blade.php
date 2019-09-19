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
                <small>编辑角色</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" method="POST" action="{{route('system.role.update',$role->id)}}">
                        @csrf
                        @method('PUT')
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab1" data-toggle="tab">基本参数</a></li>
                                <li class="pull-right"><a href="javascript:history.back(-1)" class="btn btn-sm"
                                                          style="padding:10px 2px;"><i class="fa fa-list"></i> 返回</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">


                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">角色名</label>
                                        <div class="col-sm-7">
                                            <input class="form-control" type="text" name="name" value="{{$role->name}}"
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
                                                        <input type="checkbox" class="permission1" value="{{$permission->id}}" @if($role_permissions->contains($permission->id))checked @endif
                                                               name="permission_id[]">&nbsp{{$permission->label}}
                                                    </label>
                                                </p>
                                                @foreach($permission->children as $children)
                                                    <div class="level2">
                                                    <p class="left2">
                                                        <label class="checkbox-inline ">
                                                            <input type="checkbox" class="permission2" value="{{$children->id}}" @if($role_permissions->contains($children->id))checked @endif
                                                                   name="permission_id[]">
                                                            &nbsp<span
                                                                    class="label label-info">{{$children->label}}</span>

                                                        </label>
                                                    </p>
                                                    @foreach($children->children as $c)
                                                        <div class="level3">
                                                        <p class="left3 p_left">
                                                            <label class="checkbox-inline">
                                                                <input type="checkbox" class="permission3" value="{{$c->id}}" name="permission_id[]" @if($role_permissions->contains($c->id))checked @endif>&nbsp{{$c->label}}

                                                            </label>
                                                        </p>
                                                        </div>
                                                    @endforeach
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