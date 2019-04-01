@foreach($sharedInfo as $k => $share)
    <li class="rankingList">
        <div class="flex-justify-between flex-items-center marginTop">
            <div>
                <p><img class="imgType1" src="{{$share->headimgurl}}" alt=""></p>
                <span class="nickname">{{$share->nickname}}</span>
            </div>
            <div class="flex-items-center">
                <img class="imgType2" src="/game/image/integralRanking/21.png" alt="">
                <span>{{$share->shared_score}}</span>
            </div>
            <div>
                @if($pageNumber == 1)
                    <img class="imgType5" src="/game/image/integralRanking/2{{$k+4}}.png" alt="">
                @else
                    <img class="imgType5 opacity" src="/game/image/integralRanking/2{{$k+4}}.png" alt="">
                @endif
            </div>
        </div>
    </li>
@endforeach