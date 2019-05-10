<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Router;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Router::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
Route::post('/imageUploader', 'Api\UploaderController@imageUploader'); // 接口 done
Route::post('/fileUploader', 'Api\UploaderController@fileUploader'); // 文件上传 4.17
Route::post('/imageUploaderBase64', 'Api\UploaderController@imageUploaderBase64');
Route::any('/address', 'Api\AddressController@address');
Route::get('/adminData', 'Api\DataController@adminInfo');
Route::get('/qrcode/{passport_id}', 'Api\PublicController@qrcode');
Route::get('/detailQrcode/{passport_id}/{goods_id}', 'Api\PublicController@detailCode');
Route::get('/system/announcement', 'Api\PublicController@announcement');
Route::get('/system/rewardRate', 'Api\PublicController@rewardRate');




//不需要登录的路由写在这里
Route::group(['namespace' => 'Api'], function (Router $router) {
    // 用户模块
    $router->group(['prefix' => 'user'], function (Router $router){
        $router->post('userRegister','UserController@userRegister'); // 用户注册 接口done
        $router->post('userLogin','UserController@userLogin'); // 用户登录 接口done
        $router->post('changePassword','UserController@changePassword'); // 修改密码 接口done
        $router->post('forgetPassword','UserController@forgetPassword'); // 找回密码 接口done
    });
    // 公共模块
    $router->group(['prefix' => 'public'], function (Router $router){
        $router->post('sendVerify','PublicController@sendVerify'); // 发送验证码 接口done
        $router->post('checkVerify','PublicController@checkVerify'); // 验证验证码 接口done
        $router->post('getRegion','PublicController@getRegion'); // 获得州地址 接口done
        $router->post('getTa','PublicController@getTa'); // 获得市地址 接口done
        $router->post('getDistrict','PublicController@getDistrict'); // 获得地区地址 接口done
    });
    // 租赁市场
    $router->group(['prefix' => 'house'], function (Router $router){
        $router->post('houseList', 'HouseController@houseList'); // 房屋主档列表  // 接口 done
        $router->post('houseDetail','HouseController@houseDetail'); // 房屋主档具体信息 // 接口 done
    });
    // 操作员模块
    $router->group(['prefix' => 'operator'], function (Router $router){
        $router->post('login','OperatorController@login'); // 操作员登录 3.28
    });
});


//需要登录的路由写在这里
Route::group(['namespace' => 'Api','middleware' => 'CheckLogin',], function (Router $router) {
    // 用户模块
    $router->group(['prefix' => 'user'], function (Router $router){
        $router->post('getUserRoleId','UserController@getUserRoleId'); // 用户获得各角色下的角色id 3.27 // 接口done
        $router->post('becomeLandlord','UserController@becomeLandlord'); // 成为房东 4.16 // 接口done
        $router->post('becomeProviders','UserController@becomeProviders'); // 成为服务商 4.16 // 接口done
        $router->post('becomeTenement','UserController@becomeTenement'); // 成为租客 4.16 // 接口done
        $router->post('updateHeadImg','UserController@updateHeadImg'); // 更新头像 4.17 // 接口done
        $router->post('addPhone','UserController@addPhone'); // 增加手机 4.17 //接口done
        $router->post('addEmail','UserController@addEmail'); // 增加邮箱 4.17  // 接口done
    });
    //房屋主档系统
    $router->group(['prefix' => 'house'], function (Router $router) {
        $router->post('addHouseList', 'HouseController@addHouseList'); // 添加房屋主档 // 接口done
        $router->post('houseListPut','HouseController@houseListPut'); // 房屋主档上架 3.21 // 接口done
        $router->post('houseListDown','HouseController@houseListDown'); // 房屋主档上架 4.23 // 接口done
        $router->post('getSelfHouseList','HouseController@getSelfHouseList'); // 获得房屋主档信息列表 3.25 // 接口done
        $router->post('getHouseGroupDetail','HouseController@getHouseGroupDetail'); // 获得房屋主档信息 房东编辑用 3.25
        $router->post('editHouseList', 'HouseController@editHouseList'); // 修改房屋主档 3.22 // 接口done
        $router->post('deleteHouseList', 'HouseController@deleteHouseList'); // 删除房屋主档 3.27
        $router->post('addWatchList', 'HouseController@addWatchList'); // 租户增加看房收藏 4.27 // 接口done
        $router->post('deleteWatchList', 'HouseController@deleteWatchList'); // 租户取消看房收藏 4.27 // 接口done
        $router->post('getHouseList','HouseController@getHouseList'); // 租户获得房屋主档信息列表 4.27 // 接口done
        $router->post('getWatchList','HouseController@getWatchList'); // 租户获得关注主档信息列表 4.27 // 接口done
        $router->post('selectSelfHouseList','HouseController@selectSelfHouseList'); // 获得房屋主档信息列表 3.25 // 接口done
    });
    // 租房系统
    $router->group(['prefix' => 'rent'], function (Router $router){
        $router->post('rentApplication', 'RentController@rentApplication'); // 租房申请 3.21
        $router->post('rentApplicationOutAdd', 'RentController@outRentApplicationAdd'); // 租户租房申请（非本平台） 3.26
        $router->post('rentApplicationOutInformation', 'RentController@outRentApplicationInformation'); // 租户租房申请（非本平台）信息 3.26
        $router->post('rentApplicationOutList', 'RentController@rentApplicationOutList'); // 租户租房申请（非本平台）列表 3.26
        $router->post('rentApplicationOutEdit', 'RentController@rentApplicationOutEdit'); // 租户租房申请（非本平台）编辑 3.27
        $router->post('rentApplicationOutDelete', 'RentController@rentApplicationOutDelete'); // 租户租房申请（非本平台）删除 3.27
        $router->post('rentHouseApplicationList', 'RentController@rentHouseApplicationList'); // 租户租房申请列表（房东查看） 3.30 // 接口done
        $router->post('rentTenementApplicationList', 'RentController@rentTenementApplicationList'); // 租户租房申请列表（租户查看） 3.30// 接口done
        $router->post('rentTenementApplicationDetail', 'RentController@rentTenementApplicationDetail'); // 租户租房申请详情（租户查看） 3.30
        $router->post('rentContactAdd','RentController@rentContactAdd'); // 添加租约 4.1
        $router->post('rentContactList','RentController@rentContactList'); // 租约列表 4.13
        $router->post('rentContactDetail', 'RentController@rentContactDetail'); // 租约详情 4.13
    });
    // 租户系统
    $router->group(['prefix' => 'tenement'], function (Router $router) {
        $router->post('addTenementInformation', 'TenementController@addTenementInformation'); // 添加租户信息 3.21 接口done
        $router->post('getTenementSelfInformation', 'TenementController@getTenementSelfInformation'); // 获得租户信息 3.22 接口done
        $router->post('editTenementInformation', 'TenementController@editTenementInformation'); // 修改租户信息 3.22 接口done
        $router->post('deleteTenementInformation', 'TenementController@deleteTenementInformation'); // 删除租户信息 3.27
    });
    // 房东管理
    $router->group(['prefix' => 'landlord'], function (Router $router) {
        $router->post('addLandlordInformation', 'LandlordController@addLandlordInformation'); // 添加房东联系人 3.21 //接口done
        $router->post('getLandlordList','LandlordController@getLandlordList'); // 房东获得当前已经存入的房东联系人列表 3.22 //接口done
        $router->post('getLandlordInformation', 'LandlordController@getLandlordInformation'); // 获得房东联系人信息 3.22  // 接口 done
        $router->post('editLandlordInformation', 'LandlordController@editLandlordInformation'); // 修改房东联系人 3.22 // 接口 done
        $router->post('deleteLandlordInformation', 'LandlordController@deleteLandlordInformation'); // 删除房东联系人 3.27
        $router->post('watchTenementInformation','LandlordController@watchTenementInformation'); // 房东查看租户信息 5.5
    });
    // 服务商管理
    $router->group(['prefix' => 'providers'], function (Router $router) {
        $router->post('addProvidersInformation', 'ProvidersController@addProvidersInformation'); // 添加服务商主体 3.21 // 接口done
        $router->post('getProvidersSelfList', 'ProvidersController@getProvidersSelfList'); // 获得服务商主体列表 3.25 // 接口done
        $router->post('getProvidersInformation', 'ProvidersController@getProvidersInformation'); // 获得房东联系人信息 3.25 // 接口done
        $router->post('editProvidersInformation', 'ProvidersController@editProvidersInformation'); // 修改服务商主体 3.22 // 接口done
        $router->post('deleteProvidersInformation', 'ProvidersController@deleteProvidersInformation'); // 删除服务商主体 3.27
        $router->post('getProvidersList','ProvidersController@getProvidersList'); // 获得所有服务商主体
    });
    // 操作员管理
    $router->group(['prefix' => 'operator'], function (Router $router) {
        $router->post('checkOperatorAccount', 'OperatorController@checkOperatorAccount'); // 查询操作员账号 3.29
        $router->post('addOperatorInformation', 'OperatorController@addOperatorInformation'); // 生成操作员 3.28
        $router->post('editOperatorInformation', 'OperatorController@editOperatorInformation'); // 编辑操作员 3.28
        $router->post('getOperatorList', 'OperatorController@getOperatorList'); // 获得操作员列表 3.29
        $router->post('changeOperatorStatus', 'OperatorController@changeOperatorStatus'); // 修改操作员是否禁用 3.29
    });
    // 服务商市场
    $router->group(['prefix' => 'providersMarket'], function (Router $router) {
        $router->post('landlordOrderAdd', 'ProvidersMarketController@landlordOrderAdd'); // 添加订单 4.2 // 接口done
        $router->post('getOrderList', 'ProvidersMarketController@getOrderList'); // 获得订单列表 4.2
        $router->post('getOrderDetail', 'ProvidersMarketController@getOrderDetail'); // 获得订单详情 4.2
        $router->post('tenderOrder', 'ProvidersMarketController@tenderOrder'); //  服务商报价 4.30
    });
    // 钥匙管理
    $router->group(['prefix' => 'key'], function (Router $router) {
        $router->post('keyAdd', 'KeyController@keyAdd'); // 添加钥匙 4.10
        $router->post('keyReturn', 'KeyController@keyReturn'); // 归还钥匙 4.10
        $router->post('keyList', 'KeyController@keyList'); // 钥匙列表 4.10
        $router->post('keyEdit', 'KeyController@keyEdit'); // 钥匙编辑 4.10
    });
    // 房屋检查
    $router->group(['prefix' => 'inspect'], function (Router $router) {
        $router->post('inspectAdd', 'InspectController@inspectAdd'); // 添加检查 4.12
        $router->post('inspectList', 'InspectController@inspectList'); // 检查列表 5.7
        $router->post('inspectDetail', 'InspectController@inspectDetail'); // 检查详细 5.10
        $router->post('inspectItem', 'InspectController@inspectItem'); // 检查项目 5.7
        $router->post('inspectEdit', 'InspectController@inspectEdit'); // 检查编辑 5.10
        $router->post('inspectDeleteRoom', 'InspectController@inspectDeleteRoom'); // 删除房间 5.10
        $router->post('inspectDeleteItem', 'InspectController@inspectDeleteItem'); // 删除项目 5.10
        $router->post('inspectGroupRoom', 'InspectController@inspectGroupRoom'); // 批量检查 房屋列表 5.7
        $router->post('inspectCheck','InspectController@inspectCheck'); // 房东开始检查 5.9
    });
});


//授权登陆

Route::group(['middleware' => ['auth']], function ($api) {
    Route::get('redirect/{service}','Auth\SocialAuthController@redirectToProvider');
    Route::get('callback/{service}','Auth\SocialAuthController@handleProviderCallback');
});


Route::get('/home', 'HomeController@index')->name('home');

//需要登录的路由写在这里
Route::group(['namespace' => 'Api','middleware' => 'CheckOperatorLogin'], function (Router $router) {
    // 操作员模块

});