@extends('layouts.admin.base')

@section('css')
    <link rel="stylesheet" href="/vendor/cropper/dist/cropper.min.css">
    <style>
        img {
            max-width: 100%; /* This rule is very important, please do not ignore this! */
        }
    </style>
@endsection

@section('content')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                个人资料
                {{--<small>Control panel</small>--}}
            </h1>
            {{--<ol class="breadcrumb">--}}
            {{--<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>--}}
            {{--<li class="active">Dashboard</li>--}}
            {{--</ol>--}}
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" method="POST" action="{{route('system.user.zlUpdate')}}">
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
                                        <label class="col-sm-2 control-label">用户名</label>
                                        <div class="col-sm-7">
                                            <input class="form-control" type="text" value="{{$admin->username}}"
                                                   disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">个人头像</label>
                                        <div class="col-sm-2">
                                            <input id="cover" type="hidden" name="avatar" value="{{$admin->avatar}}">
                                            <input type="file" style="display: none" id="image_upload">
                                            <button type="button" class="btn btn-success btn-sm upload_image">
                                                <i id="loading" class="fa fa-fw fa-cloud-upload"></i>更改图片
                                            </button>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"></label>
                                        <div class="col-sm-7">
                                            <img id="cover_show" src="{{$admin->avatar}}" class="img-circle"
                                                 style="width:200px;">
                                            {{--<img id="image" src="/avatar.png" class="img-circle"--}}
                                                 {{--style="width:200px;">--}}
                                            {{--<button type="button" data-method="rotate" data-option="90" class="btn btn-primary"><span class="fa fa-rotate-left"></span></button>--}}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">真实姓名</label>
                                        <div class="col-sm-7">
                                            <input class="form-control" type="text" name="real_name"
                                                   value="{{$admin->real_name}}"
                                                   placeholder="请输入真实姓名">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">邮箱</label>
                                        <div class="col-sm-7">
                                            <input class="form-control" type="text" name="email"
                                                   value="{{$admin->email}}"
                                                   placeholder="请输入真实邮箱">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">旧密码</label>
                                        <div class="col-sm-7">
                                            <input class="form-control" type="password" name="old_password" value=""
                                                   placeholder="请输入旧密码">
                                        </div>
                                        <div class="col-sm-3">
                                            <span class="help-block">若需修改密码，则以下密码项都填写，否则则忽略密码项</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">新密码</label>
                                        <div class="col-sm-7">
                                            <input class="form-control" type="password" name="password" value=""
                                                   placeholder="请输入密码">
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">确认密码</label>
                                        <div class="col-sm-7">
                                            <input class="form-control" type="password" name="password_confirmation"
                                                   value=""
                                                   placeholder="请输入确认密码">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-7">
                                    <div class="btn-group pull-right">
                                        <button type="submit" class="btn btn-info pull-right"
                                                data-loading-text="<i class='fa fa-spinner fa-spin '></i> 提交">提交
                                        </button>
                                    </div>
                                    <div class="btn-group pull-left">
                                        <button type="reset" class="btn btn-warning">撤销</button>
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
@section('js')
    <script src="/vendor/cropper/dist/cropper.min.js"></script>
    <script>
        $(function () {
            var $image = $('#image');

            $image.cropper({
                aspectRatio: 1 / 1,
                crop: function(event) {
                    // console.log(event.detail.x);
                    // console.log(event.detail.y);
                    // console.log(event.detail.width);
                    // console.log(event.detail.height);
                    // console.log(event.detail.rotate);
                    // console.log(event.detail.scaleX);
                    // console.log(event.detail.scaleY);
                }
            });

            var cropper = $image.data('cropper');

        })
    </script>
@endsection