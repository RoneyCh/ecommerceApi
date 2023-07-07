<?php
require_once('./config/database.php');
require_once('./models/Usuario.php');
class UsuarioController
{
    private $user;

    public function __construct($conn)
    {
        $this->user = new Usuario($conn);
    }

    public function listarUsuarios()
    {
        $usuarios = $this->user->listarTodos();
        echo json_encode($usuarios);
    }

    public function obterUsuario($id)
    {
        $usuario = $this->user->obterPorId($id);
        if ($usuario) {
            echo json_encode($usuario);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Usuário não encontrado.']);
        }
    }

    public function verificaAdmin($id)
    {
        $usuario = $this->user->obterPorId($id);
        if ($usuario['administrador'] == 1) {
            return true;
        }
        return false;
    }

    public function criarUsuario($data)
    {
        $usuario = [
            'nome' => $data['nome'],
            'endereco' => $data['endereco'],
            'email' => $data['email'],
            'login' => $data['nome'],
            'senha' => password_hash($data['senha'], PASSWORD_DEFAULT),
            'administrador' => $data['administrador']
        ];

        $usuarioId = $this->user->criar($usuario);
        if ($usuarioId) {
            echo json_encode(['id' => $usuarioId]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Não foi possível criar o usuário.']);
        }
    }

    public function atualizarUsuario($id, $dados)
    {
        $usuarioExistente = $this->user->obterPorId($id);

        if ($usuarioExistente) {
            if (empty($dados['administrador']) || !isset($dados['administrador'])) {
                $dados['administrador'] = 0; // Incluindo como usuário comum caso não seja informado
            }
            $usuarioAtualizado = [
                'id' => $id,
                'nome' => $dados['nome'],
                'endereco' => $dados['endereco'],
                'email' => $dados['email'],
                'login' => $dados['login'],
                'senha' => $dados['senha'],
                'administrador' => $dados['administrador']
            ];

            if ($this->user->atualizar($usuarioAtualizado)) {
                echo json_encode(['message' => 'Usuário atualizado com sucesso.']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Não foi possível atualizar o usuário.']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Usuário não encontrado.']);
        }
    }

    public function excluirUsuario($id)
    {
        $usuarioExistente = $this->user->obterPorId($id);

        if ($usuarioExistente) {
            if ($this->user->excluir($id)) {
                echo json_encode(['message' => 'Usuário excluído com sucesso.']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Não foi possível excluir o usuário.']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Usuário não encontrado.']);
        }
    }

    public function loginUsuario($data)
    {
        $email = $data['email'];
        $senha = $data['senha'];
        if (!isset($email) || !isset($senha)) {
            http_response_code(400);
            echo json_encode(['error' => 'Parâmetros incorretos.']);
        }

        $user = $this->user->obterPorEmail($email);
        if (password_verify($data['senha'], $user['senha'])) {
            if (!isset($_SESSION)) {
                session_start();
            }
            $_SESSION["user"] = $user['id'];
            http_response_code(200);
            echo json_encode(['id' => $user['id'], 'admin' => $user['administrador']]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Usuário não encontrado.']);
        }
    }

    public function getIdSession()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION['user'])) {
            $user = $this->user->obterPorId($_SESSION['user']);
            echo json_encode(['id' => $_SESSION['user'], 'isAdmin' => $user['administrador']]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Session id não encontrado']);
        }
    }

    public function logout()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        session_destroy();
        echo json_encode(['message' => 'Sessão finalizada']);
    }
}
