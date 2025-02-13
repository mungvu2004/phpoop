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
            'password' => $_ENV['DB_PASSWORD'],
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

    public function paginate($page = 1, $limit = 10) 
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
        return $query->fetchAllAssociative();
    }
    public function insert(array $data)
    {
        // $data = [];
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
    public function login($user, $pass)
    {
        $query = $this->conn->createQueryBuilder();
        $query->select('*')
            ->from($this->tableName)
            ->where('name = :name')
            ->andWhere('password = :password')
            ->setParameter('name', $user)
            ->setParameter('password', $pass);
        return $query->fetchAllAssociative();
    }
}