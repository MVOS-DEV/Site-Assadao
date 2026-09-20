<?php

require_once __DIR__ . '/conexao.php';

function obterProdutos(PDO $pdo): array
{
    $sql = '
        SELECT
            p.id,
            p.nome,
            p.descricao,
            p.preco,
            p.unidade,
            p.estoque,
            c.slug AS categoria
        FROM produtos p
        INNER JOIN categorias c ON c.id = p.categoria_id
        ORDER BY p.nome
    ';

    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

function obterCategorias(PDO $pdo): array
{
    $stmt = $pdo->query('SELECT slug, nome FROM categorias ORDER BY nome');

    $categorias = ['todos' => 'Todos'];
    foreach ($stmt->fetchAll() as $linha) {
        $categorias[$linha['slug']] = $linha['nome'];
    }

    return $categorias;
}


function obterServicos(PDO $pdo): array
{
    $sql = 'SELECT id, nome, descricao, preco_kg AS precoKg, icone FROM servicos ORDER BY id';
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}
