<?php
namespace App\Controllers\Admin;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Controller;

class OrderController extends Controller{
    private Order $order;
    private OrderDetail $orderDetail;
    public function __construct() {
        $this->order = new Order();
        $this->orderDetail = new OrderDetail();
    }
    
    public function getSorttable() {
        return [
            "created_at" => "Ngày tạo",
            "total_price" => "Tổng tiền",
            "status" => "Trạng thái"
        ];
    }
    public function arrange(array $data, string $sort_by = "created_at") {
        if(!array_key_exists($sort_by, $this->getSorttable())) {
            $sort_by = "created_at";
        }
        $count = count($data) - 1;
        return $this->order->quickSort($data,0, $count,  $sort_by);
    }
    public function index() {

        $sort = isset($_GET['sort']) ? $_GET['sort'] : null;
        $sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'created_at';
        if($sort === 'quick') {
            $allOrder = $this->order->getAll();
            $orders = $this->arrange($allOrder, $sort_by);
            return view('admin.orders.order',
                compact('orders')    
        );
            
        } else {
            $orders = $this->order->getAll();
            return view('admin.orders.order', compact('orders'));

        }
    }

    public function create() {
        echo "Form thêm dữ liệu.";
    }

    public function store() {
        echo "Xử lý thêm dữ liệu.";
    }

    public function edit($id) {
        $orderDetail = $this->order->detailOrder($id);
        return view('admin.orders.order-detail', compact('orderDetail'));
    }

    public function update($id) {
        echo "Cập nhật dữ liệu ID: $id";
    }

    public function delete($id) {
        $orderDelete = $this->order->delete($id);
        if($orderDelete) {
            $_SESSION['success'] = ['Đã xóa thành công đơn hàng mà bạn chọn'];
            header('Location: /admin/order');
            exit;
        } else {
            $_SESSION['error'] = ['Lỗi khi xóa đơn hàng '];
            header('Location: /admin/order');
            exit;
        }
    }
}