<?php

namespace App\Models;

use App\Model;

/**
 * Lớp UserAddress quản lý các thao tác liên quan đến địa chỉ người dùng
 * 
 * Lớp này kế thừa từ Model cơ sở và cung cấp các phương thức đặc thù
 * cho việc quản lý thông tin địa chỉ của người dùng trong hệ thống.
 */
class UserAddress extends Model {
    /**
     * @var string Tên bảng user_addresses trong cơ sở dữ liệu
     */
    protected $tableName = "user_addresses";

    /**
     * Đếm số lượng địa chỉ của người dùng
     * 
     * @param int $id ID của người dùng
     * @return int Số lượng địa chỉ của người dùng
     */
    public function countAddress($id) {
        $query = $this->conn->createQueryBuilder();
        $query->select("COUNT(*)")
            ->from($this->tableName)
            ->where("user_id = :id")
            ->setParameter("id", $id);
        return $query->fetchOne();
    }

    /**
     * Cập nhật địa chỉ của người dùng
     * 
     * @param int $id ID của người dùng
     * @param array $data Dữ liệu địa chỉ mới
     * @return int Số dòng bị ảnh hưởng
     */
    public function updateAddress($id, array $data) {
        return $this->conn->update($this->tableName, $data, ["user_id"=> $id]);
    }

    /**
     * Tìm địa chỉ mặc định của người dùng
     * 
     * @param int $id ID của người dùng
     * @return array|null Địa chỉ mặc định nếu tìm thấy, null nếu không tìm thấy
     */
    public function findId($id) {
        $query = $this->conn->createQueryBuilder();
        $query->select("*")
            ->from($this->tableName)
            ->where("user_id = :id")
            ->andWhere("is_default = 1")
            ->setParameter("id", $id);
        return $query->fetchOne();
    }
}