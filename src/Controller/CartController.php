<?php

namespace Code\Controller;

use Code\Session\Flash;
use Code\Session\Session;
use Code\View\View;

class CartController
{
    public function index()
    {
        $view = new View('site/cart.phtml');
        $view->cart = Session::get('cart');

        return $view->render();
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product = $_POST;

            $cart = Session::get('cart');

            if (!is_null($cart)) {
                array_push($cart, $product);
            } else {
                $cart[] =  $product;
            }

            Session::add('cart', $cart);

            Flash::add('success', 'Produto adicionado com sucesso!');
            return header('Location: ' . HOME . '/product/view/' . $product['slug']);
        }
    }
}
