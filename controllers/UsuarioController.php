<?php
require_once('./config/database.php');
require_once('./models/Usuario.php');
class UsuarioController {
    private $user;

    public function __construct($conn) {
        $this->user = new Usuario($conn);
    }

    public function listarUsuarios() {
        $usuarios = $this->user->listarTodos();
        echo json_encode($usuarios);
    }

    public function obterUsuario($id) {
        $usuario = $this->user->obterPorId($id);
        if ($usuario) {
            echo json_encode($usuario);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Usuário não encontrado.']);
        }
    }

    public function criarUsuario($data) {
        $usuario = [
            'nome' => $data['nome'],
            'endereco' => $data['endereco'],
            'email' => $data['email'],
            'login' => $data['login'],
            'senha' => $data['senha'],
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

    public function atualizarUsuario($id, $dados) {
        $usuarioExistente = $this->user->obterPorId($id);

        if ($usuarioExistente) {
            if(empty($dados['administrador']) || !isset($dados['administrador'])) { 
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

    public function excluirUsuario($id) {
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
}
