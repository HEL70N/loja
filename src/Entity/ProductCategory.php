<?php

namespace Code\Entity;

use Code\DB\Entity;

class ProductCategory extends Entity
{
    protected $table = 'products_categories';
    protected $timestamps = false;

    public function sync(int $productId, array $data)
    {
        // deletar todas as referencias
        $this->delete(['product_id' => $productId]);

        // insrir as novas passadas
        foreach ($data as $d) {
            $saveManyToMany = [
                'product_id' => $productId,
                'category' => $d
            ];
            
            $this->insert($data);
        }
    }
}