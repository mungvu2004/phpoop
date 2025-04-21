<?php
namespace App\Controllers\Client;

use App\Controllers\Controller;
use App\Models\PaySession;
use App\Models\Order;

class PaySessionController extends Controller
{
    private PaySession $paySession;

    public function __construct()
    {
        $this->paySession = new PaySession();
    }
    public function checkPay($paymentMethod) {
        if ($paymentMethod == 'cod') {
            return true;
        }
        return false;
    } 
    public function checkShipping($shippingMethod) {
        if ($shippingMethod == 'standard') {
            return true;
        }
        return false;
    }
    public function createPaySession() {
        $orderId = $_POST['order_ids'];
        $shippingMethod = $_POST['shipping_method'];
        $paymentMethod = $_POST['payment_method'];
        $totalAmount = $_POST['total_amount'];
        
        // Validate payment method
        if (!$this->checkPay($paymentMethod)) {
            return [
                'success' => false,
                'message' => 'Phương thức thanh toán không hợp lệ'
            ];
        }

        // Validate shipping method
        if (!$this->checkShipping($shippingMethod)) {
            return [
                'success' => false,
                'message' => 'Phương thức vận chuyển không hợp lệ'
            ];
        }

        // Create payment history data
        $data = [
            'order_id' => $orderId,
            'payment_method' => $paymentMethod,
            'amount' => $totalAmount,
            'status' => 'pending'
        ];

        // Insert payment history
        $paymentId = $this->paySession->create($data);

        if ($paymentId) {
            // Update order status to 'pending' for COD
            $orderModel = new Order();
            $orderModel->updateStatus($orderId, 'pending');

            return [
                'success' => true,
                'message' => 'Tạo đơn hàng COD thành công',
                'payment_id' => $paymentId
            ];
        }

        return [
            'success' => false,
            'message' => 'Có lỗi xảy ra khi tạo đơn hàng'
        ];
    }
    
}
