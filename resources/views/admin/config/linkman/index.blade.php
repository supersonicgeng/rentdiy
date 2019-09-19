@extends('layouts.admin.base')

@section('content')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                配置管理
                <small>平台联系人列表</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border" style="height:51px;">
                            <div class="pull-left">
                                @if(auth()->user()->id ==1)
                                    <a class="btn btn-success btn-sm" href="{{route('profit.linkman.create')}}"><i
                                                class="fa fa-save"></i> 新增</a>
                                @endif
                                {{--<a class="btn btn-danger btn-sm delete_all" href="javascript:;" data-url=""><i class="fa fa-trash"></i> 多选删除</a>--}}
                            </div>
                            {{--<div class="search-form-inline form-inline pull-left" style="margin-left:10px;">--}}
                            {{--<form>--}}
                            {{--<div class="input-daterange input-group input-group-sm">--}}
                            {{--<input class="form-control" name="id" value="{{Request::input('id')}}" placeholder="输入标签ID搜索"--}}
                            {{--type="text">--}}
                            {{--</div>--}}
                            {{--<div class="input-daterange input-group input-group-sm">--}}
                            {{--<input class="form-control" name="name" value="{{Request::input('name')}}" placeholder="输入标签名称搜索"--}}
                            {{--type="text">--}}
                            {{--</div>--}}
                            {{--<button type="submit" class="btn btn-default btn-sm">搜索</button>--}}
                            {{--<a href="{{ route('platform.identity.index') }}" class="btn btn-default btn-sm">重置</a>--}}
                            {{--</form>--}}
                            {{--</div>--}}

                        </div>


                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>微信号</th>
                                    <th>手机号</th>
                                    <th>展示位置</th>
                                    <th>操作人</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                                @foreach($links as $link)
                                    <tr>
                                        <td>{{$link->id}}</td>
                                        <td>{{$link->wechat}}</td>
                                        <td>{{$link->username}}</td>
                                        <td>
                                            @if($link->type ==1)
                                                升级合伙人页面的
                                            @elseif($link->type ==2)
                                                合伙人弹框显示的
                                            @endif
                                        </td>
                                        <td>{{$link->real_name}}</td>
                                        <td>{{$link->created_at}}</td>
                                        <td>
                                            <a class="btn btn-success btn-xs add"
                                               href="{{route('profit.linkman.edit',$link->id)}}"><i
                                                        class="fa fa-edit"></i> 编辑</a>
                                            @if(auth()->user()->id ==1)
                                                <a class="btn btn-danger btn-xs delete_genius"
                                                   href="javascript:void(0);"
                                                   data-url="{{route('profit.linkman.destroy',$link->id)}}"><i
                                                            class="fa fa-trash"></i> 删除</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{--<div class="pull-right">--}}
                            {{--<div class="search-form-inline form-inline pull-left" style="margin-left:10px;">--}}
                            {{--<form>--}}
                            {{--<div class="input-daterange input-group input-group-sm">--}}
                            {{--共{{$channels->total()}}条&nbsp--}}
                            {{--</div>--}}
                            {{--<div class="input-daterange input-group input-group-sm">--}}
                            {{--{{$channels->appends(Request::all())->links()}}--}}
                            {{--</div>--}}

                            {{--</form>--}}
                            {{--</div>--}}

                            {{--</div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>


@endsection
