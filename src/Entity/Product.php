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
        'price' => ['filter' => FILTER_SANITIZE_NUMBER_FLOAT, 'flags' => FILTER_FLAG_ALLOW_THOUSAND],
        'is_active' => FILTER_UNSAFE_RAW
    ];

    public function getProductWithImagesById($product, $isSlug = false)
    {
        $sql = 'SELECT
                    p.*, pi.id AS image_id, 
                    pi.image 
                FROM
                    products p 
                LEFT JOIN
                    products_images pi ON pi.product_id = p.id 
                ';

        if ($isSlug) {
            $sql .= " WHERE p.slug = :product";
        } else {
            $sql .= " WHERE p.id = :product";
        }

        $select = $this->conn->prepare($sql);
        $select->bindValue(':product', $product, $isSlug ? \PDO::PARAM_STR : \PDO::PARAM_INT);
        $select->execute();

        $productData = [];
        foreach ($select->fetchAll(\PDO::FETCH_ASSOC) as $product) {
            $productData['id'] = $product['id'];
            $productData['name'] = $product['name'];
            $productData['description'] = $product['description'];
            $productData['content'] = $product['content'];
            $productData['price'] = $product['price'];
            $productData['is_active'] = $product['is_active'];
            $productData['slug'] = $product['slug'];
            $productData['images'][] = ['id' => $product['image_id'], 'image' => $product['image']];
        }

        return $productData;
    }

    public function getAllProductsWithThumb()
    {
        $sql =
            'SELECT products.*, (SELECT image FROM products_images WHERE product_id = products.id LIMIT 1) AS image FROM products
        ';

        $product = $this->conn->query($sql);

        return $product->fetchAll(\PDO::FETCH_ASSOC);
    }
}
