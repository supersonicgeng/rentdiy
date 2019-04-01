@foreach($list as $item)
    <tr>
        <td>{{$item->title}}</td>
        <td>{{$item->price}}</td>
        <td>{{$item->reward1}}</td>
        <td>{{$item->reward2}}</td>
        <td>{{$item->store}}</td>
        <td>{{$item->category->title}}</td>
        <td>{{\App\Model\Good::$TYPE[$item->type]}}</td>
        <td>{{\App\Model\Good::$STATUS_TEXT[$item->status]}}</td>
        <td>{{$item->created_at}}</td>
        <td>
            <a href="{{url('manage/goodsDel',[$item->id])}}" class="btn btn-white btn-sm layer-get" title="是否删除?"><i class="fa fa-close"></i> 删除 </a>
            <a href="{{url('manage/goodsEdit',[$item->id])}}" class="btn btn-white btn-sm"><i class="fa fa-pencil"></i> 编辑 </a>
        </td>
    </tr>
@endforeach
