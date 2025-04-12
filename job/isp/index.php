<?php

// Inclua a classe Database e AtaManager
require_once 'admin/config/DB.php';
require_once 'admin/config/AtaManager.php';

// Conectar ao banco de dados
$db = new DB();
$conn = $db->connect();

// Criar uma instância da classe AtaManager
$ataManager = new AtaManager($conn);

// Definir o limite de ATAs por página
$limit = 8;

// Definir a página atual
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Obter o termo de busca (se houver)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Obter as ATAs e o número total de ATAs
if ($searchTerm) {
    $totalAtas = $ataManager->getTotalSearchActiveAtas($searchTerm);
    $atas = $ataManager->searchActiveAtas($searchTerm, $page, $limit, true);
} else {
    $totalAtas = $ataManager->getTotalActiveAtas(true);
    $atas = $ataManager->getActiveAtas($page, $limit, true);
}

// Calcular o número total de páginas
$totalPages = ceil($totalAtas / $limit);

?>

<!doctype html>
<html lang="pt-br">
<head>
    <title>Império Soluções Públicas</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    
     <!-- Meta Description -->
     <meta name="description" content="Entre em contato com a Império Soluções Públicas. Estamos prontos para ajudar com soluções inovadoras para o setor público, garantindo eficiência e qualidade em todos os processos.">
    
    <!-- Meta Author -->
    <meta name="author" content="Império Soluções Públicas">
    
    <!-- Favicon -->
    <link rel="icon" href="images/icon.png" type="image/x-icon">
    

     <!-- Open Graph Protocol Meta Tags -->
     <meta property="og:title" content="Império Soluções Públicas | Entre em Contato" />
    <meta property="og:description" content="Entre em contato com a Império Soluções Públicas. Estamos prontos para ajudar com soluções inovadoras para o setor público, garantindo eficiência e qualidade em todos os processos." />
    <meta property="og:image" content="https://joaovictorvieira.com.br/job/isp/images/mkt.jpg" />
    <meta property="og:url" content="https://joaovictorvieira.com.br/job/isp/contato.php" />
    <meta property="og:type" content="website" />


    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="images/logo-branca.png" class="img-fluid" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Inicial</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sobre.php">Sobre</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contato.php">Contato</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <header class="header" style="background-image: url('images/bg.jpg');">
        <div class="container header-content mt-5 text-center">
            <h1 class="text-white mb-4">Aqui vai uma frase de impacto sugerindo a atividade da empresa</h1>
        </div>
    </header>

    <!-- Lista de ATAs -->
    <section id="listaAtas" class="listaAtas">
        <div class="container text-center">
            <div class="main">
                <!-- Contêiner de Busca -->
                <section class="search my-4">
    <div class="input-group d-flex justify-content-center">
        <form method="GET" action="index.php" class="w-100">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Buscar ATA" aria-label="Buscar" value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button class="btn btn-outline-success" type="submit" id="button-addon">
                    BUSCAR ATA
                </button>
            </div>
        </form>
    </div>
</section>


                <div class="row">
                    <?php if (count($atas) > 0): ?>
                        <?php foreach ($atas as $ata): ?>
                            <div class="col-md-3 col-sm-12 my-1">
                                <div class="card-geral mx-auto">
                                    <div class="card-header"><?php echo htmlspecialchars($ata['nome']); ?></div>
                                    <div class="card-icon">
                                        <img src="images/pdf.png" alt="Ícone PDF" class="icon-img">
                                    </div>
                                    <div class="card-texto"><?php echo htmlspecialchars($ata['descricao']); ?></div>
                                    <div class="card-button">
                                        <a href="<?php echo htmlspecialchars("admin/".$ata['url']); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary">Baixar PDF</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center">Nenhuma ATA encontrada.</p>
                    <?php endif; ?>
                </div>

                <!-- Paginação -->
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo htmlspecialchars($searchTerm); ?>">Anterior</a>
                            </li>
                        <?php else: ?>
                            <li class="page-item disabled">
                                <span class="page-link">Anterior</span>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = max(1, $page - 3); $i <= min($totalPages, $page + 3); $i++): ?>
                            <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo htmlspecialchars($searchTerm); ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo htmlspecialchars($searchTerm); ?>">Próximo</a>
                            </li>
                        <?php else: ?>
                            <li class="page-item disabled">
                                <span class="page-link">Próximo</span>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>

            </div>
        </div>
    </section>

    <footer>
        <div class="footer">
            <div class="container">
                <div class="row">
                    <img src="images/logo.png" alt="" class="img-fluid">
                    <div class="direitos">
                        Todos os Direitos Reservados <br>
                        Criado em 2024
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <!-- JavaScript Personalizado -->
    <script src="js/script.js"></script>
</body>
</html>
