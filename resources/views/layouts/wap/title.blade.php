<style>
    .hisums_title_main{
        width: 100%;
        height: 80px;
        background-color: #F0F0F0;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 100;
        box-sizing: border-box;
    }
    .hisums_title{
        width: 100%;
        height: 100%;
        position: relative;
    }
    .title_text{
        width: 100%;
        height: 100%;
        overflow: hidden;
        box-sizing: border-box;
        padding:0 80px;
        line-height: 80px;
        text-align: center;
    }
    .go_back{
        width: 80px;
        height: 80px;
        position: absolute;
        left: 0;
        top: 0;
    }
</style>
<div class="hisums_title_main">
    <div class="hisums_title">
        <div class="go_back flex-center"><img src="/index/img/icon_back.png"></div>
        <div class="title_text overflowellipsis">{{$title}}</div>
    </div>
</div>
<script>
    $(".go_back").click(function(){
        window.history.go(-1);
    })
</script>