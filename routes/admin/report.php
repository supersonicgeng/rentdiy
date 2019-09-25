<?php

Route::prefix('report')->namespace('Report')->name('report.')->group(function () {

    //报表
    Route::get('/userInfo', 'ReportController@userInfo')->name('userInfo');//用户信息列表
    Route::get('/userDetail', 'ReportController@userDetail')->name('userDetail');//用户信息明细列表
    Route::get('/chargeList','ReportController@chargeList')->name('chargeList');//充值列表
    Route::get('/expenseList','ReportController@expenseList')->name('expenseList');//花费列表
    Route::get('/landlordAnalyze','ReportController@landlordAnalyze')->name('landlordAnalyze');//用户使用分析
    Route::get('/landlordRunAnalyze','ReportController@landlordRunAnalyze')->name('landlordRunAnalyze');//用户运营分析
    Route::get('/userAdd','ReportController@userAdd')->name('userAdd');//用户增量分析
});