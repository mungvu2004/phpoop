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
            "create_at" => "Ngày tạo",
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

        if(isset($_GET["sort"])) {
            $_SESSION['order_sort'] = $_GET['sort'];
        } 
        if(isset($_SESSION['sort_by'])) {
            $_SESSION['order_sort_by'] = $_SESSION['sort_by'];
        }
        $sort = isset($_SESSION['sort']) ? $_SESSION['sort'] : null;
        $sort_by = isset($_SESSION['order_sort_by']) ? $_SESSION['order_sort_by'] : 'created_at';
        $page = isset($_GET['page']) ? (int)$_GET['page'] :1;
        $page = max(1, $page);

        if($sort === 'quick') {
            $allOrder = $this->order->findALL();
            $orderSort = $this->arrange($allOrder, $sort_by);

            $totalItem = count($allOrder);
            $totalPage = ceil($totalItem / 8);

            $page = min($page, max(1, $totalPage));

            $offset = ($page -1) * 8;
            $pageOrder = array_slice($allOrder, $offset, 8);

            $result = [
                'data' => $pageOrder,
                'page' => $page,
                'totalItem'=> $totalItem,
                'totalPage'=> $totalPage
            ];
        } else {
            $result = $this->order->paginate($page);
        }

        $sortTable = $this->getSorttable();
        return view('admin.orders.order', [
            'orders' => $result['data'],
            'currentPage' => $result['page'],
            'totalPage' => $result['totalPage'],
            'sort' => $sort,
            'sort_by'=> $sort_by,
            'sortTable'=> $sortTable
        ]);

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