<!DOCTYPE html>
<html>
<head>
    @include('layouts.admin.header')
    @include('layouts.admin.dialog')
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-6">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <form class="form-horizontal m-t" id="wxSetForm" method="post" action="{{url('manage/evaluteTagAdd')}}">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">标签：</label>
                            <div class="col-sm-2">
                                <input name="tag" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">标签类型：</label>
                            <div class="col-sm-2">
                                <select class="form-control m-b" name="type">
                                    @foreach(\App\Model\Evaluate::$TYPE as $k=>$v)
                                        <option value="{{$k}}">{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
