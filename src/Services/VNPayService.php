<?php
namespace Src\Services;

class VNPayService {
    private $config;

    public function __construct() {
        $this->config = include __DIR__ . '/../Config/vnpay_config.php';
    }

    public function createPaymentUrl($orderInfo, $amount, $txnRef) {
        $vnp_TmnCode = $this->config['vnp_TmnCode'];
        $vnp_HashSecret = $this->config['vnp_HashSecret'];
        $vnp_Url = $this->config['vnp_Url'];
        $vnp_Returnurl = $this->config['vnp_Returnurl'];

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $amount * 100,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $_SERVER['REMOTE_ADDR'],
            "vnp_Locale" => "vn",
            "vnp_OrderInfo" => $orderInfo,
            "vnp_OrderType" => "billpayment",
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $txnRef
        ];

        ksort($inputData);
        $hashData = '';
        $query = '';
        foreach ($inputData as $key => $value) {
            $hashData .= $key . "=" . $value . '&';
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $hashData = rtrim($hashData, '&');
        $query = rtrim($query, '&');
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        $paymentUrl = $vnp_Url . '?' . $query . '&vnp_SecureHash=' . $secureHash;

        return $paymentUrl;
    }
}
