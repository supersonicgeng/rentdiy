@foreach($list as $item)
    <tr>
        <td>{{$item->name}}</td>
        <td>{{$item->display_name}}</td>
        <td>{{$item->description}}</td>
        <td>
            <a href="{{url('manage/rolesPermission',[$item->id])}}" class="btn btn-white btn-sm"><i class="fa fa-pencil"></i> 授权 </a>
        </td>
    </tr>
@endforeach