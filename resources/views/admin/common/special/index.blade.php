@extends('layouts.admin.model')

@section('content')




        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border" style="height:51px;">

                            {{--<div class="search-form-inline form-inline pull-left" style="margin-left:10px;">--}}
                            {{--<form>--}}
                            {{--<div class="input-daterange input-group input-group-sm">--}}
                            {{--<input class="form-control" name="_time_from" value="" placeholder="输入商品ID搜索" type="text">--}}
                            {{--</div>--}}
                            {{--<div class="input-daterange input-group input-group-sm">--}}
                            {{--<input class="form-control"  name="_time_from" value="" placeholder="输入商品名称搜索" type="text">--}}
                            {{--</div>--}}
                            {{--<input id="_filter_time" name="_filter_time" value="create_time" type="hidden">--}}
                            {{--<button type="submit" class="btn btn-default btn-sm">确定</button>--}}
                            {{--</form>--}}
                            {{--</div>--}}

                        </div>


                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <th>标题</th>
                                    <th>指定商品数量</th>
                                    <th>是否关联分类</th>
                                    <th>关联的分类</th>
                                    <th>指定商品是否分佣</th>
                                    <th>价格区间</th>

                                </tr>
                                @foreach($specials as $s)
                                    <tr>
                                        <td><input type="radio" class="checked_id" name="checked_id"
                                                   value="{{$s->id}}"></td>
                                        <td>{{$s->id}}</td>
                                        <td>{{$s->title}}</td>
                                        <td>{{count(explode(',',$s->goods))}}</td>
                                        <td>
                                            @if($s->is_and ==1 or $s->is_and ==2)
                                                <a href="javasript:;" class="fa fa-check-circle text-green"></a>
                                           @else
                                              <a href="javasript:;" class="fa fa-times-circle text-red"></a>

                                            @endif
                                        </td>
                                        <td>
                                            @if($s->is_and ==1 or $s->is_and ==2)
                                              {{implode(',',$s->cate_names->toArray())}}
                                            @endif
                                        </td>
                                        <td>{!! is_something('is_fy',$s) !!}</td>
                                        <td>{{$s->price_min}} - {{$s->price_max}}</td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pull-right">
                                <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                                    <form>
                                        <div class="input-daterange input-group input-group-sm">
                                            共{{$specials->total()}}条&nbsp
                                        </div>
                                        <div class="input-daterange input-group input-group-sm">
                                            {{$specials->appends(Request::all())->links()}}
                                        </div>

                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="btn-group pull-right">
                <button type="button" class="btn btn-info pull-right sub">确定</button>
            </div>
        </section>
        <!-- /.content -->



@endsection
@section('js')
    <script>
        $('.sub').click(function () {

            var id = $('input[name=checked_id]:checked').val();

            window.parent.$('#special_id').val(id);

            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
            parent.layer.close(index); //再执行关闭
        })
    </script>
@endsection