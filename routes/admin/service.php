<?php
/**
 * 客服模块
 */
Route::prefix('service')->namespace('Service')->name('service.')->group(function () {

    //用户列表
    Route::get('customer', 'CustomerController@index')->name('customer.index');//用户列表

});