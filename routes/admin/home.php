<?php

Route::prefix('home')->namespace('Home')->name('home.')->group(function () {

    //首页banner
    Route::patch('/banner/change_attr', 'BannerController@change_attr')->name('banner.change_attr');//修改状态

    Route::resource('banner', 'BannerController');

    //快捷入口
    Route::patch('/intry/change_attr', 'IntryController@change_attr')->name('intry.change_attr');//修改状态

    Route::resource('intry', 'IntryController');

    //专题
    Route::patch('/project/change_attr', 'ProjectController@change_attr')->name('project.change_attr');//修改状态

    Route::resource('project', 'ProjectController');


});