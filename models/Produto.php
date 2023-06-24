<?php

class Produto {
    private $conn;
    private $table = 'Produto';

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function listarTodos() {
        $query = "SELECT p.id, p.descricao, p.preco, p.foto, p.quantidade, c.id as id_categoria 
        FROM " . $this->table . " as p 
        LEFT JOIN Produto_Categoria as pc ON p.id = pc.id_produto 
        LEFT JOIN Categoria as c ON c.id = pc.id_categoria";
        $result = $this->conn->query($query);

        $produtos = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $produtos[] = $row;
            }
        }

        return $produtos;
    }

    public function obterPorId($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = " . $id;
        $result = $this->conn->query($query);
        $produto = $result->fetch_assoc();

        return $produto;
    }

    public function criar($produto) {
        $query = "INSERT INTO " . $this->table . " (descricao, preco, quantidade, foto) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ssss', $produto['descricao'], $produto['preco'], $produto['quantidade'], $produto['foto']);
        $stmt->execute();
        $stmt->close();
        return $this->conn->insert_id;
    }

    public function atualizar($produto) {
        $query = "UPDATE " . $this->table . " SET descricao = '" . $produto['descricao'] . "', preco = '" . $produto['preco'] . "', quantidade = " . $produto['quantidade'] . " WHERE id = " . $produto['id'];

        return $this->conn->query($query);
    }

    public function excluir($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = " . $id;
    
        return $this->conn->query($query);
    }
    
}
