<?php


if (!isset($tituloPagina)) {
    $tituloPagina = 'Assadão do Zé - O melhor churrasco da cidade';
}
if (!isset($paginaAtual)) {
    $paginaAtual = basename($_SERVER['PHP_SELF']);
}

function classeAtiva(string $arquivo, string $paginaAtual): string
{
    return $arquivo === $paginaAtual ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tituloPagina); ?></title>
    <meta name="description" content="Assadão do Zé - churrasco, buffet e serviços de churrascaria com o melhor sabor da cidade.">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/estilo.css" rel="stylesheet">
</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
                <img src="assets/img/logo-selo.png" alt="Assadão do Zé" class="logo-navbar">
                <span class="fw-bold fs-4 d-none d-sm-inline">Assadão do Zé</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal" aria-controls="menuPrincipal" aria-expanded="false" aria-label="Abrir menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="menuPrincipal">
                <ul class="navbar-nav ms-auto text-center align-items-center">
                    <li class="nav-item">
                        <a class="nav-link <?= classeAtiva('index.php', $paginaAtual); ?>" href="index.php">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= classeAtiva('cardapio.php', $paginaAtual); ?>" href="cardapio.php">Cardápio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= classeAtiva('servicos.php', $paginaAtual); ?>" href="servicos.php">Serviços</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= classeAtiva('sobre.php', $paginaAtual); ?>" href="sobre.php">Sobre</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= classeAtiva('contato.php', $paginaAtual); ?>" href="contato.php">Contato</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-whatsapp btn-sm mt-2 mt-lg-0" href="https://wa.me/5547996551093" target="_blank" rel="noopener">
                            <i class="bi bi-whatsapp"></i> (47) 99655-1093
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<main>
