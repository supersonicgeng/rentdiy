<link href="/admin/js/plugins/select2/css/select2.css" rel="stylesheet">
<script src="/admin/js/plugins/select2/js/select2.full.js"></script>
<script src="/admin/js/plugins/select2/js/i18n/zh-CN.js"></script>
<div class="form-group">
    <label class="col-sm-3 control-label">{{$title}}：</label>
    <div class="col-sm-9">
        <select id="{{$province}}" name="{{$province}}">
            @if(!empty($default_province))
                <option value="{{$default_province}}" selected>{{\App\Model\Region::name($default_province)}}</option>
            @endif
        </select>
        <select id="{{$city}}" name="{{$city}}">
            @if(!empty($default_city))
                <option value="{{$default_city}}" selected>{{\App\Model\Region::name($default_city)}}</option>
            @endif
        </select>
        <select id="{{$county}}" name="{{$county}}">
            @if(!empty($default_county))
                <option value="{{$default_county}}" selected>{{\App\Model\Region::name($default_county)}}</option>
            @endif
        </select>
    </div>
</div>
<script>
    $('#{{$province}}').select2({
        width: '200px',
        ajax: {
            url: "{{url('api/address')}}",
            dataType: 'json',
            data: function (params) {
                return {
                    search: params.term,
                    pageNumber: params.page || 1,
                    pageSize: 30,
                    type: 1
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: data.items,
                    pagination: {
                        more: data.incomplete_results
                    }
                }
            }
        },
        templateResult: function (repo) {
            console.log(repo);
            if (repo.loading) {
                return repo.text;
            }
            return repo.name
        },
        templateSelection: function (repo) {
            if (repo.selected) {
                return repo.text?repo.text:repo.name;
            }
            $('#{{$city}}').html('');
            $('#{{$county}}').html('');
            return repo.name?repo.name:repo.text
        },
        placeholder: '请选择'
    });
    $('#{{$city}}').select2({
        width: '200px',
        ajax: {
            url: "{{url('api/address')}}",
            dataType: 'json',
            data: function (params) {
                return {
                    search: params.term,
                    pageNumber: params.page || 1,
                    pageSize: 30,
                    type: 2,
                    province: $('#province').val(),
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: data.items,
                    pagination: {
                        more: data.incomplete_results
                    }
                }
            }
        },
        templateResult: function (repo) {
            if (repo.loading) {
                return repo.text;
            }
            return repo.name
        },
        templateSelection: function (repo) {
            if (repo.selected) {
                return repo.text?repo.text:repo.name;
            }
            $('#{{$county}}').html('');
            return repo.name?repo.name:repo.text
        },
        placeholder: '请选择'
    });

    $('#{{$county}}').select2({
        width: '200px',
        ajax: {
            url: "{{url('api/address')}}",
            dataType: 'json',
            data: function (params) {
                return {
                    search: params.term,
                    pageNumber: params.page || 1,
                    pageSize: 30,
                    type: 3,
                    city: $('#city').val(),
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: data.items,
                    pagination: {
                        more: data.incomplete_results
                    }
                }
            }
        },
        templateResult: function (repo) {
            if (repo.loading) {
                return repo.text;
            }
            return repo.name
        },
        templateSelection: function (repo) {
            if (repo.selected) {
                return repo.text?repo.text:repo.name;
            }
            return repo.name?repo.name:repo.text
        },
        placeholder: '请选择'
    });
</script>