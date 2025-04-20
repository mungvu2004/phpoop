<?php
namespace App\Controllers\Client;

use App\Controller;
use App\Models\Coupon;

/**
 * Lớp CouponController quản lý các thao tác liên quan đến mã giảm giá
 * 
 * Lớp này kế thừa từ Controller cơ sở và cung cấp các phương thức đặc thù
 * cho việc xử lý các yêu cầu liên quan đến mã giảm giá từ phía người dùng.
 */
class CouponController {
    /**
     * Hiển thị danh sách mã giảm giá
     * 
     * @return void
     */
    public function index() {
        echo "Danh sách dữ liệu.";
    }

    /**
     * Hiển thị form thêm mã giảm giá mới
     * 
     * @return void
     */
    public function create() {
        echo "Form thêm dữ liệu.";
    }

    /**
     * Xử lý thêm mã giảm giá mới
     * 
     * @return void
     */
    public function store() {
        echo "Xử lý thêm dữ liệu.";
    }

    /**
     * Hiển thị form chỉnh sửa mã giảm giá
     * 
     * @param int $id ID của mã giảm giá cần chỉnh sửa
     * @return void
     */
    public function edit($id) {
        echo "Chỉnh sửa dữ liệu ID: $id";
    }

    /**
     * Xử lý cập nhật mã giảm giá
     * 
     * @param int $id ID của mã giảm giá cần cập nhật
     * @return void
     */
    public function update($id) {
        echo "Cập nhật dữ liệu ID: $id";
    }

    /**
     * Xử lý xóa mã giảm giá
     * 
     * @param int $id ID của mã giảm giá cần xóa
     * @return void
     */
    public function delete($id) {
        echo "Xóa dữ liệu ID: $id";
    }
}