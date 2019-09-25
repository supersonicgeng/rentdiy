<?php

Route::prefix('config')->namespace('config')->name('config.')->group(function () {

    //后台用户管理
    Route::patch('/config/rechargeSetting/change_attr', 'rechargeSettingController@change_attr')->name('rechargeSetting.change_attr');
    Route::resource('rechargeSetting', 'rechargeSettingController');
    Route::patch('/config/sysSetting/change_value', 'sysSettingController@change_value')->name('sysSetting.change_value');
    Route::resource('sysSetting', 'sysSettingController');
    Route::resource('subjectCodeSetting', 'subjectCodeSettingController');


});