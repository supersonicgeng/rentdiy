<?php

Route::prefix('search')->namespace('Search')->name('search.')->group(function () {

    //关键字管理
    Route::patch('/keyword/change_attr','KeyWordController@change_attr')->name('keyword.change_attr');
    Route::resource('keyword','KeyWordController');



});