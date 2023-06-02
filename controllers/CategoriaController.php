<?php

require_once('./config/database.php');
require_once('./models/Categoria.php');
require_once('./controllers/CategoriaController.php');
require_once 'AuntenticaMiddleware.php';

class CategoriaController {
  
  private $categoriaModel;

  public function __construct($conn) {
    $this->categoriaModel = new Categoria($conn);
  }


  public function listarCategorias() {
    $categorias = $this->categoriaModel->listarTodas();

    header('Content-Type: application/json');
    echo json_encode($categorias);
  }

  public function criarCategoria($dados) {
    verificaAcesso();
    $novaCategoria = [
      'descricao' => $dados['descricao']
    ];

    $categoriaId = $this->categoriaModel->criar($novaCategoria);

    if ($categoriaId) {
      echo json_encode(['message' => 'Categoria criada com sucesso.']);
    } else {
      http_response_code(500);
      echo json_encode(['error' => 'Não foi possível criar a categoria.']);
    }
  }

  public function atualizarCategoria($id, $dados) {
    verificaAcesso();
    $categoriaAtualizada = [
      'descricao' => $dados['descricao']
    ];

    $atualizacao = $this->categoriaModel->atualizar($id, $categoriaAtualizada);

    if ($atualizacao) {
      echo json_encode(['message' => 'Categoria atualizada com sucesso.']);
    } else {
      http_response_code(500);
      echo json_encode(['error' => 'Não foi possível atualizar a categoria.']);
    }
  }

  public function excluirCategoria($id) {
    verificaAcesso();
    $exclusao = $this->categoriaModel->excluir($id);

    if ($exclusao) {
      echo json_encode(['message' => 'Categoria excluída com sucesso.']);
    } else {
      http_response_code(500);
      echo json_encode(['error' => 'Não foi possível excluir a categoria.']);
    }
  }
}



