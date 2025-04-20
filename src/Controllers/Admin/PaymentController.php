<?php
namespace App\Controllers\Admin;

use App\Models\Payment;
use App\Controller;

/**
 * Lớp PaymentController quản lý các thao tác liên quan đến thanh toán
 * 
 * Lớp này cung cấp các phương thức đặc thù cho việc quản lý thanh toán
 * từ phía quản trị viên, bao gồm xem, duyệt và xóa lịch sử thanh toán.
 */
class PaymentController {
    /**
     * @var Payment Đối tượng Payment để tương tác với cơ sở dữ liệu
     */
    private Payment $payment;

    /**
     * Khởi tạo đối tượng PaymentController
     */
    public function __construct() {
       $this->payment = new Payment();
    }

    /**
     * Hiển thị danh sách lịch sử thanh toán
     * 
     * @return View Trang hiển thị danh sách thanh toán
     */
    public function index() {
        $payments = $this->payment->getAllPay();
        return view("admin.payment", compact("payments"));
    }

    /**
     * Hiển thị form thêm thanh toán mới
     * 
     * @return void
     */
    public function create() {
        echo "Form thêm dữ liệu.";
    }

    /**
     * Xử lý thêm thanh toán mới
     * 
     * @return void
     */
    public function store() {
        echo "Xử lý thêm dữ liệu.";
    }

    /**
     * Hiển thị form chỉnh sửa thanh toán
     * 
     * @param int $id ID của thanh toán cần chỉnh sửa
     * @return void
     */
    public function edit($id) {
        echo "Chỉnh sửa dữ liệu ID: $id";
    }

    /**
     * Xử lý cập nhật thanh toán
     * 
     * @param int $id ID của thanh toán cần cập nhật
     * @return void
     */
    public function update($id) {
        echo "Cập nhật dữ liệu ID: $id";
    }

    /**
     * Xử lý xóa thanh toán
     * 
     * @param int $id ID của thanh toán cần xóa
     * @return void
     */
    public function delete($id) {
        echo "Xóa dữ liệu ID: $id";
    }
}