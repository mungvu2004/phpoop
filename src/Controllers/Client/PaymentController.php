<?php
namespace App\Controllers\Client;

use App\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\VNPayService;

class PaymentController extends Controller
{
    private $order;
    private $payment;
    private $vnpayService;

    public function __construct()
    {
        $this->order = new Order();
        $this->payment = new Payment();
        $this->vnpayService = new VNPayService();
    }

    public function processCOD()
    {
        $orderId = $_POST['order_ids'];
        $shippingMethod = $_POST['shipping_method'];

        // Kiểm tra đơn hàng
        $order = $this->order->find($orderId);
        if (!$order) {
            return response(['error' => 'Order not found'], 404);
        }

        // Cập nhật trạng thái đơn hàng
        $this->order->update($orderId, ['shipping_method' => $shippingMethod, 'status' => 'processing']);

        // Lưu lịch sử thanh toán
        $this->payment->create([
            'user_id' => $order['user_id'],
            'order_id' => $orderId,
            'amount' => $order['total_price'],
            'payment_method' => 'COD',
            'status' => 'pending',
        ]);
        $_SESSION['success'] = ['Đã xác nhận đơn hàng. Bạn hãy chờ nhân viên xác nhận'];
        return view('/cart');
    }

    public function processVNPay()
    {
        $orderId = $_POST['order_ids'];
        $shippingMethod = $_POST['shipping_method'];

        // Kiểm tra đơn hàng
        $order = $this->order->find($orderId);
        if (!$order) {
            return response(['error' => 'Order not found'], 404);
        }

        // Tạo dữ liệu thanh toán VNPay
        $paymentData = [
            'order_id' => $orderId,
            'amount' => $order['total_price'],
            'order_desc' => 'Thanh toán đơn hàng #' . $orderId,
            'bank_code' => '',
            'language' => 'vn'
        ];

        // Tạo URL thanh toán VNPay
        $paymentUrl = $this->vnpayService->createPaymentUrl($paymentData);

        // Lưu lịch sử thanh toán
        $this->payment->create([
            'user_id' => $order['user_id'],
            'order_id' => $orderId,
            'amount' => $order['total_price'],
            'payment_method' => 'VNPAY',
            'status' => 'pending',
        ]);

        // Chuyển hướng đến trang thanh toán VNPay
        header('Location: ' . $paymentUrl);
        exit;
    }

    public function vnpayReturn()
    {
        $data = $_GET;
        $result = $this->vnpayService->checkPaymentResult($data);

        if ($result['success']) {
            // Cập nhật trạng thái đơn hàng
            $this->order->update($data['vnp_TxnRef'], [
                'status' => 'processing',
                'payment_method' => 'VNPAY'
            ]);

            // Cập nhật trạng thái thanh toán
            $this->payment->updatePaymentStatus($data['vnp_TxnRef'], 'completed');

            $_SESSION['success'] = ['Thanh toán thành công! Đơn hàng của bạn đang được xử lý.'];
        } else {
            $_SESSION['error'] = ['Thanh toán thất bại: ' . $result['message']];
        }

        return view('/cart');
    }
}

// Helper function
function response($data, $status = 200)
{
    header("Content-Type: application/json", true, $status);
    echo json_encode($data);
    exit;
}