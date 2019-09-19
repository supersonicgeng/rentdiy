<?php

Route::prefix('platform')->namespace('Platform')->name('platform.')->group(function () {

    //用户列表
    Route::get('/customer', 'CustomerController@index')->name('customer.index');
    Route::patch('/customer/changeStatus', 'CustomerController@changeStatus')->name('customer.changeStatus');//冻结
    Route::get('/customer/superVip/{superVip}', 'CustomerController@supershow')->name('customer.supershow');//升级超级vip页面
    Route::put('/customer/superVip/{superVip}', 'CustomerController@setSuperVip')->name('customer.setSuperVip');//升级操作
    Route::patch('/customer/superVip/{superVip}', 'CustomerController@del_superVip')->name('customer.del_superVip');//取消超级vip
    Route::get('/customer/relation', 'CustomerController@relation')->name('customer.relation');//用户关系
    Route::get('/customer/treeShow/{customer}', 'CustomerController@treeShow')->name('customer.treeShow');//用户树形图
    Route::get('/customer/treeData/{customer}', 'CustomerController@treeData')->name('customer.treeData');//异步数据
    Route::get('/customer/setVipView/{customer}', 'CustomerController@setVipView')->name('customer.setVipView');//升级vip页面
    Route::put('/customer/setVip/{customer}', 'CustomerController@setVip')->name('customer.setVip');//升级vip页面
    Route::post('/customer/changeCode', 'CustomerController@changeCode')->name('customer.changeCode');
    Route::get('/customer/export', 'CustomerController@export')->name('customer.export');//excel导出
    Route::patch('/customer/change_attr', 'CustomerController@change_attr')->name('customer.change_attr');//修改用户是否免登
    Route::patch('/customer/Vip/{superVip}', 'CustomerController@del_Vip')->name('customer.del_Vip');//取消vip


    // 用户身份
    Route::patch('/identity/delete_all', 'IdentityController@delete_all')->name('identity.delete_all');
    Route::resource('identity', 'IdentityController');

    // 用户爱好
    Route::patch('/hobby/delete_all', 'HobbyController@delete_all')->name('hobby.delete_all');
    Route::resource('hobby', 'HobbyController');

    //提现审核
    Route::get('/withdraw', 'WithdrawController@index')->name('withdraw.index');
    Route::get('/withdraw/export', 'WithdrawController@export')->name('withdraw.export');//提现列表导出
    Route::get('/withdraw/OneKey', 'WithdrawController@OneKey')->name('withdraw.OneKey');//首页列表一键审核
    Route::get('/withdraw/NowExcept', 'WithdrawController@NowExcept')->name('withdraw.NowExcept');//导出当前页面

//    Route::get('/withdraw/{withdraw}', 'WithdrawController@show')->name('withdraw.show');
    Route::patch('/withdraw', 'WithdrawController@update')->name('withdraw.update');
    Route::get('/withdraw/record/{withdraw}', 'WithdrawController@record')->name('withdraw.record');//提现记录列表
    Route::post('/withdraw/single/{withdraw}', 'WithdrawController@single')->name('withdraw.single');//单个审核
    Route::get('/withdraw/check', 'WithdrawController@check')->name('withdraw.check');//获取待审核订单信息

});