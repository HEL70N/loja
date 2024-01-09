<?php

namespace Code\Controller;

use Code\Authenticator\Authenticator;
use Code\DB\Connection;
use Code\Entity\User;
use Code\Session\Flash;
use Code\View\View;

class StoreController
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = new User(Connection::getInstance());
            $authenticator = new Authenticator($user);

            if (!$authenticator->login($_POST)) {
                Flash::add("warning", "Usuário ou senha incorretos!");
                return header("Location: " . HOME . '/store/login');
            }

            return header("Location: " . HOME . '/cart/checkout');
        }

        $view = new View('site/login.phtml');
        return $view->render();
    }
}
