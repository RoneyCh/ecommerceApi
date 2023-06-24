<?php
require_once 'AutenticaMiddleware.php';

class ProdutoController
{
    private $produtoModel;
    private $produtoCategoriaModel;

    public function __construct($conn)
    {
        require_once('./models/Produto.php');
        require_once('./models/ProdutoCategoria.php');
        $this->produtoModel = new Produto($conn);
        $this->produtoCategoriaModel = new ProdutoCategoria($conn);
    }

    public function listarProdutos()
    {
        $produtos = $this->produtoModel->listarTodos();

        if (!empty($produtos)) {
            echo json_encode($produtos);
        } else {
            echo json_encode(['message' => 'Nenhum produto encontrado.']);
        }
    }

    public function getImagem($imagemPath)
    {
        header("Content-type: image/png");
        readfile($imagemPath);
    }

    public function obterProduto($id)
    {
        $produto = $this->produtoModel->obterPorId($id);

        if ($produto) {
            echo json_encode($produto);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Produto não encontrado.']);
        }
    }

    public function criarProduto($data)
    {
        verificaAcesso();
        // Insere o novo produto na tabela Produto
        $produtoId = $this->produtoModel->criar($data);

        // Insere os pares de IDs de produto e categoria na tabela Produto_Categoria
        $categorias = $data['categorias'];
        $categorias = str_split($categorias, 1);
        foreach ($categorias as $categoriaId) {
            $this->produtoCategoriaModel->criar($produtoId, $categoriaId);
        }

        echo json_encode(['id' => $produtoId]);
    }

    public function insereFoto()
    {
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $tempDiretorio = $_FILES['foto']['tmp_name'];
            $diretorioDestino = './files/';  // Substitua pelo caminho real do diretório de destino
            $fotoId = uniqid('', true);
            $diretorioDestino = $diretorioDestino . $fotoId;

            // Move o arquivo para o diretório de destino
            if (move_uploaded_file($tempDiretorio, $diretorioDestino)) {
                echo "$diretorioDestino";
            } else {
                echo 'Ocorreu um erro ao salvar o arquivo.';
            }
        } else {
            http_response_code(400);
            echo 'Nenhum arquivo foi enviado ou ocorreu um erro durante o envio.';
        }
    }


    public function atualizarProduto($id, $dados)
    {
        verificaAcesso();
        $produtoExistente = $this->produtoModel->obterPorId($id);

        if ($produtoExistente) {
            // Atualiza os campos do produto
            $produtoAtualizado = [
                'id' => $id,
                'descricao' => $dados['descricao'],
                'preco' => $dados['preco'],
                'quantidade' => $dados['quantidade']
            ];

            // Verifica se as categorias foram fornecidas
            if (isset($dados['categorias'])) {
                $categorias = $dados['categorias'];

                // Atualiza as categorias do produto
                $this->produtoCategoriaModel->excluirPorProduto($id); // Exclui todas as categorias do produto
                $categorias = str_split($categorias);
                foreach ($categorias as $categoriaId) {
                    $this->produtoCategoriaModel->criar($id, $categoriaId); // Cria os novos registros de ProdutoCategoria
                }
            }

            // Atualiza o produto
            if ($this->produtoModel->atualizar($produtoAtualizado)) {
                echo json_encode(['message' => 'Produto atualizado com sucesso.']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Não foi possível atualizar o produto.']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Produto não encontrado.']);
        }
    }


    public function excluirProduto($id)
    {
        verificaAcesso();
        $produtoExistente = $this->produtoModel->obterPorId($id);

        if ($produtoExistente) {
            // Exclui as associações do produto com as categorias
            $this->produtoCategoriaModel->excluirPorProduto($id);
            if ($this->produtoModel->excluir($id)) {
                echo json_encode(['message' => 'Produto excluído com sucesso.']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Não foi possível excluir o produto.']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Produto não encontrado.']);
        }
    }
}
