/**
 * Created by Administrator on 2017/11/1 0001.
 */
/**
 * Created by Administrator on 2017/4/19.
 */
var loading_layer;
//确认框封装 (ajax-get confirm)    liuwei
$(document).delegate(".layer-get", "click", function () {
    var title = $(this).attr('title');
    title = title ? title : '是否执行此操作?';
    var url = $(this).attr('url');
    var href = $(this).attr('href');
    var target = url ? url : href;
    var waiting_msg = $(this).attr('waiting_msg') ? $(this).attr('waiting_msg') : '加载中...';
    var that = this;
    layer.confirm(title, {
        btn: ['确定', '取消'] //按钮
    }, function (index) {
        layer.close(index);
        //layer.msg('已确定', {icon: 1});
        var waiting = layer.msg(waiting_msg, {'time': 0, 'shade': 0.3, 'icon': 16});
        $.get(target).success(function (data) {
            layer.close(waiting);
            ajaxCallback(data);
        });
    }, function () {
        layer.msg('已取消', {icon: 0});
    });
    return false;
})

//确认框封装 (ajax-post confirm)    liuwei
$(document).delegate(".layer-post", "click", function () {
    var target, query, form;
    var title = $(this).attr('title');
    title = title ? title : '是否执行此操作?';
    var nead_confirm = false;
    var target_form = $(this).attr('target-form');
    var waiting_msg = $(this).attr('waiting_msg') ? $(this).attr('waiting_msg') : '加载中...';
    var that = this;

    if (($(this).attr('type') == 'submit') || (target = $(this).attr('href')) || (target = $(this).attr('url'))) {
        form = $('.' + target_form);

        if (form.get(0).nodeName == 'FORM') {        //表单提交
            if ($(this).attr('url') !== undefined) {
                target = $(this).attr('url');
            } else {
                target = form.get(0).action;
            }
            //***表单验证***
            var num = check_form('.' + target_form);
            if (num) {
                layer.msg('有' + num + '项不符合规则', {icon: 0});
                return false;
            }
            //******
            query = form.serialize();
        } else if (form.get(0).nodeName == 'INPUT' || form.get(0).nodeName == 'SELECT' || form.get(0).nodeName == 'TEXTAREA') {//批量操作
            var arr = Array();
            form.each(function (k, v) {
                if (v.type == 'checkbox' && v.checked == true) {
                    nead_confirm = true;
                }
            })
            if (!nead_confirm) {
                var warn = $(this).attr('warn') ? $(this).attr('warn') : '请选择信息';
                alertError(warn);
                return false;
            }
            query = form.serialize();
        } else {
            query = form.find('input,select,textarea').serialize();
        }
        layer.confirm(title, {
            btn: ['确定', '取消'] //按钮
        }, function (index) {
            layer.close(index);
            //layer.msg('已确定', {icon: 1});
            var waiting = layer.msg(waiting_msg, {'time': 0, 'shade': 0.3, 'icon': 16});
            $.post(target, query).success(function (data) {
                layer.close(waiting);
                ajaxCallback(data);
            });
        }, function () {
            layer.msg('已取消', {icon: 0});
        })
    }
    return false;
})

$(document).delegate(".layer-delete", "click", function () {
    var title = $(this).attr('title');
    title = title ? title : '是否执行此操作?';
    var url = $(this).attr('href');
    layer.confirm(title, {
        btn: ['确定', '取消'] //按钮
    }, function (index) {
        layer.close(index);
        $.ajax({
            url: url,
            type: 'get',
            success: function (data) {
                ajaxCallback(data);
            }
        })
    })
    return false;
})

//ajax get请求
$(document).delegate('.ajax-get', "click", function () {
    var waiting_msg = $(this).attr('waiting_msg') ? $(this).attr('waiting_msg') : '加载中...';
    var target;
    var that = this;
    if ($(this).hasClass('confirm')) {
        if (!confirm('确认要执行该操作吗?')) {
            return false;
        }
    }
    if ((target = $(this).attr('href')) || (target = $(this).attr('url'))) {
        var waiting = layer.msg(waiting_msg, {'time': 0, 'shade': 0.3, 'icon': 16});
        $.get(target).success(function (data) {
            layer.close(waiting);
            ajaxCallback(data);
        });

    }
    return false;
});

//ajax post submit请求
$(document).delegate('.ajax-post', "click", function () {
    var waiting_msg = $(this).attr('waiting_msg') ? $(this).attr('waiting_msg') : '加载中...';
    var target, query, form;
    var target_form = $(this).attr('target-form');
    var that = this;
    var nead_confirm = false;
    if (($(this).attr('type') == 'submit') || (target = $(this).attr('href')) || (target = $(this).attr('url'))) {
        form = $('.' + target_form);

        if ($(this).attr('hide-data') === 'true') {//无数据时也可以使用的功能
            form = $('.hide-data');
            query = form.serialize();
        } else if (form.get(0) == undefined) {
            return false;
        } else if (form.get(0).nodeName == 'FORM') {
            if ($(this).hasClass('confirm')) {
                if (!confirm('确认要执行该操作吗?')) {
                    return false;
                }
            }
            if ($(this).attr('url') !== undefined) {
                target = $(this).attr('url');
            } else {
                target = form.get(0).action;
            }
            //***表单验证***
            var num = check_form('.' + target_form);
            if (num) {
                layer.msg('有' + num + '项不符合规则', {icon: 0});
                return false;
            }
            //******
            query = form.serialize();
        } else if (form.get(0).nodeName == 'INPUT' || form.get(0).nodeName == 'SELECT' || form.get(0).nodeName == 'TEXTAREA') {
            form.each(function (k, v) {
                if (v.type == 'checkbox' && v.checked == true) {
                    nead_confirm = true;
                }
            })
            if (nead_confirm && $(this).hasClass('confirm')) {
                if (!confirm('确认要执行该操作吗?')) {
                    return false;
                }
            }
            query = form.serialize();
        } else {
            if ($(this).hasClass('confirm')) {
                if (!confirm('确认要执行该操作吗?')) {
                    return false;
                }
            }
            query = form.find('input,select,textarea').serialize();
        }
        $(that).addClass('disabled').attr('autocomplete', 'off').prop('disabled', true);
        var waiting = layer.msg(waiting_msg, {'time': 0, 'shade': 0.3, 'icon': 16});
        $.post(target, query).success(function (data) {
            layer.close(waiting);
            ajaxCallback(data);
        });
    }
    return false;
});

//dialog弹出框(链接)
$(document).delegate("[target=dialog]", 'click', function () {
    var width = $(this).attr('width') ? $(this).attr('width') : '300px';
    var height = $(this).attr('height') ? $(this).attr('height') : '193px';
    var title = $(this).attr('title') ? $(this).attr('title') : '信息';
    var href = $(this).attr('href');
    var flow = $(this).attr('flow') ? $(this).attr('flow') : 'yes';
    var maxmin = $(this).attr('maxmin') ? $(this).attr('maxmin') : false;
    var shade = $(this).attr('shade') ? parseFloat($(this).attr('shade')) : 0.8;
    var offset = $(this).attr('offset') ? $(this).attr('offset') : 'auto';
    var move = $(this).attr('move') ? $(this).attr('move') : '.layui-layer-title';
    var btn = $(this).attr('btn');   //按钮
    var indexName = $(this).attr('layerIndex') ? $(this).attr('layerIndex') : 'index';   //按钮
    console.log(indexName);
    var config = {      //弹出框初始化
        type: 2,
        title: title,
        shadeClose: false,
        shade: shade,
        area: [width, height],
        maxmin: maxmin,
        move: move,
        content: [href, flow],
    }
    switch (offset) {
        case 'auto':
            break;
        case 'rb':
            break;
        default:
            if (offset.indexOf(',') > -1) {
                if (offset.indexOf('[') > -1) {
                    offset = offset.substr(1, offset.length - 2);
                }
                offset = offset.split(',');
                offset = [offset[0], offset[1]];
            }
            break;
    }
    config['offset'] = offset;

    if (btn) {
        var btn_info = btn.split(',');  //按钮信息
        var btn_name = new Array;       //按钮名称
        for (i in btn_info) {
            btn_info[i] = btn_info[i].split(':');
            btn_name.push(btn_info[i][0]);
        }
        config.btn = btn_name;
        var fun_name;
        for (j in btn_name) {
            switch (j) {
                case '0':
                    fun_name = 'yes';
                    break;
                case '1':
                    fun_name = 'no';
                    break;
                default:
                    fun_name = 'btn' + (parseInt(j) + 1);
                    break;
            }
            if (btn_info[j][1]) {     //存在回调函数
                try {
                    var fn = eval(btn_info[j][1]);
                } catch (e) {
                }
                if (typeof fn === 'function') {
                    config[fun_name] = eval(btn_info[j][1]);
                }
            }
        }
    }
    eval(indexName + ' = layer.open(config)');
    return false;
})

//关闭dialog
function close_dialog() {
    var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
    parent.layer.close(index); //再执行关闭
}

$('body').on('mouseout', 'a[rel=mouseout]', function () {
    layer.closeAll();
})

//提示框 tips
$(document).delegate('.tips', 'mouseenter', function () {
    var msg = $(this).attr('msg');
    var width = $(this).attr('width') ? $(this).attr('width') : 'auto';
    var eventtype = $(this).attr('eventtype');//触发事件
    var typenum = $(this).attr('typenum') ? $(this).attr('typenum') : 2;
    var color = $(this).attr('color') ? $(this).attr('color') : '#ff9900';
    var time = $(this).attr('time') ? $(this).attr('time') : 3000;

    if (msg) {    //msg不为空时显示
        if (eventtype && eventtype != 'mouseenter') {
            $(this).on(eventtype, function (event) {
                event.stopPropagation();
                var index = layer.tips(msg, this, {
                    tips: [typenum, color],
                    area: width,
                    time: time,
                });
                $('body').click(function (e) {

                    layer.close(index);
                })
            })
        } else {
            var index = layer.tips(msg, this, {
                guide: 1,
                tips: [typenum, color],
                area: width,
                time: time,
            });
            $('body').click(function (e) {
                layer.close(index);
            })
        }
    }
    $(document).delegate('.layui-layer-content', 'click', function (e) {
        e.stopPropagation();
    })
})

/**
 * [viewIMG description]
 * @param  {[type]} url      图片链接
 * @param  {Number} width    图片宽度，默认400
 * @param  {String} position Position属性默认absolute
 * @param  {Number} top      调整position属性，默认为屏幕高度的一半
 * @param  {Number} left     调整position属性，默认为屏幕宽度的一半
 * @return {[type]}          [description]
 */
function viewIMG(url, width, position, top, left) {
    var view = '<div id="view_img" style="display:none;"><img src="' + url + '"></div>';
    $("#view_img").remove();
    $("body").append(view);
    var width = arguments[1] ? arguments[1] : 0;
    var position = arguments[2] ? arguments[2] : 'fixed';
    var img_height = parseFloat($("#view_img").css('height'));
    var img_width = parseFloat($("#view_img").css('width'));
    if (img_width > $("#main-content").width() * 0.9) {
        width = img_width = $("#main-content").width() * 0.9;
    }
    var top = arguments[3] ? arguments[3] : ($(window).height()) / 2 - img_height / 2;
    var left = arguments[4] ? arguments[4] : ($(window).width()) / 2 - img_width / 2;
    if (width == 0) {
        var html = '<div class="imgViewBox" style="border: 4px solid rgb(0, 0, 0); padding: 2px; background: none repeat scroll 0% 0% rgb(255, 255, 255); position: ' + position + '; z-index: 65535; top: ' + top + 'px; left: ' + left + 'px;"><img src="' + url + '" title="按ESC关闭"><div style="padding:5px;"><span onclick="$(&quot;.imgViewBox&quot;).remove()" style="display:block;text-align:right;cursor:pointer;border-top:1px #ccc solid;">关闭</span></div></div>';
    } else {
        var html = '<div class="imgViewBox" style="border: 4px solid rgb(0, 0, 0); padding: 2px; background: none repeat scroll 0% 0% rgb(255, 255, 255); position: ' + position + '; z-index: 65535; top: ' + top + 'px; left: ' + left + 'px;"><img width="' + width + '" src="' + url + '" title="按ESC关闭"><div style="padding:5px;"><span onclick="$(&quot;.imgViewBox&quot;).remove()" style="display:block;text-align:right;cursor:pointer;border-top:1px #ccc solid;">关闭</span></div></div>';
    }
    $(".imgViewBox").remove();
    $("body").append(html);
}

function lengthLimit(elem, showElem, max) {
    var elem = document.getElementById(elem);
    var showElem = document.getElementById(showElem);
    var max = max || 50;// 最大限度字符，汉字按两个字符计算
    function getTextLength(str) {// 获取字符串的长度 一个汉字为2个字符
        return str.replace(/[^\x00-\xff]/g, "xx").length;
    };
    // 监听textarea的内容变化
    if (/msie (\d+\.\d)/i.test(navigator.userAgent) == true) {// 区分IE
        elem.onpropertychange = textChange;
    } else {
        elem.addEventListener("input", textChange, false);
    }
    function textChange() {// 内容变化时的处理
        var text = elem.value;
        var count = getTextLength(text);
        if (count > max) {// 文字超出截断
            for (var i = 0; i < text.length; i++) {
                if (getTextLength(text.substr(0, i)) >= max) {
                    elem.value = text.substr(0, i);
                    if (showElem) showElem.innerHTML = elem.value;// 显示输出结果
                    break;
                }
                ;
            }
        } else {
            if (showElem) showElem.innerHTML = elem.value;// 显示输出结果
        }
        ;
    };
    textChange();// 加载时先初始化
};

function check_form(form) {
    var num = 0;
    var msgH = '<font class="error_msg">';
    var msgF = '</font>';
    $(form + ' :input').each(function () {
        var obj = $(this);
        var msg = obj.attr('msg');
        if (obj.hasClass('error')) {
            obj.removeClass('error');
            obj.nextAll('.error_msg').remove();
        }
        var all_class = obj.attr('class');
        if (typeof(all_class) != "undefined") {
            var every_class = all_class.split(' ');
            var result;
            $.each(every_class, function (n, value) {
                try {
                    var fn = eval('check_' + value);
                } catch (e) {
                }
                if (typeof fn === 'function') {
                    result = fn(obj);
                } else {
                    result = '';
                }

                if (result.code == 2) {
                    obj.addClass('error');
                    if (msg) {
                        result.msg = msg;
                    }
                    obj.after(msgH + result.msg + msgF);
                    num++;
                    return false;
                }
            })
        }
    })
    return num;
}

function loadingShow() {
    loading_layer = layer.msg("加载中...", {'time': 0, 'shade': 0.3, 'icon': 16});
}

function loadingHide() {
    layer.close(loading_layer);
}
toastr.options = {
    "closeButton": true,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "3000",
    "extendedTimeOut": "1000",
    "positionClass": "toast-top-center",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}
function alertSuccess(msg) {
    toastr.success('页面即将跳转...', msg);
}

function alertError(msg) {
    toastr.error(msg, '操作失败');
}

function ajaxCallback(response) {
    loadingHide();
    if (parseInt(response.code) == 0) {
        alertSuccess(response.msg);
        setTimeout(function () {
            if (response.url) {
                location.href = response.url;
            } else {
                location.reload();
            }
        }, 1500);
    } else {
        alertError(response.msg);
        setTimeout(function () {
            if (response.url) {
                location.href = response.url;
            }
        }, 1500);
    }
}

function errorCallback(response) {
    loadingHide();
    switch (response.status) {
        case 422:    //验证器抛出异常
            var errorText = JSON.parse(response.responseText);
            var errorFirst = '';
            for (var item in errorText) {
                errorFirst = errorText[item][0];
            }
            alertError(errorFirst);
            break;
        default:
            break;
    }
}

function beforeAjax() {
    loadingShow();
}

/*分页功能START*/
/**
 * 初始化
 */
var defaultPageSize = 10;
function pageInit(search_from) {
    /*初始化之前先清除页面的元素*/
    search_from.find("[name=pageNumber]").remove();
    search_from.find("[name=pageSize]").remove();
    $(".page_div").remove();
    $(document).undelegate('.doJumpPage', 'click');
    $(".page").remove();
    /*再次添加要用的元素*/
    search_from.append('<input type="hidden" name="pageNumber" value="1"/>');
    search_from.append('<input type="hidden" name="pageSize" value="' + defaultPageSize + '"/>');
    $("table").after('<div class="row page_div"><div class="page col-sm-6"></div><div class="jumpPage col-sm-1 input-group"><input type="number" class="form-control jumpNumber"> <span class="input-group-btn"> <button type="button" class="btn btn-primary doJumpPage">跳转</button></span></div></div>');
    getList(search_from);
    search_from.append('<input type="hidden" name="getCount" value="1"/>');
    loadingShow();
    search_from.ajaxSubmit({
        dataType: "json",
        success: function (data) {
            search_from.find("[name=getCount]").remove();
            loadingHide();
            $('.page').pagination({
                totalData: parseInt(data),
                showData: parseInt(search_from.find("[name=pageSize]").val()),
                current: 1,
                coping: true,
                homePage: '首页',
                endPage: '末页',
                prevContent: '上页',
                nextContent: '下页',
                callback: function (api) {
                    search_from.find("[name=pageNumber]").val(api.getCurrent());
                    getList(search_from);
                }
            });
            $(document).delegate('.doJumpPage', 'click', function () {
                if (parseInt($(".jumpNumber").val()) > 0) {
                    search_from.find("[name=pageNumber]").val($(".jumpNumber").val());
                    $('.page').pagination({
                        totalData: parseInt(data),
                        showData: parseInt(search_from.find("[name=pageSize]").val()),
                        current: parseInt($(".jumpNumber").val()),
                        coping: true,
                        homePage: '首页',
                        endPage: '末页',
                        prevContent: '上页',
                        nextContent: '下页',
                        callback: function (api) {
                            search_from.find("[name=pageNumber]").val(api.getCurrent());
                            getList(search_from);
                        }
                    });
                    getList(search_from);
                }
            });
        }
    });
}
function getList(search_from) {
    loadingShow();
    search_from.ajaxSubmit({
        dataType: "html",
        success: function (data) {
            $("tbody").html(data);
            loadingHide();
        }
    });
}
/*分页功能END*/

/**
 * 后台列表 多余部分 显示...
 * @author hkw
 * @dateTime 2016-01-07T11:14:34+0800
 * @param    {[type]}                 obj  [description]
 * @param    {[type]}                 size [description]
 * @return   {[type]}                      [description]
 */
function brief_content(obj, size) {
    var title, text;
    obj.each(function () {
        title = $.trim($(this).text());
        if (title.length > size) {
            text = title.substr(0, size) + " ...";
            $(this).text(text);
            $(this).attr('title', title);
        }
    })
}

/**
 * @description:图片防盗链处理
 * @param $url
 * @author     : 黄开旺
 * @return string
 */
function imgOutShow($url) {
    return 'http://read.html5.qq.com/image?src=forum&q=5&r=0&imgflag=7&imageUrl=' + $url;
}

//手机号验证
function checkPhone(node) {
    var phoneNumber = node.val();
    var pattern = /^1[34578]\d{9}$/;
    if (phoneNumber == '') {
        layer.msg('手机号不能为空');
        return false;
    }
    if (!pattern.test(phoneNumber)) {
        layer.msg('手机格式不正确');
        return false;
    }
    return true;
}
//验证码按钮倒计时
function resetCode(node) {
    var second = 5;
    getingVerify = 1;
    node.css('background-color', '#999999');
    node.css('font-size', '1.2rem');
    node.html(second + 's后重新获取');
    var timer = null;
    timer = setInterval(function () {
        second -= 1;
        if (second > 0) {
            node.html(second + 's后重新获取');
        } else {
            clearInterval(timer);
            node.css('color', '#fff');
            node.css('background-color', '#1ab394');
            node.css('font-size', '1.3rem');
            node.html('获取验证码');
            getingVerify = 0;
        }
    }, 1000);
}

//全选的实现
$(document).delegate('.check-all', 'click', function () {
    $(".ids").prop("checked", this.checked);
});
$(document).delegate('.ids', 'click', function () {
    var option = $(".ids");
    option.each(function (i) {
        if (!this.checked) {
            $(".check-all").prop("checked", false);
            return false;
        } else {
            $(".check-all").prop("checked", true);
        }
    });
});
if ($.validator) {
    $.extend($.validator.messages, {
        required: "必选字段",
        remote: "请修正该字段",
        email: "请输入正确格式的电子邮件",
        url: "请输入合法的网址",
        date: "请输入合法的日期",
        dateISO: "请输入合法的日期 (ISO).",
        number: "请输入合法的数字",
        digits: "只能输入整数",
        creditcard: "请输入合法的信用卡号",
        equalTo: "请再次输入相同的值",
        accept: "请输入拥有合法后缀名的字符串",
        maxlength: $.validator.format("请输入一个长度最多是 {0} 的字符串"),
        minlength: $.validator.format("请输入一个长度最少是 {0} 的字符串"),
        rangelength: $.validator.format("请输入一个长度介于 {0} 和 {1} 之间的字符串"),
        range: $.validator.format("请输入一个介于 {0} 和 {1} 之间的值"),
        max: $.validator.format("请输入一个最大为 {0} 的值"),
        min: $.validator.format("请输入一个最小为 {0} 的值")
    });
}

//excel上传
$(document).delegate('.excel', 'click', function () {
    if ($('input[name=excel]').length == 0) {
        var html = '<input type="file" name="excel" style="display: none" accept=".csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">';
        $(this).parent().append(html);
    }
    var excel = $('input[name=excel]');
    excel.click();
});

$(document).delegate('input[name=excel]', 'change', function () {
    var _this = this;
    var formData = new FormData();
    var url = $('.excel').attr('url');
    var index;
    if (typeof (_this.files[0]) == 'undefined') {
        return false;
    }
    formData.append("file", _this.files[0]);

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        // 告诉jQuery不要去处理发送的数据
        processData: false,
        // 告诉jQuery不要去设置Content-Type请求头
        contentType: false,
        beforeSend: function () {
            index = layer.load(1);
        },
        success: function (responseStr) {
            if (responseStr.code == 0) {
                layer.msg('导入成功!', {time: 1000}, function () {
                    layer.close(index);
                    window.location.reload();
                })
            } else {
                layer.close(index);
                $(_this).val('');
                layer.msg(responseStr.msg, {time: 4000}, function () {
                    if (responseStr.url) {
                        location.href = responseStr.url;
                    }
                })
            }
        },
        error: function (responseStr) {
            console.log("error");
        }
    });
});
