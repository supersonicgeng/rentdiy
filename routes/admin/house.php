<?php

Route::prefix('house')->namespace('House')->name('house.')->group(function () {

    //房屋主档
    Route::get('/index', 'HouseController@index')->name('index');//房屋主档列表

    Route::patch('/house/change_attr', 'HouseController@change_attr')->name('change_attr');
});