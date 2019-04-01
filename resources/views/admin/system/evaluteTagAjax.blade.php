@foreach($list as $item)
    <tr>
        <td>{{$item->id}}</td>
        <td>{{$item->tag}}</td>
        <td>{{\App\Model\Evaluate::$TYPE[$item->type]}}</td>
        <td>
            <a href="{{url('manage/evaluteTagDel',[$item->id])}}" class="btn btn-white btn-sm layer-get" title="确定删除？"><i class="fa fa-close"></i> 删除 </a>
        </td>
    </tr>
@endforeach