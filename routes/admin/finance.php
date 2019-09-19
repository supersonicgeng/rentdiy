<?php

Route::prefix('finance')->namespace('Finance')->name('finance.')->group(function () {

    //订单报表
    Route::get('/goods', 'GoodsController@index')->name('goods.index');


});