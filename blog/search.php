<?php
include 'includes/header.php';
require_once 'models/Post.php';

$postModel = new Post();

// Verifica se tem termo pesquisado
$termo = isset($_GET['termo']) ? trim($_GET['termo']) : '';

// Configuração de paginação
$limite = 6;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina - 1) * $limite;

$resultados = [];
$totalResultados = 0;

if (!empty($termo)) {
    $resultados = $postModel->buscarPorTermo($termo, $offset, $limite);
    $totalResultados = $postModel->contarPorTermo($termo);
}

// Função para destacar o termo pesquisado
function destacarTermo($texto, $termo) {
    return preg_replace("/($termo)/i", '<mark style="background-color: #ffc107; color: black;">$1</mark>', $texto);
}
?>

<section class="principal pt-5">
    <div class="container">
        <h2 class="mb-5 text-center text-white">Resultados para: <strong><?= htmlspecialchars($termo) ?></strong></h2>

        <div class="row justify-content-center">
            <?php if (!empty($resultados)): ?>
                <?php foreach ($resultados as $post): ?>
                    <div class="card-search col-lg-8 d-flex p-2 mb-4 bg-dark rounded">
                        <div class="img-card-search">
                            <img src="../admin/uploads/<?= htmlspecialchars($post['imagem']) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($post['titulo']) ?>">
                        </div>
                        <div class="text-card-search ms-3 d-flex flex-column justify-content-between">
                            <span class="badge-news1"><?= htmlspecialchars($post['categoria']) ?></span>
                            <h5 class="">
                                <a href="<?= htmlspecialchars($post['slug']) ?>.html" class="text-white">
                                    <?= destacarTermo(htmlspecialchars($post['titulo']), $termo) ?>
                                </a>
                            </h5>
                            <p><?= substr(strip_tags($post['resumo']), 0, 90) ?>...</p>
                            <small class="text-white"><?= htmlspecialchars($post['autor']) ?> - <?= formatarDataExtenso($post['criado_em']) ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center text-white">Nenhum resultado encontrado para <strong><?= htmlspecialchars($termo) ?></strong>.</p>
            <?php endif; ?>
        </div>

        <!-- Paginação -->
        <?php if ($totalResultados > $limite):
            $totalPaginas = ceil($totalResultados / $limite);
        ?>
            <nav class="mt-5">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                        <li class="page-item <?= ($pagina == $i) ? 'active' : '' ?>">
                            <a class="page-link bg-dark text-white border-secondary" href="?termo=<?= urlencode($termo) ?>&pagina=<?= $i ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
