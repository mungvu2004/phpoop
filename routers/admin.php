<?php

use App\Controllers\Admin\UserController;
use App\Controllers\Admin\ReviewController;
use App\Controllers\Admin\ProductController;
use App\Controllers\Admin\CouponController;
use App\Controllers\Admin\OrderController;
use App\Controllers\Admin\OrderDetailController;
use App\Controllers\Admin\PaymentController;
use App\Controllers\Admin\CartController;
use App\Controllers\Admin\DashBoardController;

$router->mount('/admin', function() use ($router) {
    // $router->get('/', DashBoardController::class . '@index');
    $router->get('/', DashBoardController::class . '@index');
    $router->get('/dashboard/sale', DashBoardController::class . '@getSaleData');
    $router->post('/users', UserController::class . '@account');
    $router->post('/users/testUploadFile', UserController::class . '@testUploadFile');
});

$router->mount('/login', function() use ($router) {
    $router->get('/', function() {
        return view('elements.dashboard-login');
    });
    $router->post('/register', UserController::class . '@signUp');
});
$router->mount('/coupon', function() use ($router) {
    $router->get('/', CouponController::class . '@index');
    $router->post('/create', CouponController::class . '@create');
    $router->post('/store', CouponController::class . '@store');
    $router->post('/edit/{id}', CouponController::class . '@edit');
    $router->post('/update/{id}', CouponController::class . '@update');
    $router->post('/delete/{id}', CouponController::class . '@delete');
});
$router->mount('/admin/contact', function() use ($router) {
    $router->get('/', UserController::class . '@index');
    $router->post('/delete/{id}', UserController::class . '@delete');
    $router->get('/edit/{id}', UserController::class . '@edit');
});
$router->mount('/admin/product', function() use ($router) {
    $router->get('/', ProductController::class . '@index');
    $router->post('/create', ProductController::class . '@create');
    $router->get('/show/{id}', ProductController::class . '@show');
    $router->post('/store', ProductController::class . '@store');
    $router->post('/edit/{id}', ProductController::class . '@edit');
    $router->post('/update/{id}', ProductController::class . '@update');
    $router->post('/delete/{id}', ProductController::class . '@delete');
});

$router->mount('/admin/cart', function() use ($router) {
    $router->get('/', CartController::class . '@index');
    $router->get('/create', CartController::class . '@create');
    $router->post('/store', CartController::class . '@store');
    $router->post('/edit/{id}', CartController::class . '@edit');
    $router->post('/update/{id}', CartController::class . '@update');
    $router->post('/delete/{id}', CartController::class . '@delete');
});

$router->mount('/admin/order', function() use ($router) {
    $router->get('/', OrderController::class . '@index');
    $router->get('/create', OrderController::class . '@create');
    $router->post('/store', OrderController::class . '@store');
    $router->get('/edit/{id}', OrderController::class . '@edit');
    $router->post('/update/{id}', OrderController::class . '@update');
    $router->post('/delete/{id}', OrderController::class . '@delete');
});

$router->mount('/payment', function() use ($router) {
    $router->get('/', PaymentController::class . '@index');
    $router->get('/create', PaymentController::class . '@create');
    $router->post('/store', PaymentController::class . '@store');
    $router->get('/edit/{id}', PaymentController::class . '@edit');
    $router->post('/update/{id}', PaymentController::class . '@update');
    $router->get('/delete/{id}', PaymentController::class . '@delete');
});
