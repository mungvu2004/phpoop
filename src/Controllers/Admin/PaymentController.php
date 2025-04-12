<?php
namespace App\Controllers\Admin;

use App\Models\Payment;
use App\Controller;

class PaymentController {
    private Payment $payment;
    public function __construct() {
       $this->payment = new Payment();
    }
    public function index() {
        $payments = $this->payment->getAllPay();
        return view("admin.payment", compact("payments"));
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