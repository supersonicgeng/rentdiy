<?php

Route::prefix('common')->namespace('Common')->name('common.')->group(function () {

    //商品列表
    Route::get('/product', 'ProductController@index')->name('product.index');//多选
    Route::get('/product/single', 'ProductController@single')->name('product.single');//单选
    Route::post('/produect/update','ProductController@update')->name('product.update');//更新商品库

    //分类列表
    Route::get('/category', 'CategoryController@index')->name('category.index');

    //选择人物
    Route::get('/role', 'RoleController@index')->name('role.index');

    //多选标签
    Route::get('/tag','TagController@index')->name('tag.index');

    //选择专题
    Route::get('/special','SpecialController@index')->name('special.index');
});