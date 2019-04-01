<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Admin\LoginController@showLoginForm'); //网站根目录
/*后台路由*/
$route = Service('Route')->getAdminRoutes();
foreach ($route as $item) {
    if ($item['pid'] == \App\Permission::$TOP_CATE_PID) {
        continue;
    }
    if ($item['type'] == \App\Permission::$TYPE_ANY) {
        Route::any($item['name'], $item['module'] . '\\' . $item['method']);
    }
    if ($item['type'] == \App\Permission::$TYPE_GET) {
        Route::get($item['name'], $item['module'] . '\\' . $item['method']);
    }
    if ($item['type'] == \App\Permission::$TYPE_POST) {
        Route::post($item['name'], $item['module'] . '\\' . $item['method']);
    }
    if ($item['type'] == \App\Permission::$TYPE_PUT) {
        Route::put($item['name'], $item['module'] . '\\' . $item['method']);
    }
    if ($item['type'] == \App\Permission::$TYPE_DELETE) {
        Route::delete($item['name'], $item['module'] . '\\' . $item['method']);
    }
}

/*后台其他路由*/
Route::group(['prefix' => 'manage', 'namespace' => 'Admin'], function () {
    Route::get('/', 'IndexController@index2');
    Route::get('/index', 'IndexController@index');
    Route::get('/index2', 'IndexController@index2');
    Route::get('/info', 'IndexController@info');
    //登录页面
    Route::get('/login', 'LoginController@showLoginForm');
    Route::get('/test', 'LoginController@test');
    //登录方法
    Route::post('/loginAction', 'LoginController@login');
    //登出方法
    Route::get('/logoutAction', 'LoginController@logout');
    //修改密码
    Route::get('/editPassword', 'IndexController@editPassword');
    Route::post('/editPwdAction', 'IndexController@editPwdAction');
});

//Route::any('/imgGenerate', 'ToolsController@imgGenerate');
//Route::any('/water', 'ToolsController@water');
/*微信路由*/
Route::any('/wechat', 'WechatController@serve');
Route::any('/wechat/demo', 'WechatController@demo');

/*微信端*/
Route::group(['prefix' => 'wap'], function () {
    Route::get('/', 'Wap\IndexController@index'); //移动端首页
    Route::any('/test','Wap\IndexController@test');
    Route::get('/game/loading/{share_passport_id?}','Wap\GameController@loading'); // 游戏登录页面
    Route::get('/game/showloading/{share_passport_id}/{passport_id}','Wap\GameController@showloading'); // 游戏登录页面
    Route::any('/game/{share_passport_id?}/{passport_id}','Wap\GameController@index'); // 游戏开始 发送游戏初始设置给H5页面发送初始设置信息
    Route::any('/game/gameCount/{share_passport_id?}/{passport_id}','Wap\GameController@gameCount'); // 游戏倒计时
    Route::any('/game/gamepage/{share_passport_id?}/{passport_id}','Wap\GameController@game'); // 游戏开始 发送游戏初始设置给H5页面发送初始设置信息
    Route::any('/game/commit/{score}/{passport_id}/{share_passport_id}','Wap\GameController@commit'); // 游戏结束 推送信息
    Route::any('/gameInfo/{passport_id}/{user_id}','Wap\GameController@scoreInfo'); //游戏积分详情
    Route::any('/gameInfo/shared','Wap\GameController@shared'); //游戏积分详情
    Route::any('/game/rank','Wap\GameController@rank'); //游戏积分
});

Route::get('/test', 'TestController@index'); //测试路由
Route::get('/index/index', 'Index\IndexController@index');// 中间件跳转

/*游戏路由*/
