<?php

namespace App\Middleware;

class AuthMiddleware {
    public static function isAuthenticated() {
        // Kiểm tra session hoặc trạng thái đăng nhập
        if (!isset($_SESSION['user'])) {
            // Nếu chưa đăng nhập, chuyển hướng tới trang đăng nhập
            header('Location: /login');
            exit();
        }
    }
}