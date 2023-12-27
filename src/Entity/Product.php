<?php

namespace Code\Entity;

use Code\DB\Entity;

class Product extends Entity
{
    protected $table = 'products';
    static $filters = [
        'name' => FILTER_UNSAFE_RAW,
        'description' => FILTER_UNSAFE_RAW,
        'content' => FILTER_UNSAFE_RAW,
        'price' => ['filter' => FILTER_SANITIZE_NUMBER_FLOAT, 'flags' => FILTER_FLAG_ALLOW_THOUSAND]
    ];

    public function getProductWithImagesById($product_id)
    {
        $sql = 'SELECT
                    p.*, pi.id AS image_id, 
                    pi.image 
                FROM
                    products p 
                INNER JOIN
                    products_images pi ON pi.product_id = p.id 
                WHERE p.id = :productId';

        $select = $this->conn->prepare($sql);
        $select->bindValue(':productId', $product_id, \PDO::PARAM_INT);
        $select->execute();

        return $select->fetchAll(\PDO::FETCH_ASSOC);
    }
}