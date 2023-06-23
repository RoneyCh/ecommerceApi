<?php

require_once './config/database.php';
require_once 'controllers/CategoriaController.php';
require_once 'controllers/ProdutoController.php';
require_once 'controllers/UsuarioController.php';

$method = $_SERVER['REQUEST_METHOD'];
$routeParts = explode('/', $_GET['route']);
$route = $routeParts[0];
$id = getIdFromRoute($_GET['route']);

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json");
header("Access-Control-Allow-Credentials: true");
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

            $data = json_decode(file_get_contents('php://input'), true);
            $categoriaController->atualizarCategoria($id, $data);
        } elseif ($method === 'DELETE') {

            $categoriaController->excluirCategoria($id);
        }

        break;

    case 'produtos':
        $produtoController = new ProdutoController($conn);

        if ($method === 'GET' && empty($id)) {
            $produtoController->listarProdutos();
        } elseif ($method === 'GET' && !empty($id)) {
            $produtoController->obterProduto($id);
        } elseif ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $produtoController->criarProduto($data);
        } elseif ($method === 'PUT') {
            $data = json_decode(file_get_contents('php://input'), true);
            $produtoController->atualizarProduto($id, $data);
        } elseif ($method === 'DELETE') {
            $produtoController->excluirProduto($id);
        } else if ($method === 'OPTIONS') {
            echo 'teste';
            header("Access-Control-Allow-Origin: http://localhost:5173");
            header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
            header("Access-Control-Allow-Headers: Content-Type");
            header("Access-Control-Allow-Credentials: true");
            exit;
        }

        break;
    
    case 'foto':
        $produtoController = new ProdutoController($conn);
        if ($method === 'POST') {
            $produtoController->insereFoto($data);
        } else if ($method === 'OPTIONS') {
            header("Access-Control-Allow-Origin: http://localhost:5173");
            header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
            header("Access-Control-Allow-Headers: Content-Type");
            header("Access-Control-Allow-Credentials: true");
        } else if ($method === 'GET') {
            $produtoController->getImagem($_GET['path']);
        }
        break;
    case 'usuarios':
        $usuarioController = new UsuarioController($conn);
        if ($method === 'GET' && empty($id)) {
            $usuarioController->listarUsuarios();
        } elseif ($method === 'GET' && !empty($id)) {
            $usuarioController->obterUsuario($id);
        } elseif ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $usuarioController->criarUsuario($data);
        } elseif ($method === 'PUT') {
            $data = json_decode(file_get_contents('php://input'), true);
            $usuarioController->atualizarUsuario($id, $data);
        } elseif ($method === 'DELETE') {
            $usuarioController->excluirUsuario($id);
        } else if ($method === 'OPTIONS') {
            header("Access-Control-Allow-Origin: http://localhost:5173");
            header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
            header("Access-Control-Allow-Headers: Content-Type");
            header("Access-Control-Allow-Credentials: true");
            exit;
        }

        break;
    case 'login':
        $usuarioController = new UsuarioController($conn);
        if ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $usuarioController->loginUsuario($data);
        } else if ($method === 'OPTIONS') {
            header("Access-Control-Allow-Origin: http://localhost:5173");
            header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
            header("Access-Control-Allow-Headers: Content-Type");
            header("Access-Control-Allow-Credentials: true");
            exit;
        } else if ($method === 'GET') {
            $usuarioController->getIdSession();
        } else if ($method === 'DELETE') {
            $usuarioController->logout();
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Método não permitido']);
        }
        break;
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Rota não encontrada']);
        break;
}

function getIdFromRoute($route)
{
    $parts = explode('/', $route);
    $lastPart = end($parts);
    $id = null;

    if ($lastPart == 'usuarios' || $lastPart == 'produtos' || $lastPart == 'categorias') {
        $id = "";
    } else {
        if (strpos($lastPart, '{') === false) {
            $id = $lastPart;
        } else {
            $id = str_replace(['{', '}'], '', $lastPart);
        }
    }
    return $id;
}
