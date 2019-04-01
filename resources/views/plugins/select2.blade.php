<link href="/admin/js/plugins/select2/css/select2.css" rel="stylesheet">
<script src="/admin/js/plugins/select2/js/select2.full.js"></script>
<script src="/admin/js/plugins/select2/js/i18n/zh-CN.js"></script>
<script>
    $(function(){
        $('.select2_div').each(function(){
            var url = $(this).attr('href');
            var pageSize = $(this).attr('pageSize')?$(this).attr('pageSize'):20;
            var name = $(this).attr('nameText')?$(this).attr('nameText'):'name';
            var placeholder = $(this).attr('placeholder')?$(this).attr('placeholder'):'请选择';
            $(this).select2({
                ajax: {
                    url: url,
                    dataType: 'json',
                    data: function (params) {
                        return {
                            search: params.term,
                            pageNumber: params.page || 1,
                            pageSize: pageSize
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
                    return eval('repo.'+name);
                },
                templateSelection: function (repo) {
                    if (!repo.id) {
                        return repo.text;
                    }
                    return eval('repo.'+name);
                },
                placeholder: placeholder
            });
        });
        $('.select2_div_selected').each(function(){
            var url = $(this).attr('href');
            var pageSize = $(this).attr('pageSize')?$(this).attr('pageSize'):20;
            var name = $(this).attr('nameText')?$(this).attr('nameText'):'name';
            var nameSelect = $(this).attr('nameSelect')?$(this).attr('nameSelect'):name;
            var placeholder = $(this).attr('placeholder')?$(this).attr('placeholder'):'请选择';
            $(this).select2({
                ajax: {
                    url: url,
                    dataType: 'json',
                    data: function (params) {
                        return {
                            search: params.term,
                            pageNumber: params.page || 1,
                            pageSize: pageSize
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
                    return eval('repo.'+name);
                },
                templateSelection: function (repo) {
                    if (repo.selected) {
                        return repo.text?repo.text:eval('repo.'+name);
                    }
                    return eval('repo.'+name)?eval('repo.'+name):repo.text;
                },
                placeholder: placeholder
            });
        })
    })
</script>