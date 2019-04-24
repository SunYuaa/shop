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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//商品列表
Route::get('/goods/goodsList','GoodsController@goodsList');   //商品列表
Route::get('/goods/goodsDetail','GoodsController@goodsDetail');   //商品详情 浏览次数
Route::get('/goods/goodsSort','GoodsController@getSort');   //浏览次数排序
Route::get('/goods/goodsHistory','GoodsController@viewHistory');   //浏览历史记录

//购物车
Route::get('/cart','CartController@index');  //购物车首页
Route::get('/cart/add/{goods_id?}','CartController@add');  //添加至购物车

Route::get('/order/create','OrderController@create');  //创建订单
Route::get('/order/list','OrderController@OrderList');  //订单列表
Route::get('/order/payStatus','OrderController@payStatus');  //查询订单支付状态

Route::get('/pay/weixin','Weixin\PayController@pay');  //微信支付
Route::post('/pay/weixin/notify','Weixin\PayController@notify');  //支付成功回调

Route::get('/pay/success','Weixin\PayController@paySuccess');  //微信支付成功

//微信JSSDk测试
Route::get('/wx/js/jssdk','Weixin\JssdkController@jsTest');  //Jssdk测试
Route::get('/wx/js/getImg','Weixin\JssdkController@getImg');  //Jssdk上传的图

//微信回复图文消息
Route::get('/wx/valid','Weixin\WxController@valid');
Route::post('/wx/valid','Weixin\WxController@event');


