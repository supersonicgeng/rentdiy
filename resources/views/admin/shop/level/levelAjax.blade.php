@foreach($list as $item)
    <tr>
        <td>{{$item->id}}</td>
        <td>{{$item->level}}</td>
        <td>{{$item->level_name}}</td>
        <td>{{$item->min_score}}</td>
        <td>{{$item->max_score}}</td>
        <td>
            {{--@if($item->status == \App\Model\Passport::$STATUS_ON)--}}
            {{--<a href="{{url('manage/member/applyPass',[$item->passport_id])}}" class="btn btn-white btn-sm layer-get" title="是否通过认证?"><i class="fa fa-check"></i> 通过 </a>--}}
            {{--@else--}}
            {{--<a href="{{url('manage/member/applyDeny',[$item->passport_id])}}" class="btn btn-white btn-sm layer-get" title="是否取消认证?"><i class="fa fa-close"></i> 取消 </a>--}}
            {{--@endif--}}
            <a class="btn btn-white btn-sm" href="{{url('manage/edit_level',[$item->id])}}"><i class="fa fa-pencil"></i> 编辑</a>
            <a class="btn btn-white btn-sm layer-get" href="{{url('manage/del_level',[$item->id])}}" title="是否删除?"><i class="fa fa-close"></i> 删除</a>
        </td>
    </tr>
@endforeach