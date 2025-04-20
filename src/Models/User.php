<?php

namespace App\Models;

use App\Model;

/**
 * Lớp User quản lý các thao tác liên quan đến người dùng
 * 
 * Lớp này kế thừa từ Model cơ sở và cung cấp các phương thức đặc thù
 * cho việc quản lý thông tin người dùng trong hệ thống.
 */
class User extends Model
{
    /**
     * @var string Tên bảng users trong cơ sở dữ liệu
     */
    protected $tableName = 'users';
    
    /**
     * Lấy danh sách tất cả người dùng với thông tin địa chỉ
     * 
     * @return array Danh sách người dùng với các thông tin: id, username, email, role, is_active, recipient_name
     */
    public function getAllUser() {
        $query = $this->conn->createQueryBuilder();
        $query->select(
            'u.id',
            'u.username',
            'u.email',
            'u.role',
            'u.is_active',
            'ud.recipient_name'
        )
            ->from($this->tableName, 'u')
            ->leftJoin('u', 'user_addresses', 'ud', 'u.id = ud.user_id');
        return $query->fetchAllAssociative();
    }
    /**
     * Lấy thông tin cơ bản của một người dùng theo ID
     * 
     * @param int $id ID của người dùng
     * @return array|null Thông tin người dùng (email, username) nếu tìm thấy
     */
    public function getUser($id) {
        $query = $this->conn->createQueryBuilder();
        $query->select(
            'u.email',
            'u.username'
        )
            ->from($this->tableName, 'u')
            ->where('u.id = :id')
            ->setParameter('id', $id);
        return $query->fetchAssociative();
    }
    /**
     * Lấy chi tiết thông tin địa chỉ của người dùng
     * 
     * @param int $id ID của người dùng
     * @return array Danh sách địa chỉ của người dùng
     */
    public function detailUser($id) {
        $query = $this->conn->createQueryBuilder();
        $query->select(
            'ud.*',
        )
            ->from($this->tableName, 'u')
            ->leftJoin('u', 'user_addresses', 'ud', 'u.id = ud.user_id')
            ->where('u.id = :id')
            ->setParameter('id', $id);
        return $query->fetchAllAssociative();
    }
    /**
     * Kiểm tra tên đăng nhập đã tồn tại chưa
     * 
     * @param string $username Tên đăng nhập cần kiểm tra
     * @return int Số lượng tài khoản có tên đăng nhập này
     */
    public function checkUsername($username) {
        $query = $this->conn->createQueryBuilder();
        $query->select('COUNT(*)')
            ->from($this->tableName)
            ->where('username = :username')
            ->setParameter('username', $username);
        return $query->fetchOne();
    }
    /**
     * Kiểm tra email đã tồn tại chưa
     * 
     * @param string $email Email cần kiểm tra
     * @return int Số lượng tài khoản có email này
     */
    public function checkEmail($email) {
        $query = $this->conn->createQueryBuilder();
        $query->select('COUNT(*)')
            ->from($this->tableName)
            ->where('email = :email')
            ->setParameter('email', $email);
        return $query->fetchOne();
    }
}