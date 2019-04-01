var clientWidth = 0 ;
if (typeof document.body == 'undefined') {
    if (window.innerWidth) {
        clientWidth = window.innerWidth;
    } else {
        clientWidth = document.documentElement.clientWidth;
    }
} else {
    clientWidth = document.body.clientWidth;
}

var scale = clientWidth / 750;
var meta = document.getElementsByTagName('meta');
var mvp = meta[0];

mvp.setAttribute('content','width=750,initial-scale=' + scale +',maximum-scale=' + scale+ ',minimum-scale=' +scale + ',user-scalable=no');

var customStyler = {
    setSelectStyle: function(height) {
        $('.am-selected').css('font-size', '32px');
        $('.am-selected-btn').css('font-size', '32px');
        $('.am-selected-btn').css('border', 'solid 2px #ccc');
        $('.am-selected-btn').css('padding-left', '10px');
        $('.am-selected-btn').css('height', height + 'px');
        $('.am-selected-btn').css('padding', '0em 1em');
        $('.am-selected-btn').css('color', '#333');
        $('.am-icon-check').css('font-size', '32px');
        $('.am-selected-btn').css('border-radius', '12px');
        $('.am-selected-content').css('font-size', '32px');
        $('.am-selected-header').css('font-size', '32px');
        $('.am-selected-text').css('font-size', '32px');
        $('.am-selected-list li').css('padding-left', '30px');
    },
    setDatePickerStyle: function() {
        $('.am-datepicker').css('font-size', '32px');
        $('.am-datepicker-caret').css('font-size', '32px');
        $('.am-datepicker-days').css('font-size', '32px');
        $('.am-datepicker-table').css('font-size', '32px');
        $('.am-datepicker-header').css('font-size', '32px');
        $('.am-datepicker-dow').css('font-size', '32px');
        $('.am-datepicker-day').css('font-size', '32px');
        $('.am-datepicker-month').css('font-size', '32px');
        $('.am-datepicker-years').css('font-size', '32px');
    }
};

$(function() {
    customStyler.setSelectStyle(60);
    //customStyler.setDatePickerStyle();
    /*$('.picture-box').each(function() {
        $(this).on('click', function() {
            var file = $(this).find('input[type=\'file\']:eq(0)')[0];
            var imagebox = $(this).find('img:eq(0)')[0];
            file.click();
            file.accept = "image/gif,image/jpeg,image/jpg,image/png,image/svg,image/bmp";
            file.addEventListener('change', function(e) {
                var files = e.target.files;

                for (var i = 0, f; f = files[i]; i++) {

                    if (!f.type.match('image.*')) {
                      continue;
                    }

                    var reader = new FileReader();
                    reader.onload = (function(theFile) {
                        return function(e) {
                            imagebox.src = e.target.result;
                        };
                    })(f);

                    reader.readAsDataURL(f);
                }
            }, false);
        });
    });*/

    if (/ipad|iphone|mac/i.test(navigator.userAgent)) {
        $('.outerScroll').css('position', 'absolute');
        $('.outerScroll').css('top', '0px');
        var height = 0;
        if (window.innerHeight) {
            height = window.innerHeight;
        } else {
            height = document.documentElement.clientHeight;
        }
        height = height - 100;
        $('.outerScroll').css('width', '750px');
        $('.outerScroll').css('height', height + 'px');
        $('.outerScroll').css('overflow-y', 'auto');
        $('.outerScroll').css('overflow-x', 'hidden');
        $('.outerScroll').css('-webkit-overflow-scrolling', 'touch');
        // $('.bottom-tab-bar').css('position', 'absolute');
        // $('.bottom-tab-bar').css('top', height + 'px');
    }

    $('.bottom-tab-bar').find('.tab-item').each(function(i) {
        if (i == 0) {
            $(this).on('click', function(){
                if (window.location != 'captureOrder.htm') {
                    window.location = 'captureOrder.htm';
                }
            });
        } else if (i == 1) {
            $(this).on('click', function(){
                if (window.location != 'myOrders.htm') {
                    window.location = 'myOrders.htm';
                }
            });
        } else if (i == 2) {
            $(this).on('click', function(){
                if (window.location != 'ucenter.htm') {
                    window.location = 'ucenter.htm';
                }
            });
        }
    });
});
