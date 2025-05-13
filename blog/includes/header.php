<?php
include 'includes/links.php';
require_once 'config.php';

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= htmlspecialchars($metaTitle ?? 'Blog - Agência João Victor Vieira') ?></title>
    <meta name="description" content="<?= htmlspecialchars($metaDescription ?? 'Dicas sobre marketing, desenvolvimento web e programação.') ?>">
    <meta name="keywords" content="<?= htmlspecialchars($metaKeywords ?? 'marketing digital, desenvolvimento web, programação, blog') ?>">

    <!-- Open Graph -->
    <meta property="og:title" content="<?= htmlspecialchars($metaTitle ?? 'Blog - Agência João Victor Vieira') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($metaDescription ?? 'Dicas sobre marketing, desenvolvimento web e programação.') ?>">
    <meta property="og:type" content="article">
    <meta property="og:url" content="<?= $metaUrl ?? BASE_URL ?>">
    <meta property="og:image" content="<?= $metaImage ?? (BASE_URL . 'assets/images/og-default.jpg') ?>">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($metaTitle ?? 'Blog - Agência João Victor Vieira') ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($metaDescription ?? 'Dicas sobre marketing, desenvolvimento web e programação.') ?>">
    <meta name="twitter:image" content="<?= $metaImage ?? (BASE_URL . 'assets/images/og-default.jpg') ?>">



    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/plugins/line-numbers/prism-line-numbers.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


    <link href="<?= BASE_URL ?>assets/css/style.css" rel="stylesheet">

</head>

<body class="bg-dark text-light">

    <!-- NAVBAR PROFISSIONAL -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom py-3">
        <div class="container d-flex justify-content-between align-items-center">

            <!-- Botão Mobile: Menu -->
            <button class="btn d-lg-none text-white fs-4" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuMobile">
                <i class="bi bi-grid-fill"></i>
            </button>

            <!-- LOGO -->
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?= BASE_URL ?>">
                <img src="<?= BASE_URL ?>assets/images/logo_icon.png" alt="Logo" height="32">
                <span class="fw-bold fs-4 text-white d-none">Agência JV</span>
            </a>

            <!-- MENU DESKTOP -->
            <div class="collapse navbar-collapse justify-content-center d-none d-lg-flex" id="navbarNav">
                <ul class="navbar-nav gap-4">
                    <li class="nav-item">
                        <a class="nav-link fw-semibold text-white" href="<?= BASE_URL ?>">INICIAL</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link fw-semibold dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                            CATEGORIAS
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <?php foreach ($categoriasMenu as $categoria): ?>
                                <li>
                                    <a class="dropdown-item" href="<?= BASE_URL ?>categoria/<?= urlencode($categoria['slug']) ?>">
                                        <?= htmlspecialchars($categoria['nome']) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link fw-semibold text-white" href="<?= BASE_URL ?>../index.html#sobre">SOBRE</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold text-white" href="<?= BASE_URL ?>../index.html#contato">CONTATO</a>
                    </li>
                </ul>
            </div>

            <!-- Ícone de busca -->
            <button class="btn text-white fs-5" type="button" id="openSearch">
                <i class="bi bi-search"></i>
            </button>

            <!-- Overlay de busca -->
            <div id="searchOverlay" class="search-overlay">
                <!-- BOTÃO X (FECHAR) -->
                <button id="closeSearch" class="btn-close btn-close-white position-absolute top-0 end-0 m-4" aria-label="Fechar"></button>

                <div class="search-box">
                    <form action="<?= BASE_URL ?>search.php" method="get" class="position-relative w-100">
                        <input type="text" name="termo" placeholder="O que você está procurando?" class="form-control search-input" required>
                        <button type="submit" class="btn btn-search">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>
            </div>


        </div>
    </nav>

    <!-- MENU MOBILE (OFFCANVAS) -->
    <div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="menuMobile">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Menu</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link text-white" href="index.php">INICIAL</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link text-white dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        CATEGORIAS
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <?php foreach ($categoriasMenu as $cat): ?>
                            <li>
                                <a class="dropdown-item" href="categoria.php?slug=<?= htmlspecialchars($cat['slug']) ?>">
                                    <?= htmlspecialchars($cat['nome']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>

                    </ul>
                </li>

                <li class="nav-item"><a class="nav-link text-white" href="../index.html#sobre">SOBRE</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="../index.html#contato">CONTATO</a></li>
            </ul>
        </div>
    </div>