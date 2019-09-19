<?php

Route::prefix('profit')->namespace('Profit')->name('profit.')->group(function () {

    //好物说商品配置
    Route::get('/commission', 'CommissionController@index')->name('commission.index');
    Route::post('/commission', 'CommissionController@store')->name('commission.store');

    //卡多分信用卡分佣配置
    Route::get('/card', 'CardCommissionController@index')->name('card.index');
    Route::post('/card', 'CardCommissionController@store')->name('card.store');

    //分享模板列 表
    Route::resource('template', 'TemplateController');

    //单品配置列表
    Route::get('/product', 'CheckProductController@index')->name('checkProduct.index');

    //配置过分类列表
    Route::get('/cate', 'CheckCateController@index')->name('checkCate.index');

    //启动闪图配置
    Route::get('/startConf', 'StartController@index')->name('start.index');
    Route::post('/startConf', 'StartController@store')->name('start.store');//保存闪图


    //强制更新提示语
    Route::get('/instruction', 'InstructionController@index')->name('instruction.index');
    Route::post('/instruction', 'InstructionController@store')->name('instruction.store');

    //开屏广告图
    Route::patch('/ad/change_attr', 'AdController@change_attr')->name('ad.change_attr');//上下架
    Route::resource('ad', 'AdController');

    //开屏滑动图
    Route::patch('/screen/change_attr', 'ScreenController@change_attr')->name('screen.change_attr');//上下架
    Route::resource('screen', 'ScreenController');

    //版本控制列表
    Route::resource('version', 'VersionController');

    //渠道号列表
    Route::resource('channel', 'ChannelController');

    //新手引导语
    Route::get('guide', 'GuideController@index')->name('guide.index');
    Route::post('guide', 'GuideController@store')->name('guide.store');

    //开通微信联系人
    Route::resource('linkman', 'LinkManController');

    //云控管理
    Route::resource('iosc', 'IosPController');


});