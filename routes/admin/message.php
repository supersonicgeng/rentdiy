<?php

Route::prefix('message')->namespace('Message')->name('message.')->group(function () {

    //个推列表
    Route::get('/push', 'PushController@index')->name('push.index');
    Route::get('/push/create', 'PushController@create')->name('push.create');
    Route::post('/push', 'PushController@store')->name('push.store');



});