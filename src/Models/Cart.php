<?php

namespace App\Models;

use App\Model;

/**
 * Lớp Cart quản lý các thao tác liên quan đến giỏ hàng
 * 
 * Lớp này kế thừa từ Model cơ sở và cung cấp các phương thức đặc thù
 * cho việc quản lý thông tin giỏ hàng trong hệ thống.
 */
class Cart extends Model
{
    /**
     * @var string Tên bảng cart trong cơ sở dữ liệu
     */
    protected $tableName = 'cart';
    
}