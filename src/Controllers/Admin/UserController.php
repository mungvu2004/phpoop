<?php

namespace App\Controllers\Admin;

use App\Models\User;

class UserController
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
        
        
    }
}