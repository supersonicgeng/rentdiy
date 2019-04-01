@foreach($list as $item)
    <tr>
        <td>{{$item->passport->nickname}}</td>
        <td>{{\App\Model\PassportReward::$TYPE_TEXT[$item->type]}}</td>
        <td>{{$item->account}}</td>
        <td>{{$item->username}}</td>
        <td>{{$item->apply_money}}</td>
        <td>{{$item->rate}}</td>
        <td>{{$item->money}}</td>
        <td>{{$item->created_at}}</td>
        <td>{{$item->apply_time}}</td>
        <td><span class="label label-{{\App\Model\PassportReward::$STATUS_TEXT[$item->status]['color']}}">{{\App\Model\PassportReward::$STATUS_TEXT[$item->status]['text']}}</span></td>
        <td>
            @if($item['status'] == \App\Model\PassportReward::$UNDO)
            <a href="{{url('manage/finance/financePass',[$item->id])}}" class="btn btn-white btn-sm layer-get" title="该操作将会支付用户{{$item->money}}元，确认？"><i class="fa fa-check"></i> 通过 </a>
            <a href="{{url('manage/finance/financeDeny',[$item->id])}}" class="btn btn-white btn-sm layer-get" title="确认驳回？"><i class="fa fa-close"></i> 驳回 </a>
            @endif
        </td>
    </tr>
@endforeach
