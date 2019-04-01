<!DOCTYPE html>
<html>


<!-- Mirrored from www.zi-han.net/theme/hplus/table_data_tables.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:20:01 GMT -->
<head>
    @include('layouts.admin.header')
    @include('plugins.switch')
</head>
<body class="gray-bg">
<style>
    .switch{
        margin-top: 5px;
    }
</style>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <form class="form-horizontal m-t" id="wxSetForm" method="post" action="{{url('manage/signSet')}}">
            <div class="col-sm-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">

                        <div class="form-group" >
                            <label class="col-sm-3 control-label">要编辑的天数：</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" id="limit_days" value="7">
                                <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 最多28天,此处内容用于快捷生成，不会保存</span>
                            </div>
                        </div>
                        <div class="form-group order_pay_expire_content">
                            <label class="col-sm-3 control-label">快捷生成：</label>
                            <div class="col-sm-3">
                                <label>初始值</label>
                                <input class="form-control" type="text" id="num_start" value="1">
                            </div>
                            <div class="col-sm-3">
                                <label>累加数</label>
                                <input class="form-control" type="text" id="num_limit" value="1">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-3">
                                <button class="btn btn-primary" id="submit">保存</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>赠送积分设置</h5>
                    </div>
                    <div class="ibox-content accumulation">
                        @foreach($list as $vo)
                            <div class="form-group">
                                <label class="col-sm-3 control-label">第{{$vo->times}}次签到积分:</label>
                                <div class="col-sm-3">
                                    <input type="text"  class="required form-control nums" name="times[]" value="{{$vo->value}}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>签到设置简介</h5>
                </div>
                <div class="ibox-content">
                    <p>1.输入设置的天数为N，当连续签到大于等于N时，赠送积分数都等于第N天赠送的积分数；</p>
                    <p>2.依次累加设置的赠送积分数为默认值，可以再次修改。</p>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/admin/js/input-key.js"></script>
<script type="text/javascript">
    //默认选中
    $("#submit").click(function () {
        $("#wxSetForm").ajaxSubmit({
            dataType : "json",
            success : ajaxCallback
        });
        return false;
    });
    /*输入天数显示签到次数*/
    $("body").on("keyup","#limit_days,#num_limit,#num_start",function(){
        var limit_days =$("#limit_days").val()*1;
        var num_limit = $("#num_limit").val()*1;
        var num_start = $("#num_start").val()*1;
        var html ="";
        if(((limit_days>=1)&&(limit_days<=28))&&((num_limit>=0)&&(num_start>=1))){
            for(var i=1; i<=limit_days; i++){
                if(i>1){
                    num_start += num_limit;
                }
                html +='<div class="form-group"><label class="col-sm-3 control-label">第'+i+'次签到积分:</label><div class="col-sm-3">';
                html +='<input type="text"  class="required form-control nums" name="times[]" value="'+num_start+'">'
                html +='</div></div>';
            }
            $(".accumulation").html(html);
        }else{
            if((limit_days<1)||(limit_days>28)){
                $("#limit_days").val("");
                alertError("编辑的天数必须在1到28之间，请重新输入");
            }
            if(num_limit<0){
                $("#num_limit").val("");
                alertError("累加数不能小于0，请重新输入");
            }
            if(num_start<1){
                $("#num_start").val("");
                alertError("初始值不能小于1，请重新输入");
            }
        }
    });
    $(".switch_choose").on("change",function(){
    });
</script>
</body>
</html>
