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
            compact('users', 'orders', 'payments', 'reviews', 'sale', 'allOrder'),
            'Bảng điều khiển Admin - PWShop'
        );
    }

    /**
     * Lấy dữ liệu doanh thu theo tháng và năm
     * 
     * @return void Trả về dữ liệu doanh thu dưới dạng JSON
     */
    public function getSaleData() {
        try {
            // Lấy tham số từ request
            $month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
            $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
            
            // Log thông tin debug
            error_log("Getting sale data for month: $month, year: $year");
            
            // Lấy dữ liệu doanh thu từ model
            $saleData = $this->sale->dataMonth($month, $year);
            error_log("Raw sale data: " . json_encode($saleData));

            // Tạo dữ liệu biểu đồ đúng định dạng
            $dayInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $data = array_fill(0, $dayInMonth, 0);

            foreach ($saleData as $item) {
                $day = intval($item['day']) - 1;
                if ($day >= 0 && $day < $dayInMonth) {
                    $data[$day] = floatval($item['total_price']);
                }
            }

            $labels = range(1, $dayInMonth);
            
            // Set header và trả về JSON
            header('Content-Type: application/json');
            echo json_encode([
                'labels' => $labels,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            // Log lỗi
            error_log("Error in getSaleData: " . $e->getMessage());
            
            // Trả về lỗi dưới dạng JSON
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'error' => true,
                'message' => 'Đã xảy ra lỗi khi lấy dữ liệu doanh thu: ' . $e->getMessage()
            ]);
        }
        return;
    }
}