<?php

namespace App;

use Doctrine\DBAL\DriverManager;

/**
 * Lớp Model cơ sở cung cấp các thao tác cơ bản với cơ sở dữ liệu
 * 
 * Lớp này là nền tảng cho tất cả các lớp model trong ứng dụng.
 * Cung cấp các thao tác CRUD cơ bản và quản lý kết nối cơ sở dữ liệu.
 */
class Model
{
    /**
     * @var \Doctrine\DBAL\Connection Kết nối cơ sở dữ liệu
     */
    protected $conn;

    /**
     * @var string Tên bảng cơ sở dữ liệu tương ứng với model
     */
    protected $tableName;

    /**
     * @var \Doctrine\DBAL\Connection|null Kết nối cơ sở dữ liệu tĩnh
     */
    private static $connection = null;
    
    /**
     * Khởi tạo - Thiết lập kết nối cơ sở dữ liệu sử dụng biến môi trường
     */
    public function __construct()
    {
        if (self::$connection === null) {
            $connection = [
                'dbname' => $_ENV['DB_NAME'],
                'user' => $_ENV['DB_USERNAME'],
                'password' => $_ENV['DB_PASSWORD'] ?? '',
                'host' => $_ENV['DB_HOST'],
                'driver' => $_ENV['DB_DRIVER'],
            ];

            self::$connection = DriverManager::getConnection($connection);
        }
        $this->conn = self::$connection;
    }

    /**
     * Hủy - Đóng kết nối cơ sở dữ liệu
     */
    public function __destruct()
    {
        // Only close if this is the last instance using the connection
        if (self::$connection !== null && !$this->hasActiveConnections()) {
            self::$connection->close();
            self::$connection = null;
        }
    }

    /**
     * Kiểm tra xem có kết nối nào đang hoạt động không
     * 
     * @return bool True nếu có kết nối đang hoạt động, False nếu không
     */
    private function hasActiveConnections()
    {
        return count(get_object_vars($this)) > 0;
    }
    
    /**
     * Lấy tất cả bản ghi từ bảng
     * 
     * @return array Tất cả bản ghi từ bảng
     */
    public function findALL()
    {
        $query = $this->conn->createQueryBuilder();
        $query->select('*')->from($this->tableName);

        return $query->fetchAllAssociative();
    }

    /**
     * Lấy dữ liệu phân trang từ bảng
     * 
     * @param int $page Số trang hiện tại
     * @param int $limit Số bản ghi trên mỗi trang
     * @return array Dữ liệu phân trang cùng với thông tin meta
     */
    public function paginate($page = 1, $limit = 8)
    {
        $offset = ($page - 1) * $limit;

        $query = $this->conn->createQueryBuilder();
        $query->select('*')
            ->from($this->tableName)
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $data = $query->fetchAllAssociative();
        $totalPage = ceil($this->count() / $limit);

        return [
            'data' => $data,
            'page' => $page,
            'limit' => $limit,
            'totalPage' => $totalPage,
        ];
    }

    /**
     * Đếm tổng số bản ghi trong bảng
     * 
     * @return int Tổng số bản ghi
     */
    public function count()
    {
        $query = $this->conn->createQueryBuilder();
        $query->select('COUNT(*) AS total')->from($this->tableName);
        return $query->fetchOne();
    }

    /**
     * Tìm bản ghi theo ID
     * 
     * @param int $id ID của bản ghi cần tìm
     * @return array|null Bản ghi nếu tìm thấy, null nếu không tìm thấy
     */
    public function find($id)
    {
        $query = $this->conn->createQueryBuilder();
        $query->select('*')
            ->from($this->tableName)
            ->where('id = :id')
            ->setParameter('id', $id);
        return $query->fetchAssociative();
    }

    /**
     * Thêm bản ghi mới vào bảng
     * 
     * @param array $data Dữ liệu cần thêm
     * @return int ID của bản ghi vừa được thêm
     */
    public function insert(array $data)
    {
        // $data = [];
        if (isset($data['id'])) {
            unset($data['id']); // Loại bỏ id để database tự sinh
        }
        $this->conn->insert($this->tableName, $data);
        return $this->conn->lastInsertId();
    }

    /**
     * Cập nhật bản ghi đã tồn tại
     * 
     * @param int $id ID của bản ghi cần cập nhật
     * @param array $data Dữ liệu mới cho bản ghi
     * @return int Số dòng bị ảnh hưởng
     */
    public function update($id, array $data)
    {
        return $this->conn->update($this->tableName, $data, ['id' => $id]);
    }

    /**
     * Xóa bản ghi khỏi bảng
     * 
     * @param int $id ID của bản ghi cần xóa
     * @return int Số dòng bị ảnh hưởng
     */
    public function delete($id)
    {
        return $this->conn->delete($this->tableName, ['id' => $id]);
    }

    /**
     * Bắt đầu một giao dịch cơ sở dữ liệu
     */
    public function beginTransaction()
    {
        $this->conn->beginTransaction();
    }

    /**
     * Xác nhận giao dịch hiện tại
     */
    public function commit()
    {
        $this->conn->commit();
    }

    /**
     * Hủy bỏ giao dịch hiện tại
     */
    public function rollback()
    {
        $this->conn->rollBack();
    }

    /**
     * Xác thực người dùng bằng tên đăng nhập
     * 
     * @param string $user Tên đăng nhập cần xác thực
     * @return array Dữ liệu người dùng nếu tìm thấy
     */
    public function login($user)
    {
        $query = $this->conn->createQueryBuilder();
        $query->select('*')
            ->from($this->tableName)
            ->where('username = :name')
            ->setParameter('name', $user);
        return $query->fetchAllAssociative();
    }

    /**
     * Tìm kiếm sản phẩm theo tên kèm thông tin danh mục
     * 
     * @param string $keyword Từ khóa tìm kiếm
     * @return array Các sản phẩm phù hợp cùng với tên danh mục
     */
    public function searchProductsByName($keyword)
    {
        $query = $this->conn->createQueryBuilder();
        $query->select('p.id','p.name', 'c.name as category_name', 'p.price', 'p.image_url')
            ->from('products', 'p')
            ->leftJoin('p', 'categories', 'c', 'p.category_id = c.id')
            ->where('p.name LIKE :keyword')
            ->setParameter('keyword', '%' . $keyword . '%');
        return $query->fetchAllAssociative();
    }

    /**
     * Sắp xếp mảng sử dụng thuật toán quicksort
     * 
     * @param array $data Mảng cần sắp xếp
     * @param int $left Giới hạn trái
     * @param string $sort_by Trường cần sắp xếp
     * @param int|null $right Giới hạn phải
     * @return array Mảng đã được sắp xếp
     */
    public function quickSort(array $data, int $left, $sort_by, ?int $right = null)
    {
        $right = $right ?? count($data) - 1;
        if ($left < $right) {
            $pivotIndex = (int) (($left + $right) / 2);
            $pivot = $data[$pivotIndex];
            $i = $left;
            $j = $right;

            while ($i <= $j) {
                while ($data[$i][$sort_by] > $pivot[$sort_by]) $i++;
                while ($data[$j][$sort_by] < $pivot[$sort_by]) $j--;
                if ($i <= $j) {
                    $tmp = $data[$i];
                    $data[$i] = $data[$j];
                    $data[$j] = $tmp;

                    $i++;
                    $j--;
                }
            }

            if ($left < $j) {
                $data = $this->quickSort($data, $left, $sort_by, $j);
            }
            if ($i < $right) {
                $data = $this->quickSort($data, $i, $sort_by, $right);
            }
        }
        return $data;
    }
}

