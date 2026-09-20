<?php


/**
 * Filtra os produtos por categoria.
 *
 * @param array       $produtos  Array de produtos (vindo de obterProdutos()).
 * @param string|null $categoria Categoria a filtrar ou 'todos'/null para não filtrar.
 * @return array Produtos filtrados.
 */
function filtrarProdutosPorCategoria(array $produtos, ?string $categoria = null): array
{
    if (empty($categoria) || $categoria === 'todos') {
        return $produtos;
    }

    $resultado = [];
    foreach ($produtos as $produto) {
        if ($produto['categoria'] === $categoria) {
            $resultado[] = $produto;
        }
    }
    return $resultado;
}

/**
 * Pesquisa produtos pelo nome (busca parcial, sem diferenciar maiúsculas/minúsculas).
 *
 * @param array  $produtos Array de produtos já filtrados ou completos.
 * @param string $termo    Termo digitado pelo usuário.
 * @return array Produtos cujo nome contém o termo pesquisado.
 */
function pesquisarProdutos(array $produtos, string $termo): array
{
    $termo = trim($termo);
    if ($termo === '') {
        return $produtos;
    }

    $termo = mb_strtolower($termo, 'UTF-8');
    $resultado = [];

    foreach ($produtos as $produto) {
        $nome = mb_strtolower($produto['nome'], 'UTF-8');
        if (str_contains($nome, $termo)) {
            $resultado[] = $produto;
        }
    }

    return $resultado;
}

/**
 * Formata um valor numérico como preço em Real (R$).
 */
function formatarPreco(float $valor): string
{
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

/**
 * Verifica a situação do estoque de um produto e devolve um "selo"
 * (badge) para exibir na tela. Regra de negócio simples com condicionais.
 */
function situacaoEstoque(int $quantidade): array
{
    if ($quantidade <= 0) {
        return ['texto' => 'Esgotado', 'classe' => 'bg-secondary'];
    }

    if ($quantidade <= 15) {
        return ['texto' => 'Últimas unidades', 'classe' => 'bg-warning text-dark'];
    }

    return ['texto' => 'Disponível', 'classe' => 'bg-success'];
}

/**
 * Calcula o orçamento de um churrasco a partir da quantidade de
 * convidados. Aplica regras de negócio validadas com condicionais:
 *   - número de convidados deve ser maior que zero;
 *   - consumo por pessoa e preço do kg devem ser maiores que zero;
 *   - eventos com 30 convidados ou mais recebem 10% de desconto.
 *
 * @param int   $convidados       Quantidade de convidados.
 * @param float $gramasPorPessoa  Consumo estimado de carne por pessoa (em gramas).
 * @param float $precoKg          Preço do quilo da carne escolhida.
 * @return array Resultado do cálculo ou mensagem de erro.
 */
function calcularOrcamentoChurrasco(int $convidados, float $gramasPorPessoa, float $precoKg): array
{
    if ($convidados <= 0) {
        return [
            'sucesso'   => false,
            'mensagem'  => 'A quantidade de convidados precisa ser maior que zero.',
        ];
    }

    if ($gramasPorPessoa <= 0 || $precoKg <= 0) {
        return [
            'sucesso'  => false,
            'mensagem' => 'Informe valores válidos (maiores que zero) para consumo e preço.',
        ];
    }

    $totalGramas = $convidados * $gramasPorPessoa;
    $totalKg     = $totalGramas / 1000;
    $totalPreco  = $totalKg * $precoKg;

    // Regra de negócio: eventos grandes (30+ convidados) ganham 10% de desconto.
    $desconto = 0.0;
    if ($convidados >= 30) {
        $desconto   = $totalPreco * 0.10;
        $totalPreco = $totalPreco - $desconto;
    }

    return [
        'sucesso'     => true,
        'totalKg'     => round($totalKg, 2),
        'totalPreco'  => round($totalPreco, 2),
        'desconto'    => round($desconto, 2),
        'convidados'  => $convidados,
    ];
}

/**
 * Valida os campos do formulário de contato.
 *
 * @return array Lista de mensagens de erro. Vazio significa formulário válido.
 */
function validarFormularioContato(string $nome, string $email, string $telefone, string $mensagem): array
{
    $erros = [];

    if (mb_strlen(trim($nome)) < 3) {
        $erros[] = 'Digite seu nome completo (mínimo 3 caracteres).';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'Informe um e-mail válido.';
    }

    $telefoneNumeros = preg_replace('/\D/', '', $telefone);
    if (strlen($telefoneNumeros) < 10) {
        $erros[] = 'Informe um telefone válido, com DDD.';
    }

    if (mb_strlen(trim($mensagem)) < 10) {
        $erros[] = 'Conte um pouco mais sobre o que você precisa (mínimo 10 caracteres).';
    }

    return $erros;
}
