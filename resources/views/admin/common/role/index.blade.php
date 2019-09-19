@extends('layouts.admin.model')

@section('content')



    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border" style="height:51px;">
                        {{--<div class="pull-left">--}}
                        {{--<a class="btn btn-success btn-sm" href="{{route('shop.category.create')}}"><i class="fa fa-save"></i> 新增</a>--}}
                        {{--</div>--}}
                        <div class="search-form-inline form-inline pull-left">
                            <form>
                                <div class="input-daterange input-group input-group-sm">
                                    <input class="form-control" name="mname" value="{{Request::input('mname')}}"
                                           placeholder="角色名称搜索" type="text">
                                </div>
                                <button type="submit" class="btn btn-default btn-sm">搜索</button>
                            </form>
                        </div>

                    </div>


                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-hover">
                            <tbody>
                            <tr>
                                <th class="del_check"></th>
                                <th>ID</th>
                                <th>名称</th>
                                <th>头像</th>
                                <th>角色分类</th>
                                <th>标签</th>
                            </tr>
                            @foreach($matusers as $m)
                                <tr>
                                    <td class="del_check"><input type="radio" name="mid" class="checked_id"></td>
                                    <input type="hidden" name="muid" value="{{$m->mid}}">
                                    <td>{{$m->mid}}</td>
                                    <td>{{$m->mname}}</td>
                                    <td>
                                        <img src="{{$m->imageurl}}" alt="" style="height: 50px;width: 50px">
                                    </td>
                                    <td>{{$m->cat_name}}</td>
                                    <td>{{$m->tag_name}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="pull-right">
                            <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                                <form>
                                    <div class="input-daterange input-group input-group-sm">
                                        共{{$matusers->total()}}条&nbsp
                                    </div>
                                    <div class="input-daterange input-group input-group-sm">
                                        {{$matusers->appends(Request::all())->links()}}
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



@endsection

@section('js')
    <script>

//选中提交
        $('.sub').click(function () {

            var a = $('.checked_id:checked').parents('tbody').clone(true);
            $(a).find('.checked_id').not("input:checked").parents('tr').remove();
            $(a).find('.del_check').remove();

            window.parent.$('table').html(a);
            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
            parent.layer.close(index); //再执行关闭
        })
    </script>
@endsection