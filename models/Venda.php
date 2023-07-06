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
    $query = "SELECT * FROM " . $this->table . "
        JOIN usuario ON venda.id_cliente = usuario.id
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

  public function criar($id_cliente, $produtos)
  {
    try {
      $query = "INSERT INTO " . $this->table . " (id_cliente) VALUES ($id_cliente)";
      if ($this->conn->query($query)) {
        $id_venda = $this->conn->insert_id;
        $query_produtos = "INSERT INTO Produto_Venda (id_venda, id_produto, quantidade) VALUES ";
        foreach($produtos as $index=>$produto) {
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
    $query = "DELETE FROM " . $this->table . " WHERE id = $id";

    return $this->conn->query($query);
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
