<?php
require_once __DIR__ . '/config/dados.php';
require_once __DIR__ . '/includes/funcoes.php';

$tituloPagina = 'Serviços - Assadão do Zé';
$paginaAtual  = 'servicos.php';


$pdo      = conectarBanco();
$servicos = obterServicos($pdo);


$resultadoCalculo = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calcular_orcamento'])) {
    $convidados      = (int) ($_POST['convidados'] ?? 0);
    $gramasPorPessoa = (float) ($_POST['gramas'] ?? 0);
    $precoKgEscolhido = (float) ($_POST['preco_kg'] ?? 0);


    $resultadoCalculo = calcularOrcamentoChurrasco($convidados, $gramasPorPessoa, $precoKgEscolhido);
}

require __DIR__ . '/includes/cabecalho.php';
?>

<section class="container my-5">
    <h1 class="secao-titulo">Nossos Serviços</h1>
    <p class="text-muted">Além do cardápio, o Assadão do Zé leva o churrasco até você.</p>

    <div class="row g-4 mb-5">
        <?php foreach ($servicos as $servico) : ?>
            <div class="col-md-6 col-lg-4">
                <div class="card card-produto p-4 h-100">
                    <div class="fs-1 text-center mb-3" style="color: var(--laranja-principal);">
                        <i class="bi <?= htmlspecialchars($servico['icone']); ?>"></i>
                    </div>
                    <h5 class="text-center"><?= htmlspecialchars($servico['nome']); ?></h5>
                    <p class="small text-muted"><?= htmlspecialchars($servico['descricao']); ?></p>
                    <?php if ($servico['precoKg'] > 0) : ?>
                        <p class="preco text-center mb-0">a partir de <?= formatarPreco($servico['precoKg']); ?> / kg</p>
                    <?php else : ?>
                        <p class="text-center mb-0"><span class="badge bg-dark">Sob consulta</span></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- ===================== CALCULADORA DE ORÇAMENTO ===================== -->
    <div class="card card-produto p-4 p-md-5">
        <h3 class="secao-titulo">Calcule o Orçamento do seu Churrasco</h3>
        <p class="text-muted">Preencha os campos abaixo e descubra quanto de carne comprar e o valor estimado.</p>

        <form method="POST" action="servicos.php#calculadora" class="row g-3" id="calculadora">
            <div class="col-md-4">
                <label class="form-label">Número de convidados</label>
                <input type="number" name="convidados" min="1" class="form-control" required
                       value="<?= htmlspecialchars($_POST['convidados'] ?? '20'); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Consumo por pessoa (gramas)</label>
                <input type="number" name="gramas" min="1" class="form-control" required
                       value="<?= htmlspecialchars($_POST['gramas'] ?? '400'); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Preço do kg da carne (R$)</label>
                <input type="number" step="0.01" name="preco_kg" min="0.01" class="form-control" required
                       value="<?= htmlspecialchars($_POST['preco_kg'] ?? '54.90'); ?>">
            </div>
            <div class="col-12">
                <button type="submit" name="calcular_orcamento" value="1" class="btn btn-laranja">
                    <i class="bi bi-calculator-fill"></i> Calcular
                </button>
            </div>
        </form>

        <?php if ($resultadoCalculo !== null) : ?>
            <div class="mt-4">
                <?php if ($resultadoCalculo['sucesso']) : ?>
                    <!-- Componente Bootstrap: Alert -->
                    <div class="alert alert-success">
                        <p class="mb-1">Para <strong><?= $resultadoCalculo['convidados']; ?></strong> convidados, você vai precisar de aproximadamente:</p>
                        <h4 class="mb-2"><?= $resultadoCalculo['totalKg']; ?> kg de carne</h4>
                        <p class="mb-1">Valor estimado: <strong><?= formatarPreco($resultadoCalculo['totalPreco']); ?></strong></p>
                        <?php if ($resultadoCalculo['desconto'] > 0) : ?>
                            <p class="mb-0 small text-success">
                                <i class="bi bi-check-circle-fill"></i>
                                Desconto de 10% aplicado (<?= formatarPreco($resultadoCalculo['desconto']); ?>) por ser um evento com 30 ou mais convidados!
                            </p>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($resultadoCalculo['mensagem']); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/includes/rodape.php'; ?>
