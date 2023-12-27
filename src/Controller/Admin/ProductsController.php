<?php

namespace Code\Controller\Admin;

use Ausi\SlugGenerator\SlugGenerator;
use Code\DB\Connection;
use Code\Session\Flash;
use Code\Entity\Product;
use Code\Entity\ProductImage;
use Code\Security\Validator\Sanitizer;
use Code\Security\Validator\Validator;
use Code\Upload\Upload;
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
			$images = $_FILES['images'];

			$data = Sanitizer::sanitizeData($data, Product::$filters);

			if (!Validator::validateRequiredFields($data)) {
				Flash::add('warning', 'Preencha todos os campos!');
				return header('Location: ' . HOME . '/admin/products/new');
			}

			$data['slug'] = (new SlugGenerator())->generate($data['name']);
			$data['price'] = str_replace('.', '', $data['price']);
			$data['price'] = str_replace(',', '.', $data['price']);

			$product = new Product(Connection::getInstance());
			$productId = $product->insert($data);

			if (!$productId) {
				Flash::add('error', 'Erro ao criar produto!');
				return header('Location: ' . HOME . '/admin/products/new');
			}

			if (isset($images['name']) && $images['name']) {
				$upload = new Upload();
				$upload->setFolder(UPLOAD_PATH . '/products/');
				$images = $upload->doUpload($images);

				foreach ($images as $image) {
					$imagesData = [];
					$imagesData['product_id'] = $productId;
					$imagesData['image'] = $images;

					$productImages = new ProductImage(Connection::getInstance());
					$productImages->insert($imagesData);
				}
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

			if (!Validator::validateRequiredFields($data)) {
				Flash::add('warning', 'Preencha todos os campos!');
				return header('Location: ' . HOME . '/admin/products/new');
			}

			$data['id'] = (int) $id;
			$data['price'] = str_replace('.', '', $data['price']);
			$data['price'] = str_replace(',', '.', $data['price']);

			$product = new Product(Connection::getInstance());

			if (!$product->update($data)) {
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

	public function remove($id = null)
	{
		try {
			$product = new Product(Connection::getInstance());

			if (!$product->delete($id)) {
				Flash::add('error', 'Erro ao realizar remoção do produto!');
				return header('Location: ' . HOME . '/admin/products');
			}

			Flash::add('success', 'Produto removido com sucesso!');
			return header('Location: ' . HOME . '/admin/products');
		} catch (\Exception $e) {
			if (APP_DEBUG) {
				Flash::add('error', $e->getMessage());
				return header('Location: ' . HOME . '/admin/products');
			}
			Flash::add('error', 'Ocorreu um problema interno, por favor contacte o admin.');
			return header('Location: ' . HOME . '/admin/products');
		}
	}
}
