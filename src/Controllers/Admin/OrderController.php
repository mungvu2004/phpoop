<?php
namespace App\Controllers\Admin;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Controller;

/**
 * Lớp OrderController quản lý các thao tác liên quan đến đơn hàng
 * 
 * Lớp này cung cấp các phương thức đặc thù cho việc quản lý đơn hàng
 * từ phía quản trị viên, bao gồm xem, sắp xếp, chỉnh sửa và xóa đơn hàng.
 */
class OrderController extends Controller{
    /**
     * @var Order Đối tượng Order để tương tác với cơ sở dữ liệu
     */
    private Order $order;

    /**
     * Khởi tạo đối tượng OrderController
     */
    public function __construct() {
        $this->order = new Order();
    }
    
    /**
     * Lấy danh sách các trường có thể sắp xếp
     * 
     * @return array Mảng chứa các trường có thể sắp xếp và tên hiển thị tương ứng
     */
    public function getSorttable() {
        return [
            "created_at" => "Ngày tạo",
            "total_price" => "Tổng tiền",
            "status" => "Trạng thái"
        ];
    }

    /**
     * Sắp xếp danh sách đơn hàng theo trường được chỉ định
     * 
     * @param array $data Danh sách đơn hàng cần sắp xếp
     * @param string $sort_by Trường sắp xếp (mặc định là created_at)
     * @return array Danh sách đơn hàng đã được sắp xếp
     */
    public function arrange(array $data, string $sort_by = "created_at") {
        if(!array_key_exists($sort_by, $this->getSorttable())) {
            $sort_by = "created_at";
        }
        $count = count($data) - 1;
        return $this->order->quickSort($data,0,  $sort_by,$count);
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

   
    public function edit($id) {
        $orderDetail = $this->order->detailOrder($id);
        return view('admin.orders.order-detail', compact('orderDetail'));
    }

    /**
     * Xử lý xóa đơn hàng
     * 
     * @param int $id ID của đơn hàng cần xóa
     * @return void
     */
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