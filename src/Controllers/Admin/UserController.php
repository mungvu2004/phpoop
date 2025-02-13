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
    public function account() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['username'];
            $password = $_POST['password'];
            // echo $name . ' + ' . $password;
            $account = $this->user->login($name, $password);

            if($account == null || count($account) >= 2) {
                $_SESSION['msg'] = 'Username hoặc password không đúng';
            } else {
                if($account[0]['role'] == 'admin') {
                    $_SESSION['admin'] = $account;
                    $_SESSION['msg'] = 'Đã đăng nhập thành công với quyền admin';
                    return view('admin.layouts.main');
                } else {
                    $_SESSION['msg'] = 'Đã đăng nhập thành công';
                    // header('Location: /client/home.php');
                    exit;
                }
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