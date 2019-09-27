<?php

Route::prefix('paper')->namespace('Paper')->name('paper.')->group(function () {

    //房屋主档
    Route::get('/index', 'PaperController@index')->name('index');//邮件列表

    Route::patch('/paperPrint', 'PaperController@paperPrint')->name('paperPrint');//房屋主档列表
});