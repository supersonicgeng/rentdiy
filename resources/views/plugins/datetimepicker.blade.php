<link href="/admin/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
<script src="/admin/js/plugins/datapicker/bootstrap-datepicker.js"></script>
<link href="/admin/css/plugins/clockpicker/clockpicker.css" rel="stylesheet">
<script src="/admin/js/plugins/clockpicker/clockpicker.js"></script>
<script>
    $(function(){
        $(".datetimepicker").datepicker({
            keyboardNavigation: !1,
            forceParse: true,
            autoclose: true,
            todayHighlight:true
        });
    })
</script>