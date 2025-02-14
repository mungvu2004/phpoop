<?php

namespace App\Controllers\Admin;

use App\Models\User;
use App\Controller;

class UserController extends Controller
{
    private User $user;
    public function __construct()
    {
        $this->user = new User();
    }

    // public function testBaseModel()
    // {
    //     echo '<pre>';
    //     // print_r($this->user);
    //     // $newUserId = $this->user->insert($data);
    //     // echo $newUserId;
    //     $data = $this->user->find(1);
    //     print_r($data);
    // }
    private function role($role) {
        if ($role == 'admin') {
            $_SESSION['msg'] = 'Đã đăng nhập thành công với quyền admin.';
            return true;
        } else {
            $_SESSION['msg'] = 'Đã đăng nhập thành công.';
            return false;
        }
    }
    public function account() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Làm sạch và xác thực dữ liệu đầu vào
            $name = htmlspecialchars($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($name) || empty($password)) {
                $_SESSION['msg'] = 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.';
                return view('elements.login.login');
            }
    
            // Thực hiện đăng nhập
            $account = $this->user->login($name, $password);
    
            if ($account === null || count($account) != 1) {
                $_SESSION['msg'] = 'Tên đăng nhập hoặc mật khẩu không đúng.';
                return view('elements.login.login');
            }
            $role = $account[0]['role'];
            // Kiểm tra vai trò của người dùng
            if($this->role($role)) {
                $_SESSION['admin'] = $account[0];
                return view('admin.layouts.main');
            } else {
                $_SESSION['user'] = $account[0];
                return view('client.layouts.main');
            }
        }
        return view('elements.login.login');
    }
    public function testUploadFile()
    {
        try {
            $pathFile = $this->uploadFile($_FILES['avatar'], 'users');
            $_SESSION['msg'] = 'Upload file Thanh cong';

        } catch (\Throwable $error) {
            $this->logError($error->getMessage());
            $_SESSION['msg'] = 'Upload file That bai';
        }
        header('Location: /admin/users');
        exit;
    }
}