@foreach($list as $item)
    <tr>
        <td>{{$item->id}}</td>
        <td>{{$item->title}}</td>
        <td>{{$item->created_at}}</td>
        <td>
            <a href="{{url('manage/goodsCategoryDel',[$item->id])}}" class="btn btn-white btn-sm layer-get" title="是否删除?"><i class="fa fa-close"></i> 删除 </a>
            <a href="{{url('manage/goodsCategoryEdit',[$item->id])}}" class="btn btn-white btn-sm"><i class="fa fa-pencil"></i> 编辑 </a>
        </td>
    </tr>
@endforeach
