<?php

require_once './config/database.php';
require_once 'controllers/CategoriaController.php';
require_once 'controllers/ProdutoController.php';

$method = $_SERVER['REQUEST_METHOD'];
$routeParts = explode('/', $_GET['route']);
$route = $routeParts[0];

// Instância do controlador com base na rota
switch ($route) {
    case 'categorias':
        $categoriaController = new CategoriaController($conn);

        // Ações a partir do método da solicitação
        if ($method === 'GET') {
            $categoriaController->listarCategorias();
        } elseif ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $categoriaController->criarCategoria($data);
        } elseif ($method === 'PUT') {
            $id = getIdFromRoute($_GET['route']);
            $data = json_decode(file_get_contents('php://input'), true);
            $categoriaController->atualizarCategoria($id, $data);
        } elseif ($method === 'DELETE') {
            $id = getIdFromRoute($_GET['route']);
            $categoriaController->excluirCategoria($id);
        }
        
        break;

    case 'produtos':
        $produtoController = new ProdutoController($conn);

        if ($method === 'GET') {
            $produtoController->listarProdutos();
        } elseif ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $produtoController->criarProduto($data);
        } elseif ($method === 'PUT') {
            $id = getIdFromRoute($_GET['route']);
            $data = json_decode(file_get_contents('php://input'), true);
            $produtoController->atualizarProduto($id, $data);
        } elseif ($method === 'DELETE') {
            $id = getIdFromRoute($_GET['route']);
            $produtoController->excluirProduto($id);
        }
        
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Rota não encontrada']);
        break;

        
}

function getIdFromRoute($route) {
    $parts = explode('/', $route);
    $lastPart = end($parts);
    $id = null;
    if (strpos($lastPart, '{') === false) {
        $id = $lastPart;
    } else {
        $id = str_replace(['{', '}'], '', $lastPart);
    }
    return $id;
}
