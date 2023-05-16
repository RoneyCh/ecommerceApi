<?php

class Produto {
    private $conn;
    private $table = 'Produto';

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function listarTodos() {
        $query = "SELECT p.id, p.descricao, p.preco, p.foto, p.quantidade, c.descricao as categoria 
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
        $query = "INSERT INTO " . $this->table . " (descricao, preco,quantidade) VALUES ('" . $produto['descricao'] . "', '" . $produto['preco'] . "', " . $produto['quantidade'] . ")";

        if ($this->conn->query($query)) {
            echo "\n Inseriu o produto! \n";
            return $this->conn->insert_id;
        } else {
            echo "\n Deu ruim! \n";
            return false;
        }
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
