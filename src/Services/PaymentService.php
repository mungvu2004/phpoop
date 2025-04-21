<?php

namespace App\Services;

class PaymentService
{
    private $vnp_TmnCode = ""; // Mã website tại VNPAY 
    private $vnp_HashSecret = ""; // Chuỗi bí mật
    private $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    private $vnp_ReturnUrl = "";

    public function __construct()
    {
        // Đọc các giá trị từ .env file
        if (isset($_ENV['VNPAY_TMN_CODE'])) {
            $this->vnp_TmnCode = trim($_ENV['VNPAY_TMN_CODE']);
        }
        
        if (isset($_ENV['VNPAY_HASH_SECRET'])) {
            $this->vnp_HashSecret = trim($_ENV['VNPAY_HASH_SECRET']);
        }
        
        // Cấu hình returnUrl sử dụng thông tin từ request hiện tại
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https://" : "http://";
        $this->vnp_ReturnUrl = $protocol . $host . "/payment/vnpay-return";
        
        error_log('VNPay config: TMN_CODE=' . $this->vnp_TmnCode . ', ReturnUrl=' . $this->vnp_ReturnUrl);
    }

    public function createPaymentUrl($orderId, $amount, $orderInfo = '')
    {
        error_log('createPaymentUrl params: orderId=' . $orderId . ', amount=' . $amount . ', orderInfo=' . $orderInfo);
        
        // Kiểm tra thông tin cấu hình VNPay
        if (empty($this->vnp_TmnCode) || empty($this->vnp_HashSecret)) {
            error_log('Missing VNPay config: TMN_CODE=' . $this->vnp_TmnCode . ', HashSecret is ' . (empty($this->vnp_HashSecret) ? 'empty' : 'set'));
            throw new \Exception('Chưa cấu hình thông tin VNPay trong file .env');
        }

        // Tạo mã giao dịch duy nhất bằng cách thêm timestamp và random string
        $uniqueTxnRef = $orderId . '_' . time() . '_' . substr(md5(uniqid()), 0, 8);
        error_log('Generated unique TxnRef: ' . $uniqueTxnRef);

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $this->vnp_TmnCode,
            "vnp_Amount" => (int)($amount * 100),
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            "vnp_Locale" => "vn",
            "vnp_OrderInfo" => $orderInfo,
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => $this->vnp_ReturnUrl,
            "vnp_TxnRef" => $uniqueTxnRef,
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $this->vnp_Url . "?" . $query;
        if (!empty($this->vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $this->vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return $vnp_Url;
    }

    public function verifyPayment($vnp_SecureHash, $inputData)
    {
        // Kiểm tra thông tin cấu hình VNPay
        if (empty($this->vnp_HashSecret)) {
            throw new \Exception('Chưa cấu hình VNPAY_HASH_SECRET trong file .env');
        }

        if (empty($vnp_SecureHash)) {
            throw new \Exception('Thiếu thông tin xác thực từ VNPay');
        }

        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $this->vnp_HashSecret);
        return $vnp_SecureHash === $secureHash;
    }
} 