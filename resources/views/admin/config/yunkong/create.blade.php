@extends('layouts.admin.base')


@section('content')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                配置管理
                <small>新增云控</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" method="post" action="{{route('profit.iosc.store')}}">
                         @csrf
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
                                        <label class="col-sm-2 control-label">版本号<span style="color: red;">&nbsp*</span></label>
                                        <div class="col-sm-3">
                                            <input class="form-control" name="version" placeholder="" value="{{old('version')}}"
                                                   type="text" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">是否启用</label>

                                        <label class="radio-inline">
                                            <input type="radio" name="is_on" value="1" checked>&nbsp是&nbsp&nbsp
                                            <input type="radio" name="is_on" value="0" >&nbsp否
                                        </label>
                                    </div>



                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-7">
                                    <div class="btn-group pull-left">
                                        <button type="submit" class="btn btn-info pull-right submits">提交
                                        </button>
                                    </div>

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


