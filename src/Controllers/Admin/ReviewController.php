<?php
namespace App\Controllers\Admin;

use App\Models\Review;
use App\Controller;

/**
 * Lớp ReviewController quản lý các thao tác liên quan đến đánh giá sản phẩm
 * 
 * Lớp này cung cấp các phương thức đặc thù cho việc quản lý đánh giá sản phẩm
 * từ phía quản trị viên, bao gồm xem, duyệt và xóa đánh giá của người dùng.
 */
class ReviewController {
    /**
     * Hiển thị danh sách đánh giá
     * 
     * @return void
     */
    public function index() {
        echo "Danh sách dữ liệu.";
    }

    /**
     * Hiển thị form thêm đánh giá mới
     * 
     * @return void
     */
    public function create() {
        echo "Form thêm dữ liệu.";
    }

    /**
     * Xử lý thêm đánh giá mới
     * 
     * @return void
     */
    public function store() {
        echo "Xử lý thêm dữ liệu.";
    }

    /**
     * Hiển thị form chỉnh sửa đánh giá
     * 
     * @param int $id ID của đánh giá cần chỉnh sửa
     * @return void
     */
    public function edit($id) {
        echo "Chỉnh sửa dữ liệu ID: $id";
    }

    /**
     * Xử lý cập nhật đánh giá
     * 
     * @param int $id ID của đánh giá cần cập nhật
     * @return void
     */
    public function update($id) {
        echo "Cập nhật dữ liệu ID: $id";
    }

    /**
     * Xử lý xóa đánh giá
     * 
     * @param int $id ID của đánh giá cần xóa
     * @return void
     */
    public function delete($id) {
        echo "Xóa dữ liệu ID: $id";
    }
}