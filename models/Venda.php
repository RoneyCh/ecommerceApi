<?php
class Venda
{
  private $conn;
  private $table = 'Venda';

  public function __construct($conn)
  {
    $this->conn = $conn;
  }

  public function listarTodas()
  {
    $query = "SELECT
      v.id,
      v.data_hora,
      v.id_cliente,
      u.nome,
      u.endereco
     FROM " . $this->table . " v
        JOIN Usuario u ON v.id_cliente = u.id
    ";

    $result = $this->conn->query($query);

    $vendas = [];
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $vendas[] = $row;
      }
    }

    return $vendas;
  }

  public function getProdutosVenda($id)
  {
    $query = "SELECT
      produto.id,
      produto.descricao,
      produto.preco,
      produto_venda.quantidade
     FROM Produto produto
      JOIN Produto_Venda produto_venda ON produto.id = produto_venda.id_produto
      WHERE produto_venda.id_venda = $id";

    $result = $this->conn->query($query);
    $produtos = [];
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $produtos[] = $row;
      }
    }
    return $produtos;
  }

  public function criar($id_cliente, $produtos)
  {
    try {
      $query = "INSERT INTO " . $this->table . " (id_cliente) VALUES ($id_cliente)";
      if ($this->conn->query($query)) {
        $id_venda = $this->conn->insert_id;
        $query_produtos = "INSERT INTO Produto_Venda (id_venda, id_produto, quantidade) VALUES ";
        foreach ($produtos as $index => $produto) {
          if ($index > 0) $query_produtos .= ',';
          $query_produtos .= "($id_venda, $produto->id, $produto->quantidade)";
        }
        if ($this->conn->query($query_produtos)) {
          return $id_venda;
        }
        return false;
      } else {
        return false;
      }
    } catch (Exception $e) {
      return false;
    }
  }

  public function excluir($id)
  {
    $query = "DELETE FROM Produto_Venda produto_venda
              WHERE produto_venda.id_venda = $id
            ";
    $result = $this->conn->query($query);
    
    if ($result) {
      $query = "DELETE FROM " . $this->table . " WHERE id = $id";
      return $this->conn->query($query);
    }
    return false;
  }

  public function getVendas($id)
  {
    $query = "SELECT * FROM " . $this->table .
      " JOIN Produto_Venda ON Produto_Venda.id_venda = Venda.id
               JOIN Produto ON Produto_Venda.id_produto = Produto.id
               WHERE id_cliente = $id";
    $result = $this->conn->query($query);

    $vendas = [];
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $vendas[] = $row;
      }
    }

    return $vendas;
  }
}
