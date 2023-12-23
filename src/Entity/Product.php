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
}