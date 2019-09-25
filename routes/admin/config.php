<?php

Route::prefix('config')->namespace('Config')->name('config.')->group(function () {

    //后台用户管理
    Route::patch('/config/rechargeSetting/change_attr', 'RechargeSettingController@change_attr')->name('rechargeSetting.change_attr');
    Route::resource('rechargeSetting', 'RechargeSettingController');
    Route::patch('/config/sysSetting/change_value', 'SysSettingController@change_value')->name('sysSetting.change_value');
    Route::resource('sysSetting', 'SysSettingController');
    Route::resource('subjectCodeSetting', 'SubjectCodeSettingController');


});