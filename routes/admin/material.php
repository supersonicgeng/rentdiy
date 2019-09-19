<?php

Route::prefix('material')->namespace('Material')->name('material.')->group(function () {

    //物料管理
    Route::patch('/supply/change_attr', 'SupplyController@change_attr')->name('supply.change_attr');
    Route::resource('supply', 'SupplyController');

    //人物管理
    Route::patch('/person/change_attr', 'PersonController@change_attr')->name('person.change_attr');
    Route::resource('person', 'PersonController');


    //好物说栏目管理
    Route::resource('category', 'CategoryController');
    //好物说文章列表
    Route::patch('/article/change_attr', 'ArticleController@change_attr')->name('article.change_attr');
    Route::resource('article', 'ArticleController');
});