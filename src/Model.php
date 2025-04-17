<?php

namespace App;

use Doctrine\DBAL\DriverManager;

class Model
{
    protected $conn;
    protected $tableName;

    public function __construct()
    {
        $connection = [
            'dbname' => $_ENV['DB_NAME'],
            'user' => $_ENV['DB_USERNAME'],
            'password' => $_ENV['DB_PASSWORD'] ?? '',
            'host' => $_ENV['DB_HOST'],
            'driver' => $_ENV['DB_DRIVER'],
        ];

        $this->conn = DriverManager::getConnection($connection);
    }

    public function __destruct()
    {
        $this->conn->close();
    }
    
    public function findALL()
    {
        $query = $this->conn->createQueryBuilder();
        $query->select('*')->from($this->tableName);

        return $query->fetchAllAssociative();
    }

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
    public function count()
    {
        $query = $this->conn->createQueryBuilder();
        $query->select('COUNT(*) AS total')->from($this->tableName);
        return $query->fetchOne();
    }

    public function find($id)
    {
        $query = $this->conn->createQueryBuilder();
        $query->select('*')
            ->from($this->tableName)
            ->where('id = :id')
            ->setParameter('id', $id);
        return $query->fetchAssociative();
    }
    public function insert(array $data)
    {
        // $data = [];
        if (isset($data['id'])) {
            unset($data['id']); // Loại bỏ id để database tự sinh
        }
        $this->conn->insert($this->tableName, $data);
        return $this->conn->lastInsertId();
    }

    public function update($id, array $data)
    {
        return $this->conn->update($this->tableName, $data, ['id' => $id]);
    }

    public function delete($id)
    {
        return $this->conn->delete($this->tableName, ['id' => $id]);
    }

    public function beginTransaction()
    {
        $this->conn->beginTransaction();
    }

    public function commit()
    {
        $this->conn->commit();
    }

    public function rollback()
    {
        $this->conn->rollBack();
    }
    public function login($user)
    {
        $query = $this->conn->createQueryBuilder();
        $query->select('*')
            ->from($this->tableName)
            ->where('username = :name')
            ->setParameter('name', $user);
        return $query->fetchAllAssociative();
    }
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
                $data = $this->quickSort($data, $left, $j, $sort_by);
            }
            if ($i < $right) {
                $data = $this->quickSort($data, $i, $right, $sort_by);
            }
        }
        return $data;
    }

}

