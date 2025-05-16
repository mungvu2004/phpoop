<?php
namespace App\Controllers\Client;

use App\Controller;
use App\Services\PaymentService;
use App\Models\Order;

class PaymentController extends Controller
{
    private $paymentService;
    private $orderModel;

    public function __construct()
    {
        $this->paymentService = new PaymentService();
        $this->orderModel = new Order();
    }

    public function processVNPay()
    {
        try {
            // Đảm bảo request là POST
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Method not allowed'
                ]);
                exit;
            }

            // Lấy dữ liệu từ request
            $rawData = file_get_contents('php://input');
            error_log('Raw request data: ' . $rawData);
            
            $data = json_decode($rawData, true);
            error_log('Decoded data: ' . print_r($data, true));

            if (json_last_error() !== JSON_ERROR_NONE) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Invalid JSON data: ' . json_last_error_msg()
                ]);
                exit;
            }

            $orderIds = $data['order_ids'] ?? '';
            $amount = $data['amount'] ?? 0;
            $shippingMethod = $data['shipping_method'] ?? 'standard';

            error_log('Order IDs: ' . $orderIds);
            error_log('Amount: ' . $amount);
            error_log('Shipping method: ' . $shippingMethod);

            // Validate dữ liệu
            if (empty($orderIds) || $amount <= 0) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Dữ liệu không hợp lệ: orderIds=' . $orderIds . ', amount=' . $amount
                ]);
                exit;
            }

            // Cập nhật thông tin vận chuyển cho đơn hàng
            $orderIdArray = explode(',', $orderIds);
            foreach ($orderIdArray as $orderId) {
                $this->orderModel->update($orderId, [
                    'shipping_method' => $shippingMethod,
                    'shipping_fee' => $shippingMethod === 'express' ? 40000 : 20000
                ]);
            }

            // Tạo URL thanh toán VNPay
            try {
                $vnpayUrl = $this->paymentService->createPaymentUrl(
                    $orderIds,
                    $amount,
                    "Thanh toan don hang " . $orderIds
                );
                
                error_log('VNPay URL: ' . $vnpayUrl);
                
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'success',
                    'payment_url' => $vnpayUrl
                ]);
                exit;
            } catch (\Exception $e) {
                error_log('Error creating VNPay URL: ' . $e->getMessage());
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Lỗi tạo URL thanh toán: ' . $e->getMessage()
                ]);
                exit;
            }

        } catch (\Exception $e) {
            error_log('Exception in processVNPay: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            exit;
        }
    }

    public function vnpayReturn()
    {
        try {
            // Ghi log các cột trong bảng orders để debug
            $columns = $this->logTableColumns('orders');
            $columnNames = array_column($columns, 'Field');
            error_log('Orders table columns: ' . implode(', ', $columnNames));
            
            $inputData = $_GET;
            error_log('VNPay return data: ' . print_r($inputData, true));
            
            $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
            $vnp_ResponseCode = $inputData['vnp_ResponseCode'] ?? '';
            $vnp_TxnRef = $inputData['vnp_TxnRef'] ?? '';
            $vnp_Amount = $inputData['vnp_Amount'] ?? 0;
            $vnp_TransactionNo = $inputData['vnp_TransactionNo'] ?? '';
            $vnp_BankCode = $inputData['vnp_BankCode'] ?? '';
            
            error_log('VNPay transaction details - TxnRef: ' . $vnp_TxnRef . ', Amount: ' . $vnp_Amount . ', ResponseCode: ' . $vnp_ResponseCode);
            error_log('VNPay transaction details - TransactionNo: ' . $vnp_TransactionNo . ', BankCode: ' . $vnp_BankCode);
            
            // Kiểm tra response code từ VNPay
            if ($vnp_ResponseCode !== '00') {
                error_log('VNPay payment failed with code: ' . $vnp_ResponseCode);
                $_SESSION['error'] = 'Thanh toán không thành công: ' . $this->getVnpResponseMessage($vnp_ResponseCode);
                header('Location: /order');
                exit;
            }
            
            // Verify payment
            try {
                $isValid = $this->paymentService->verifyPayment($vnp_SecureHash, $inputData);
                error_log('VNPay verification result: ' . ($isValid ? 'valid' : 'invalid'));
                
                if ($isValid) {
                    // Trích xuất order IDs từ vnp_TxnRef
                    // Format: orderId_timestamp_randomString
                    $orderIdParts = explode('_', $vnp_TxnRef);
                    $orderIds = $orderIdParts[0]; // Lấy phần đầu tiên là order IDs
                    
                    $amount = $vnp_Amount / 100; // Convert back from VNPay amount
                    
                    error_log('Processing order(s): ' . $orderIds . ' with amount: ' . $amount);
                    
                    // Cập nhật trạng thái các đơn hàng
                    $orderIdArray = explode(',', $orderIds);
                    foreach ($orderIdArray as $orderId) {
                        error_log('Updating order: ' . $orderId);
                        
                        // Lấy thông tin đơn hàng để lấy user_id
                        $order = $this->orderModel->find($orderId);
                        if (!$order) {
                            error_log('Order not found: ' . $orderId);
                            continue;
                        }
                        
                        error_log('Found order: ' . print_r($order, true));
                        
                        // Chuẩn bị dữ liệu cập nhật
                        $updateData = [
                            'payment_status' => 'completed',
                            'payment_method' => 'vnpay',
                            'status' => 'processing'
                        ];
                        
                        // Thêm payment_time nếu cột này tồn tại
                        if (in_array('payment_time', $columnNames)) {
                            $updateData['payment_time'] = date('Y-m-d H:i:s');
                        }
                        
                        // Cập nhật trạng thái đơn hàng
                        $updateResult = $this->orderModel->update($orderId, $updateData);
                        
                        error_log('Order update result: ' . ($updateResult ? 'success' : 'failed'));
                        
                        // Thêm dữ liệu vào bảng payment_history
                        $paymentData = [
                            'order_id' => $orderId,
                            'amount' => $amount,
                            'payment_method' => 'vnpay',
                            'status' => 1,
                            'bank_code' => $vnp_BankCode
                        ];
                        
                        $this->savePaymentHistory($paymentData, $vnp_TransactionNo);
                    }

                    $_SESSION['success'] = 'Thanh toán thành công!';
                    header('Location: /order');
                    exit;
                }
            } catch (\Exception $e) {
                error_log('Error verifying VNPay payment: ' . $e->getMessage());
            }

            $_SESSION['error'] = 'Xác thực thanh toán thất bại';
            header('Location: /order');
            exit;

        } catch (\Exception $e) {
            error_log('Exception in vnpayReturn: ' . $e->getMessage());
            $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
            header('Location: /order');
            exit;
        }
    }

    /**
     * Lưu lịch sử thanh toán vào CSDL
     * 
     * @param array $data
     * @param string $transactionCode
     * @return bool
     */
    public function savePaymentHistory($data, $transactionCode = '')
    {
        try {
            error_log('Attempting to save payment history');
            
            // Kiểm tra sự tồn tại của bảng payment_history
            $tableName = 'payment_history';
            if (!$this->isTableExists($tableName)) {
                error_log('Table payment_history does not exist, creating...');
                
                // Tạo bảng nếu chưa tồn tại
                $connection = $this->orderModel->getConnection();
                $sql = "CREATE TABLE `$tableName` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `order_id` int(11) NOT NULL,
                    `amount` decimal(15,2) NOT NULL,
                    `status` tinyint(1) NOT NULL DEFAULT 0,
                    `payment_method` varchar(50) NOT NULL,
                    `transaction_code` varchar(100) DEFAULT NULL,
                    `bank_code` varchar(50) DEFAULT NULL,
                    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
                
                $connection->executeStatement($sql);
                error_log('Table payment_history created successfully');
            }
            
            // Lấy thông tin cấu trúc bảng để kiểm tra các cột
            $columns = $this->logTableColumns($tableName);
            $columnNames = array_column($columns, 'Field');
            error_log('Available columns: ' . json_encode($columnNames));
            
            // Chuẩn bị dữ liệu để lưu
            $paymentData = [
                'order_id' => $data['order_id'],
                'amount' => $data['amount'],
                'status' => isset($data['status']) ? $data['status'] : 0,
                'payment_method' => $data['payment_method']
            ];
            
            // Thêm transaction_code nếu cột tồn tại và có giá trị
            if (in_array('transaction_code', $columnNames) && !empty($transactionCode)) {
                $paymentData['transaction_code'] = $transactionCode;
            }
            
            // Thêm bank_code nếu cột tồn tại và có giá trị trong data
            if (in_array('bank_code', $columnNames) && isset($data['bank_code'])) {
                $paymentData['bank_code'] = $data['bank_code'];
            }
            
            // Thêm thời gian tạo và cập nhật nếu các cột này tồn tại
            $now = date('Y-m-d H:i:s');
            if (in_array('created_at', $columnNames)) {
                $paymentData['created_at'] = $now;
            }
            
            if (in_array('updated_at', $columnNames)) {
                $paymentData['updated_at'] = $now;
            }
            
            // Tạo câu truy vấn SQL động dựa trên các cột thực tế
            $columns = implode('`, `', array_keys($paymentData));
            $placeholders = implode(', ', array_fill(0, count($paymentData), '?'));
            
            $sql = "INSERT INTO `$tableName` (`$columns`) VALUES ($placeholders)";
            error_log('Executing SQL: ' . $sql);
            error_log('With values: ' . json_encode(array_values($paymentData)));
            
            // Thực thi truy vấn
            $connection = $this->orderModel->getConnection();
            $stmt = $connection->prepare($sql);
            $result = $stmt->executeStatement(array_values($paymentData));
            
            error_log('Payment history saved successfully with ID: ' . $connection->lastInsertId());
            return true;
        } catch (\Exception $e) {
            error_log('Error saving payment history: ' . $e->getMessage());
            
            // Thử phương thức dự phòng với dữ liệu tối thiểu
            try {
                error_log('Attempting fallback payment history save with minimal data');
                $connection = $this->orderModel->getConnection();
                $sql = "INSERT INTO `payment_history` 
                        (`order_id`, `amount`, `payment_method`, `status`) 
                        VALUES (?, ?, ?, ?)";
                
                $result = $connection->executeQuery($sql, [
                    $data['order_id'],
                    $data['amount'],
                    $data['payment_method'],
                    isset($data['status']) ? $data['status'] : 0
                ]);
                
                error_log('Fallback payment history saved successfully');
                return true;
            } catch (\Exception $innerEx) {
                error_log('Fallback payment history save failed: ' . $innerEx->getMessage());
                return false;
            }
        }
    }

    /**
     * Kiểm tra xem bảng có tồn tại trong cơ sở dữ liệu hay không
     * 
     * @param string $tableName Tên bảng cần kiểm tra
     * @return bool Kết quả kiểm tra
     */
    private function isTableExists($tableName)
    {
        try {
            error_log('Checking if table exists: ' . $tableName);
            $connection = $this->orderModel->getConnection();
            $sql = "SHOW TABLES LIKE ?";
            $result = $connection->executeQuery($sql, [$tableName]);
            $tables = $result->fetchAllAssociative();
            
            $exists = (count($tables) > 0);
            error_log('Table ' . $tableName . ' exists: ' . ($exists ? 'yes' : 'no'));
            return $exists;
        } catch (\Exception $e) {
            error_log('Error checking table existence: ' . $e->getMessage());
            return false;
        }
    }

    // private function createPaymentHistoryTable()
    // {
    //     try {
    //         $connection = $this->orderModel->getConnection();
    //         $query = "CREATE TABLE IF NOT EXISTS payment_history (
    //             id INT(11) NOT NULL AUTO_INCREMENT,
    //             user_id INT(11) NOT NULL,
    //             order_id INT(11) NOT NULL,
    //             amount DECIMAL(10,2) NOT NULL,
    //             payment_method VARCHAR(50) NOT NULL,
    //             transaction_code VARCHAR(100) NULL,
    //             bank_code VARCHAR(50) NULL,
    //             status VARCHAR(50) NOT NULL DEFAULT 'pending',
    //             created_at DATETIME NOT NULL,
    //             updated_at DATETIME NOT NULL,
    //             PRIMARY KEY (id),
    //             KEY order_id (order_id),
    //             KEY user_id (user_id)
    //         ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
            
    //         $result = $connection->exec($query);
    //         error_log('Payment history table created: ' . ($result !== false ? 'success' : 'failed'));
            
    //         return $result !== false;
    //     } catch (\Exception $e) {
    //         error_log('Error creating payment_history table: ' . $e->getMessage());
    //         return false;
    //     }
    // }

    // Hàm phụ trợ để lấy thông báo lỗi từ mã lỗi VNPay
    private function getVnpResponseMessage($responseCode)
    {
        $messages = [
            '00' => 'Giao dịch thành công',
            '01' => 'Giao dịch đã tồn tại',
            '02' => 'Merchant không hợp lệ (kiểm tra lại vnp_TmnCode)',
            '03' => 'Dữ liệu gửi sang không đúng định dạng',
            '04' => 'Khởi tạo GD không thành công do Website đang bị tạm khóa',
            '05' => 'Giao dịch không thành công do: Quý khách nhập sai mật khẩu quá số lần quy định',
            '06' => 'Giao dịch không thành công do Quý khách nhập sai mật khẩu',
            '07' => 'Giao dịch bị nghi ngờ là gian lận',
            '09' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng bị khóa',
            '10' => 'Giao dịch không thành công do: Quý khách nhập sai mã xác thực',
            '11' => 'Giao dịch không thành công do: Đã hết hạn chờ thanh toán',
            '12' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng bị khóa',
            '24' => 'Giao dịch không thành công do: Khách hàng hủy giao dịch',
            '51' => 'Giao dịch không thành công do: Tài khoản không đủ số dư để thực hiện giao dịch',
            '65' => 'Giao dịch không thành công do: Tài khoản của Quý khách đã vượt quá hạn mức giao dịch trong ngày',
            '75' => 'Ngân hàng thanh toán đang bảo trì',
            '79' => 'Giao dịch không thành công do: KH nhập sai mật khẩu thanh toán quá số lần quy định',
            '99' => 'Lỗi không xác định'
        ];
        
        return $messages[$responseCode] ?? 'Lỗi không xác định';
    }

    public function processCOD()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Method not allowed'
                ]);
                exit;
            }

            // Lấy dữ liệu từ request
            $rawData = file_get_contents('php://input');
            $data = json_decode($rawData, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Invalid JSON data'
                ]);
                exit;
            }

            $orderIds = $data['order_ids'] ?? '';
            $shippingMethod = $data['shipping_method'] ?? 'standard';

            if (empty($orderIds)) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Dữ liệu không hợp lệ'
                ]);
                exit;
            }

            // Cập nhật thông tin các đơn hàng
            $orderIdArray = explode(',', $orderIds);
            foreach ($orderIdArray as $orderId) {
                // Kiểm tra xem đơn hàng có thể thanh toán COD không
                if (!$this->orderModel->canPayCOD($orderId)) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Đơn hàng #' . $orderId . ' không thể thanh toán COD'
                    ]);
                    exit;
                }

                $this->orderModel->updateCOD($orderId, [
                    'status' => 'processing',
                    'shipping_method' => $shippingMethod,
                    'shipping_fee' => $shippingMethod === 'express' ? 40000 : 20000
                ]);
                
                // Lấy thông tin đơn hàng để lưu lịch sử thanh toán
                $order = $this->orderModel->getOrderById($orderId);
                if ($order) {
                    // Lưu lịch sử thanh toán
                    $paymentData = [
                        'order_id' => $orderId,
                        'amount' => $order['total_price'],
                        'status' => 0, // Pending
                        'payment_method' => 'cod'
                    ];
                    
                    $this->savePaymentHistory($paymentData);
                    error_log('Payment history saved for COD order #' . $orderId);
                }
            }

            $_SESSION['success'] = 'Đặt hàng thành công!';
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'message' => 'Đặt hàng thành công'
            ]);
            exit;

        } catch (\Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            exit;
        }
    }

    /**
     * Ghi log cấu trúc cột của bảng để debug
     * 
     * @param string $tableName Tên bảng cần kiểm tra
     * @return array Danh sách các cột
     */
    private function logTableColumns($tableName)
    {
        try {
            error_log('Fetching columns for table: ' . $tableName);
            $connection = $this->orderModel->getConnection();
            $sql = "SHOW COLUMNS FROM `$tableName`";
            $result = $connection->executeQuery($sql);
            $columns = $result->fetchAllAssociative();
            
            error_log('Columns for ' . $tableName . ': ' . json_encode(array_column($columns, 'Field')));
            return $columns;
        } catch (\Exception $e) {
            error_log('Error fetching table columns: ' . $e->getMessage());
            return [];
        }
    }
}

// Helper function
function response($data, $status = 200)
{
    header("Content-Type: application/json", true, $status);
    echo json_encode($data);
    exit;
}