<?php

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');


/***
 * 后台路由
 */
Route::prefix('admin')->group(function () {

    Auth::routes();

    Route::middleware('auth')->namespace('Admin')->group(function () {
        //锁屏操作
        Route::post('editorUpload', 'PhotoController@editorUpload')->name('photo.editorUpload');//编辑器图片上传
        Route::get('lock', 'LockController@lock')->name('admin.lock');
        Route::post('/lock/login', 'LockController@login')->name('admin.lock.login');//锁屏后登陆
        Route::get('lockView', 'LockController@lockView')->name('admin.lockView');//锁定展示页
    });

    Route::middleware(['auth', 'sidebar', 'role'])->namespace('Admin')->group(function () {

        //后台首页
        Route::get('/', 'HomeController@index')->name('admin.index');

        //清除缓存
        Route::get('clear', 'HomeController@clear')->name('admin.clear');


        //上传图片和上传视频
        Route::post('photo', 'PhotoController@store')->name('photo.store');
        Route::post('video', 'PhotoController@video')->name('photo.video');


        //系统管理
        require 'admin/system.php';
        //管理员操作记录
        require 'admin/action_log.php';

        //平台设置
        require 'admin/config.php';

        //优惠券管理
        require 'admin/coupon.php';

        //报表管理
        require 'admin/report.php';
        //商品管理
   //     require 'admin/shop.php';

        //公共查询
   //     require 'admin/common.php';

        //订单管理
    //    require 'admin/order.php';

        //平台用户管理
     //   require 'admin/platform.php';

        //分佣管理
    //    require 'admin/profit.php';

        //物料管理
    //    require 'admin/material.php';

        //首页配置管理
     //   require 'admin/home.php';

        //搜索管理
     //   require 'admin/search.php';

        //消息推送
    //    require 'admin/message.php';

        //反馈和公告
     //   require 'admin/notice.php';

        //统计分析
//        require 'admin/statistical.php';

        //客服专区
     //   require 'admin/service.php';

        //运营位管理
    //    require 'admin/operate.php';

        //财务管理
     //   require 'admin/finance.php';




    });

});