@foreach($list as $item)
    <tr>
        <td>{{$item->title}}</td>
        <td>{{$item->username}}</td>
        <td>{{\App\Model\Region::name($item->province)}} {{\App\Model\Region::name($item->city)}} {{\App\Model\Region::name($item->county)}} {{$item->address}}</td>
        <td>{{$item->phone}}</td>
        <td>{{$item->group_id}}</td>
        <td><img style="width: 100px;" src="{{imgShow(@$item->pic,true)}}"/></td>
        <td>{{$item->expired_at}}</td>
        <td>{{$item->created_at}}</td>
        <td><span class="label label-{{\App\Model\Wish::$STATUS_TEXT[$item->status]['color']}}">{{\App\Model\Wish::$STATUS_TEXT[$item->status]['text']}}</span></td>
        <td>
            <a href="{{url('manage/wishDel',[$item->id])}}" class="btn btn-white btn-sm layer-get" title="是否删除?"><i class="fa fa-close"></i> 删除 </a>
            <a href="{{url('manage/wishEdit',[$item->id])}}" class="btn btn-white btn-sm"><i class="fa fa-pencil"></i> 编辑 </a>
        </td>
    </tr>
@endforeach
