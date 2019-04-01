@foreach($list as $item)
    <tr>
        <td>{{$item->passport_id}}</td>
        <td>{{$item->nickname}}</td>
        <td><img style="width: 30px;height: 30px;border-radius: 50%;margin-right: 10px"
                 src="{{$item->headimgurl}}">{{$item->nickname}}
        </td>
        <td>@if($item->sex == 1)男@else女@endif</td>
        <td>{{$item->country}}/{{$item->province}}/{{$item->city}}</td>
        <td>{{$item->created_at}}</td>
        <td>{{$item->share}}</td>
        <td>{{$item->add_up_score}}</td>
        <td>{{$item->recommend_score}}</td>
        <td>{{$item->total_score}}</td>
        {{--<td>{{$item->unsubscribe_time?:'未取关'}}</td>--}}
        {{--<td>{{\App\Model\Passport::$STATUS_TEXT[$item->status]}}</td>--}}
        {{--<td>{{$item->level->level_name}}</td>--}}
        <td>
            <button type="button" class="btn btn-white btn-sm"
                    href="{{url('manage/member/gameInfo',[$item->passport_id])}}"
                    target="dialog" shade='0.1' height="800px" width="1200px" btn="提交:submit,关闭" title="游戏记录"><i
                        class="fa fa-pencil"></i> 游戏记录
            </button>
        </td>
    </tr>
@endforeach
<script>
    $('.big-img').zoomify();
</script>