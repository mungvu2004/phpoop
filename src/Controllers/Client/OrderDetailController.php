<?php

namespace App\Controllers\Client;

use App\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\UserAddress;

/**
 * Controller xử lý các thao tác liên quan đến chi tiết đơn hàng
 */
class OrderDetailController extends Controller
{
    private OrderDetail $orderDetail;
    private Order $order;
    private UserAddress $userAddress;

    /**
     * Khởi tạo controller và các model cần thiết
     */
    public function __construct()
    {
        $this->orderDetail = new OrderDetail();
        $this->order = new Order();
        $this->userAddress = new UserAddress();
    }

    /**
     * Hiển thị danh sách đơn hàng và chi tiết đơn hàng
     * 
     * @return mixed View hiển thị danh sách đơn hàng
     */
    public function index()
    {
        $orders = $this->order->findALL();
        $orderDetail = $this->orderDetail->findALL();
        return view("", compact("orders", "orderDetail"));
    }

    /**
     * Kiểm tra trạng thái đăng nhập của người dùng
     * 
     * @return bool True nếu người dùng đã đăng nhập, false nếu chưa
     */
    private function isLoggedIn(): bool
    {
        return isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
    }

    /**
     * Kiểm tra tính hợp lệ của dữ liệu đầu vào
     * 
     * @param mixed $productId ID của sản phẩm
     * @param mixed $sizeId ID của size sản phẩm
     * @param mixed $quantity Số lượng sản phẩm
     * @return bool True nếu dữ liệu hợp lệ, false nếu không hợp lệ
     */
    private function isValidInput($productId, $sizeId, $quantity): bool
    {
        return !empty($productId) && !empty($sizeId) && is_numeric($quantity) && $quantity > 0;
    }

    /**
     * Chuyển hướng người dùng với thông báo lỗi
     * 
     * @param string $url URL cần chuyển hướng đến
     * @param string $message Thông báo lỗi
     */
    private function redirectWithError(string $url, string $message)
    {
        $_SESSION['errors'] = [$message];
        header("Location: $url");
        exit();
    }

    /**
     * Tạo đơn hàng mới cho người dùng
     * 
     * @param int $userId ID của người dùng
     * @param int $productId ID của sản phẩm
     * @return int|null ID của đơn hàng mới tạo hoặc null nếu có lỗi
     */
    private function createNewOrder($userId, $productId)
    {
        $addressId = $this->userAddress->findId($userId);
        if (!$addressId) {
            $this->redirectWithError('/account', 'Vui lòng cập nhật địa chỉ giao hàng');
            return null;
        }

        $order = [
            'user_id' => $userId,
            'address_id' => $addressId,
            'status' => 'pending',
        ];
        $orderId = $this->order->insert($order);

        if (!$orderId) {
            $this->redirectWithError("/products/show/$productId", 'Không thể tạo đơn hàng mới');
            return null;
        }

        return $orderId;
    }

    /**
     * Thêm sản phẩm vào đơn hàng
     * 
     * @param int $orderId ID của đơn hàng
     * @param int $productId ID của sản phẩm
     * @param int $sizeId ID của size sản phẩm
     * @param int $quantity Số lượng sản phẩm
     */
    private function addProductToOrder($orderId, $productId, $sizeId, $quantity)
    {
        $existing = $this->orderDetail->checkInsert($orderId, $productId, $sizeId);

        if ($existing > 0) {
            $currentOrderDetail = $this->orderDetail->findByOrderAndProduct($orderId, $productId, $sizeId);
            $currentQuantity = $currentOrderDetail['quantity'] ?? 0;
            
            $this->orderDetail->update($currentOrderDetail['id'], [
                'quantity' => $currentQuantity + $quantity
            ]);
        } else {
            $this->orderDetail->insert([
                'product_id' => $productId,
                'order_id' => $orderId,
                'size_id' => $sizeId,
                'quantity' => $quantity
            ]);
        }
    }

    /**
     * Xử lý tạo đơn hàng mới và thêm sản phẩm vào đơn hàng
     * 
     * @return void
     */
    public function create()
    {
        if (!$this->isLoggedIn()) {
            $this->redirectWithError('/login', 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng');
        }

        $user = $_SESSION['user'];
        $productId = $_POST['product_id'] ?? null;
        $sizeId = $_POST['size_id'] ?? null;
        $quantity = $_POST['quantity'] ?? 0;
        $orderId = $_POST['order_id'] ?? null;

        if (!$this->isValidInput($productId, $sizeId, $quantity)) {
            $this->redirectWithError("/products/show/$productId", 'Dữ liệu không hợp lệ!');
        }

        if (!$orderId) {
            $orderId = $this->createNewOrder($user['id'], $productId);
            if (!$orderId) return;
        }

        try {
            $this->addProductToOrder($orderId, $productId, $sizeId, $quantity);
            $_SESSION['success'] = ['Đã thêm sản phẩm vào giỏ hàng thành công!'];
        } catch (\Exception $e) {
            $this->redirectWithError("/products/show/$productId", 'Lỗi thêm vào giỏ hàng: ' . $e->getMessage());
        }

        header("Location: /products/show/$productId");
        exit();
    }

    public function store()
    {
        echo "Xử lý thêm dữ liệu.";
    }
    public function edit($id)
    {
        echo "Chỉnh sửa dữ liệu ID: $id";
    }
    public function update($id)
    {
        echo "Cập nhật dữ liệu ID: $id";
    }
    public function delete($id)
    {
        echo "Xóa dữ liệu ID: $id";
    }
}
