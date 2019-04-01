@include('layouts.admin.header')
@include('plugins.upload')
@include('plugins.datetimepicker')
@section('header')
<style>
    td {
        text-align: center;
    }

    th {
        text-align: center;
    }

    .zoomify-shadow {
        background: rgba(0, 0, 0, 0);
    }
</style>
@endsection
<div class="container">
    <table class="col-md-10 table  table-striped table-bordered" style="text-align: center">
        <tr>
            <th style="text-align: center">游戏ID</th>
            <th style="text-align: center">用户昵称</th>
            <th style="text-align: center">当前局分</th>
            <th style="text-align: center">个人最高分</th>
            <th style="text-align: center">累计得分</th>
            <th style="text-align: center">分享得分</th>
            <th style="text-align: center">总得分</th>
        </tr>
        @foreach($gameInfo as $v)
        <tr>
            <td>{{$v->id}}</td>
            <td>{{$nickname->nickname}}</td>
            <td>{{$v->current_score}}</td>
            <td>{{$v->best_score}}</td>
            <td>{{$v->add_up_score}}</td>
            <td>{{$v->recommend_score}}</td>
            <td>{{$v->total_score}}</td>
        </tr>
        @endforeach
    </table>

</div>
