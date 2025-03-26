<?php
namespace App\Controllers\Admin;

use App\Models\Coupon;
use App\Controller;

class CouponController extends Controller
{
    private Coupon $coupon;
    public function __construct()
    {
        $this->coupon = new Coupon();
    }
    public function index() {
        echo "Danh sách dữ liệu.";
    }

    public function create() {
        echo "Form thêm dữ liệu.";
    }

    public function store() {
        echo "Xử lý thêm dữ liệu.";
    }

    public function edit($id) {
        echo "Chỉnh sửa dữ liệu ID: $id";
    }

    public function update($id) {
        echo "Cập nhật dữ liệu ID: $id";
    }

    public function delete($id) {
        echo "Xóa dữ liệu ID: $id";
    }
}