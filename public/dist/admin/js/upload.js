//图片上传
var opts1 = {
    url: "/admin/photo",
    type: "POST",
    beforeSend: function () {
        $("#loading").attr("class", "fa fa-spinner fa-spin");
    },
    success: function (result, status, xhr) {

        if (result.status == "0") {
            alert(result.msg);
            $("#loading").attr("class", "fa fa-fw fa-cloud-upload");
            return false;
        }

        $("#cover").val(result.image);
        $("#cover_show").attr('src', result.image);
        $("#loading").attr("class", "fa fa-fw fa-cloud-upload");

        layer.msg('上传成功', {icon: 6, time: 1500});
    },
    error: function (result, status, errorThrown) {

        layer.alert('上传失败', {
            skin: 'layui-layer-lan'
            , title: '错误'
            , closeBtn: 0
            , anim: 4 //动画类型
        });

        $("#loading").attr("class", "fa fa-fw fa-cloud-upload");
    }
}

$('#image_upload').fileUpload(opts1);

//视频上传
var opts = {
    url: "/admin/video",
    type: "POST",
    beforeSend: function () {
        $("#loading_v").attr("class", "fa fa-spinner fa-spin");
    },
    success: function (result, status, xhr) {



        if (result.status == "0") {
            alert(result.msg);
            $("#loading_v").attr("class", "fa fa-fw fa-cloud-upload");
            return false;
        }

        $("input[name='video']").val(result.image);
        // $("input[name='duration']").val(result.duration);
        $("video").attr('src', result.image);
        $("#loading_v").attr("class", "fa fa-fw fa-cloud-upload");
    },
    error: function (result, status, errorThrown) {

        layer.alert('文件太大', {
            skin: 'layui-layer-lan'
            , title: '错误'
            , closeBtn: 0
            , anim: 4 //动画类型
        });

        $("#loading_v").attr("class", "fa fa-fw fa-cloud-upload");
    }
}

$('#video_upload').fileUpload(opts);

