<?php

require_once './config/database.php';
require_once 'controllers/CategoriaController.php';

$method = $_SERVER['REQUEST_METHOD'];
$routeParts = explode('/', $_GET['route']);
$route = $routeParts[0];

// instância do controler com base na rota
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
