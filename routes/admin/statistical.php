<?php

Route::prefix('statistical')->namespace('Statistical')->name('statistical.')->group(function () {

    //首页列表商品点击统计
    Route::get('/home', 'HomeController@index')->name('home.index');

    //banner统计
    Route::get('/banner', 'BannerController@index')->name('banner.index');

    //快捷入口统计
    Route::get('/intry', 'IntryController@index')->name('intry.index');

    //专题图统计
    Route::get('/project', 'ProjectController@index')->name('project.index');

    //专题榜单统计
    Route::get('/list', 'ListController@index')->name('list.index');


});