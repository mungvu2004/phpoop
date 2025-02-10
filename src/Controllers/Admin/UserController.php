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

    public function index()
    {
        $title = 'Trang danh sách người dùng';
        $data = $this->user->findALL();

        return view(
            'admin.users.index',
            compact('title', 'data')
            // [
            //     'title' => $title,
            //     'data' => $data
            // ]
        );
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