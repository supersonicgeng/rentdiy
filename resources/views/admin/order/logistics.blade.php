<style>
    .result-info {
        width: 1180px;
    }

    .result-info .last td, .result-info .last td a {
        color: #ff7800;
    }

    .result-info .row1 {
        width: 170px;
        text-align: center;
        padding-left: 14px;
        padding-right: 0;
    }

    .result-info td {
        padding: 7px;
        color: #828282;
    }

    .result-info .status {
        width: 30px;
        background: url("/admin/img/spider_search_v4.png") 16px -772px no-repeat;
    }

    .result-info .status-check {
        background: url("/admin/img/spider_search_v4.png") 13px -726px no-repeat;
    }

    .result-info .status-first {
        background: url("/admin/img/spider_search_v4.png") 16px -813px no-repeat;
    }

    .status .col2 {
        position: relative;
        z-index: -1;
    }

    .status .line1 {
        position: absolute;
        left: 6.4px;
        width: 0.57em;
        height: 2em;
        border-right: 1px solid #d2d2d2;
        top: -5px;
        z-index: -1;
    }
    .title{
        padding-left: 20px;
        height: 50px;
        line-height: 50px;
        font-size: 18px;
        color: #666666;
    }
</style>
<div class="title">快递公司：{{$logisticType->ShipperName}}</div>
<table class="result-info" cellspacing="0">

    <tbody>
    @foreach($traces as $k=>$v)
        @if($k == 0)
            <tr>
                <td class="row1">{{$v->AcceptTime}}</td>
                <td class="status status-first">&nbsp;</td>
                <td class="context">{{$v->AcceptStation}}</td>
            </tr>
        @elseif($k == (count($traces) -1))
            <tr class="last">
                <td class="row1">{{$v->AcceptTime}}</td>
                <td class="status status-check">&nbsp;
                    <div class="col2"><span class="step"><span class="line1"></span><span class="line2"></span></span></div>
                </td>
                <td class="contex">{{$v->AcceptStation}}</td>
            </tr>
        @else
            <tr>
                <td class="row1">{{$v->AcceptTime}}</td>
                <td class="status">&nbsp;
                    <div class="col2"><span class="step"><span class="line1"></span><span class="line2"></span></span></div>
                </td>
                <td class="context">{{$v->AcceptStation}}</td>
            </tr>
        @endif
    @endforeach
    </tbody>
</table>