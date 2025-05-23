<?php

use App\Controllers\Client\HomeController;
use App\Controllers\Client\ProductController;
use App\Controllers\Client\CategoryController;
use App\Controllers\Client\CouponController;
use App\Controllers\Client\OrderController;
use App\Controllers\Client\OrderDetailController;
use App\Controllers\Client\PaymentController;
use App\Controllers\Client\ReviewController;
use App\Controllers\Client\UserController;
use App\Middleware\AuthMiddleware;
$router->before('GET|POST', '/(account|order|payment|review)(/.*)?', function() {
    AuthMiddleware::isAuthenticated();
});
$router->mount('', function() use ($router) {
    $router->get('/', HomeController::class . '@index');
});
$router->get('/about', function() {
    return view('client.about-us');
});
$router->get('/policy', function() {
    return view('client.privacy-policy');
});
$router->get('/shipping', function() {
    return view('client.shipping-policy');
});
$router->get('/payment', function() {
    return view('client.payment-methods');
});
$router->get('/return', function() {
    return view('client.return-policy');
});
$router->mount('/coupon', function() use ($router) {
    $router->get('/', CouponController::class . '@index');
    $router->get('/create', CouponController::class . '@create');
    $router->post('/store', CouponController::class . '@store');
    $router->get('/edit/{id}', CouponController::class . '@edit');
    $router->post('/update/{id}', CouponController::class . '@update');
    $router->get('/delete/{id}', CouponController::class . '@delete');
});
$router->mount('/products', function() use ($router) {
    $router->get('/', ProductController::class . '@listIndex');
    $router->get('/show/{id}', ProductController::class . '@show');
    $router->get('/search/{text}', ProductController::class . '@search');
});
$router->mount('/category', function() use ($router) {
    $router->get('/', CategoryController::class . '@index');
    $router->get('/create', CategoryController::class . '@create');
    $router->post('/store', CategoryController::class . '@store');
    $router->get('/edit/{id}', CategoryController::class . '@edit');
    $router->post('/update/{id}', CategoryController::class . '@update');
    $router->get('/delete/{id}', CategoryController::class . '@delete');
});
$router->mount('/account', function () use ($router) {
    $router->before('GET|POST', '.*', function() {
        AuthMiddleware::isAuthenticated();
    });
    $router->get('/', UserController::class . '@index');
    $router->post('/update', UserController::class .'@update');
});
$router->mount('/order', function() use ($router) {
    $router->before('GET|POST', '.*', function() {
        AuthMiddleware::isAuthenticated();
    });
    $router->get('/', OrderController::class . '@index');
    $router->get('/create', OrderController::class . '@create');
    $router->post('/store', OrderController::class . '@store');
    $router->get('/edit/{id}', OrderController::class . '@edit');
    $router->post('/update', OrderController::class . '@update');
    $router->post('/delete/{id}', OrderController::class . '@delete');
});
$router->mount('/order_detail', function() use ($router) {
    $router->get('/', OrderDetailController::class . '@index');
    $router->post('/create', OrderDetailController::class . '@create');
    $router->post('/update/{id}', OrderDetailController::class . '@update');
});
$router->mount('/payment', function() use ($router) {
    $router->before('GET|POST', '.*', function() {
        AuthMiddleware::isAuthenticated();
    });
    $router->get('/', PaymentController::class . '@index');
    $router->get('/create', PaymentController::class . '@create');
    $router->post('/processCOD', PaymentController::class . '@processCOD');
    $router->get('/edit/{id}', PaymentController::class . '@edit');
    $router->post('/update/{id}', PaymentController::class . '@update');
    $router->get('/delete/{id}', PaymentController::class . '@delete');
    $router->post('/vnpay', PaymentController::class . '@processVNPay');
});
$router->get('/payment/vnpay-return', PaymentController::class . '@vnpayReturn');
$router->mount('/review', function() use ($router) {
    $router->before('GET|POST', '.*', function() {
        AuthMiddleware::isAuthenticated();
    });
    $router->get('/', ReviewController::class . '@index');
    $router->get('/create', ReviewController::class . '@create');
    $router->post('/store', ReviewController::class . '@store');
    $router->get('/edit/{id}', ReviewController::class . '@edit');
    $router->post('/update/{id}', ReviewController::class . '@update');
    $router->get('/delete/{id}', ReviewController::class . '@delete');
});
$router->get('/news', function() {
    return view('client.news.news');
});

