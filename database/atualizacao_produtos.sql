-- =====================================================================
-- ATUALIZAÇÃO DO BANCO - Assadão do Zé
-- =====================================================================
-- Use este script SE VOCÊ JÁ IMPORTOU o banco.sql antes e não quer
-- perder dados (como mensagens de contato já testadas).
-- Ele só ajusta os produtos, sem apagar as outras tabelas.
--
-- Se preferir começar do zero, basta reimportar o database/banco.sql
-- (ele já vem com esses produtos e apaga tudo antes de recriar).
-- =====================================================================

USE assadao_do_ze;

-- 1) Renomeia "Frango Caipira" para "Frango Assado"
UPDATE produtos
SET nome = 'Frango Assado',
    descricao = 'Frango assado inteiro, temperado e preparado no espeto giratório, tradição do Assadão do Zé.'
WHERE nome = 'Frango Caipira';

-- 2) Adiciona a Sobrecoxa Assada (categoria Aves = id 3)
INSERT INTO produtos (categoria_id, nome, descricao, preco, unidade, estoque)
SELECT 3, 'Sobrecoxa Assada', 'Coxa e sobrecoxa de frango assadas lentamente até ficarem douradas e macias.', 22.90, 'kg', 20
WHERE NOT EXISTS (SELECT 1 FROM produtos WHERE nome = 'Sobrecoxa Assada');

-- 3) Adiciona a Maionese em pote (categoria Acompanhamentos = id 4)
INSERT INTO produtos (categoria_id, nome, descricao, preco, unidade, estoque)
SELECT 4, 'Maionese', 'Maionese cremosa da casa, servida em pote, perfeita para acompanhar o assado.', 9.90, 'un', 30
WHERE NOT EXISTS (SELECT 1 FROM produtos WHERE nome = 'Maionese');

-- 4) Remove as bebidas genéricas antigas (e qualquer pedido de exemplo que
--    apontava para elas, para não violar a chave estrangeira)
DELETE pp FROM pedido_produtos pp
INNER JOIN produtos p ON p.id = pp.produto_id
WHERE p.nome IN ('Refrigerante 2L', 'Cerveja Long Neck');

DELETE FROM produtos WHERE nome IN ('Refrigerante 2L', 'Cerveja Long Neck');

-- 5) Adiciona as bebidas reais (categoria Bebidas = id 5)
INSERT INTO produtos (categoria_id, nome, descricao, preco, unidade, estoque)
SELECT 5, 'Skol', 'Cerveja pilsen bem gelada, a queridinha para acompanhar o assado.', 7.00, 'un', 60
WHERE NOT EXISTS (SELECT 1 FROM produtos WHERE nome = 'Skol');

INSERT INTO produtos (categoria_id, nome, descricao, preco, unidade, estoque)
SELECT 5, 'Brahma', 'Cerveja pilsen leve e refrescante, sempre geladinha.', 7.00, 'un', 60
WHERE NOT EXISTS (SELECT 1 FROM produtos WHERE nome = 'Brahma');

INSERT INTO produtos (categoria_id, nome, descricao, preco, unidade, estoque)
SELECT 5, 'Coca-Cola', 'Refrigerante de cola clássico, gelado na medida certa.', 7.50, 'un', 60
WHERE NOT EXISTS (SELECT 1 FROM produtos WHERE nome = 'Coca-Cola');

INSERT INTO produtos (categoria_id, nome, descricao, preco, unidade, estoque)
SELECT 5, 'Fanta Laranja', 'Refrigerante sabor laranja, docinho e refrescante.', 7.00, 'un', 50
WHERE NOT EXISTS (SELECT 1 FROM produtos WHERE nome = 'Fanta Laranja');

INSERT INTO produtos (categoria_id, nome, descricao, preco, unidade, estoque)
SELECT 5, 'Guaraná Antarctica', 'Refrigerante de guaraná, o clássico brasileiro.', 7.50, 'un', 50
WHERE NOT EXISTS (SELECT 1 FROM produtos WHERE nome = 'Guaraná Antarctica');

INSERT INTO produtos (categoria_id, nome, descricao, preco, unidade, estoque)
SELECT 5, 'Fanta Uva', 'Refrigerante sabor uva, refrescante para qualquer hora.', 7.00, 'un', 40
WHERE NOT EXISTS (SELECT 1 FROM produtos WHERE nome = 'Fanta Uva');

INSERT INTO produtos (categoria_id, nome, descricao, preco, unidade, estoque)
SELECT 5, 'Sprite', 'Refrigerante de limão, leve e geladinho.', 7.00, 'un', 50
WHERE NOT EXISTS (SELECT 1 FROM produtos WHERE nome = 'Sprite');

INSERT INTO produtos (categoria_id, nome, descricao, preco, unidade, estoque)
SELECT 5, 'Cerveja Long Neck', 'Cerveja long neck bem gelada, ótima opção pra quem gosta de um copo mais refinado.', 9.90, 'un', 40
WHERE NOT EXISTS (SELECT 1 FROM produtos WHERE nome = 'Cerveja Long Neck');
