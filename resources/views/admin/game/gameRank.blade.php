@foreach($rank as $k => $item)
<li class="list-li flex-items-center flex-justify-between" onclick="jump('{{url('/gameInfo',['passport_id'=>$item->passport_id])}}')">
    <div class="flex-items-center ">
        <div class="num">{{($pageNumber-1)*$pageSize+4+$k}}</div>
        <img class="imgType-2" src="{{$item->headimgurl}}" alt="">
        <span class="font-1">{{$item->nickname}}@if($passport_id == $item->passport_id)<span style="font-weight: 900;color: #FFA500;">(我)</span>@endif</span>
    </div>
    <div class="flex-center font-2 flex-1">{{$item->total_score}}</div>
    <div class="flex-center">
        @if($pageNumber == 1)
        <img class="imgType-3 " src="/game/image/scoresRanking/3{{$k+4}}.png" alt="">
        @else
        <img class="imgType-3 opacity" src="/game/image/scoresRanking/3{{$k+4}}.png" alt="">
        @endif
    </div>
</li>
@endforeach