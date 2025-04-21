<?php

namespace App\Controllers\Client;

use App\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderDetail;

/**
 * Lớp OrderController quản lý các thao tác liên quan đến đơn hàng
 */
class OrderController extends Controller
{
    private Order $order;
    private OrderDetail $orderDetail;
    private Coupon $coupon;

    public function __construct()
    {
        $this->order = new Order();
        $this->orderDetail = new OrderDetail();
        $this->coupon = new Coupon();
    }

    public function index()
    {
        $user = $_SESSION['user'];
        $orders = $this->order->userOrder($user['id']);
        for ($i = 0; $i < count($orders); $i++) {
            $orders[$i]['item'] = $this->orderDetail->getAllOrder($orders[$i]['order_id']);
        }
        return view('client.cart', compact('orders'));
    }

    public function update()
    {
        try {
            header('Content-Type: application/json');

            // Kiểm tra input
            $input = file_get_contents('php://input');
            if (empty($input)) {
                throw new \Exception('Không có dữ liệu đầu vào');
            }

            $data = json_decode($input, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Dữ liệu JSON không hợp lệ');
            }


            $couponCode = strtolower(trim($data['coupon']));
            $orderId = $data['orders'][0];

            $coupon = $this->coupon->getCouponDetails($couponCode);
            if (!$coupon) {
                throw new \Exception('Không có mã giảm giá này hoặc mã đã hết hạn');
            }

            // Cập nhật đơn hàng
            $this->order->update($orderId, ['coupon_id' => $coupon['id']]);

            // Lấy thông tin đơn hàng cập nhật
            $updatedOrder = $this->order->getOrderById($orderId);
            if (!$updatedOrder) {
                throw new \Exception('Không tìm thấy thông tin đơn hàng');
            }

            echo json_encode([
                'success' => true,
                'message' => 'Áp dụng mã giảm giá thành công!',
                'data' => [
                    'discount' => $coupon['discount'],
                    'discount_type' => $coupon['discount_type'],
                    'order_total' => $updatedOrder['total_price'],
                    'final_total' => $updatedOrder['final_price']
                ]
            ]);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Xử lý xóa đơn hàng
     * 
     * @param int $id ID của đơn hàng cần xóa
     * @return void
     */
    public function delete($id)
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (isset($input['order_detail_id'])) {
            $orderDetailId = $input['order_detail_id'];

            $statement = $this->orderDetail->delete($orderDetailId);

            if ($statement) {
                echo json_encode(['success' => true, 'message' => 'Chi tiết đơn hàng đã được xóa.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể xóa chi tiết đơn hàng.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Thiếu order_detail_id.']);
        }
    }
}
