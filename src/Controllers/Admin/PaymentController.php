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

    
    public function index() {
        $payments = $this->payment->getAllPay();
        return view("admin.payment", compact("payments"));
    }
}