<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Caminhos absolutos baseados no diretório atual do header.php
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Comentario.php';


if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit;
}

include __DIR__ . '/../includes/config.php';
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <div class="d-flex" id="wrapper">

        <!-- Sidebar -->
        <div class="bg-dark text-white p-3" id="sidebar">
            <h4 class="mb-4">Admin Blog</h4>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="<?php echo $baseUrl; ?>/dashboard.php"><i class="fas fa-chart-line me-2"></i>Dashboard</a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="<?php echo $baseUrl; ?>/posts/index.php"><i class="fas fa-file-alt me-2"></i>Postagens</a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="<?php echo $baseUrl; ?>/categorias/index.php"><i class="fas fa-list me-2"></i>Categorias</a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="<?php echo $baseUrl; ?>/comentarios/index.php">
                        <i class="fas fa-comments me-2"></i>Comentários
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="#"><i class="fas fa-chart-pie me-2"></i>Estatísticas</a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="<?php echo $baseUrl; ?>/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Sair</a>
                </li>
            </ul>

        </div>

        <!-- Conteúdo principal -->
        <div class="flex-grow-1 p-4" id="main-content">
            <nav class="navbar navbar-light bg-light mb-4 rounded shadow-sm px-3">
                <span class="navbar-text fw-bold">
                    Bem-vindo, João Victor
                </span>
            </nav>