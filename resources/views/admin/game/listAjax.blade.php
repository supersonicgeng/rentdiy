@foreach($list as $k => $item)
    <tr>
        <td>{{$item->nickname}}</td>
        <td><img style="width: 30px;height: 30px;border-radius: 50%;margin-right: 10px"
                 src="{{$item->headimgurl}}">
        </td>
        <td>@if($item->sex == 1)男@else女@endif</td>
        <td>{{$item->total_score}}</td>
        {{--<td>{{$item->unsubscribe_time?:'未取关'}}</td>--}}
        {{--<td>{{\App\Model\Passport::$STATUS_TEXT[$item->status]}}</td>--}}
        {{--<td>{{$item->level->level_name}}</td>--}}
        <td>
            {{($pageNumber-1)*$pageSize+$k+1}}
        </td>
    </tr>
@endforeach
<script>
    $('.big-img').zoomify();
</script>