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

    public function testBaseModel()
    {
        echo '<pre>';
        print_r($this->user);
        // $newUserId = $this->user->insert([
        //     'username' => 'Ahihi',
        //     'password' => password_hash('12345678', PASSWORD_DEFAULT),
        //     'full_name' => 'VU XUAN MUNG',
        //     'email' => 'mxuan003$gmail.com', 
        //     'phone' => '0362413032',
        //     'address' => 'Thai Binh',

        // ]);
        // echo $newUserId;
    }
}