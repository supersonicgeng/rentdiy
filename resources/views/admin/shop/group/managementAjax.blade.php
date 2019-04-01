@foreach($list as $item)
    <tr>
        <td>{{$item->name}}</td>
        <td>{{$item->email}}</td>
        <td>{{$item->phone}}</td>
        <td>{{\App\User::$IS_FORBIDDEN_TEXT[$item->is_forbidden]}}</td>
        <td>
            <a href="{{url('manage/edit_management',[$item->group_id])}}" class="btn btn-white btn-sm"><i class="fa fa-pencil"></i> 编辑 </a>
            <a href="{{url('manage/del_management',[$item->group_id])}}" class="btn btn-white btn-sm layer-get" title="确定删除？"><i class="fa fa-close"></i> 删除 </a>
        </td>
    </tr>
@endforeach