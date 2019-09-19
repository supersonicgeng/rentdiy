<?php

Route::prefix('coupon')->namespace('Coupon')->name('coupon.')->group(function () {

    //后台用户管理
    Route::get('/issue_coupon/make_coupon/{id}', 'IssueCouponController@makeCoupon')->name('issue_coupon.make_coupon');
    Route::post('/issue_coupon/save_coupon/{id}', 'IssueCouponController@saveCoupon')->name('issue_coupon.save_coupon');
    Route::resource('issue_coupon', 'IssueCouponController');
    Route::get('/coupon_list', 'CouponListController@index')->name('coupon_list.index');
   // Route::resource('coupon', 'sysSettingController');


});