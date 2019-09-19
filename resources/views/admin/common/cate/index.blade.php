@extends('layouts.admin.model')

@section('css')
    <style>
        .rule_node {
            line-height: 34px;
        }

        .rule_node .left1 {
            background: #f9f9f9;
        }

        .rule_node p {
            clear: both;
            margin-bottom: 0px;
        }

        .rule_node .left2 {
            float: left;
            margin-left: 24px;
        }

        .rule_node .left3 {
            margin-left: 0px;
            clear: none;
        }

        .rule_node .p_left {
            float: left;
        }
    </style>
@endsection

@section('content')





    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal">

                    <div class="nav-tabs-custom">

                        <div class="tab-content">
                            <div>



                                <div class="form-group">
                                    <label class="col-sm-2 control-label">分类列表</label>
                                    <div class="col-sm-7 rule_node">

                                            @foreach($cates as $child)
                                                <p class="left3 p_left">

                                                    &nbsp<input type="checkbox" @if(in_array($child->id,$checked)) checked @endif class="cate_id" value="{{$child->id}}" name="cate_id[]">
                                                        &nbsp<span class="label label-info">{{$child->name}}</span>


                                                </p>
                                            @endforeach



                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-7">
                                <div class="btn-group pull-right">
                                    <button type="button" class="btn btn-info pull-left submits sub">提交
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>



@endsection

@section('js')
    <script>
        $('.sub').click(function () {
            var length = $('.cate_id:checked').length;


            if (length == 0) {
                layer.msg('请选择一个分类！', {icon: 5});
                return false;
            }

            // var a = $('.checked_id:checked').serialize();
            var id_array=new Array();

            $('.cate_id:checked').each(function(){
                id_array.push($(this).val());//向数组中添加元素
            })

            s = id_array.join(',');
            window.parent.$('#cates').html(s);

            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
            parent.layer.close(index); //再执行关闭
        })
    </script>
@endsection