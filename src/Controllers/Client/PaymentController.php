<?php
namespace App\Controllers\Client;

use App\Controller;
use App\Models\PaySession;
use App\Models\Order;
use App\Models\Payment;
use PDO;

/**
 * Lớp PaymentController quản lý các thao tác liên quan đến thanh toán
 * 
 * Lớp này kế thừa từ Controller cơ sở và cung cấp các phương thức đặc thù
 * cho việc xử lý các yêu cầu liên quan đến thanh toán từ phía người dùng.
 */
class PaymentController extends Controller{
    private PaySession $paySession;
    private Order $order;
    private Payment $payment;

    public function __construct()
    {
        $this->paySession = new PaySession();
        $this->order = new Order();
        $this->payment = new Payment();
    }

    public function checkPay($paymentMethod) {
        return strtolower($paymentMethod) === 'cod';
    } 

    public function checkShipping($shippingMethod) {
        return strtolower($shippingMethod) === 'standard';
    }

    public function processCOD() {
        $orderId = $_POST['order_id'];
        $method = $_POST['shipping_method'];
        
    }
}