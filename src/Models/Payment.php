<?php

namespace App\Models;

use App\Model;

/**
 * Lớp Payment quản lý các thao tác liên quan đến lịch sử thanh toán
 * 
 * Lớp này kế thừa từ Model cơ sở và cung cấp các phương thức đặc thù
 * cho việc quản lý thông tin thanh toán trong hệ thống.
 */
class Payment extends Model
{
    /**
     * @var string Tên bảng paymenthistory trong cơ sở dữ liệu
     */
    protected $tableName = 'paymenthistory';

    /**
     * Lấy danh sách tất cả lịch sử thanh toán với thông tin người nhận
     * 
     * @return array Danh sách lịch sử thanh toán với các thông tin: payment_id, user_id,
     * order_id, payment_method, amount, status, created_at, recipient_name
     */
    public function getAllPay() {
        $query = $this->conn->createQueryBuilder();
        $query->select('p.*', 'ud.recipient_name')
        ->from($this->tableName, 'p')
        ->leftJoin('p', 'user_addresses', 'ud', 'p.user_id = ud.user_id');

    return $query->fetchAllAssociative();
    }
}