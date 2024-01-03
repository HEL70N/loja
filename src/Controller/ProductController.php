<?php

namespace Code\Controller;

use Code\DB\Connection;
use Code\Entity\Product;
use Code\View\View;

class ProductController
{
    public function view($slug)
    {
        $product = (new Product(Connection::getInstance()))->where(['slug' => $slug]);
        
        $view = new View('site/single.phtml');
        $view->product = $product;
        
        return $view->render();
    }
}
