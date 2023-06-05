<?php

require_once 'AutenticaMiddleware.php';

class Usuario {
    private $conn;
    private $table = 'Usuario';

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function listarTodos() {
        verificaAcesso();
        $query = "SELECT * FROM " . $this->table;
        $result = $this->conn->query($query);

        $usuarios = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $usuarios[] = $row;
            }
        }

        return $usuarios;
    }

    public function obterPorId($id) {
        verificaAcesso();
        $query = "SELECT * FROM " . $this->table . " WHERE id = " . $id;
        $result = $this->conn->query($query);
        $usuario = $result->fetch_assoc();

        return $usuario;
    }

    public function criar($usuario) {
        verificaAcesso();
        $nome = $usuario['nome'];
        $endereco = $usuario['endereco'];
        $email = $usuario['email'];
        $login = $usuario['login'];
        $senha = $usuario['senha'];
        $administrador = $usuario['administrador'];

        $query = "INSERT INTO " . $this->table . " (nome, endereco, email, login, senha, administrador) VALUES ('$nome', '$endereco', '$email', '$login', '$senha', $administrador)";
        if ($this->conn->query($query)) {
            echo "\n Inseriu o usuario! \n";
            return $this->conn->insert_id;
        } else {
            echo "\n Deu ruim! \n";
            return false;
        }
    }

    public function atualizar($usuario) {
        verificaAcesso();
        $id = $usuario['id'];
        $nome = $usuario['nome'];
        $endereco = $usuario['endereco'];
        $email = $usuario['email'];
        $login = $usuario['login'];
        $senha = $usuario['senha'];
        $administrador = $usuario['administrador'];

        $query = "UPDATE " . $this->table . " SET nome = '$nome', endereco = '$endereco', email = '$email', login = '$login', senha = '$senha', administrador = $administrador WHERE id = " . $id;
        return $this->conn->query($query);
    }

    public function excluir($id) {
        verificaAcesso();
        $query = "DELETE FROM " . $this->table . " WHERE id = " . $id;
        if($this->conn->query($query)){
            echo "\n Deletou o usuario! \n";
            return true;
        } else {
            echo "\n Deu ruim! \n";
            return false;
        }
    }
}
