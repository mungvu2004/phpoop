<?php

namespace App\Models;

use App\Model;

class Product extends Model
{
    protected $tableName = 'products';

    public function getAll()
    {
        $qb = $this->conn->createQueryBuilder();

        $qb->select(
            'p.id',
            'p.name',
            'p.category_id',
            'p.price',
            'p.stock_quantity',
            'p.image_url',
            'p.description',
            'p.is_active',
            'AVG(r.rating) AS average_rating'
        )
            ->from($this->tableName, 'p')
            ->leftJoin('p', 'reviews', 'r', 'p.id = r.product_id')
            ->where('p.is_active = 1')
            ->groupBy(
                'p.id',
                'p.name',
                'p.category_id',
                'p.price',
                'p.stock_quantity',
                'p.image_url',
                'p.description',
                'p.is_active'
            )
            ->orderBy('p.id', 'DESC') // hoặc 'p.created_at' nếu có
            ->setMaxResults(4);

        return $qb->fetchAllAssociative();
    }
    public function rating(): mixed
    {
        $qb = $this->conn->createQueryBuilder();
        $qb->select(
            'p.id',
            'p.name',
            'p.category_id',
            'p.price',
            'p.stock_quantity',
            'p.image_url',
            'p.description',
            'p.is_active',
            'AVG(r.rating) AS average_rating'
        )
            ->from($this->tableName, 'p')
            ->leftJoin('p', 'reviews', 'r', 'p.id = r.product_id')
            ->where('p.is_active = 1')
            ->groupBy(
                'p.id',
                'p.name',
                'p.category_id',
                'p.price',
                'p.stock_quantity',
                'p.image_url',
                'p.description',
                'p.is_active'
            )
            ->orderBy('average_rating', 'DESC')
            ->setMaxResults(4);
        return $qb->fetchAllAssociative();
    }
    public function listProduct(): mixed
    {
        $qb = $this->conn->createQueryBuilder();

        $qb->select(
            'p.id',
            'p.name',
            'p.category_id',
            'p.price',
            'p.stock_quantity',
            'p.image_url',
            'p.description',
            'p.is_active',
            'AVG(r.rating) AS average_rating'
        )
            ->from('products', 'p')
            ->leftJoin('p', 'reviews', 'r', 'p.id = r.product_id')
            ->groupBy(
                'p.id',
                'p.name',
                'p.category_id',
                'p.price',
                'p.stock_quantity',
                'p.image_url',
                'p.description',
                'p.is_active'
            );
        return $qb->fetchAllAssociative();
    }
}
