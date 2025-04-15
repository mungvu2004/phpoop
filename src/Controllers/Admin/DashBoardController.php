<?php
namespace App\Controllers\Admin;

use App\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Review;


class DashBoardController extends Controller{
    
    private User $user;
    private Order $order;
    private Payment $payment;
    private Review $review;
    private Order $sale;
    private Order $allOrder;

    public function __construct()
    {
        $this->user = new User();
        $this->order = new Order();
        $this->payment = new Payment();
        $this->review = new Review();
        $this->sale = new Order();
        $this->allOrder = new Order();
    }

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