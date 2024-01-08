<?php

namespace Code\Controller;

use Code\DB\Connection;
use Code\Entity\Product;
use Code\View\View;

class ProductController
{
    public function view($slug)
    {
        $product = (new Product(Connection::getInstance()))->getProductWithImagesById($slug, true);
        $lgPhoto = array_shift($product['images']);

        $view = new View('site/single.phtml');
        $view->product = $product;

        $view->lgPhoto = $lgPhoto;

        return $view->render();
    }
}
