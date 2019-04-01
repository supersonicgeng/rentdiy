<link rel="stylesheet" type="text/css" href="/admin/css/plugins/webuploader/webuploader.css">
<script src="/admin/js/plugins/webuploader/webuploader.min.js"></script>
<script>
(function($) {
    $.fn.imgUpload = function (label,dir,callback) {
        return this.each(function() {

            // 此处运行代码，可以通过“this”来获得每个单独的元素
            // 例如： $(this).show()；
            var $this = $(this);
            var BASE_URL              = '/admin/js/plugins/webuploader';
            var uploader              = WebUploader.create({
                pick            : {
                    id      : $this,
                    label   : label,
                    multiple: false
                },
                accept          : {
                    title     : "Images",
                    extensions: "gif,jpg,jpeg,bmp,png",
                    mimeTypes : "image/gif,image/jpg,image/jpeg,image/bmp,image/png"
                },
                swf             : BASE_URL + "/Uploader.swf",
                disableGlobalDnd: !0,
                chunked         : !0,
                server          : uploadPath,
                auto            : true,
                formData        : {dir: dir}
            });
            uploader.onUploadStart    = function (file) {
                $this.append('<div class="progress progress-striped active"><div id="progress_bar" style="width: 0%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="0" role="progressbar" class="progress-bar progress-bar-success"> <span class="sr-only">40% Complete (success)</span></div></div>')
            };
            uploader.onUploadProgress = function (e, a) {
                $("#progress_bar").attr('aria-valuenow', a * 100);
                $("#progress_bar").css("width", 100 * a + "%");
            };
            uploader.onUploadSuccess  = function (file, response) {
                $("#progress_bar").parent().remove();
                var result = eval(response);
                if(parseInt(result.code) == 0){
                    callback(file, result.data);
                }else{
                    alertError(result.msg);
                }

            };
            uploader.onError          = function (msg) {
                alertError('不符合规格');
            }
        });
    }
})(jQuery);
</script>