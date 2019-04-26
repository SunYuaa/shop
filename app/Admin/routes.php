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
    $router->resource('/insertM', WxController::class); //素材添加
    $router->resource('/listM', WxController::class); //素材列表




});
