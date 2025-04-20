<?php 

namespace App\Models;

use App\Model;

/**
 * Lớp Category quản lý các thao tác liên quan đến danh mục sản phẩm
 * 
 * Lớp này kế thừa từ Model cơ sở và cung cấp các phương thức đặc thù
 * cho việc quản lý thông tin danh mục sản phẩm trong hệ thống.
 */
class Category extends Model
{
    /**
     * @var string Tên bảng categories trong cơ sở dữ liệu
     */
    protected $tableName = 'categories';
}