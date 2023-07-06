<?php

class Produto {
    private $conn;
    private $table = 'Produto';

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function listarTodos() {
        $query = "SELECT p.id, p.descricao, p.preco, p.foto, p.quantidade, c.id as id_categoria 
        FROM {$this->table} as p 
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
        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $produto = $result->fetch_assoc();

        return $produto;
    }

    public function criar($produto) {
        $query = "INSERT INTO {$this->table} (descricao, preco, quantidade, foto) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ssis', $produto['descricao'], $produto['preco'], $produto['quantidade'], $produto['foto']);
        $stmt->execute();
        $insertId = $stmt->insert_id;
        $stmt->close();

        return $insertId;
    }

    public function atualizar($produto) {
        $query = "UPDATE {$this->table} SET descricao = ?, preco = ?, quantidade = ?, foto = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ssdsi', $produto['descricao'], $produto['preco'], $produto['quantidade'], $produto['foto'], $produto['id']);

        $stmt->execute();
        $stmt->close();

        return $this->conn->affected_rows;
    }

    public function excluir($id) {
        $query = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();

        return $this->conn->affected_rows;
    }
    
}