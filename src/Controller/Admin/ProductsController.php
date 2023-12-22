<?php
namespace Code\Controller\Admin;

use Code\View\View;
use Code\DB\Connection;
use Code\Entity\User;

class ProductsController
{
    public function index()
    {
        $view = new View('admin/products/index.phtml');
        $view->products = (new Product(Connection::getInstance()))->findAll();
        
        return $view->render();
    }
}