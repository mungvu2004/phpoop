<?php

use App\Controllers\Admin\UserController;
$router->get('/', function() {
    require __DIR__ . '/../views/client/home.php'; // Thay 'home' bằng trang bạn muốn hiển thị
});

$router->mount('/admin', function() use ($router) {
    $router->get('/', function() {
        return view('admin.dashboard');
    });
    // $router->get('/', function() {
    //     return view('elements.dashboard-login');
    // });
    
    $router->post('/users', UserController::class . '@account');

    $router->post('/users/testUploadFile', UserController::class . '@testUploadFile');
});

$router->mount('/login', function() use ($router) {
    $router->get('/', function() {
        return view('elements.dashboard-login');
    });
    $router->post('/register', UserController::class . '@signUp');
});