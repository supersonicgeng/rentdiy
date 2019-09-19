<?php

Route::prefix('order')->namespace('Order')->name('order.')->group(function () {

    //购买商品订单
    Route::get('/goods', 'GoodsOrderController@index')->name('goods.index');
    Route::get('/goods/export', 'GoodsOrderController@export')->name('goods.export');//导出excel

    //购买会员订单
    Route::get('/member', 'MemberOrderController@index')->name('member.index');
    Route::get('/member/export', 'MemberOrderController@export')->name('member.export');//导出excel

    //信用卡订单
    Route::get('/card', 'CardController@index')->name('card.index');//信用卡订单列表
    Route::get('/card/export', 'CardController@export')->name('card.export');//导出excel

    //手机回收订单
    Route::get('/phone', 'PhoneOrderController@index')->name('phone.index');//手机订单列表
    Route::get('/phone/export', 'PhoneOrderController@export')->name('phone.export');//导出excel
});