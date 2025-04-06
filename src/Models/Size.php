<?php
namespace App\Models;

use App\Model;

class Size extends Model 
{
    protected $tableName = "size";
    public function selectAll($id)  {
        $query = $this->conn->createQueryBuilder();
        $query->select('*')
            ->from($this->tableName)
            ->where('product_id = :id')
            ->setParameter('id', $id);
        return $query->fetchAllAssociative();
    }
    public function deleteSize($id) {
        $query = $this->conn->createQueryBuilder();
        $query->delete($this->tableName)
            ->where('product_id = :id')
            ->setParameter('id', $id);
        return $query->executeStatement();
    }
}