@extends('layouts.admin.base')

@section('content')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
               分佣管理
                <small>信用卡分佣配置</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" method="POST" action="{{route('profit.card.store')}}">
                        @csrf
                        <div class="nav-tabs-custom">

                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">
                                    {{--<div class="form-group">--}}
                                        {{--<label class="col-sm-2 control-label">VIP开通费</label>--}}
                                        {{--<div class="col-sm-9">--}}
                                            {{--<div class="input-group">--}}
                                                {{--<input class="form-control" type="number" step="0.1" name="openFee" value="{{$config->openFee}}" required>--}}


                                            {{--</div>--}}
                                            {{--<div class="row">--}}
                                                {{--<div class="col-md-4">--}}
                                                    {{--<div class="input-group">--}}
                                                        {{--<span class="input-group-addon">月度</span>--}}
                                                        {{--<input type="number" min="0" step="0.1" name="openFee" value="{{$config->openFee}}" class="form-control" placeholder="请输入0~100" required>--}}
                                                    {{--</div>--}}

                                                {{--</div>--}}
                                                {{--<div class="col-md-4">--}}
                                                    {{--<div class="input-group">--}}
                                                        {{--<span class="input-group-addon">季度</span>--}}
                                                        {{--<input type="number" min="0" step="0.1"  value="{{$config->quarterFee}}" name="quarterFee" class="form-control" placeholder="请输入0~100" required>--}}

                                                    {{--</div>--}}
                                                {{--</div>--}}
                                                {{--<div class="col-md-4">--}}
                                                    {{--<div class="input-group">--}}
                                                        {{--<span class="input-group-addon">年度</span>--}}
                                                        {{--<input type="number" min="0" step="0.1" value="{{$config->yearFee}}"  name="yearFee" class="form-control" placeholder="请输入0~100" required>--}}

                                                    {{--</div>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">全平台扣减的佣金比例</label>
                                        <div class="col-sm-3">
                                            <div class="input-group">
                                            <input class="form-control" type="number" min="0" max="100" step="0.1" name="del_ratio" value="{{$config->del_ratio*100}}" placeholder="请输入0~100" required>
                                                <div class="input-group-addon">
                                                    %
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">自购返佣比例</label>
                                        <div class="col-sm-9">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">高级会员</span>
                                                        <input type="number" min="0" max="100" step="0.1" name="buyer_self_ratio" value="{{$config->buyer_self_ratio*100}}" class="form-control" placeholder="请输入0~100" required>
                                                        <div class="input-group-addon">
                                                            %
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">推广顾问</span>
                                                        <input type="number" min="0" max="100" step="0.1"  value="{{$config->vip_self_ratio*100}}" name="vip_self_ratio" class="form-control" placeholder="请输入0~100" required>
                                                        <div class="input-group-addon">
                                                            %
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">超级合作人</span>
                                                        <input type="number" min="0" max="100" step="0.1" value="{{$config->partner_self_ratio*100}}"  name="partner_self_ratio" class="form-control" placeholder="请输入0~100" required>
                                                        <div class="input-group-addon">
                                                            %
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">分享返佣比例</label>
                                        <div class="col-sm-9">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">高级会员</span>
                                                        <input type="number" min="0" max="100" step="0.1" value="{{$config->buyer_one_ratio*100}}"  class="form-control" name="buyer_one_ratio" placeholder="请输入0~100" required>
                                                        <div class="input-group-addon">
                                                            %
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">推广顾问</span>
                                                        <input type="number" min="0" max="100" step="0.1" value="{{$config->vip_one_ratio*100}}" class="form-control" name="vip_one_ratio" placeholder="请输入0~100" required>
                                                        <div class="input-group-addon">
                                                            %
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">超级合作人</span>
                                                        <input type="number" min="0" max="100" step="0.1" value="{{$config->partner_one_ratio*100}}" class="form-control" name="partner_one_ratio" placeholder="请输入0~100" required>
                                                        <div class="input-group-addon">
                                                            %
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">团队返佣比例</label>
                                        <div class="col-sm-9">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">高级会员</span>
                                                        <input type="number" min="0" max="100" step="0.1" value="{{$config->buyer_two_ratio*100}}"  class="form-control" name="buyer_two_ratio" placeholder="请输入0~100" required>
                                                        <div class="input-group-addon">
                                                            %
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">推广顾问</span>
                                                        <input type="number" min="0" max="100" step="0.1" value="{{$config->vip_two_ratio*100}}" name="vip_two_ratio" class="form-control" placeholder="请输入0~100" required>
                                                        <div class="input-group-addon">
                                                            %
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">超级合作人</span>
                                                        <input type="number" min="0" max="100" step="0.1" value="{{$config->partner_two_ratio*100}}"  name="partner_two_ratio" class="form-control" placeholder="请输入0~100" required>
                                                        <div class="input-group-addon">
                                                            %
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{--<div class="form-group">--}}
                                        {{--<label class="col-sm-2 control-label">管理津贴比例</label>--}}
                                        {{--<div class="col-sm-9">--}}
                                            {{--<div class="row">--}}
                                                {{--<div class="col-md-4">--}}
                                                    {{--<div class="input-group">--}}
                                                        {{--<span class="input-group-addon">买手</span>--}}
                                                        {{--<input type="text" class="form-control" placeholder="">--}}
                                                    {{--</div>--}}

                                                {{--</div>--}}
                                                {{--<div class="col-md-4">--}}
                                                    {{--<div class="input-group">--}}
                                                        {{--<span class="input-group-addon">VIP第一市场</span>--}}
                                                        {{--<input type="number" min="0" max="100" step="0.1" value="{{$config->vip_allowance_one*100}}" name="vip_allowance_one" class="form-control" placeholder="请输入0~100" required>--}}
                                                        {{--<div class="input-group-addon">--}}
                                                            {{--%--}}
                                                        {{--</div>--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                                {{--<div class="col-md-4">--}}
                                                    {{--<div class="input-group">--}}
                                                        {{--<span class="input-group-addon">超级VIP第一市场</span>--}}
                                                        {{--<input type="number" min="0" max="100" step="0.1" value="{{$config->parther_allowance_one*100}}" name="parther_allowance_one" class="form-control" placeholder="请输入0~100" required>--}}
                                                        {{--<div class="input-group-addon">--}}
                                                            {{--%--}}
                                                        {{--</div>--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label class="col-sm-2 control-label"></label>--}}
                                        {{--<div class="col-sm-9">--}}
                                            {{--<div class="row">--}}
                                                {{--<div class="col-md-4">--}}
                                                    {{--<div class="input-group">--}}

                                                    {{--</div>--}}

                                                {{--</div>--}}
                                                {{--<div class="col-md-4">--}}
                                                    {{--<div class="input-group">--}}
                                                        {{--<span class="input-group-addon">VIP第二市场</span>--}}
                                                        {{--<input type="number" min="0" max="100" step="0.1" value="{{$config->vip_allowance_two*100}}"  name="vip_allowance_two" class="form-control" placeholder="请输入0~100" required>--}}
                                                        {{--<div class="input-group-addon">--}}
                                                            {{--%--}}
                                                        {{--</div>--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                                {{--<div class="col-md-4">--}}
                                                    {{--<div class="input-group">--}}
                                                        {{--<span class="input-group-addon">超级VIP第二市场</span>--}}
                                                        {{--<input type="number" min="0" max="100" step="0.1" value="{{$config->parther_allowance_two*100}}"  name="parther_allowance_two" class="form-control" placeholder="请输入0~100" required>--}}
                                                        {{--<div class="input-group-addon">--}}
                                                            {{--%--}}
                                                        {{--</div>--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label class="col-sm-2 control-label"></label>--}}
                                        {{--<div class="col-sm-9">--}}
                                            {{--<div class="row">--}}
                                                {{--<div class="col-md-4">--}}
                                                {{--<div class="input-group">--}}

                                                {{--</div>--}}

                                                {{--</div>--}}
                                                {{--<div class="col-md-4">--}}
                                                    {{--<div class="input-group">--}}

                                                    {{--</div>--}}
                                                {{--</div>--}}
                                                {{--<div class="col-md-4">--}}
                                                    {{--<div class="input-group">--}}
                                                        {{--<span class="input-group-addon">超级VIP第三市场</span>--}}
                                                        {{--<input type="number" min="0" max="100" step="0.1" value="{{$config->parther_allowance_third*100}}"  name="parther_allowance_third" class="form-control" placeholder="请输入0~100" required>--}}
                                                        {{--<div class="input-group-addon">--}}
                                                            {{--%--}}
                                                        {{--</div>--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}


                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-7">
                                    <div class="btn-group pull-left">
                                        <button type="submit" class="btn btn-info pull-right submits"
                                                data-loading-text="<i class='fa fa-spinner fa-spin '></i> 提交">提交
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>


@endsection