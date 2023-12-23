<?php
namespace Code\Controller\Admin;

use Ausi\SlugGenerator\SlugGenerator;
use Code\DB\Connection;
use Code\Session\Flash;
use Code\Entity\Product;
use Code\Security\Validator\Sanitizer;
use Code\Security\Validator\Validator;
use Code\View\View;

class ProductsController
{
    public function index()
    {
        $view = new View('admin/products/index.phtml');
        $view->products = (new Product(Connection::getInstance()))->findAll();
        
        return $view->render();
    }

    public function new()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $_POST;

            $data = Sanitizer::sanitizeData($data, Product::$filters);

			if(!Validator::validateRequiredFields($data)) {
				Flash::add('warning', 'Preencha todos os campos!');
				return header('Location: ' . HOME . '/admin/products/new');
			}

            $data['slug'] = (new SlugGenerator())->generate($data['name']);
            $data['price'] = str_replace('.', '', $data['price']);
			$data['price'] = str_replace(',', '.', $data['price']);

            $product = new Product(Connection::getInstance());

			if(!$product->insert($data)) {
				Flash::add('error', 'Erro ao criar produto!');
				return header('Location: ' . HOME . '/admin/products/new');
			}

			Flash::add('success', 'Produto criado com sucesso!');
			return header('Location: ' . HOME . '/admin/products');
        }

        return (new View('admin/products/new.phtml'))->render();
    }

    public function edit($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $_POST;
            
            $data = Sanitizer::sanitizeData($data, Product::$filters);
            
			if(!Validator::validateRequiredFields($data)) {
                Flash::add('warning', 'Preencha todos os campos!');
				return header('Location: ' . HOME . '/admin/products/new');
			}
            
            $data['id'] = (int) $id;
            $data['price'] = str_replace('.', '', $data['price']);
            $data['price'] = str_replace(',', '.', $data['price']);

            $product = new Product(Connection::getInstance());

            if(!$product->update($data)) {
				Flash::add('error', 'Erro actualizar produto!');
				return header('Location: ' . HOME . '/admin/products/edit' . $id);
			}

			Flash::add('success', 'Produto actualizado com sucesso!');
			return header('Location: ' . HOME . '/admin/products/edit' . $id);
        }

        $view = (new View('admin/products/edit.phtml'));
        $view->product = (new Product(Connection::getInstance()))->find($id);

        return $view->render();
    }
}