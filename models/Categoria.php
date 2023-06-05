<?php
class Categoria
{
  private $conn;
  private $table = 'Categoria';

  public function __construct($conn)
  {
    $this->conn = $conn;
  }

  public function listarTodas()
  {
    $query = "SELECT * FROM " . $this->table;
    $result = $this->conn->query($query);

    $categorias = [];
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $categorias[] = $row;
      }
    }

    return $categorias;
  }

  public function criar($categoria)
  {
    if (!$this->getCategoria($categoria['descricao']) == false) {

      $descricao = $categoria['descricao'];
      $query = "INSERT INTO " . $this->table . " (descricao) VALUES ('$descricao')";

      if ($this->conn->query($query)) {
        return $this->conn->insert_id;
      } else {
        return false;
      }
    } else {
      http_response_code(500);
      echo json_encode(['error' => 'Categoria já existe.']);
    }
  }

  public function atualizar($id, $categoria)
  {
    if($this->getCategoria($categoria['descricao']) == false) {
    $descricao = $categoria['descricao'];
    $query = "UPDATE " . $this->table . " SET descricao = '$descricao' WHERE id = $id";

    return $this->conn->query($query);
    } else {
      http_response_code(500);
      echo json_encode(['error' => 'Categoria já existe.']);
    }
  }

  public function excluir($id)
  {
    $query = "DELETE FROM " . $this->table . " WHERE id = $id";

    return $this->conn->query($query);
  }

  public function getCategoria($descricao)
  {
    $query = "SELECT 1 FROM " . $this->table . " WHERE descricao = '$descricao'";
    $result = $this->conn->query($query);

    if ($result->num_rows > 0) {
      return true;
    } else {
      return false;
    }
  }
}
