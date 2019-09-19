@extends('layouts.admin.model')

@section('content')

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border" style="height:51px;">
                        {{--<div class="pull-left">--}}
                        {{--<a class="btn btn-success btn-sm" href="{{route('shop.category.create')}}"><i class="fa fa-save"></i> 新增</a>--}}
                        {{--</div>--}}
                        <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                            <form>

                                <div class="input-daterange input-group input-group-sm">
                                    <input class="form-control" name="name" value="{{Request::input('name')}}"
                                           placeholder="输入标签名称" type="text">
                                </div>

                                <button type="submit" class="btn btn-default btn-sm">确定</button>
                            </form>
                        </div>

                    </div>


                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-hover">
                            <tbody>
                            <tr>
                                <th width="100"><input class="check_all" type="checkbox"></th>
                                <th>ID</th>
                                <th>标签名</th>
                                <th>被使用次数</th>

                            </tr>
                            @foreach($tags as $tag)
                                <tr>
                                    <td style="display: none"><input type="text" name="tag_id[]" value="{{$tag->id}}"></td>
                                    <td class="del_check"><input class="checked_id" type="checkbox" name="checked_id[]" value="{{$tag->id}}"></td>
                                    <td>{{$tag->id}}</td>
                                    <td>{{$tag->name}}</td>
                                    <td>{{$tag->be_use_time}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="pull-right">
                            <div class="search-form-inline form-inline pull-left" style="margin-left:10px;">
                                <form>
                                    <div class="input-daterange input-group input-group-sm">
                                        共{{$tags->total()}}条&nbsp
                                    </div>
                                    <div class="input-daterange input-group input-group-sm">
                                        {{$tags->appends(Request::all())->links()}}
                                    </div>

                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-group pull-right">
            <button type="button" class="btn btn-info pull-right check">确定</button>
        </div>
    </section>
    <!-- /.content -->

@endsection
