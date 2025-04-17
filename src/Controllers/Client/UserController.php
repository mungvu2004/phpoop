<?php

namespace App\Controllers\Client;

use App\Models\User;
use App\Models\UserAddress;
use App\Controller;

class UserController extends Controller
{
    private User $user;
    private UserAddress $userAddress;
    public function __construct()
    {
        $this->user = new User();
        $this->userAddress = new UserAddress();
    }

    public function index()
    {
        if (!isset($_SESSION['user'])) {
            return view('client.dashboard');
        } else {
            $users = $_SESSION['user'];
            $userDetail = $this->user->detailUser($users['id']);
            return view(
                'client.account',
                compact('users', "userDetail")
            );
        }
    }
    public function update()
    {
        $user = $_SESSION['user'];
        $nameAdd = $_POST['address_name'];
        $nameRec = $_POST['recipient_name'];
        $phone = $_POST['phone_number'];
        $address = $_POST['address_line1'];
        $city = $_POST['city'];
        $country = $_POST['country'];
        $is_default = 1;
        $image = $_FILES['image_url'];

        $data = [
            'user_id' => $user['id'],
            'address_name' => $nameAdd,
            'recipient_name' => $nameRec,
            'phone_number' => $phone,
            'address_line1' => $address,
            'city' => $city,
            'country' => $country,
            'is_default' => $is_default,
        ];
        $img_path = '';
        if (empty($image) || $image['error'] != UPLOAD_ERR_OK) {
            $_SESSION['errors'] = ["Vui lòng chọn file ảnh hợp lệ!"];
            header("Location: /account");
            exit;
        } else {
            try {
                $img_path = $this->uploadFile($image, 'users');
                $data['image_url'] = $img_path;
            } catch (\Exception $e) {
                $_SESSION['errors'] = ["Lỗi khi upload file: " . $e->getMessage()];
                header("Location: /account");
                exit;
            }
        }
        try {
            $userId = $this->userAddress->countAddress($user['id']);
            if ($userId > 0) {
                $this->userAddress->updateAddress($user['id'], $data);
                $_SESSION['success'] = ['Đã cập nhập thành công thông tin địa chỉ'];
            } else {
                $this->userAddress->insert($data);
                $_SESSION['success'] = ['Đã thêm thành công thông tin địa chỉ'];
            }
        } catch (\Exception $e) {
            $_SESSION['errors'] = ['Lỗi không cập nhập được thông tin' . $e->getMessage()];
        }
        header("Location: /account");
        exit;
    }
}
