@foreach($list as $item)
    <tr>
        <td>{{$item->id}}</td>
        <td>{{$item->name}}</td>
        <td>{{$item->code}}</td>
    </tr>
@endforeach