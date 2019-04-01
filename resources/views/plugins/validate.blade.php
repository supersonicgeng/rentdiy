<script src="/admin/js/plugins/validate/jquery.validate.min.js"></script>
<script src="/admin/js/plugins/validate/messages_zh.min.js"></script>
<script>
    jQuery.validator.addMethod("isMobile", function(value, element) {
        var length = value.length;
        var mobile = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
        return this.optional(element) || (length == 11 && mobile.test(value));
    }, "请正确填写您的手机号码");
    jQuery.validator.addMethod("decimal", function(value, element) {
        var number = /(^[1-9]([0-9]+)?(\.[0-9]{1,2})?$)|(^(0){1}$)|(^[0-9]\.[0-9]([0-9])?$)/;
        return this.optional(element) ||  number.test(value);
    }, "请输入正确的金额");
</script>