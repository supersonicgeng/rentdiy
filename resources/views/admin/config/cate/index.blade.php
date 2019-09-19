@extends('layouts.admin.base')

@section('content')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                配置管理
                <small>已配置分类</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border" style="height:51px;">
                            {{--<div class="pull-left">--}}
                            {{--<a class="btn btn-success btn-sm" href="{{route('shop.category.create')}}"><i class="fa fa-save"></i> 新增</a>--}}
                            {{--</div>--}}
                            <div class="search-form-inline form-inline pull-left">
                                <form>

                                    <div class="input-daterange input-group input-group-sm">
                                        <input class="form-control" name="id" value="{{Request::input('id')}}"
                                               placeholder="输入分类ID搜索" type="text">
                                    </div>
                                    <div class="input-daterange input-group input-group-sm">
                                        <input class="form-control" name="name" value="{{Request::input('name')}}"
                                               placeholder="输入分类名称搜索" type="text">
                                    </div>

                                    <button type="submit" class="btn btn-default btn-sm">搜索</button>
                                    <a href="{{route('profit.checkCate.index')}}" class="btn btn-default btn-sm">重置</a>
                                </form>
                            </div>

                        </div>


                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>分类</th>
                                    <th>图片</th>
                                    <th>包含的商品数量</th>
                                    <th>佣金比例</th>
                                    {{--<th>排序</th>--}}
                                    <th>上架/下架</th>
                                    <th>操作</th>
                                </tr>
                                @foreach($cates as $cate)
                                    <tr data-id="{{$cate->id}}">
                                        <td>{{$cate->id}}</td>
                                        <td>{{$cate->name}}</td>
                                        <td>
                                            <img src="{{$cate->image}}" alt="" style="height: 50px;width: 50px">
                                        </td>
                                        <td>{{$cate->goods_count}}</td>
                                        <td>{{$cate->del_good_ratio}}</td>
                                        {{--<td>{{$cate->sort}}</td>--}}
                                        <td>
                                            {!! is_something('is_show',$cate) !!}

                                        </td>
                                        <td>
                                            <a class="btn btn-info btn-xs" href="/admin/shop?cate_id={{$cate->id}}"><i
                                                        class="fa fa-fw fa-pencil-square"></i> 查看包含商品</a>
                                            <a class="btn btn-success btn-xs"
                                               href="{{route('shop.category.secondEdit',$cate->id)}}"><i
                                                        class="fa fa-edit"></i> 编辑</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pull-right">
                                <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                                    <form>
                                        <div class="input-daterange input-group input-group-sm">
                                            共{{$cates->total()}}条&nbsp
                                        </div>
                                        <div class="input-daterange input-group input-group-sm">
                                            {{$cates->appends(Request::all())->links()}}
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
                    url: '{{route('shop.category.change_attr')}}',
                    success: function (data) {
                        if (data.status == 1) {
                            toastr.success(data.msg);
                        } else {
                            toastr.error(data.msg);
                            return false;
                        }
                        setTimeout(function () {
                            window.location.reload();//页面刷新
                        }, 150);
                    }
                })
            })

        })
    </script>
@endsection
