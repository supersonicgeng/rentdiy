@include('layouts.admin.header')
@include('plugins.upload')
@include('plugins.datetimepicker')
<style>
    .form-group {
        height: 50px;
    }

    .my-common {
        text-align: right;
    }
</style>
<div class="container">
    <form id="edit_form" method="post" action="{{url('manage/member/userDetail',[$id])}}">
        <div class="col-md-10">
            @foreach($p_store as $v)
                <div class="form-group flex-align-items-center">
                    <label class="col-sm-3 control-label my-common">{{\App\Model\Good::$TYPE[$v->type]}}：</label>
                    <div class="col-sm-9">
                        <input name="filed[{{$v->id}}]" class="form-control" type="text" value="{{$v->qty}}">
                    </div>
                </div>
            @endforeach
        </div>
    </form>
</div>