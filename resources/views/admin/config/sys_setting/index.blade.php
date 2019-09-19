@extends('layouts.admin.base')

@section('content')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Setting
                <small> Param Setting</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" method="POST" action="{{route('config.sysSetting.store')}}">
                        @csrf
                        <div class="nav-tabs-custom">

                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">


                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Rates</label>
                                        <div class="col-sm-9">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">整租服务费率</span>
                                                        <input type="number" min="0" max="100" step="1" data-id="RSF"
                                                               name="RSF"
                                                               value="{{$res["RSF"]*100}}"
                                                               class="form-control changeValue"
                                                               placeholder="Please Input 0~100" required>
                                                        <div class="input-group-addon">
                                                            %
                                                        </div>
                                                    </div>

                                                </div>


                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">分租服务费率</span>
                                                        <input type="number" min="0" max="100" step="1" data-id="BSF"
                                                               name="BSF"
                                                               value="{{$res["BSF"]*100}}"
                                                               class="form-control changeValue"
                                                               placeholder="Please Input 0~100" required>
                                                        <div class="input-group-addon">
                                                            %
                                                        </div>
                                                    </div>

                                                </div>


                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">室友服务费率</span>
                                                        <input type="number" min="0" max="100" step="1" data-id="FSF"
                                                               name="FSF"
                                                               value="{{$res["FSF"]*100}}"
                                                               class="form-control changeValue"
                                                               placeholder="Please Input 0~100" required>
                                                        <div class="input-group-addon">
                                                            %
                                                        </div>
                                                    </div>

                                                </div>


                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">商业服务费率</span>
                                                        <input type="number" min="0" max="100" step="1" data-id="CSF"
                                                               name="CSF"
                                                               value="{{$res["CSF"]*100}}"
                                                               class="form-control changeValue"
                                                               placeholder="Please Input 0~100" required>
                                                        <div class="input-group-addon">
                                                            %
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Providers Look</span>
                                                        <input type="number" min="0" max="100" step="1" data-id="PSFL"
                                                               name="PSFL"
                                                               value="{{$res["PSFL"]*100}}"
                                                               class="form-control changeValue"
                                                               placeholder="Please Input 0~100" required>
                                                        <div class="input-group-addon">
                                                            %
                                                        </div>
                                                    </div>

                                                </div>


                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Providers Research</span>
                                                        <input type="number" min="0" max="100" step="1" data-id="PSFR"
                                                               name="PSFR"
                                                               value="{{$res["PSFR"]*100}}"
                                                               class="form-control changeValue"
                                                               placeholder="Please Input 0~100" required>
                                                        <div class="input-group-addon">
                                                            %
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Providers Inspect</span>
                                                        <input type="number" min="0" max="100" step="1" data-id="PSFI"
                                                               name="PSFI"
                                                               value="{{$res["PSFI"]*100}}"
                                                               class="form-control changeValue"
                                                               placeholder="Please Input 0~100" required>
                                                        <div class="input-group-addon">
                                                            %
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Providers Maintain</span>
                                                        <input type="number" min="0" max="100" step="1" data-id="PSFM"
                                                               name="PSFM"
                                                               value="{{$res["PSFM"]*100}}"
                                                               class="form-control changeValue"
                                                               placeholder="Please Input 0~100" required>
                                                        <div class="input-group-addon">
                                                            %
                                                        </div>
                                                    </div>

                                                </div>


                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Providers Litigation</span>
                                                        <input type="number" min="0" max="100" step="1" data-id="PSFLI"
                                                               name="PSFLI"
                                                               value="{{$res["PSFLI"]*100}}"
                                                               class="form-control changeValue"
                                                               placeholder="Please Input 0~100" required>
                                                        <div class="input-group-addon">
                                                            %
                                                        </div>
                                                    </div>

                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Amount</label>
                                        <div class="col-sm-9">
                                            <div class="row">


                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">整租VIP原价 </span>
                                                        <input type="number" min="0" max="100000000" step="0.1"
                                                               data-id="RF"
                                                               value="{{$res["RF"]}}"
                                                               class="form-control changeValue" name="RF"
                                                               placeholder="Please Input Amount" required>
                                                        <div class="input-group-addon">
                                                            $
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">整租VIP实际价格</span>
                                                        <input type="number" min="0" max="100000000" step="0.1"
                                                               data-id="RVF"
                                                               value="{{$res["RVF"]}}"
                                                               class="form-control changeValue" name="RVF"
                                                               placeholder="Please Input Amount" required>
                                                        <div class="input-group-addon">
                                                            $
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">整租VIP充值赠送金额</span>
                                                        <input type="number" min="0" max="100000000" step="0.1"
                                                               data-id="RFB"
                                                               value="{{$res["RFB"]}}"
                                                               class="form-control changeValue" name="RFB"
                                                               placeholder="Please Input Amount" required>
                                                        <div class="input-group-addon">
                                                            $
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">分租VIP原价 </span>
                                                        <input type="number" min="0" max="100000000" step="0.1"
                                                               data-id="BF"
                                                               value="{{$res["BF"]}}"
                                                               class="form-control changeValue" name="BF"
                                                               placeholder="Please Input Amount" required>
                                                        <div class="input-group-addon">
                                                            $
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">分租VIP实际价格</span>
                                                        <input type="number" min="0" max="100000000" step="0.1"
                                                               data-id="BVF"
                                                               value="{{$res["BVF"]}}"
                                                               class="form-control changeValue" name="BVF"
                                                               placeholder="Please Input Amount" required>
                                                        <div class="input-group-addon">
                                                            $
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">分租VIP充值赠送余额</span>
                                                        <input type="number" min="0" max="100000000" step="0.1"
                                                               data-id="BFB"
                                                               value="{{$res["BFB"]}}"
                                                               class="form-control changeValue" name="BFB"
                                                               placeholder="Please Input Amount" required>
                                                        <div class="input-group-addon">
                                                            $
                                                        </div>
                                                    </div>

                                                </div>


                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">室友VIP原价 </span>
                                                        <input type="number" min="0" max="100000000" step="0.1"
                                                               data-id="FF"
                                                               value="{{$res["FF"]}}"
                                                               class="form-control changeValue" name="FF"
                                                               placeholder="Please Input Amount" required>
                                                        <div class="input-group-addon">
                                                            $
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">室友VIP实际充值金额 </span>
                                                        <input type="number" min="0" max="100000000" step="0.1"
                                                               data-id="FVF"
                                                               value="{{$res["FVF"]}}"
                                                               class="form-control changeValue" name="FVF"
                                                               placeholder="Please Input Amount" required>
                                                        <div class="input-group-addon">
                                                            $
                                                        </div>
                                                    </div>

                                                </div>


                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">室友VIP充值赠送金额 </span>
                                                        <input type="number" min="0" max="100000000" step="0.1"
                                                               data-id="FFB"
                                                               value="{{$res["FFB"]}}"
                                                               class="form-control changeValue" name="FFB"
                                                               placeholder="Please Input Amount" required>
                                                        <div class="input-group-addon">
                                                            $
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"> 商业VIP原价 </span>
                                                        <input type="number" min="0" max="100000000" step="0.1"
                                                               data-id="CF"
                                                               value="{{$res["CF"]}}"
                                                               class="form-control changeValue" name="CF"
                                                               placeholder="Please Input Amount" required>
                                                        <div class="input-group-addon">
                                                            $
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"> 商业VIP实际充值金额 </span>
                                                        <input type="number" min="0" max="100000000" step="0.1"
                                                               data-id="CVF"
                                                               value="{{$res["CVF"]}}"
                                                               class="form-control changeValue" name="CVF"
                                                               placeholder="Please Input Amount" required>
                                                        <div class="input-group-addon">
                                                            $
                                                        </div>
                                                    </div>

                                                </div>


                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"> 商业VIP充值赠送金额 </span>
                                                        <input type="number" min="0" max="100000000" step="0.1"
                                                               data-id="CFB"
                                                               value="{{$res["CFB"]}}"
                                                               class="form-control changeValue" name="CFB"
                                                               placeholder="Please Input Amount" required>
                                                        <div class="input-group-addon">
                                                            $
                                                        </div>
                                                    </div>

                                                </div>


                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">新用户赠送金额(抵扣)</span>
                                                        <input type="number" min="0" max="100000000" step="0.1"
                                                               data-id="NUB"
                                                               value="{{$res["NUB"]}}"
                                                               class="form-control changeValue" name="NUB"
                                                               placeholder="Please Input Amount" required>
                                                        <div class="input-group-addon">
                                                            $
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">短信发送费用 </span>
                                                        <input type="number" min="0" max="100000000" step="0.1"
                                                               data-id="SMF"
                                                               value="{{$res["SMF"]}}"
                                                               class="form-control changeValue" name="SMF"
                                                               placeholder="Please Input Amount" required>
                                                        <div class="input-group-addon">
                                                            $
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">代发邮件价格 </span>
                                                        <input type="number" min="0" max="100000000" step="0.1"
                                                               data-id="PMF"
                                                               value="{{$res["PMF"]}}"
                                                               class="form-control changeValue" name="PMF"
                                                               placeholder="Please Input Amount" required>
                                                        <div class="input-group-addon">
                                                            $
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">最低余额</span>
                                                        <input type="number" min="0" max="100000000" step="0.1"
                                                               data-id="LB"
                                                               value="{{$res["LB"]}}"
                                                               class="form-control changeValue" name="LB"
                                                               placeholder="Please Input Amount" required>
                                                        <div class="input-group-addon">
                                                            $
                                                        </div>
                                                    </div>

                                                </div>


                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">房屋检查频率</span>
                                                        <input type="number" min="2" max="90" step="1" data-id="HCR"
                                                               value="{{$res["HCR"]}}"
                                                               class="form-control changeValue" name="HCR"
                                                               placeholder="Please Input Amount" required>
                                                        <div class="input-group-addon">
                                                            Day
                                                        </div>
                                                    </div>

                                                </div>


                                            </div>
                                        </div>
                                    </div>


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
@section('js')


    <script>
        $(function () {
            //ajax
            $('.changeValue').change(function () {

                var value = $(this).val();
                var code = $(this).data('id');

                $.ajax({
                    type: 'PATCH',
                    data: {value: value, code: code},
                    url: '{{route('config.sysSetting.change_value')}}',
                    success: function (data) {
                        if (data.status == 1) {
                            toastr.success(data.msg);
                        } else {
                            toastr.error(data.msg);
                            return false;
                        }
                        setTimeout(function () {
                            window.location.reload();//页面刷新
                        }, 150);
                    }
                })
            })

        })

    </script>
@endsection