@foreach($list as $item)
    <tr>
        <td>{{$item->passport()->passport_id}}</td>
        <td>{{$item->passport()->nickname}}</td>
        <td><img class="big-img" style="width: 30px;height: 30px;" src="{{$item->passport()->headimgurl}}"></td>
        <td>{{\App\Model\Passport::$SEX_TXT[$item->passport()->sex]}}</td>
        <td>{{\App\Model\Passport::$SUBSCRIPT_TXT[$item->passport()->subscribe]}}</td>
        <td>{{$item->phone}}</td>
        <td>
            <a href="{{url('manage/noValidate/del',[$item->id])}}" class="btn btn-white btn-sm layer-get" title="确认删除？"><i class="fa fa-close"></i> 删除 </a>
        </td>
    </tr>
@endforeach
<script>
    $('.big-img').zoomify();
</script>