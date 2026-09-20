<?php
require_once __DIR__ . '/config/dados.php';
require_once __DIR__ . '/includes/funcoes.php';

$tituloPagina = 'Cardápio - Assadão do Zé';
$paginaAtual  = 'cardapio.php';


$pdo           = conectarBanco();
$todosProdutos = obterProdutos($pdo);
$categorias    = obterCategorias($pdo);


$categoriaSelecionada = $_GET['categoria'] ?? 'todos';
$termoPesquisa         = trim($_GET['busca'] ?? '');


$produtosFiltrados = filtrarProdutosPorCategoria($todosProdutos, $categoriaSelecionada);
$produtosFiltrados = pesquisarProdutos($produtosFiltrados, $termoPesquisa);


$fotosProdutos = [
    'Picanha Premium'         => 'assets/img/produtos/picanha.jpg',
    'Costela Bovina'          => 'assets/img/produtos/costela-bovina.jpg',
    'Fraldinha'               => 'assets/img/produtos/fraldinha.jpg',
    'Cupim Assado'            => 'assets/img/produtos/cupim.jpg',
    'Linguiça Artesanal'      => 'assets/img/produtos/linguica.jpg',
    'Costelinha Suína'        => 'assets/img/produtos/costelinha-suina.jpg',
    'Pernil Temperado'        => 'assets/img/produtos/pernil.jpg',
    'Frango Assado'           => 'assets/img/produtos/frango-assado.jpg',
    'Coração de Frango'       => 'assets/img/produtos/coracao-frango.jpg',
    'Farofa Especial da Casa' => 'assets/img/produtos/farofa.jpg',
    'Vinagrete'               => 'assets/img/produtos/vinagrete.jpg',
    'Pão de Alho'             => 'assets/img/produtos/pao-de-alho.jpg',
    'Maionese'                => 'assets/img/produtos/maionese.jpg',
    'Coca-Cola'               => 'assets/img/produtos/coca-cola.jpg',
    'Brahma'                  => 'assets/img/produtos/brahma.jpg',
    'Cerveja Long Neck'       => 'assets/img/produtos/cerveja-long-neck.jpg',
];

require __DIR__ . '/includes/cabecalho.php';
?>

<section class="container my-5">
    <h1 class="secao-titulo">Nosso Cardápio</h1>
    <p class="text-muted">Carnes selecionadas, acompanhamentos e bebidas geladas para o seu churrasco.</p>

    <!-- ===================== CARDÁPIO ESPECIAL DE FIM DE SEMANA (arte real de divulgação) ===================== -->
    <div class="card card-produto p-3 p-md-4 mb-5">
        <div class="row g-4 align-items-center">
            <div class="col-md-4 text-center">
                <img src="assets/img/cardapio-fimdesemana.jpg" alt="Cardápio especial de fim de semana - Assadão do Zé" class="poster-cardapio img-fluid">
            </div>
            <div class="col-md-8">
                <span class="badge bg-dark mb-2">Fim de Semana</span>
                <h4>Cardápio Especial de Fim de Semana</h4>
                <p class="text-muted mb-2">Aos sábados e domingos, o Assadão do Zé prepara opções exclusivas: frango recheado, costela bovina e suína na brasa e acompanhamentos da casa. Peça pelo delivery e receba fresquinho!</p>
                <a href="https://wa.me/5547996551093" target="_blank" rel="noopener" class="btn btn-whatsapp">
                    <i class="bi bi-whatsapp"></i> Pedir pelo Delivery
                </a>
            </div>
        </div>
    </div>

    <!-- ===================== FORMULÁRIO DE PESQUISA ===================== -->
    <form method="GET" action="cardapio.php" class="row g-2 mb-4">
        <div class="col-md-6">
            <input type="text" name="busca" class="form-control" placeholder="Pesquisar produto (ex: picanha)"
                   value="<?= htmlspecialchars($termoPesquisa); ?>">
        </div>
        <input type="hidden" name="categoria" value="<?= htmlspecialchars($categoriaSelecionada); ?>">
        <div class="col-md-2">
            <button type="submit" class="btn btn-laranja w-100"><i class="bi bi-search"></i> Buscar</button>
        </div>
    </form>

    <!-- ===================== FILTRO POR CATEGORIA ===================== -->
    <div class="filtro-categoria mb-4">
        <?php foreach ($categorias as $chave => $nome) : ?>
            <?php
            // [TECH FORGE] IF para decidir a classe visual do botão ativo.
            if ($chave === $categoriaSelecionada) {
                $classeBotao = 'btn-laranja';
            } else {
                $classeBotao = 'btn-outline-dark';
            }
            ?>
            <a href="cardapio.php?categoria=<?= urlencode($chave); ?>&busca=<?= urlencode($termoPesquisa); ?>"
               class="btn btn-sm <?= $classeBotao; ?>">
                <?= htmlspecialchars($nome); ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- ===================== LISTAGEM DE PRODUTOS ===================== -->
    <?php if (empty($produtosFiltrados)) : ?>
        <div class="alert alert-warning">
            Nenhum produto encontrado para esse filtro/pesquisa. Tente outro termo.
        </div>
    <?php else : ?>
        <div class="row g-4">
            <?php foreach ($produtosFiltrados as $produto) : ?>
                <?php $estoque = situacaoEstoque($produto['estoque']); ?>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card card-produto">
                        <?php $foto = $fotosProdutos[$produto['nome']] ?? null; ?>
                        <?php if ($foto) : ?>
                            <img src="<?= htmlspecialchars($foto); ?>" alt="<?= htmlspecialchars($produto['nome']); ?>" class="card-img-top">
                        <?php else : ?>
                            <div class="card-img-top">
                                <?php
                                // Sem foto própria: ícone conforme a categoria (IF/ELSEIF encadeado).
                                if ($produto['categoria'] === 'bovina') {
                                    echo '🥩';
                                } elseif ($produto['categoria'] === 'suina') {
                                    echo '🍖';
                                } elseif ($produto['categoria'] === 'aves') {
                                    echo '🍗';
                                } elseif ($produto['categoria'] === 'acompanhamento') {
                                    echo '🥗';
                                } else {
                                    echo '🥤';
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <span class="badge badge-categoria mb-2 align-self-start">
                                <?= htmlspecialchars($categorias[$produto['categoria']] ?? $produto['categoria']); ?>
                            </span>
                            <h6 class="card-title"><?= htmlspecialchars($produto['nome']); ?></h6>
                            <p class="preco mb-1"><?= formatarPreco($produto['preco']); ?> / <?= htmlspecialchars($produto['unidade']); ?></p>
                            <span class="badge <?= $estoque['classe']; ?> mb-3 align-self-start"><?= $estoque['texto']; ?></span>

                            <button type="button" class="btn btn-outline-dark btn-sm mt-auto"
                                    data-bs-toggle="modal" data-bs-target="#modalProduto<?= $produto['id']; ?>">
                                Ver detalhes
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ===================== MODAL DE DETALHES (Bootstrap) ===================== -->
                <div class="modal fade" id="modalProduto<?= $produto['id']; ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><?= htmlspecialchars($produto['nome']); ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <?php if ($foto) : ?>
                                    <img src="<?= htmlspecialchars($foto); ?>" alt="<?= htmlspecialchars($produto['nome']); ?>" class="img-fluid rounded mb-3">
                                <?php endif; ?>
                                <p><?= htmlspecialchars($produto['descricao']); ?></p>
                                <p class="preco mb-0"><?= formatarPreco($produto['preco']); ?> / <?= htmlspecialchars($produto['unidade']); ?></p>
                            </div>
                            <div class="modal-footer">
                                <a href="contato.php#pedido" class="btn btn-laranja">Fazer Pedido</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/includes/rodape.php'; ?>
