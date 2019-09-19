<?php

Route::prefix('action_log')->namespace('ActionLog')->name('action_log.')->group(function () {

    //后台用户管理

    Route::resource('action_log', 'ActionLogController');



});