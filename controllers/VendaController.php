<?php

require_once('./config/database.php');
require_once('./models/Venda.php');
class VendaController {
  
  private $vendaModel;

  public function __construct($conn) {
    $this->vendaModel = new Venda($conn);
  }

  public function listarVendas() {
    header('Content-Type: application/json');
    $vendas = $this->vendaModel->listarTodas();
    for ($i = 0; $i < sizeof($vendas); $i++) {
      $produtos = $this->vendaModel->getProdutosVenda($vendas[$i]['id']);
      $vendas[$i]['produtos'] = $produtos;
    }
    echo json_encode($vendas);
  }

  public function listarVendasUsuario() {
    header('Content-Type: application/json');
    $vendas = $this->vendaModel->getVendas($_SESSION['user']);
    for ($i = 0; $i < sizeof($vendas); $i++) {
      $produtos = $this->vendaModel->getProdutosVenda($vendas[$i]['id']);
      $vendas[$i]['produtos'] = $produtos;
    }
    echo json_encode($vendas);
  }

  public function criarVenda($dados) {
    verificaAcesso();
    $produtos = $dados->produtos;
    $categoriaId = $this->vendaModel->criar($_SESSION['user'], $produtos);

    if ($categoriaId) {
      echo json_encode(['message' => 'Venda criada com sucesso.']);
    } else {
      http_response_code(500);
      echo json_encode(['error' => 'Não foi possível criar a venda.']);
    }
  }

  public function excluirVenda($id) {
    verificaAcesso();
    $exclusao = $this->vendaModel->excluir($id);

    if ($exclusao) {
      echo json_encode(['message' => 'Venda excluída com sucesso.']);
    } else {
      http_response_code(500);
      echo json_encode(['error' => 'Não foi possível excluir a venda.']);
    }
  }
}
