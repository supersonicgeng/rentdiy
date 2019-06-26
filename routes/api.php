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
        $router->post('getLat','PublicController@getLat'); // 获得地区地址 接口done
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
    // 测试
    $router->group(['prefix' => 'test'], function (Router $router){
        $router->get('test','TestController@test'); // 测试 pdf 5.28
        $router->post('testIM','ImController@sendMsg'); // 测试 pdf 5.28
        $router->post('getImInfo','ImController@getImInfo'); // 获得im 历史消息 pdf 5.28
    });
    $router->group(['prefix' => 'im'], function (Router $router) {
        $router->post('sendSystemMsg', 'ImController@sendSystemMsg'); // 发送系统消息 4.10
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
        $router->post('rentTenementApplicationAgree', 'RentController@rentTenementApplicationAgree'); // 同意申请3.30
        $router->post('rentTenementApplicationBackup', 'RentController@rentTenementApplicationBackup'); // 备用 3.30
        $router->post('rentTenementApplicationReject', 'RentController@rentTenementApplicationReject'); // 拒绝 3.30
        $router->post('rentContactAdd','RentController@rentContactAdd'); // 添加租约 4.1
        $router->post('rentContactList','RentController@rentContactList'); // 租约列表 4.13
        $router->post('rentTenementContractList','RentController@rentTenementContractList');
        $router->post('rentContractDetail', 'RentController@rentContractDetail'); // 租约详情 4.13
        $router->post('viewTenementInfo', 'RentController@viewTenementInfo'); // 查看证件 5.13
        $router->post('rentContactEffect', 'RentController@rentContactEffect'); // 租约生效 5.13
        $router->post('tenementScore', 'RentController@tenementScore'); // 租户打分 5.13
        $router->post('changeRentFee', 'RentController@changeRentFee'); // 租金调整 5.13
        $router->post('rentSuspend', 'RentController@rentSuspend'); // 租金中止 5.13
        $router->post('rentSuspendSure', 'RentController@rentSuspendSure'); // 租金中止确认 5.13
    });
    // 租户系统
    $router->group(['prefix' => 'tenement'], function (Router $router) {
        $router->post('addTenementInformation', 'TenementController@addTenementInformation'); // 添加租户信息 3.21 接口done
        $router->post('getTenementSelfInformation', 'TenementController@getTenementSelfInformation'); // 获得租户信息 3.22 接口done
        $router->post('editTenementInformation', 'TenementController@editTenementInformation'); // 修改租户信息 3.22 接口done
        $router->post('deleteTenementInformation', 'TenementController@deleteTenementInformation'); // 删除租户信息 3.27
        $router->post('houseScore', 'TenementController@houseScore'); // 房屋打分 3.27
        $router->post('getArrearsHouseList', 'TenementController@getArrearsHouseList'); // 房屋账单列表 3.27
        $router->post('getArrearsList', 'TenementController@getArrearsList'); // 账单列表 3.27
    });
    // 房东管理
    $router->group(['prefix' => 'landlord'], function (Router $router) {
        $router->post('addLandlordInformation', 'LandlordController@addLandlordInformation'); // 添加房东联系人 3.21 //接口done
        $router->post('getLandlordList','LandlordController@getLandlordList'); // 房东获得当前已经存入的房东联系人列表 3.22 //接口done
        $router->post('getLandlordInformation', 'LandlordController@getLandlordInformation'); // 获得房东联系人信息 3.22  // 接口 done
        $router->post('editLandlordInformation', 'LandlordController@editLandlordInformation'); // 修改房东联系人 3.22 // 接口 done
        $router->post('deleteLandlordInformation', 'LandlordController@deleteLandlordInformation'); // 删除房东联系人 3.27
        $router->post('watchTenementInformation','LandlordController@watchTenementInformation'); // 房东查看租户信息 5.5
        $router->post('orderList','LandlordController@orderList'); // 查看订单列表
        $router->post('tenderList','LandlordController@tenderList'); // 查看订单报价
        $router->post('tenderAccept','LandlordController@tenderAccept'); // 订单确认
        $router->post('orderStop','LandlordController@orderStop'); // 订单中止
        $router->post('getTenementList','LandlordController@getTenementList'); // 获得租户列表
        $router->post('tenementNote','LandlordController@tenementNote'); // 租户行为记录
        $router->post('tenementManage','LandlordController@tenementManage'); // 租户管理
        $router->post('getTenementInfo','LandlordController@getTenementInfo'); // 租约生成时获取租户信息
        $router->post('getProvidersList','LandlordController@getProvidersList'); // 获取服务商列表
        $router->post('getProvidersDetail','LandlordController@getProvidersDetail'); // 获取服务商列表
    });
    // 服务商管理
    $router->group(['prefix' => 'providers'], function (Router $router) {
        $router->post('addProvidersInformation', 'ProvidersController@addProvidersInformation'); // 添加服务商主体 3.21 // 接口done
        $router->post('getProvidersSelfList', 'ProvidersController@getProvidersSelfList'); // 获得服务商主体列表 3.25 // 接口done
        $router->post('getProvidersInformation', 'ProvidersController@getProvidersInformation'); // 获得房东联系人信息 3.25 // 接口done
        $router->post('editProvidersInformation', 'ProvidersController@editProvidersInformation'); // 修改服务商主体 3.22 // 接口done
        $router->post('deleteProvidersInformation', 'ProvidersController@deleteProvidersInformation'); // 删除服务商主体 3.27
        $router->post('getProvidersList','ProvidersController@getProvidersList'); // 获得所有服务商主体
        $router->post('getOrderList','ProvidersController@getOrderList'); // 获得所有订单列表
        $router->post('getLookOrderList','ProvidersController@getLookOrderList'); // 获得看房订单列表
        $router->post('getTenementOrderList','ProvidersController@getTenementOrderList'); // 获得租户调查订单列表
        $router->post('getInspectOrderList','ProvidersController@getInspectOrderList'); // 获得看房订单列表
        $router->post('getRepairOrderList','ProvidersController@getRepairOrderList'); // 获得看房订单列表
        $router->post('getLitigationOrderList','ProvidersController@getLitigationOrderList'); // 获得看房订单列表
        $router->post('getLookOrderDetail','ProvidersController@getLookOrderDetail'); // 获得看房订单详情
        $router->post('getTenementOrderDetail','ProvidersController@getTenementOrderDetail'); // 获得租户调查订单详情
        $router->post('getRepairOrderDetail','ProvidersController@getRepairOrderDetail'); // 获得维修订单详细
        $router->post('getLitigationOrderDetail','ProvidersController@getLitigationOrderDetail'); // 获得诉讼订单详细
        $router->post('tenementReview','ProvidersController@tenementReview'); // 服务商给处理租户调查
        $router->post('lookOrder','ProvidersController@lookOrder'); // 服务商给处理租户调查
        $router->post('landlordScore','ProvidersController@landlordScore'); // 服务商给房东打分
        $router->post('landlordScore','ProvidersController@landlordScore'); // 服务商给房东打分
    });
    // 操作员管理
    $router->group(['prefix' => 'operator'], function (Router $router) {
        $router->post('checkOperatorAccount', 'OperatorController@checkOperatorAccount'); // 查询操作员账号 3.29
        $router->post('addOperatorInformation', 'OperatorController@addOperatorInformation'); // 生成操作员 3.28
        $router->post('editOperatorInformation', 'OperatorController@editOperatorInformation'); // 编辑操作员 3.28
        $router->post('getOperatorList', 'OperatorController@getOperatorList'); // 获得操作员列表 3.29
        $router->post('getOperatorDetail', 'OperatorController@getOperatorDetail'); // 获得操作员详细 3.29
        $router->post('changeOperatorStatus', 'OperatorController@changeOperatorStatus'); // 修改操作员是否禁用 3.29
        $router->post('getHouseList', 'OperatorController@getHouseList'); // 房东检查操作员获取列表 4.10
        $router->post('getOrderList', 'OperatorController@getOrderList'); // 服务商检查操作员获取列表 4.10
    });
    // 服务商市场
    $router->group(['prefix' => 'providersMarket'], function (Router $router) {
        $router->post('landlordOrderAdd', 'ProvidersMarketController@landlordOrderAdd'); // 添加订单 4.2 // 接口done
        $router->post('getOrderList', 'ProvidersMarketController@getOrderList'); // 获得订单列表 4.2
        $router->post('getOrderDetail', 'ProvidersMarketController@getOrderDetail'); // 获得订单详情 4.2
        $router->post('tenderOrder', 'ProvidersMarketController@tenderOrder'); //  服务商报价 4.30
        $router->post('orderScore','ProvidersMarketController@orderScore'); // 评价服务商 5.16
        $router->post('tenderRepairOrder', 'ProvidersMarketController@tenderRepairOrder'); //  服务商维修报价 4.30
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
        $router->post('inspectConfirm','InspectController@inspectConfirm'); // 检查确认信息 5.10
        $router->post('inspectRecord','InspectController@inspectRecord'); // 检查确认信息 5.10
        $router->post('landlordCheckDetail','InspectController@landlordCheckDetail'); // 获取信息 5.11
        $router->post('addIssuesBatch','InspectController@addIssuesBatch'); // 批量增加维修单 5.11
        $router->post('landlordConfirm','InspectController@landlordConfirm'); // 房东确认检查 5.11
        $router->post('issueRecord','InspectController@issueRecord'); // 待维修记录 5.10
        $router->post('addIssues','InspectController@addIssues'); // 增加维修单 5.11
    });
    // 押金管理
    $router->group(['prefix' => 'bond'], function (Router $router) {
        $router->post('bondList', 'BondController@bondList'); // 押金列表 5.27
        $router->post('bondArrearsList', 'BondController@bondArrearsList'); // 押金欠款列表 5.27
        $router->post('bondLodgedList', 'BondController@bondLodgedList'); // 押金上缴列表 5.27
        $router->post('bondRefundList', 'BondController@bondRefundList'); // 押金退缴列表 5.27
        $router->post('bondTransformList', 'BondController@bondTransformList'); // 押金转移列表 5.27
        $router->post('addBondLodgedDate', 'BondController@addBondLodgedDate'); // 押金上缴日期 5.27
        $router->post('addBondLodgedSn', 'BondController@addBondLodgedSn'); // 押金上缴编号 5.27
        $router->post('refundInfo', 'BondController@refundInfo'); // 押金退缴信息 5.28
        $router->post('refundBond', 'BondController@refundBond'); // 押金退缴 5.28
        $router->post('refundBondConfirm', 'BondController@refundBondConfirm'); // 押金退缴确认 5.28
        $router->post('refundBondDate', 'BondController@refundBondDate'); // 押金退缴时间 5.28
        $router->post('transferBond', 'BondController@transferBond'); // 押金退缴 5.28
        $router->post('transferBondConfirm', 'BondController@transferBondConfirm'); // 押金退缴确认 5.28
        $router->post('transferBondDate', 'BondController@transferBondDate'); // 押金退缴时间 5.28
    });
    // 欠款管理
    $router->group(['prefix' => 'fee'], function (Router $router) {
        $router->post('feeAdd', 'FeeController@feeAdd'); // 添加费用单 4.10
        $router->post('getContractList', 'FeeController@getContractList'); // 获得租约列表 4.10
        $router->post('sendNotice', 'FeeController@sendNotice'); // 发布通知 4.10
        $router->post('arrearsList', 'FeeController@arrearsList'); // 追欠款列表 4.10
        $router->post('arrearsDetail', 'FeeController@arrearsDetail'); // 追欠款详情 4.10
        $router->post('feeList', 'FeeController@feeList'); // 费用单列表 4.10
        $router->post('feeDetail', 'FeeController@feeDetail'); // 费用单详情 4.10
        $router->post('cashList', 'FeeController@cashList'); // 现金收据列表 4.10
        $router->post('cashDetail', 'FeeController@cashDetail'); // 现金收据详情 4.10
        $router->post('cashPay', 'FeeController@cashPay'); // 现金收据冲账 4.10
        $router->post('bankCheck', 'FeeController@bankCheck'); // 银行对账上传CSV文件 4.10
        $router->post('bankCheckList', 'FeeController@bankCheckList'); // 银行对账列表 4.10
        $router->post('bankCheckDetail', 'FeeController@bankCheckDetail'); // 银行对账详情 4.10
        $router->post('bankCheckTenementInfo', 'FeeController@bankCheckTenementInfo'); // 银行对账详情 租户信息 4.10
        $router->post('matchData', 'FeeController@matchData'); // 银行对账符合费用单 4.10
        $router->post('confirmMatchCheck', 'FeeController@confirmMatchCheck'); // 银行对账确认符合费用单 4.10
        $router->post('unMatchData', 'FeeController@unMatchData'); // 银行对账符合费用单 4.10
        $router->post('balanceAdjust', 'FeeController@balanceAdjust'); // 银行对账余额调整 4.10
        $router->post('balanceAdjustConfirm', 'FeeController@balanceAdjustConfirm'); // 银行对账余额调整确认 4.10
        $router->post('historyList', 'FeeController@historyList'); // 银行对账历史账单 4.10
        $router->post('unMatchList', 'FeeController@unMatchList'); // 银行对账未对账单 4.10
        $router->post('bankAdjust', 'FeeController@bankAdjust'); // 银行对账手工调整 4.10
        $router->post('bankAdjustConfirm', 'FeeController@bankAdjustConfirm'); // 银行对账手工调整确认 4.10
        $router->post('bankCheckMatch', 'FeeController@bankCheckMatch'); // 银行对账详情 确认租户4.10
        $router->post('providersFeeList', 'FeeController@providersFeeList'); // 服务商费用单列表 4.10
        $router->post('providersFeeAdd', 'FeeController@providersFeeAdd'); // 服务商添加费用单 4.10
        $router->post('providersFeeDetail', 'FeeController@providersFeeDetail'); // 服务商费用单详情 4.10
    });
    // im系统
    $router->group(['prefix' => 'im'], function (Router $router) {
        $router->post('sendMsg', 'ImController@sendMsg'); // 发送消息 4.10
        $router->post('getImList', 'ImController@getImList'); // 获取列表 4.10
        $router->post('getImInfo', 'ImController@getImInfo'); // 获取消息 4.10
        $router->post('getSystemInfo', 'ImController@getSystemInfo'); // 获取系统消息 4.10
        $router->post('getImUserInfo', 'ImController@getImUserInfo'); // 获取消息发送人信息 4.10
        $router->post('searchFriend', 'ImController@searchFriend'); // 搜索好友 4.10
        $router->post('addFriend', 'ImController@addFriend'); // 加好友 4.10
        $router->post('getFriendList', 'ImController@getFriendList'); // 获取好友列表 4.10
        $router->post('searchHistory', 'ImController@searchHistory'); // 搜索历史 4.10
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
    // 欠款管理
    $router->group(['prefix' => 'operator'], function (Router $router) {


    });
});