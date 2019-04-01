<style>
    .foot{
        width: 100%;
        height: 120px;
        font-size: 28px;
        bottom: 0;
        left: 0;
        position: fixed;
        z-index: 1;
        background-color: #F0F0F0;
    }
    .foot_item{
        width: 25%;
        height: 100%;
    }
    .foot_item img{
        width: 45px;
        height: 45px;
    }
    .foot_item span{
        margin-top: 5px;
    }
    .container{
        margin-bottom: 120px;
    }
</style>
<div class="foot flex">
    <a class="foot_item flex-1 flex-center flex-direction-col" href="{{url('wap',[request()->get('agent')->unique_code])}}"><img src="/index/img/home.png"><span>首页</span></a>
    <a class="foot_item flex-1 flex-center flex-direction-col" href="{{url('wap/search',[request()->get('agent')->unique_code])}}"><img src="/index/img/search.png"><span>搜索</span></a>
    <a class="foot_item flex-1 flex-center flex-direction-col" href="{{url('wap/cart',[request()->get('agent')->unique_code])}}"><img src="/index/img/cart.png"><span>购物车</span></a>
    <a class="foot_item flex-1 flex-center flex-direction-col" href="{{url('wap/center',[request()->get('agent')->unique_code])}}"><img src="/index/img/my.png"><span>我的</span></a>
</div>