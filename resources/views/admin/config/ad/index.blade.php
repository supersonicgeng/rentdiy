@extends('layouts.admin.base')

@section('content')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                配置管理
                <small>开屏广告列表</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <div class="pull-left">
                                <a class="btn btn-primary btn-sm" href="{{route('profit.ad.create')}}"><i
                                            class="fa fa-save"></i> 新增</a>
                            </div>
                            <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                                <form>
                                    <div class="input-daterange input-group input-group-sm">
                                        <input class="form-control" name="title" value="{{Request::input('title')}}" placeholder="输入广告名称"
                                               type="text">
                                    </div>
                                    <div class="input-group input-group-sm">
                                        <select class="form-control" name="type" style="width: 100%">
                                            <option value="-1">跳转类型</option>
                                            <option value="1"
                                                    @if(Request::input('type') == 1) selected @endif>商品详情
                                            </option>
                                            <option value="2"
                                                    @if(Request::input('type') == 2) selected @endif>专题
                                            </option>
                                            <option value="3"
                                                    @if(Request::input('type') == 3) selected @endif>URL活动页
                                            </option>

                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-default btn-sm">搜索</button>
                                    <a type="submit" href="{{route('profit.ad.index')}}" class="btn btn-default btn-sm">重置</a>
                                </form>
                            </div>

                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>广告名称</th>
                                    <th>跳转类型</th>
                                    <th>图片</th>
                                    <th>排序</th>
                                    <th>上架/下架</th>
                                    <th>配置生效时间</th>
                                    <th>配置结束时间</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                                @foreach($ads as $banner)
                                    <tr data-id="{{$banner->id}}">
                                        <td>{{$banner->id}}</td>
                                        <td>{{$banner->title}}</td>
                                        <td>
                                            @if($banner->type ==1)
                                                商品详情
                                            @elseif($banner->type ==2)
                                                专题
                                             {{--@elseif($banner->type==6)--}}
                                                {{--VIP开通页--}}
                                            {{--@elseif($banner->type==7)--}}
                                                {{--端内指定页--}}
                                            @else
                                               URL活动页
                                            @endif
                                        </td>
                                        <td><img src="{{$banner->image1}}" alt="" style="height: 50px;width:50px"></td>
                                        <td>{{$banner->sort}}</td>
                                        <td>{!! is_something('is_on',$banner) !!}</td>
                                        <td>{{$banner->start_at}}</td>
                                        <td>{{$banner->stop_at}}</td>
                                        <td>{{$banner->created_at}}</td>
                                        <td>
                                            <a class="btn btn-primary btn-xs"
                                               href="{{route('profit.ad.edit',$banner->id)}}"><i
                                                        class="fa fa-edit"></i> 编辑</a>
                                            <a class="btn btn-danger btn-xs delete_genius" href="javascript:void(0);"
                                               data-url="{{route('profit.ad.destroy',$banner->id)}}"><i
                                                        class="fa fa-trash"></i> 删除</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <div class="pull-right">
                                <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                                    <form>
                                        <div class="input-daterange input-group input-group-sm">
                                            共{{$ads->total()}}条&nbsp
                                        </div>
                                        <div class="input-daterange input-group input-group-sm">
                                            {{$ads->appends(Request::all())->links()}}
                                        </div>

                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>


@endsection
@section('js')
    <script>
        $(function () {
            //改变状态
            $('.change_attr').click(function () {
                var attr = $(this).data('attr');
                var id = $(this).parents('tr').data('id');

                $.ajax({
                    type: 'PATCH',
                    data: {attr: attr, id: id},
                    url: '{{route('profit.ad.change_attr')}}',
                    success: function (data) {
                        if (data.status == 1) {
                            toastr.success(data.msg);
                        } else {
                            toastr.error(data.msg);
                            return false;
                        }
                        setTimeout(function(){
                            window.location.reload();//页面刷新
                        },150);
                    }
                })
            })



        })
    </script>
@endsection