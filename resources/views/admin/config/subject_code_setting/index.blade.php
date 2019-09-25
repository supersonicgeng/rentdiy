@extends('layouts.admin.base')

@section('content')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Setting
                <small> subject code Setting</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <div class="pull-left">
                                <a class="btn btn-primary btn-sm" href="{{route('config.subjectCodeSetting.create')}}"><i
                                            class="fa fa-save"></i> 新增</a>
                            </div>


                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>items</th>
                                    <th>subject code</th>

                                    <th>操作</th>
                                </tr>
                                @foreach($items as $item)
                                    <tr data-id="{{$item->id}}">
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->items}}</td>
                                        <td>
                                           {{$item->subject_code}}
                                        </td>

                                        <td>
                                            <a class="btn btn-primary btn-xs" href="{{route('config.subjectCodeSetting.edit',$item->id)}}"><i class="fa fa-edit"></i> 编辑</a>
                                            <a class="btn btn-danger btn-xs delete_genius" href="javascript:void(0);"
                                               data-url="{{route('config.subjectCodeSetting.destroy',$item->id)}}"><i class="fa fa-trash"></i> 删除</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>


@endsection
@section('js')
    <script>

    </script>
@endsection

