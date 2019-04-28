<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    $router->resource('/goods', GoodsController::class);
    $router->resource('/order', OrderController::class);

    $router->resource('/wxuser', WxuserController::class); //微信用户列表


//    Route::post('/listM','WxController@material');//素材列表
    Route::get('/insertM','WxController@index');//素材添加
    $router->resource('/listM', WxController::class);

    Route::get('/groups/index','WxuserController@index');
    Route::get('/groups/group','WxuserController@groups');
    $router->resource('/groups/group', WxuserController::class);



});
