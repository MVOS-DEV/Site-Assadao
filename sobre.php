<?php
$tituloPagina = 'Sobre Nós - Assadão do Zé';
$paginaAtual  = 'sobre.php';


$perguntasFrequentes = [
    ['pergunta' => 'Vocês entregam em domicílio?', 'resposta' => 'Sim! Trabalhamos com entrega das carnes já temperadas ou com churrasqueiro incluso, dependendo do serviço escolhido.'],
    ['pergunta' => 'Qual o prazo mínimo para contratar o buffet?', 'resposta' => 'Recomendamos agendar com pelo menos 5 dias de antecedência para garantirmos a melhor experiência no seu evento.'],
    ['pergunta' => 'Vocês têm opções para quem não come carne vermelha?', 'resposta' => 'Sim, temos frango, linguiças variadas e uma linha completa de acompanhamentos.'],
    ['pergunta' => 'Como funciona a calculadora de orçamento?', 'resposta' => 'Basta informar o número de convidados, o consumo médio por pessoa e o preço do corte escolhido, na página de Serviços.'],
];

require __DIR__ . '/includes/cabecalho.php';
?>

<section class="container my-5">
    <h1 class="secao-titulo">Sobre o Assadão do Zé</h1>

    <div class="row align-items-center g-4 mb-5">
        <div class="col-md-6">
            <p>O <strong>Assadão do Zé</strong> nasceu da paixão do Zé pelo assado feito na brasa e pela vontade de reunir família e amigos em Santa Regina, Itajaí - SC. O que começou como um churrasco de fim de semana para os vizinhos se tornou um dos assados mais queridos da região.</p>
            <p>Hoje, além das reservas para o salão, o Assadão do Zé leva o sabor do assado até a casa dos clientes pelo delivery, sempre com carnes selecionadas e o tempero de casa que virou marca registrada.</p>
        </div>
        <div class="col-md-6">
            <div class="card-galeria">
                <img src="assets/img/flyer-domingo.png" alt="O melhor assado para o seu domingo - Assadão do Zé">
                <div class="galeria-legenda">
                    <h6 class="mb-0">O melhor assado para o seu domingo!</h6>
                    <small>Faça já a sua reserva</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5 text-center">
        <div class="col-md-4">
            <div class="card-produto p-4">
                <i class="bi bi-award-fill fs-1" style="color: var(--laranja-principal);"></i>
                <h6 class="mt-3">Qualidade</h6>
                <p class="small text-muted mb-0">Cortes selecionados e fornecedores de confiança.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-produto p-4">
                <i class="bi bi-clock-fill fs-1" style="color: var(--laranja-principal);"></i>
                <h6 class="mt-3">Pontualidade</h6>
                <p class="small text-muted mb-0">Seu evento no horário combinado, sem atrasos.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-produto p-4">
                <i class="bi bi-heart-fill fs-1" style="color: var(--laranja-principal);"></i>
                <h6 class="mt-3">Carinho</h6>
                <p class="small text-muted mb-0">Cada prato preparado como se fosse para a nossa própria família.</p>
            </div>
        </div>
    </div>

    <!-- ===================== NOSSA MARCA ===================== -->
    <div class="text-center p-5 mb-5" style="background-color: var(--laranja-claro); border-radius: 14px;">
        <h3 class="fw-bold mb-4">Nossa Marca</h3>
        <div class="row justify-content-center align-items-center g-4">
            <div class="col-6 col-md-3">
                <img src="assets/img/logo-selo.png" alt="Selo Assadão do Zé" class="img-fluid" style="max-width: 160px;">
            </div>
            <div class="col-8 col-md-4">
                <img src="assets/img/logo-marca.jpg" alt="Assadão do Zé" class="img-fluid logo-marca-grande" style="max-width: 260px;">
            </div>
        </div>
        <p class="mt-4 mb-0">
            Siga a gente no Facebook:
            <a href="https://www.facebook.com/assadaodoze.josecarlos.3" target="_blank" rel="noopener" class="fw-bold text-dark">
                <i class="bi bi-facebook"></i> /assadaodoze.josecarlos.3
            </a>
        </p>
    </div>

    <!-- ===================== FAQ (Componente Bootstrap: Accordion) ===================== -->
    <h3 class="secao-titulo">Perguntas Frequentes</h3>
    <div class="accordion" id="accordionFAQ">
        <?php foreach ($perguntasFrequentes as $indice => $item) : ?>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button <?= $indice === 0 ? '' : 'collapsed'; ?>" type="button"
                            data-bs-toggle="collapse" data-bs-target="#faq<?= $indice; ?>">
                        <?= htmlspecialchars($item['pergunta']); ?>
                    </button>
                </h2>
                <div id="faq<?= $indice; ?>" class="accordion-collapse collapse <?= $indice === 0 ? 'show' : ''; ?>"
                     data-bs-parent="#accordionFAQ">
                    <div class="accordion-body">
                        <?= htmlspecialchars($item['resposta']); ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php require __DIR__ . '/includes/rodape.php'; ?>
