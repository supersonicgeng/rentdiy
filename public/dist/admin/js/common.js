$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

NProgress.start();
NProgress.done();


/**
 * 选择弹框
 */
$('.check_model').click(function () {
    var url = $(this).data('url');
    var title = $(this).data('title');
    layer.open({
        title: title,
        type: 2,
        shadeClose: true,
        tipsMore: false,
        shade: [0.5, '#393D49'],
        maxmin: true, //开启最大化最小化按钮
        area: ['50%', '70%'],
        content: url

    });
})

//全选
// $("#checked").click(function () {
//     $('.checked_id').prop("checked", this.checked);
// });

// $('.select2').select2();

//全选
$('.check_all').on('ifChecked', function (event) {

    $('.checked_id').each(function () {
        if(!$(this).is(':disabled')){
            $(this).iCheck('check');

        }
    })
});

//反选
$('.check_all').on('ifUnchecked', function (event) {
    // $('.checked_id').iCheck('uncheck');
    $('.checked_id').each(function () {
        if(!$(this).is(':disabled')){
            $(this).iCheck('uncheck');

        }
    })
});

//上传图片按钮
$('.upload_image').click(function () {

    $('.image_button').click();
})

//ajax提交删除功能公用
$('.delete_genius').click(function () {
    var _this = $(this);

    layer.open({
        title: '警告',
        shadeClose: true,
        content: '您确定要删除吗？',
        yes: function (index, layero) {

            var url = _this.data('url');//获取删除提交地址



            $.ajax({
                type: 'DELETE',
                url: url,
                success: function (info) {

                    //删除成功
                    if (info.status == 1) {
                        layer.msg(info.msg, {
                            icon: 6,
                            time: 700
                        }, function () {
                            location.href = location.href;
                        });

                    } else {
                        layer.msg(info.msg, {
                            icon: 5,
                            time: 2000 //2秒关闭（如果不配置，默认是3秒）
                        });
                    }
                }
            })
            layer.close(index); //如果设定了yes回调，需进行手工关闭
        }
    });
    return false;


})

toastr.options = {
    closeButton: true,                  //是否显示关闭按钮
    debug: false,                       //是否使用debug模式
    progressBar: true,                  //是否显示进度条
    positionClass: "toast-top-center",   //弹出窗的位置
    showDuration: "300",                //显示动作时间
    preventDuplicates: true,            //提示框只出现一次
    hideDuration: "300",                //隐藏动作时间
    timeOut: "3000",                    //自动关闭超时时间
    extendedTimeOut: "1000",            ////加长展示时间
    showEasing: "swing",                //显示时的动画缓冲方式
    hideEasing: "linear",               //消失时的动画缓冲方式
    showMethod: "fadeIn",               //显示时的动画方式
    hideMethod: "fadeOut"               //消失时的动画方式
};


/***
 * 选择标签
 */
//选中提交
$('.check').click(function () {
    var length = $('.checked_id:checked').length;
    if (length == 0) {
        layer.msg('至少选择一个标签！', {icon: 5});
        return false;
    }
    var a = $('.checked_id:checked').parents('tr').clone(true);
    $(a).find('.del_check').remove();
    $(a).append('<td>' +
        '<a class="btn btn-danger btn-xs remove_tag" href="javascript:void(0);" data-url=""><i class="fa fa-trash"></i> 移除</a>' +
        '</td>');


    window.parent.$('tbody').append(a);
    var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
    parent.layer.close(index); //再执行关闭
})


$('.upload_image').click(function () {

    $('#image_upload').click();
})

/**
 * 权限管理模块
 */

//一级
$('.permission1').on('ifClicked', function (event) {

    var checked = $(this).prop('checked');

    if(checked == false){

        $(this).parents('.level1').find('input').iCheck('check');

    }else{
        $(this).parents('.level1').find('input').iCheck('Uncheck');

    }

});



//二级
$('.permission2').on('ifClicked', function (event) {

    var checked = $(this).prop('checked');


    if(checked == false){
         $(this).parents('.level2').find('input').iCheck('check');
     }else{
         $(this).parents('.level2').find('input').iCheck('uncheck');
     }

    var $body = $(this).parents('.level1');
    var length2_checked = $body.find('.permission2:checked').length;

    if(length2_checked ==0){
        $body.find('.permission1').iCheck('uncheck');

    }else{
        $body.find('.permission1').iCheck('check');

    }

});


//三级
$('.permission3').on('ifClicked', function (event) {

    var checked = $(this).prop('checked');

    if(checked == false){
        $(this).iCheck('check');
    }else{
        $(this).iCheck('uncheck');
    }

    var $permission_div3 = $(this).parents('.level3');
    var $permission_div2 = $permission_div3.parents('.level2');
    var $body = $(this).parents('.level1');

    //
    // 如果有三级一个选中，自动选择二级

    // var length3_checked = $permission_div3.find("input:checked").length;
    //
    // if (length3_checked > 0) {
    //
    //     $permission_div2.find('.permission2').iCheck('check');
    //
    // }
    // else {
    //
    //     $permission_div2.find('.permission2').iCheck('uncheck');
    // }
    //
    //如果二级有一个选中，自动选择一级

    var length2_checked = $body.find('.permission2:checked').length;
    if (length2_checked > 0) {
        $body.find(".permission1").iCheck('check');
    } else {
        $body.find(".permission1").iCheck('uncheck');
    }
})





// //一级 二级多选框，自动全选
// $(".permissions3").click(function () {
//     var $permission_div3 = $(this).parents('.permission-div3');
//     var $permission_div2 = $permission_div3.siblings('.permission-div2');
//     var $body = $(this).parents('.am-panel-bd');
//     var $panel = $(this).parents('.am-panel');
//     //
//     // 如果有三级一个选中，自动选择二级
//     // var length3 = $permission_div3.children().length;
//     var length3_checked = $permission_div3.find("input:checked").length;
//     if (length3_checked > 0) {
//         $permission_div2.find(".permission2").prop('checked', true);
//     } else {
//         $permission_div2.find(".permission2").prop('checked', false);
//     }
//     //
//     //如果二级有一个选中，自动选择一级
//     // var length2 = $body.find('.permission2').length;
//     var length2_checked = $body.find('.permission2:checked').length;
//     if (length2_checked > 0) {
//         $panel.find(".permission1").prop('checked', true);
//     } else {
//         $panel.find(".permission1").prop('checked', false);
//     }
// })