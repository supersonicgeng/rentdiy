<?php

Route::prefix('report')->namespace('Report')->name('report.')->group(function () {

    //报表
    Route::get('/userInfo', 'ReportController@userInfo')->name('userInfo');//用户信息列表
    Route::get('/userDetail', 'ReportController@userDetail')->name('userDetail');//用户信息明细列表
    Route::get('/chargeList','ReportController@chargeList')->name('chargeList');//充值列表

});