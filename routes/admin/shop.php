<?php

Route::prefix('shop')->namespace('Shop')->name('shop.')->group(function () {

    //商品列表
    Route::get('/', 'ProductController@index')->name('product.index');
    Route::get('/product/edit/{product}', 'ProductController@edit')->name('product.edit');
    Route::put('/product/update/{product}', 'ProductController@update')->name('product.update');
    Route::get('/product/addTag/{product}', 'ProductController@addTag')->name('product.addTag');//打标签
    Route::put('/product/updateTag/{product}', 'ProductController@updateTag')->name('product.updateTag');//更新标签
    Route::patch('/product/change_attr', 'ProductController@change_attr')->name('product.change_attr');//修改状态
    Route::patch('/product/clear_weight', 'ProductController@clear_weight')->name('product.clear_weight');//一键清除人工权重
    Route::get('/product/search', 'ProductController@search')->name('product.search');//商品搜索
    Route::get('/product/warehouse', 'ProductController@warehouse')->name('product.warehouse');//商品入库页面
    Route::post('/product/warehouse', 'ProductController@addProduct')->name('product.Putwarehouse');//商品入库操作
    Route::get('/product/noProfit','ProductController@noProfit')->name('product.noProfit');//不分佣商品列表

    //标签列表
    Route::patch('/tag/delete_all', 'TagController@delete_all')->name('tag.delete_all');
    Route::get('/tag/tag_goods', 'TagController@tag_goods')->name('tag.tag_goods');
    Route::resource('tag', 'TagController');

    //分类列表
    Route::get('/category/secondCate', 'CategoryController@secondCate')->name('category.secondCate');//二级分类列表
    Route::patch('/category/change_attr', 'CategoryController@change_attr')->name('category.change_attr');//分类上下架
    Route::get('/category/secondCate/{category}/edit', 'CategoryController@secondEdit')->name('category.secondEdit');//二级分类编辑
    Route::put('/category/secondCate/{category}', 'CategoryController@secondUpdate')->name('category.secondUpdate');//二级分类更新
    Route::delete('/category/secondCate/{category}', 'CategoryController@secondDelete')->name('category.secondDelete');//二级分类删除
    Route::resource('category', 'CategoryController');

    //商品专题列表
    Route::get('/special/goods/{special}', 'SpecialController@goods')->name('special.goods');//专题下面的商品列表
    Route::delete('/special/goods/{special}', 'SpecialController@rm_good')->name('special.rm_good');//移除专题商品
    Route::post('/special/goods/change_title','SpecialController@change_title')->name('special.change_title');//修改vip标题
    Route::post('/special/goods/{special}', 'SpecialController@import')->name('special.import');//导入商品
    Route::resource('special', 'SpecialController');

});