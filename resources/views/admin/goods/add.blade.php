<!DOCTYPE html>
<html>
<head>
    @include('layouts.admin.header')
    @include('plugins.validate')
    @include('plugins.upload')
    @include('plugins.ueditor')
    @include('plugins.icheck')
    <style>
        #detail{
            width: 400px;
            height: 300px;
        }
        .img_box{
            float: left;
            margin-left: 10px;
            height:200px;
            position: relative;
        }
        .img_box img{
            height:200px;
        }
        .del{
            position: absolute;
            left: 100%;
            top: 0;
            margin-left: -35px;
            margin-top: 10px;
        }
        .spec_box{
            width: 100%;
            height: 50px;
        }
        .spec_box select{
            width: 150px;
            float: left;
            margin-left: 10px;
        }
        .spec_box select:first-child{
            margin-left: 0;
        }
        .spec_value_box{
            width: 100%;
        }
        .spec_value_item{
            width: 100%;
            height: 40px;
        }
        table{
            border: 1px solid #999;
        }
        th,td{
            text-align: center;
            padding: 5px 10px;
        }
        .help-block{
            margin-top: 0 !important;
        }
        .gift_group{
            display: none;
        }
    </style>
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <form class="form-horizontal m-t" id="wxSetForm" method="post" action="{{url('manage/goodsAdd')}}">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">商品名称：</label>
                            <div class="col-sm-2">
                                <input name="title" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">所属分类：</label>
                            <div class="col-sm-2">
                                <select name="category_id" class="form-control">
                                    @foreach($categorys as $category)
                                        <option value="{{$category->id}}">{{$category->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">图片(375X200)：</label>
                            <div class="col-sm-8">
                                <div id="filePicker"></div>
                                <div id="preview">
                                    {{--<div class="img_box">
                                        <img src="http://store.com/storage/goods_brand/2018-03-14/011a9c8c25c3e1d6d0a468de60dcf728.jpg">
                                        <a href="javascript:;" class="btn btn-white btn-sm del"><i class="fa fa-close"></i></a>
                                    </div>--}}
                                </div>
                                <div id="album_input">
                                    {{--<input name="album[]" class="form-control album_input" type="hidden">--}}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">价格(元/年)：</label>
                            <div class="col-sm-2">
                                <input name="price" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">直接佣金：</label>
                            <div class="col-sm-2">
                                <input name="reward1" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">间接佣金：</label>
                            <div class="col-sm-2">
                                <input name="reward2" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">库存：</label>
                            <div class="col-sm-2">
                                <input name="store" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">详情描述：</label>
                            <div class="col-sm-9">
                                <textarea name="detail" id="detail"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">关联系统：</label>
                            <div class="col-sm-2">
                                <select name="type" class="form-control relation_type">
                                    @foreach(\App\Model\Good::$TYPE as $k=>$v)
                                        <option value="{{$k}}">{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group gift_group">
                            <label class="col-sm-3 control-label">赠品：</label>
                            <div class="col-sm-6">
                                <table class="gift_form">
                                    <tr><th>系统</th><th>配套服务</th></tr>
                                    @foreach(\App\Model\Good::$TYPE as $k=>$v)
                                        @if($k>1)
                                            <tr>
                                                <td>
                                                    <label>
                                                        <input type="hidden" name="gift[]" value="{{$k}}"/>
                                                        {{$v}}
                                                    </label>

                                                </td>
                                                <td>
                                                    <input type="text" name="gift_number[]" class="form-control" style="width: 150px;" value="0"/>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </table>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">状态：</label>
                            <div class="col-sm-8">
                                @foreach(\App\Model\Good::$STATUS_TEXT as $k1=>$v1)
                                    <label class="checkbox-inline i-checks"><input type="radio" name="status" value="{{$k1}}" @if(\App\Model\Good::$STATUS_ON == $k1) checked @endif />{{$v1}}</label>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-3">
                                <button class="btn btn-primary" type="submit">提交</button>
                            </div>
                        </div>
                    </form>
                    <script>
                        var ue = UE.getEditor('detail',{
                            textarea:'detail',
                            initialFrameWidth:600,
                            initialFrameHeight:200,
                            autoHeightEnabled:false,
                            elementPathEnabled:false,
                            wordCount:false
                        });
                        $.validator.setDefaults({
                            highlight: function (e) {
                                $(e).closest(".form-group").removeClass("has-success").addClass("has-error")
                            },
                            success: function (e) {
                                e.closest(".form-group").removeClass("has-error").addClass("has-success")
                            },
                            errorElement: "span",
                            errorPlacement: function (e, r) {
                                e.appendTo(r.is(":radio") || r.is(":checkbox") ? r.parent().parent().parent() : r.parent())
                            },
                            submitHandler: function () {
                                $("#wxSetForm").ajaxSubmit({
                                    beforeSubmit: beforeAjax,
                                    dataType: "json",
                                    success: ajaxCallback,
                                    error: errorCallback
                                });

                            },
                            errorClass: "help-block m-b-none",
                            validClass: "help-block m-b-none"
                        });
                        $().ready(function () {
                            var e = "<i class='fa fa-times-circle'></i> ";
                            $("#wxSetForm").validate({
                                rules: {
                                    title: "required",
                                    price:{
                                        required: true,
                                        decimal:true
                                    },
                                    reward1:{
                                        required: true,
                                        decimal:true
                                    },
                                    reward2:{
                                        required: true,
                                        decimal:true
                                    },
                                    store:{
                                        required: true,
                                        digits:true,
                                        min:0
                                    }

                                },
                                messages: {
                                    title: e + "请输入商品名称",
                                    price:{
                                        required:e + "请输入价格"
                                    },
                                    reward1:{
                                        required:e + "请输入直接佣金"
                                    },
                                    reward2:{
                                        required:e + "请输入间接佣金"
                                    },
                                    store:{
                                        required:e + "请输入库存",
                                        digits:e+'库存必须为数字',
                                        min:e+'库存不能小于0',
                                    }
                                }
                            })
                        });
                        var file_index = 0;
                        $("#filePicker").imgUpload('点击上传', 'goods_album', function (file, response) {
                            file_index++;
                            $("#preview").append('<div class="img_box" findex="'+file_index+'"><img src="' + storagePath + '/' + response.img + '"> <a href="javascript:;" class="btn btn-white btn-sm del"><i class="fa fa-close"></i></a></div>');
                            $("#album_input").append('<input name="album[]" class="form-control album_input" findex="'+file_index+'" type="hidden" value="'+response.img+'">');
                        });
                        $(document).delegate('.del','click',function(){
                            var findex = $(this).parent().attr('findex');
                            $("[findex="+findex+"]").remove();
                        });
                        $(document).delegate('.relation_type','change',function(){
                            console.log($(this).val());
                            if($(this).val() == "{{\App\Model\Good::$VIP_TYPE}}"){
                                $(".gift_group").show();
                            }else{
                                $(".gift_group").hide();
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
