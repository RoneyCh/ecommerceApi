CREATE TABLE Usuario (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nome VARCHAR(255) NOT NULL,
  endereco VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  login VARCHAR(255) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  administrador BOOLEAN NOT NULL
);

CREATE TABLE Produto (
  id INT PRIMARY KEY AUTO_INCREMENT,
  descricao VARCHAR(255) NOT NULL,
  preco DOUBLE NOT NULL,
  foto VARCHAR(255),
  quantidade INT NOT NULL
);

CREATE TABLE Categoria (
  id INT PRIMARY KEY AUTO_INCREMENT,
  descricao VARCHAR(255) NOT NULL
);

CREATE TABLE Venda (
  id INT PRIMARY KEY AUTO_INCREMENT,
  data_hora TIMESTAMP NOT NULL,
  id_cliente INT NOT NULL,
  FOREIGN KEY (id_cliente) REFERENCES Usuario (id)
);

CREATE TABLE Produto_Venda (
  id_venda INT NOT NULL,
  id_produto INT NOT NULL,
  quantidade INT NOT NULL,
  FOREIGN KEY (id_venda) REFERENCES Venda (id),
  FOREIGN KEY (id_produto) REFERENCES Produto (id),
  PRIMARY KEY (id_venda, id_produto)
);

CREATE TABLE Produto_Categoria (
  id_produto INT NOT NULL,
  id_categoria INT NOT NULL,
  FOREIGN KEY (id_produto) REFERENCES Produto (id),
  FOREIGN KEY (id_categoria) REFERENCES Categoria (id),
  PRIMARY KEY (id_produto, id_categoria)
);