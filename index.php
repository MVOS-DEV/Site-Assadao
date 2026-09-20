<?php
require_once __DIR__ . '/config/dados.php';
require_once __DIR__ . '/includes/funcoes.php';

$tituloPagina = 'Assadão do Zé - O melhor churrasco da cidade';
$paginaAtual  = 'index.php';


$pdo        = conectarBanco();
$produtos   = obterProdutos($pdo);
$categorias = obterCategorias($pdo);


$nomesDestaque = ['Costela Bovina', 'Frango Assado', 'Sobrecoxa Assada', 'Maionese'];


$fotoPorDestaque = [
    'Costela Bovina'   => 'assets/img/produtos/costela-bovina.jpg',
    'Frango Assado'    => 'assets/img/produtos/frango-assado.jpg',
    'Sobrecoxa Assada' => 'assets/img/promo-coxa-sobrecoxa.jpg',
    'Maionese'         => 'assets/img/produtos/maionese.jpg',
];

$destaques = [];
foreach ($nomesDestaque as $nomeProcurado) {
    foreach ($produtos as $produto) {
        if ($produto['nome'] === $nomeProcurado) {
            $destaques[] = $produto;
            break;
        }
    }
}

$novidades = [
    ['img' => 'assets/img/promo-delivery.jpg',        'legenda' => 'Hoje tem Assadão do Zé! Peça seu delivery.'],
    ['img' => 'assets/img/promo-tiradentes.jpg',       'legenda' => 'Feriado de Tiradentes: estamos atendendo!'],
    ['img' => 'assets/img/promo-frango-fds.jpg',       'legenda' => 'Frango assado aos sábados e domingos.'],
    ['img' => 'assets/img/promo-almoco-sabado.jpg',    'legenda' => 'Almoço de hoje é sábado! Confira o cardápio.'],
    ['img' => 'assets/img/promo-chegou.jpg',           'legenda' => 'Chegou! Frangos recheados, costelas e acompanhamentos.'],
    ['img' => 'assets/img/promo-hora-costela.jpg',     'legenda' => 'É hora da costela suculenta. Faça sua reserva!'],
    ['img' => 'assets/img/promo-coxa-sobrecoxa.jpg',   'legenda' => 'Coxa e sobrecoxa assada, alguém tem?'],
    ['img' => 'assets/img/foto-costela-corte.jpg',     'legenda' => 'Costela suculenta, direto na brasa.'],
    ['img' => 'assets/img/promo-melhor-costela.jpg',   'legenda' => 'A melhor costela está no Assadão do Zé.'],
    ['img' => 'assets/img/promo-cardapio-delivery.jpg','legenda' => 'Cardápio completo pelo delivery.'],
    ['img' => 'assets/img/foto-linguica.jpg',          'legenda' => 'Linguiça artesanal na brasa.'],
];

require __DIR__ . '/includes/cabecalho.php';
?>


<section class="hero text-center">
    <div class="container">
        <div class="logo-hero-wrap mb-3">
            <img src="assets/img/logo-selo.png" alt="Selo Assadão do Zé - Santa Regina, Itajaí" class="selo-hero-central">
            <img src="assets/img/logo-marca-transparente.png" alt="Assadão do Zé" class="logo-marca-hero">
        </div>
        <p class="lead">Faça já sua reserva! O melhor assado de Santa Regina, direto na brasa.</p>
        <a href="cardapio.php" class="btn btn-light btn-lg fw-bold me-2 mb-2">Ver Cardápio</a>
        <a href="https://wa.me/5547996551093" target="_blank" rel="noopener" class="btn btn-whatsapp btn-lg mb-2">
            <i class="bi bi-whatsapp"></i> Fazer Reserva
        </a>
    </div>
</section>


<section class="container my-5">
    <h2 class="secao-titulo">Fique Ligado nas Novidades</h2>
    <p class="text-muted">Use as setas para o lado e confira nossas últimas divulgações.</p>

    <?php $gruposNovidades = array_chunk($novidades, 3); ?>

    <div id="carrosselNovidades" class="carousel slide carrossel-novidades" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php foreach ($gruposNovidades as $indiceGrupo => $grupo) : ?>
                <div class="carousel-item <?= $indiceGrupo === 0 ? 'active' : ''; ?>">
                    <div class="row g-3 justify-content-center">
                        <?php foreach ($grupo as $novidade) : ?>
                            <div class="col-4">
                                <div class="novidade-item">
                                    <img src="<?= htmlspecialchars($novidade['img']); ?>" alt="<?= htmlspecialchars($novidade['legenda']); ?>">
                                    <p class="novidade-legenda"><?= htmlspecialchars($novidade['legenda']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#carrosselNovidades" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carrosselNovidades" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</section>


<section class="container my-5">
    <h2 class="secao-titulo">Destaques da Casa</h2>

    <div id="carrosselDestaques" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <?php
            $indice = 0;
            $totalDestaques = count($destaques);
            while ($indice < $totalDestaques) {
                $ativo = $indice === 0 ? 'active' : '';
                echo '<button type="button" data-bs-target="#carrosselDestaques" data-bs-slide-to="' . $indice . '" class="' . $ativo . '" aria-current="true"></button>';
                $indice++;
            }
            ?>
        </div>

        <div class="carousel-inner">
            <?php
            // Reaproveitamos o laço WHILE para montar cada slide do carrossel.
            $indice = 0;
            while ($indice < $totalDestaques) {
                $produto = $destaques[$indice];
                $classeAtiva = $indice === 0 ? 'active' : '';
                $foto = $fotoPorDestaque[$produto['nome']] ?? null;

                echo '<div class="carousel-item ' . $classeAtiva . '">';
                if ($foto) {
                    echo '  <div class="slide-foto"><img src="' . htmlspecialchars($foto) . '" alt="' . htmlspecialchars($produto['nome']) . '"></div>';
                } else {
                    echo '  <div class="slide-bg"><i class="bi bi-jar-fill me-3"></i> ' . htmlspecialchars($produto['nome']) . '</div>';
                }
                echo '  <div class="carousel-caption d-none d-md-block">';
                echo '      <h5 class="mb-1">' . htmlspecialchars($produto['nome']) . '</h5>';
                echo '      <p class="mb-1">' . htmlspecialchars($produto['descricao']) . '</p>';
                echo '      <span class="fw-bold">' . formatarPreco($produto['preco']) . ' / ' . htmlspecialchars($produto['unidade']) . '</span>';
                echo '  </div>';
                echo '</div>';
                $indice++;
            }
            ?>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#carrosselDestaques" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carrosselDestaques" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</section>


<section class="container my-5">
    <h2 class="secao-titulo">Explore por Categoria</h2>
    <div class="row g-4">
        <?php foreach ($categorias as $chave => $nomeCategoria) : ?>
            <?php if ($chave === 'todos') { continue; } // não faz sentido mostrar "todos" como categoria aqui ?>
            <div class="col-6 col-md-3">
                <a href="cardapio.php?categoria=<?= urlencode($chave); ?>" class="text-decoration-none">
                    <div class="card card-produto text-center p-3">
                        <div class="fs-1 mb-2">
                            <?php
                            // [TECH FORGE] Validação simples com condicionais (IF/ELSEIF) para escolher o ícone.
                            if ($chave === 'bovina') {
                                echo '🥩';
                            } elseif ($chave === 'suina') {
                                echo '🍖';
                            } elseif ($chave === 'aves') {
                                echo '🍗';
                            } elseif ($chave === 'acompanhamento') {
                                echo '🥗';
                            } else {
                                echo '🥤';
                            }
                            ?>
                        </div>
                        <h6 class="text-dark mb-0"><?= htmlspecialchars($nomeCategoria); ?></h6>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>


<section class="text-center py-5" style="background-color: var(--laranja-claro);">
    <div class="container">
        <h3 class="fw-bold">Vai fazer uma festa ou evento?</h3>
        <p class="mb-4">Conheça nossos serviços de buffet e churrasco em domicílio, e use nossa calculadora de orçamento!</p>
        <a href="servicos.php" class="btn btn-dark btn-lg">Quero Saber Mais</a>
    </div>
</section>

<?php require __DIR__ . '/includes/rodape.php'; ?>
