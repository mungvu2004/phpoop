<?php

namespace App\Middleware;
class AuthMiddleware {
    public static function isAuthenticated() {
        if (!isset($_SESSION['admin']) && !isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }
    }
}