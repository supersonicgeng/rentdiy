@extends('layouts.admin.model')

@section('content')



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
                                    <input class="form-control" name="num_iid" value="{{Request::input('num_iid')}}"
                                           placeholder="淘宝商品ID搜索"
                                           type="text">
                                </div>
                                <div class="input-daterange input-group input-group-sm">
                                    <input class="form-control" name="title" value="{{Request::input('title')}}"
                                           placeholder="商品标题搜索"
                                           type="text">
                                </div>
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

                                <input type="hidden" name="ids" value="{{Request::input('ids')}}">

                                <button type="submit" class="btn btn-default btn-sm">搜索</button>
                            </form>
                        </div>

                    </div>


                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-hover">
                            <tbody>
                            <tr>
                                <th><input type="checkbox" class="check_all"></th>
                                <th>ID</th>
                                <th>商品标题</th>
                                <th>商品头图</th>

                                <th>淘宝折后价(元){!! table_sort('zk_final_price') !!}</th>
                                <th>券(元)</th>
                                <th>卷后价</th>
                                <th>默认佣金比例{!! table_sort('commission_rate') !!}</th>
                                <th>默认佣金(元){!! table_sort('com_price')  !!}</th>
                                <th>调整后佣金比例</th>
                                <th>调整后佣金(元)</th>

                                <th>所属分类</th>

                                <th>销售量{!! table_sort('volume') !!}</th>
                                <th>人工调整比例</th>
                                <th>调整后权重值</th>
                                <th>首页权重</th>
                                <th>分类权重</th>
                                <th>是否品牌</th>
                                <th>上架/下架</th>
                                <th>商品平台</th>
                            </tr>
                            @foreach($goods as $good)
                                <tr>
                                    <td><input type="checkbox" class="checked_id" name="checked_id[]" @if(in_array($good->id,$ids)) checked @endif
        data-name="{{str_limit($good->title,30,'...')}}" data-image="{{$good->pict_url}}" data-price="{{$good->zk_final_price}}" value="{{$good->id}}"></td>
                                    <td>{{$good->id}}</td>
                                    <td>{{$good->title}}</td>
                                    <td><img data-action="zoom" style="height: 50px;width: 50px" src="{{$good->pict_url}}" alt=""></td>
                                    <td>{{$good->zk_final_price}}</td>
                                    <td>{{$good->coupon_price}}</td>
                                    <td>{{$good->after_coupon_price}}</td>
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
                                    <td>{{$good->s_cate->name ?? ''}}</td>
                                    <td>{{display_times($good->volume)}}</td>
                                    <td>
                                        @if($good->set_commission_rate ==0)
                                            未调整
                                        @else
                                            {{$good->set_commission_rate}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($good->weight ==0)
                                            未调整
                                        @else
                                            {{$good->weight}}
                                        @endif
                                    </td>
                                    <td>
                                        {{$good->index_weight}}
                                    </td>
                                    <td>
                                        {{$good->weight}}
                                    </td>
                                    <td>
                                        {!! is_something('is_brand',$good) !!}
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
        <div class="btn-group pull-right">
            <button type="button" class="btn btn-info pull-right sub">确定</button>
        </div>
    </section>



@endsection

@section('js')
    <script>

        function GoodsItem(goods_id, goods_name, goods_price, goods_image) {
            this.goods_id = goods_id;
            this.goods_name = goods_name;
            this.goods_price = goods_price;
            this.goods_image = goods_image;
        }

        //获取url中的参数
        function getUrlParam(name) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
            var r = window.location.search.substr(1).match(reg);  //匹配目标参数


            if (r != null) return unescape(r[2]); return ''; //返回参数值
        }

        function call_back(goodsArr) {
            var html = '';
            $.each(goodsArr,function (index,item) {
                html += '<li data-id="'+item.goods_id+'">' +
                    '<span class="mailbox-attachment-icon has-img"><img src="'+item.goods_image+'" style="width:100%;height: 120px"></span>' +
                    '<input type="hidden" name="goods_id[]" value="'+item.goods_id+'">'+
                    '<div class="mailbox-attachment-info">' +
                    '<a href="javascript:void(0);" class="mailbox-attachment-name">'+item.goods_name+'</a>' +
                    '<span class="mailbox-attachment-size">价格: '+item.goods_price+'<a href="javascript:void(0);" class="btn btn-default btn-xs pull-right goods_del"><i class="fa fa-trash-o text-red"></i></a></span>' +
                    '</div>' +
                    '</li>';
            })
            window.parent.$('ul.clearfix').append(html);

        }

        $('.sub').click(function () {
            var inputs = $('.checked_id:checked');

            console.log(inputs.length);

            if (inputs.length == 0) {
                layer.msg('至少选择一个商品！', {icon: 5});
                return false;
            }

            // var a = $('.checked_id:checked').serialize();
            var goodsArr = new Array();



            var ids = getUrlParam('ids');


            {{--var ids = '{{implode(',',$ids)}}';--}}



            ids = ids.split(",");


            inputs.each(function (i, o) {

                if($.inArray($(o).val(),ids) == -1){
                    var goodsItem = new GoodsItem($(o).val(),$(o).data('name'),$(o).data('price'),$(o).data('image'));
                    goodsArr.push(goodsItem);
                }
            })

            call_back(goodsArr);
            // $('.checked_id:checked').each(function(){
            //     id_array.push($(this).val());//向数组中添加元素
            // })
            //
            //
            // var p = window.parent.$('#goods').val();
            // var v = p.split(",");
            //
            // v.push.apply(v,id_array);
            // var s = v.filter(function(item){
            //     return item!='';
            // });
            //
            // s = s.join(',');
            // window.parent.$('#goods').val(s);

            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
            parent.layer.close(index); //再执行关闭
        })
    </script>
@endsection