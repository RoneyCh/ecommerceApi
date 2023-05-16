<?php 
class ProdutoCategoria {
    private $conn;
    private $table = 'Produto_Categoria';

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function criar($produtoId, $categoriaId) {
        $query = "INSERT INTO " . $this->table . " (id_produto, id_categoria) VALUES ('$produtoId', '$categoriaId')";

        if ($this->conn->query($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function excluirPorProduto($produtoId) {
        $query = "DELETE FROM " . $this->table . " WHERE id_produto = '$produtoId'";

        if ($this->conn->query($query)) {
            return true;
        } else {
            return false;
        }
    }
}

