<?php
require_once __DIR__ . '/config/conexao.php';
require_once __DIR__ . '/includes/funcoes.php';

$tituloPagina = 'Contato - Assadão do Zé';
$paginaAtual  = 'contato.php';

$erros        = [];
$enviadoComSucesso = false;

// ===================== PROCESSAMENTO DO FORMULÁRIO =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar_contato'])) {
    $nome     = trim($_POST['nome'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $assunto  = trim($_POST['assunto'] ?? '');
    $mensagem = trim($_POST['mensagem'] ?? '');


    $erros = validarFormularioContato($nome, $email, $telefone, $mensagem);

    if (empty($erros)) {

        $pdo = conectarBanco();
        $stmt = $pdo->prepare('
            INSERT INTO mensagens_contato (nome, email, telefone, assunto, mensagem)
            VALUES (:nome, :email, :telefone, :assunto, :mensagem)
        ');
        $stmt->execute([
            ':nome'     => $nome,
            ':email'    => $email,
            ':telefone' => $telefone,
            ':assunto'  => $assunto,
            ':mensagem' => $mensagem,
        ]);

        $enviadoComSucesso = true;
    }
}

require __DIR__ . '/includes/cabecalho.php';
?>

<section class="container my-5" id="pedido">
    <h1 class="secao-titulo">Fale com o Assadão do Zé</h1>
    <p class="text-muted">Dúvidas, pedidos ou orçamentos: preencha o formulário que retornamos rapidinho.</p>

    <div class="row g-4">
        <div class="col-md-7">

            <?php if ($enviadoComSucesso) : ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill"></i> Mensagem enviada com sucesso! Em breve entraremos em contato.
                </div>
            <?php elseif (!empty($erros)) : ?>
                <div class="alert alert-danger">
                    <strong>Corrija os itens abaixo:</strong>
                    <ul class="mb-0">
                        <?php foreach ($erros as $erro) : ?>
                            <li><?= htmlspecialchars($erro); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="contato.php#pedido" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nome completo</label>
                    <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($_POST['nome'] ?? ''); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Telefone / WhatsApp</label>
                    <input type="text" name="telefone" class="form-control" placeholder="(47) 99999-0000" value="<?= htmlspecialchars($_POST['telefone'] ?? ''); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Assunto</label>
                    <select name="assunto" class="form-select">
                        <option>Pedido / Orçamento</option>
                        <option>Dúvida sobre o cardápio</option>
                        <option>Serviço para eventos</option>
                        <option>Outro assunto</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Mensagem</label>
                    <textarea name="mensagem" rows="4" class="form-control"><?= htmlspecialchars($_POST['mensagem'] ?? ''); ?></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" name="enviar_contato" value="1" class="btn btn-laranja btn-lg">
                        <i class="bi bi-send-fill"></i> Enviar Mensagem
                    </button>
                </div>
            </form>
        </div>

        <div class="col-md-5">
            <div class="card-produto p-4 h-100">
                <img src="assets/img/logo-selo.png" alt="Assadão do Zé" style="width: 90px;" class="mb-3">
                <h5 class="mb-3">Outras formas de contato</h5>
                <p>
                    <a href="https://wa.me/5547996551093" target="_blank" rel="noopener" class="text-dark text-decoration-none">
                        <i class="bi bi-whatsapp me-2"></i> (47) 99655-1093
                    </a>
                </p>
                <p>
                    <a href="https://www.facebook.com/assadaodoze.josecarlos.3" target="_blank" rel="noopener" class="text-dark text-decoration-none">
                        <i class="bi bi-facebook me-2"></i> /assadaodoze.josecarlos.3
                    </a>
                </p>
                <p><i class="bi bi-geo-alt-fill me-2"></i> Rua Mariangela Rocha Fabeni, 71 - Santa Regina - Itajaí - SC</p>
                <p class="mb-0"><i class="bi bi-clock-fill me-2"></i> Faça já sua reserva para o fim de semana!</p>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/rodape.php'; ?>
