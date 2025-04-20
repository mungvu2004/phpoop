<?php
namespace App\Controllers\Admin;

use App\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Review;

/**
 * Lớp DashBoardController quản lý các thao tác liên quan đến bảng điều khiển quản trị
 * 
 * Lớp này kế thừa từ Controller cơ sở và cung cấp các phương thức đặc thù
 * cho việc hiển thị thống kê và dữ liệu tổng quan của hệ thống.
 */
class DashBoardController extends Controller{
    
    /**
     * @var User Model xử lý dữ liệu người dùng
     */
    private User $user;

    /**
     * @var Order Model xử lý dữ liệu đơn hàng
     */
    private Order $order;

    /**
     * @var Payment Model xử lý dữ liệu thanh toán
     */
    private Payment $payment;

    /**
     * @var Review Model xử lý dữ liệu đánh giá
     */
    private Review $review;

    /**
     * @var Order Model xử lý dữ liệu doanh thu
     */
    private Order $sale;

    /**
     * @var Order Model xử lý dữ liệu tất cả đơn hàng
     */
    private Order $allOrder;

    /**
     * Khởi tạo controller và các model liên quan
     */
    public function __construct()
    {
        $this->user = new User();
        $this->order = new Order();
        $this->payment = new Payment();
        $this->review = new Review();
        $this->sale = new Order();
        $this->allOrder = new Order();
    }

    /**
     * Hiển thị trang bảng điều khiển với các thống kê
     * 
     * @return mixed View hiển thị trang bảng điều khiển
     */
    public function index() {
        $users = $this->user->count();
        $orders = $this->order->count();
        $payments = $this->payment->count();
        $reviews = $this->review->count();
        $sale = $this->sale->dataMonth();
        $allOrder = $this->allOrder->getAll();
        return view(
            'admin.dashboard',
            compact('users', 'orders', 'payments', 'reviews', 'sale', 'allOrder')
        );
    }

    /**
     * Lấy dữ liệu doanh thu theo tháng và năm
     * 
     * @return void Trả về dữ liệu doanh thu dưới dạng JSON
     */
    public function getSaleData() {
        $month = isset($_GET['month']) ? $_GET['month'] : date('n');
        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');

        $saleData = $this->sale->dataMonth($month, $year);
        $dayInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $labels = range(1, $dayInMonth);
        $data = array_fill(0, $dayInMonth, 0);

        foreach($saleData as $row) {
            $dayIndex = $row['day'] - 1;
            $data[$dayIndex] = (float)$row['total_price'];
        }
        header('Content-Type: application/json');
        echo json_encode([
            'labels' => $labels,
            'data' => $data,
        ]);
        var_dump($data);
        var_dump($labels);
        exit;
    }
}