<link href="/index/js/dropload/dropload.css" rel="stylesheet">
<script src="/index/js/dropload/dropload.min.js"></script>
<style>
    .opacity{
        -webkit-animation: opacity 0.3s linear;
        animation: opacity 0.3s linear;
    }
    @-webkit-keyframes opacity {
        0% {
            opacity:0;
        }
        100% {
            opacity:1;
        }
    }
    @keyframes opacity {
        0% {
            opacity:0;
        }
        100% {
            opacity:1;
        }
    }
    .dropload-down{
        height: 100px;
        font-size: 32px;
    }
    .dropload-refresh,.dropload-load,.dropload-noData{
        height: 100px;
        line-height: 100px;
    }
    .dropload-load .loading{
        height: 30px;
        width: 30px;
    }
    .dropload-noData{
        display: none;
    }
</style>
<script>
    $(function(){
        var load_div = $('.load_div');
        var load_url = load_div.attr('url');
        var load_layer;
        function initDropLoad(){
            var page = 1;
            var size = load_div.attr('size')?load_div.attr('size'):5;
            var scroll_area = $(".scroll_div").length>0?$(".scroll_div"):window;
            load_div.dropload({
                scrollArea : scroll_area,
                loadDownFn : function(me){
                    var params = {};
                    if($(".load_search").length > 0){
                        $(".load_search").each(function(){
                            if($(this).attr('name')){
                                eval("params."+$(this).attr('name')+"='"+$(this).val()+"';");
                            }
                        });
                    }
                    params.pageNumber = page;
                    params.pageSize = size;
                    $.ajax({
                        type: 'POST',
                        url: load_url,
                        data:params,
                        dataType: 'html',
                        async:false,
                        success: function(data){
                            if(data != ''){
                                page++;
                                load_div.find('.dropload-down').before(data);
                                me.resetload();
                            }else{
                                // 锁定
                                me.lock();
                                // 无数据
                                me.noData();
                                me.resetload();
                            }
                        },
                        error: function(xhr, type){
                            layer.msg('服务器繁忙');
                            // 即使加载出错，也得重置
                            me.lock();
                            me.noData();
                            me.resetload();
                        },
                        complete:function(){
                            if(load_layer){
                                layer.close(load_layer);
                            }
                        }
                    });
                }
            });
        }
        initDropLoad();
        $(".load_search_btn").click(function(){
            load_layer = layer.load(2);
            load_div.html('');
            initDropLoad();
        });
    })
</script>