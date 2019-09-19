<?php

Route::prefix('system')->namespace('System')->name('system.')->group(function () {

    //后台用户管理
    Route::put('/user/zlUpdate', 'UserController@zlUpdate')->name('user.zlUpdate');//更新个人资料
    Route::get('/user/person', 'UserController@person')->name('user.person');//个人资料
    Route::resource('user', 'UserController');
    Route::resource('permission', 'PermissionController');
    Route::resource('role', 'RoleController');


});