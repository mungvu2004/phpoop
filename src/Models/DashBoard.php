<?php

namespace App\Models;

use App\Model;

/**
 * Lớp DashBoard quản lý các thao tác liên quan đến bảng điều khiển
 * 
 * Lớp này kế thừa từ Model cơ sở và cung cấp các phương thức đặc thù
 * cho việc quản lý thông tin thống kê và báo cáo trong hệ thống.
 */
class DashBoard extends Model
{
    /**
     * @var string Tên bảng dashboard trong cơ sở dữ liệu
     */
    protected $tableName = 'dashboard';
}
