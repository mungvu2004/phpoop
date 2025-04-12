<?php
namespace App\Controllers\Admin;

use App\Models\OrderDetail;
use App\Controller;


class OrderDetailController {
    private OrderDetail $orderDetail;

    public function __construct() {
        $this->orderDetail = new OrderDetail();
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