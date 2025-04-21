<?php

namespace App\Models;

use App\Model;

/**
 * Lớp Order quản lý các thao tác liên quan đến đơn hàng
 * 
 * Lớp này kế thừa từ Model cơ sở và cung cấp các phương thức đặc thù
 * cho việc quản lý thông tin đơn hàng trong hệ thống.
 */
class Order extends Model
{
    /**
     * @var string Tên bảng orders trong cơ sở dữ liệu
     */
    protected $tableName = 'orders';

    protected $fillable = [
        'user_id',
        'total_price',
        'total_amount',
        'status',
        'payment_status',
        'payment_method',
        'payment_time',
        'created_at',
        'updated_at'
    ];

    /**
     * Trả về kết nối database để sử dụng trong các class khác
     */
    public function getConnection() 
    {
        return $this->conn;
    }

    /**
     * Tạo mới một đơn hàng
     * 
     * @param array $data Dữ liệu đơn hàng
     * @return int ID của đơn hàng mới
     */
    public function create(array $data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['payment_status'] = $data['payment_status'] ?? 'pending';
        
        return $this->insert($data);
    }

    /**
     * Cập nhật thông tin đơn hàng
     * 
     * @param int $id ID đơn hàng
     * @param array $data Dữ liệu cập nhật
     * @return int Số dòng ảnh hưởng
     */
    public function update($id, array $data)
    {
        // Ghi log trước khi cập nhật
        $oldData = $this->find($id);
        error_log("Update order $id - Old data: " . json_encode($oldData));
        error_log("Update order $id - New data: " . json_encode($data));
        
        // Kiểm tra cấu trúc bảng trước khi thêm updated_at
        try {
            $tableColumns = $this->getTableColumns($this->tableName);
            $columnNames = array_column($tableColumns, 'Field');
            
            // Chỉ thêm updated_at nếu cột này tồn tại trong bảng
            if (in_array('updated_at', $columnNames)) {
                $data['updated_at'] = date('Y-m-d H:i:s');
            }
            
            // Lọc dữ liệu để chỉ lấy các cột tồn tại trong bảng
            $filteredData = [];
            foreach ($data as $key => $value) {
                if (in_array($key, $columnNames)) {
                    $filteredData[$key] = $value;
                } else {
                    error_log("Column '$key' does not exist in table '$this->tableName', skipping...");
                }
            }
            
            error_log("Filtered data for update: " . json_encode($filteredData));
            $result = parent::update($id, $filteredData);
            
            // Ghi log sau khi cập nhật
            error_log("Update order $id - Result: " . ($result ? "success" : "failed"));
            
            return $result;
        } catch (\Exception $e) {
            error_log("Error in update method: " . $e->getMessage());
            
            // Thử cập nhật không có updated_at
            unset($data['updated_at']);
            $result = parent::update($id, $data);
            
            error_log("Fallback update order $id - Result: " . ($result ? "success" : "failed"));
            return $result;
        }
    }

    /**
     * Lấy cấu trúc bảng
     * 
     * @param string $tableName Tên bảng
     * @return array Thông tin cấu trúc bảng
     */
    private function getTableColumns($tableName)
    {
        $statement = $this->conn->executeQuery("DESCRIBE $tableName");
        return $statement->fetchAllAssociative();
    }

    /**
     * Lấy dữ liệu doanh thu theo tháng
     * 
     * @param int|null $month Tháng cần lấy dữ liệu (mặc định là tháng hiện tại)
     * @param int|null $year Năm cần lấy dữ liệu (mặc định là năm hiện tại)
     * @return array Dữ liệu doanh thu theo ngày trong tháng
     */
    public function dataMonth($month = null, $year = null)
    {
        $month = $month ?? date('n');
        $year = $year ?? date('Y');
        $query = $this->conn->createQueryBuilder();
        $query->select('DAY(created_at) AS day, total_price')
            ->from($this->tableName)
            ->where('MONTH(created_at) = :month')
            ->andWhere('YEAR(created_at) = :year')
            ->andWhere('status = :status')
            ->setParameter('month', $month)
            ->setParameter('year', $year)
            ->setParameter('status', 'completed')
            ->orderBy('DAY(created_at)', 'ASC');

        return $query->fetchAllAssociative();
    }

    /**
     * Lấy danh sách tất cả đơn hàng với thông tin người dùng và mã giảm giá
     * 
     * @return array Danh sách đơn hàng với các thông tin: order_id, user_id, total_price, 
     * status, shipping_address, shipping_method, shipping_fee, username, coupon_code
     */
    public function getAll()
    {
        $query = $this->conn->createQueryBuilder();
        $query->select('o.*, u.username, c.code AS coupon_code')
            ->from($this->tableName, 'o')
            ->leftJoin('o', 'users', 'u', 'o.user_id = u.id')
            ->leftJoin('o', 'coupons', 'c', 'o.coupon_id = c.id');
        return $query->fetchAllAssociative();
    }

    /**
     * Lấy danh sách đơn hàng phân trang
     * 
     * @param int $page Số trang hiện tại
     * @param int $limit Số đơn hàng trên mỗi trang
     * @return array Dữ liệu phân trang gồm: data, page, limit, totalItem, totalPage
     */
    public function paginateOrder($page = 1, $limit = 8)
    {
        $offset = ($page - 1) * $limit;
        $query = $this->conn->createQueryBuilder();
        $query->select('o.*, u.username, c.code AS coupon_code')
            ->from($this->tableName, 'o')
            ->leftJoin('o', 'users', 'u', 'o.user_id = u.id')
            ->leftJoin('o', 'coupons', 'c', 'o.coupon_id = c.id')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $data =  $query->fetchAllAssociative();
        $totalItem = $this->count();
        $totalPage = ceil($this->count() / $limit);

        return [
            'data' => $data,
            'page' => $page,
            'limit' => $limit,
            'totalItem' => $totalItem,
            'totalPage' => $totalPage,
        ];
    }

    /**
     * Lấy chi tiết đơn hàng theo ID
     * 
     * @param int $id ID của đơn hàng
     * @return array Chi tiết đơn hàng với các thông tin: order_id, user_id, total_price,
     * status, shipping_address, shipping_method, shipping_fee, quantity, subtotal,
     * product_name, price, image_url, username, payment_method
     */
    public function detailOrder($id)
    {
        $query = $this->conn->createQueryBuilder();
        $query->select(
            // Từ bảng orders (o)
            'o.id AS order_id',
            'o.user_id',
            'o.total_price',
            'o.status',
            'o.shipping_address',
            'o.shipping_method',
            'o.shipping_fee',

            // Từ bảng orderdetails (od)
            'od.quantity',
            'od.subtotal',

            // Từ bảng products (p)
            'p.name AS product_name',
            'p.price',
            'p.image_url',

            // Từ bảng users (u) - loại bỏ password_hash
            'u.username',

            // Từ bảng paymenthistory (ph)
            'ph.payment_method'
        )
            ->from($this->tableName, 'o') // Bảng orders
            ->leftJoin('o', 'orderdetails', 'od', 'o.id = od.order_id') // Liên kết với orderdetails
            ->leftJoin('od', 'products', 'p', 'od.product_id = p.id') // Liên kết với products
            ->leftJoin('o', 'users', 'u', 'o.user_id = u.id') // Liên kết với users
            ->leftJoin('o', 'paymenthistory', 'ph', 'o.id = ph.order_id') // Liên kết với paymenthistory
            ->where('o.id = :id')
            ->setParameter('id', $id);

        return $query->fetchAllAssociative();
    }

    /**
     * Tìm đơn hàng theo ID người dùng
     * 
     * @param int $id ID của người dùng
     * @return array Danh sách đơn hàng của người dùng
     */
    public function findUser(int $id)
    {
        $qb = $this->conn->createQueryBuilder();
        $qb->select('*')
            ->from($this->tableName)
            ->where('user_id = :id')
            ->setParameter('id', $id);
        return $qb->fetchAllAssociative();
    }
    public function userOrder($id)
    {
        $query = $this->conn->createQueryBuilder();

        $query
            ->select(
                'o.id AS order_id',
                'o.user_id',
                'o.total_price',
                'o.coupon_id',
                'c.code AS coupon_name',
                'c.discount',
                'o.status',
                'o.shipping_fee',
                'o.created_at',
                'SUM(oi.quantity) AS total_quantity'
            )
            ->from($this->tableName, 'o')
            ->join('o', 'orderdetails', 'oi', 'o.id = oi.order_id')
            ->leftJoin('o', 'coupons', 'c', 'o.coupon_id = c.id')
            ->where('o.user_id = :user_id')
            ->groupBy('o.id, o.user_id, o.total_price, o.status, o.created_at')
            ->orderBy('o.created_at', 'DESC')
            ->setParameter('user_id', $id);
        return $query->fetchAllAssociative();
    }

    /**
     * Lấy thông tin chi tiết của một đơn hàng theo ID
     * 
     * @param int $id ID của đơn hàng
     * @return array|null Thông tin chi tiết đơn hàng hoặc null nếu không tìm thấy
     */
    public function getOrderById($id)
    {
        $query = $this->conn->createQueryBuilder();
        $query->select(
                'o.*',
                'c.code AS coupon_code',
                'c.discount',
                'c.is_percentage',
                'CASE 
                    WHEN c.is_percentage = 1 THEN o.total_price * (1 - c.discount/100)
                    ELSE o.total_price - c.discount
                END AS final_price'
            )
            ->from($this->tableName, 'o')
            ->leftJoin('o', 'coupons', 'c', 'o.coupon_id = c.id')
            ->where('o.id = :id')
            ->setParameter('id', $id);

        return $query->fetchAssociative();
    }

    public function canPayCOD($orderId)
    {
        $order = $this->getOrderById($orderId);
        if (!$order) {
            return false;
        }

        // Kiểm tra trạng thái đơn hàng
        if ($order['status'] != 'pending') {
            return false;
        }

        // Kiểm tra tổng giá trị đơn hàng
        if ($order['total_price'] > 5000000) { // Giới hạn 5 triệu cho COD
            return false;
        }

        return true;
    }

    /**
     * Cập nhật thông tin thanh toán COD
     * 
     * @param int $orderId ID của đơn hàng
     * @param array $paymentData Dữ liệu thanh toán
     * @return bool Kết quả cập nhật
     */
    public function updateCOD($orderId, $paymentData)
    {
        if (!$this->canPayCOD($orderId)) {
            return false;
        }

        $query = $this->conn->createQueryBuilder();
        $query->update($this->tableName)
            ->set('payment_method', ':payment_method')
            ->set('status', ':status')
            ->where('id = :id')
            ->setParameter('payment_method', 'COD')
            ->setParameter('status', 'pending')
            ->setParameter('id', $orderId);

        return $query->fetchOne();
    }
}
