<?php
namespace Code\Controller\Admin;

use Code\View\View;

class ProductsController
{
    public function index()
    {
        $view = new View('admin/products/index.phtml');
        $view->products = [];
        
        return $view->render();
    }
}