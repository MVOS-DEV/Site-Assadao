-- =====================================================================
-- BANCO DE DADOS - Assadão do Zé
-- =====================================================================
-- [MODELAGEM E BANCO DE DADOS]
--   - O sistema possui tabelas suficientes para o propósito do projeto
--     (mínimo 3): este script cria 7 tabelas.
--   - Foram adicionadas corretamente as chaves primárias em TODAS as
--     tabelas (PRIMARY KEY).
--   - O projeto possui ao menos 1 chave estrangeira:
--       produtos.categoria_id        -> categorias.id
--       pedidos.cliente_id           -> clientes.id
--       pedido_produtos.pedido_id    -> pedidos.id
--       pedido_produtos.produto_id   -> produtos.id
--   - O projeto possui ao menos 1 relacionamento muitos-para-muitos
--     (N:N): PEDIDOS <-> PRODUTOS, implementado através da tabela
--     associativa PEDIDO_PRODUTOS (um pedido pode ter vários produtos
--     e um produto pode aparecer em vários pedidos).
--
-- Veja também: database/der-assadao-do-ze.mermaid (Diagrama de
-- Entidade Relacionamento).
-- =====================================================================

CREATE DATABASE IF NOT EXISTS assadao_do_ze
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE assadao_do_ze;

-- ---------------------------------------------------------------------
-- Limpeza (útil ao reimportar o script durante o desenvolvimento)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS mensagens_contato;
DROP TABLE IF EXISTS pedido_produtos;
DROP TABLE IF EXISTS pedidos;
DROP TABLE IF EXISTS clientes;
DROP TABLE IF EXISTS servicos;
DROP TABLE IF EXISTS produtos;
DROP TABLE IF EXISTS categorias;

-- ---------------------------------------------------------------------
-- Tabela: categorias
-- ---------------------------------------------------------------------
CREATE TABLE categorias (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    slug VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Tabela: produtos
-- Chave estrangeira: categoria_id -> categorias.id
-- ---------------------------------------------------------------------
CREATE TABLE produtos (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT NOT NULL,
    nome         VARCHAR(100) NOT NULL,
    descricao    TEXT,
    preco        DECIMAL(10,2) NOT NULL,
    unidade      VARCHAR(10) NOT NULL DEFAULT 'kg',
    estoque      INT NOT NULL DEFAULT 0,
    criado_em    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_produtos_categoria
        FOREIGN KEY (categoria_id) REFERENCES categorias(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Tabela: servicos
-- ---------------------------------------------------------------------
CREATE TABLE servicos (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    nome      VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco_kg  DECIMAL(10,2) NOT NULL DEFAULT 0,
    icone     VARCHAR(50)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Tabela: clientes
-- ---------------------------------------------------------------------
CREATE TABLE clientes (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    nome      VARCHAR(100) NOT NULL,
    email     VARCHAR(150) NOT NULL UNIQUE,
    telefone  VARCHAR(20),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Tabela: pedidos
-- Chave estrangeira: cliente_id -> clientes.id
-- ---------------------------------------------------------------------
CREATE TABLE pedidos (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id  INT NOT NULL,
    data_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    status      ENUM('pendente', 'confirmado', 'entregue', 'cancelado') DEFAULT 'pendente',
    observacoes TEXT,
    CONSTRAINT fk_pedidos_cliente
        FOREIGN KEY (cliente_id) REFERENCES clientes(id)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Tabela associativa: pedido_produtos
-- [MODELAGEM] Relacionamento N:N entre PEDIDOS e PRODUTOS.
-- A chave primária composta (pedido_id, produto_id) evita que o mesmo
-- produto seja lançado duas vezes no mesmo pedido.
-- ---------------------------------------------------------------------
CREATE TABLE pedido_produtos (
    pedido_id      INT NOT NULL,
    produto_id     INT NOT NULL,
    quantidade     DECIMAL(10,2) NOT NULL DEFAULT 1,
    preco_unitario DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (pedido_id, produto_id),
    CONSTRAINT fk_pp_pedido
        FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_pp_produto
        FOREIGN KEY (produto_id) REFERENCES produtos(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Tabela: mensagens_contato (armazena os envios do formulário de contato)
-- ---------------------------------------------------------------------
CREATE TABLE mensagens_contato (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    nome      VARCHAR(100) NOT NULL,
    email     VARCHAR(150) NOT NULL,
    telefone  VARCHAR(20),
    assunto   VARCHAR(100),
    mensagem  TEXT NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


-- =====================================================================
-- DADOS INICIAIS
-- =====================================================================

INSERT INTO categorias (id, nome, slug) VALUES
(1, 'Carnes Bovinas', 'bovina'),
(2, 'Carnes Suínas', 'suina'),
(3, 'Aves', 'aves'),
(4, 'Acompanhamentos', 'acompanhamento'),
(5, 'Bebidas', 'bebida');

INSERT INTO produtos (categoria_id, nome, descricao, preco, unidade, estoque) VALUES
(1, 'Picanha Premium',         'Corte nobre, maturado por 21 dias, ideal para quem gosta de uma carne suculenta e macia.', 89.90, 'kg', 25),
(1, 'Costela Bovina',          'Assada lentamente por mais de 8 horas na brasa, desmancha na boca.',                        49.90, 'kg', 18),
(1, 'Fraldinha',               'Corte saboroso e suculento, um dos preferidos do churrasco brasileiro.',                    54.90, 'kg', 20),
(1, 'Cupim Assado',            'Cozido lentamente até ficar extremamente macio e cheio de sabor.',                          59.90, 'kg', 12),
(2, 'Linguiça Artesanal',      'Receita da casa, temperada com ervas frescas e defumada no ponto certo.',                   32.90, 'kg', 30),
(2, 'Costelinha Suína',        'Marinada por 24 horas antes de ir para a brasa, fica crocante por fora e macia por dentro.',39.90, 'kg', 15),
(2, 'Pernil Temperado',        'Tempero especial da casa com ervas e páprica defumada.',                                    36.90, 'kg', 10),
(3, 'Frango Assado',           'Frango assado inteiro, temperado e preparado no espeto giratório, tradição do Assadão do Zé.', 24.90, 'kg', 22),
(3, 'Coração de Frango',       'Clássico do churrasco, temperado com sal grosso e alho.',                                   29.90, 'kg', 14),
(3, 'Sobrecoxa Assada',        'Coxa e sobrecoxa de frango assadas lentamente até ficarem douradas e macias.',              22.90, 'kg', 20),
(4, 'Farofa Especial da Casa', 'Farofa crocante com bacon e banana, receita exclusiva do Zé.',                              12.90, 'un', 40),
(4, 'Vinagrete',               'Fresco e levemente ácido, o combinado perfeito para qualquer carne.',                        8.90, 'un', 35),
(4, 'Pão de Alho',             'Assado na brasa com bastante alho e manteiga de ervas.',                                    14.90, 'un', 28),
(4, 'Maionese',                'Maionese cremosa da casa, servida em pote, perfeita para acompanhar o assado.',              9.90, 'un', 30),
(5, 'Skol',                    'Cerveja pilsen bem gelada, a queridinha para acompanhar o assado.',                          7.00, 'un', 60),
(5, 'Brahma',                  'Cerveja pilsen leve e refrescante, sempre geladinha.',                                       7.00, 'un', 60),
(5, 'Coca-Cola',               'Refrigerante de cola clássico, gelado na medida certa.',                                     7.50, 'un', 60),
(5, 'Fanta Laranja',           'Refrigerante sabor laranja, docinho e refrescante.',                                         7.00, 'un', 50),
(5, 'Guaraná Antarctica',      'Refrigerante de guaraná, o clássico brasileiro.',                                            7.50, 'un', 50),
(5, 'Fanta Uva',               'Refrigerante sabor uva, refrescante para qualquer hora.',                                    7.00, 'un', 40),
(5, 'Sprite',                  'Refrigerante de limão, leve e geladinho.',                                                   7.00, 'un', 50),
(5, 'Cerveja Long Neck',       'Cerveja long neck bem gelada, ótima opção pra quem gosta de um copo mais refinado.',         9.90, 'un', 40);

INSERT INTO servicos (nome, descricao, preco_kg, icone) VALUES
('Buffet Completo para Eventos',        'Montamos toda a estrutura de churrasco no local do seu evento: carnes, acompanhamentos, churrasqueiro e equipe de apoio.', 65.00, 'bi-people-fill'),
('Churrasco em Domicílio',              'Um churrasqueiro do Assadão do Zé vai até sua casa com as carnes e prepara tudo fresquinho na sua churrasqueira.',        70.00, 'bi-house-heart-fill'),
('Aluguel de Churrasqueira Elétrica',   'Não tem espaço para churrasqueira a carvão? Alugamos churrasqueiras elétricas compactas para qualquer ambiente.',         0.00,  'bi-fire'),
('Corte e Preparo Personalizado',       'Escolha o corte, o ponto e o tempero. Preparamos exatamente do jeito que você e seus convidados gostam.',                 75.00, 'bi-scissors'),
('Consultoria para seu Churrasco',      'Não sabe quanta carne comprar? Nossa equipe te ajuda a calcular a quantidade certa para não faltar nem sobrar.',          0.00,  'bi-calculator-fill');

-- Cliente e pedido de exemplo, para já demonstrar o relacionamento N:N
-- funcionando (um pedido com vários produtos).
INSERT INTO clientes (nome, email, telefone) VALUES
('João Ferreira', 'joao.ferreira@example.com', '41999990000');

INSERT INTO pedidos (cliente_id, status, observacoes) VALUES
(1, 'confirmado', 'Entregar até as 18h no sábado.');

INSERT INTO pedido_produtos (pedido_id, produto_id, quantidade, preco_unitario) VALUES
(1, 1, 2.5, 89.90),   -- 2,5 kg de Picanha Premium
(1, 5, 1.0, 32.90),   -- 1 kg de Linguiça Artesanal
(1, 17, 6.0, 7.50);   -- 6 Coca-Colas
