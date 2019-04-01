<style>
    .table {
        border: none;
    }

    .order_title_table {
        width: 100%;
    }

    .order_title_table th {
        width: 25%;
        height: 60px;
        background-color: #eeeeee;
        padding-left: 10px;
    }

    .order_detail_table {
        width: 100%;
        border: 1px solid #e6ebf5;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .order_detail_table td {
        border: 1px solid #e6ebf5;
        height: 42px;
        text-align: center;
    }

    .tag {
        display: inline-block;
        background-color: hsla(225, 4%, 58%, .1);
        border-color: hsla(225, 4%, 58%, .2);
        color: red;
        margin-right: 10px;
        padding: 0 10px;
        height: 26px;
        line-height: 24px;
        font-size: 12px;
        border-radius: 4px;
        box-sizing: border-box;
        border: 1px solid rgba(64, 158, 255, .2);
        white-space: nowrap;
    }

    .avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
    }
</style>
@foreach($list as $item)
    <tr>
        <td>
            <table class="order_title_table">
                <th>订单号：{{$item->order_number}}</th>
                <th>
                    下单时间：{{$item->created_time}}
                    <br/>
                    付款时间：{{$item->pay_time?:'-'}}
                </th>
                <th>
                    买家：<img src="{{$item->passport->headimgurl}}" class="avatar"><span
                            style="padding-left: 10px;">{{$item->passport->nickname}}</span>
                </th>
                <th style="text-align: right;padding-right: 20px;">
                    状态：{{\App\Model\Order::$ORDER_STATUS_TEXT[$item->status]}}
                    @if($item->status == \App\Model\Order::$TYPE_ZERO)
                        <a style="margin-left: 20px;" class="btn btn-white btn-sm layer-get" href="{{url('manage/order/cancel',[$item->id])}}" title="是否取消订单?"><i class="fa fa-close"></i> 取消订单</a>
                    @endif
                </th>
            </table>
            <table class="order_detail_table">
                <tr>
                    <td width="30%">商品</td>
                    <td width="10%">数量</td>
                    <td width="10%">价格</td>
                    <td width="10%">直接分佣人</td>
                    <td width="10%">直接分佣金额</td>
                    <td width="10%">间接分佣人</td>
                    <td width="10%">间接分拥金额</td>
                </tr>
                <tr>
                    <td>{{$item->goods->title}}</td>
                    <td>{{$item->goods_number}}</td>
                    <td>￥{{$item->order_price}}</td>
                    <td rowspan="{{$item->goods->type == 1?count($item->goods->gift)+1:''}}">{{@$item->passport_one->nickname?:'-'}}</td>
                    <td rowspan="{{$item->goods->type == 1?count($item->goods->gift)+1:''}}">￥{{@$item->passport_one?$item->reward_one:'-'}}</td>
                    <td rowspan="{{$item->goods->type == 1?count($item->goods->gift)+1:''}}">{{@$item->passport_two->nickname?:'-'}}</td>
                    <td rowspan="{{$item->goods->type == 1?count($item->goods->gift)+1:''}}">￥{{@$item->passport_two?$item->reward_two:'-'}}</td>
                </tr>
                @if($item->goods->type == 1)
                    @foreach($item->goods->gift as $v)
                    <tr>
                        <td><span class="tag">赠送</span>{{\App\Model\Good::$TYPE[$v->type]}}</td>
                        <td>{{$v->qty}}</td>
                        <td>0</td>
                    </tr>
                    @endforeach
                @endif
                <tr>
                    <td style="padding-left: 10px;text-align: left;background-color: #eeeeee" colspan="8">
                        账号信息：{{$item->phone}}　　收货地址：{{$item->address}}
                    </td>
                </tr>
            </table>
        </td>
        {{--<td>{{$item->order_num}}</td>--}}
        {{--<td>{{\App\Model\Order::$ORDER_STATUS_TEXT[$item->order_status]}}</td>--}}
        {{--<td>{{\App\Model\Order::$TYPE_TEXT[$item->type]}}</td>--}}
        {{--<td>{{$item->logistics}}</td>--}}
        {{--<td>{{$item->wishes->title}}</td>--}}
        {{--<td>{{$item->name}}</td>--}}
        {{--<td>{{$item->phone}}</td>--}}
        {{--<td>--}}
        {{--<a class="btn btn-white btn-sm" href="{{url('manage/order/edit',[$item->id])}}"><i class="fa fa-pencil"></i>编辑</a>--}}
        {{--<a class="btn btn-white btn-sm layer-get" href="{{url('manage/order/del',[$item->id])}}" title="是否删除?"><i class="fa fa-close"></i> 删除</a>--}}
        {{--@if($item->type == \App\Model\Order::$TYPE_ONE)--}}
        {{--<button type="button" class="btn btn-white btn-sm"--}}
        {{--href="{{url('manage/order/logistics',[$item->logistics])}}"--}}
        {{--target="dialog" shade='0.1' height="800px" width="1200px" btn="关闭" title="物流信息"><i class="fa fa-map"></i> 查看物流信息--}}
        {{--</button>--}}
        {{--@endif--}}
        {{--</td>--}}
    </tr>
@endforeach
