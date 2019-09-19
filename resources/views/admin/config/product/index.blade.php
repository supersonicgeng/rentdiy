@extends('layouts.admin.base')

@section('content')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                配置管理
                <small>单品配置列表</small>
            </h1>
            {{--<ol class="breadcrumb">--}}
            {{--<li><a href="#"><i class="fa fa-dashboard"></i> 系统管理</a></li>--}}
            {{--<li class="active">菜单与权限</li>--}}
            {{--</ol>--}}
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            {{--<div class="pull-right">--}}
                            {{--<a class="btn btn-danger btn-sm clear_weight" href="javascript:void(0);" data-url="{{route('shop.product.clear_weight')}}"><i class="fa fa-trash"></i> 清除人工权重</a>--}}
                            {{--</div>--}}
                            <div class="search-form-inline form-inline pull-left">
                                <form>
                                    <div class="input-daterange input-group input-group-sm">
                                        <input class="form-control" name="num_iid" value="{{Request::input('num_iid')}}"
                                               placeholder="输入淘宝商品ID搜索"
                                               type="text">
                                    </div>
                                    <div class="input-daterange input-group input-group-sm">
                                        <input class="form-control" name="title" value="{{Request::input('title')}}"
                                               placeholder="输入商品名称搜索"
                                               type="text">
                                    </div>
                                    <input type="hidden" name="tag_id" value="{{Request::input('tag_id')}}">
                                    <input type="hidden" name="cate_id" value="{{Request::input('cate_id')}}">
                                    <div class="input-daterange input-group input-group-sm">
                                        <select class="form-control" name="user_type">
                                            <option value="-1">店铺类型</option>
                                            <option value="0" @if(Request::input('user_type') == '0') selected @endif>
                                                淘宝
                                            </option>
                                            <option value="1" @if(Request::input('user_type') == 1) selected @endif>天猫
                                            </option>
                                            <option value="2" @if(Request::input('user_type') == 2) selected @endif>京东
                                            </option>
                                        </select>
                                    </div>
                                    <div class="input-daterange input-group input-group-sm">
                                        <select class="form-control" name="is_brand">
                                            <option value="-1">是否品牌</option>
                                            <option value="1" @if(Request::input('is_brand') == 1) selected @endif>品牌</option>
                                            <option value="0" @if(Request::input('is_brand') == '0') selected @endif>非品牌</option>
                                        </select>
                                    </div>
                                    <div class="input-daterange input-group input-group-sm">
                                        <select class="form-control" name="is_index_send">
                                            <option value="-1">是否首页置顶</option>
                                            <option value="1" @if(Request::input('is_index_send') == 1) selected @endif>首页置顶</option>
                                            <option value="0" @if(Request::input('is_index_send') == '0') selected @endif>非首页置顶</option>
                                        </select>
                                    </div>
                                    <div class="input-daterange input-group input-group-sm">
                                        <select class="form-control" name="is_cate_send">
                                            <option value="-1">是否分类置顶</option>
                                            <option value="1" @if(Request::input('is_cate_send') == 1) selected @endif>分类置顶</option>
                                            <option value="0" @if(Request::input('is_cate_send') == '0') selected @endif>非分类置顶</option>
                                        </select>
                                    </div>
                                    <div class="input-daterange input-group input-group-sm">
                                        <select class="form-control" name="is_on">
                                            <option value="-1">是否上架</option>
                                            <option value="1" @if(Request::input('is_on') == 1) selected @endif>上架</option>
                                            <option value="0" @if(Request::input('is_on') == '0') selected @endif>下架</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-default btn-sm">确定</button>
                                    <a href="{{route('profit.checkProduct.index')}}" class="btn btn-default btn-sm">重置</a>
                                </form>
                            </div>

                        </div>


                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>淘宝商品ID</th>
                                    <th>商品标题</th>
                                    <th>商品头图</th>
                                    <th>淘宝现价(元){!! table_sort('zk_final_price') !!}</th>
                                    <th>券(元)</th>
                                    <th>卷后价</th>
                                    {{--<th>优惠截止时间</th>--}}
                                    <th>默认佣金比例{!! table_sort('commission_rate') !!}</th>
                                    <th>默认佣金(元){!! table_sort('com_price')  !!}</th>
                                    <th>调整后佣金比例</th>
                                    <th>调整后佣金(元)</th>
                                    <th>销售量{!! table_sort('volume') !!}</th>
                                    <th>一级分类</th>
                                    <th>二级分类</th>
                                    <th>标签</th>
                                    {{--<th>默认权重值</th>--}}
                                    <th>模型权重{!! table_sort('model_weight')  !!}</th>
                                    <th>人工权重{!! table_sort('weight') !!}</th>
                                    <th>是否品牌</th>
                                    <th>首页置顶</th>
                                    <th>分类置顶</th>
                                    <th>上架/下架</th>
                                    <th>商品平台</th>
                                    <th>操作</th>
                                </tr>
                                @foreach($goods as $good)
                                    <tr data-id="{{$good->id}}">
                                        <td>{{$good->id}}</td>
                                        <td>{{$good->num_iid}}</td>
                                        <td>{{str_limit($good->title,16,'...')}}</td>
                                        <td><img data-action="zoom" style="height: 50px;width: 50px" src="{{$good->pict_url}}" alt=""></td>
                                        <td>{{$good->zk_final_price}}</td>
                                        <td>{{$good->coupon_price}}</td>
                                        <td>{{$good->after_coupon_price}}</td>

                                        {{--<td>{{$good->coupon_end_time}}</td>--}}
                                        <td>{{$good->commission_rate/10000}}</td>
                                        <td>{{$good->com_price}}</td>

                                        <td>
                                            @if($good->set_commission_rate ==0)
                                                未调整
                                            @else
                                                {{$good->commission_rate/10000*(1+$good->set_commission_rate)}}
                                            @endif
                                        </td>

                                        <td>
                                            @if($good->set_commission_rate ==0)
                                                未调整
                                            @else
                                                {{($good->commission_rate/10000*(1+$good->set_commission_rate))*$good->zk_final_price}}
                                            @endif
                                        </td>
                                        <td>{{display_times($good->volume)}}</td>
                                        <td>{{$good->s_cate->f_cate->name ?? ''}}</td>
                                        <td>{{$good->s_cate->name ?? ''}}</td>
                                        <td>{{str_limit($good->tags->implode('name', ','),16,'...')}}</td>
                                        {{--<td></td>--}}
                                        <td>{{$good->model_weight}}</td>
                                        <td>{{$good->weight}}</td>
                                        <td>
                                            {!! is_something('is_brand',$good) !!}
                                        </td>
                                        <td>
                                            {!! is_something('is_index_send',$good) !!}
                                        </td>
                                        <td>
                                            {!! is_something('is_cate_send',$good) !!}
                                        </td>
                                        <td>
                                            {!! is_something('is_on',$good) !!}
                                        </td>
                                        <td>
                                            @if($good->user_type ==0)
                                                淘宝
                                            @elseif($good->user_type ==1)
                                                天猫
                                            @elseif($good->user_type == 2)
                                                京东
                                            @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-info btn-xs" href="{{route('shop.product.addTag',$good->id)}}"><i
                                                        class="fa fa-fw fa-pencil-square"></i> 打标签</a>

                                            <a class="btn btn-success btn-xs"
                                               href="{{route('shop.product.edit',$good->id)}}"><i
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
                                            共{{$goods->total()}}条&nbsp
                                        </div>
                                        <div class="input-daterange input-group input-group-sm">
                                            {{$goods->appends(Request::all())->links()}}
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
                    url: '{{route('shop.product.change_attr')}}',
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

            //清除人工权重
            $('.clear_weight').click(function () {
                var _this = $(this);
                layer.open({
                    title: '警告',
                    shadeClose: true,
                    content: '您将清除所有人工权重？',
                    yes: function (index, layero) {

                        var url = _this.data('url');//获取删除提交地址

                        $.ajax({
                            type: 'PATCH',
                            url: url,
                            success: function (info) {

                                //删除成功
                                if (info.status == 1) {
                                    layer.msg(info.msg, {
                                        icon: 6,
                                        time: 700
                                    }, function () {
                                        location.href = location.href
                                    });

                                } else {
                                    layer.msg(info.msg, {
                                        icon: 5,
                                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                                    });
                                }
                            }
                        })
                        layer.close(index); //如果设定了yes回调，需进行手工关闭
                    }
                });
            })

        })
    </script>
@endsection