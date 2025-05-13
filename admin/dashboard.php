<?php include 'includes/header.php';





$contagemPosts = Post::contarPostsPorStatus();

$topVisualizacoes = Post::listarTopVisualizacoes();

$topComentarios = Comentario::listarTopComentarios();

$totalComentariosPendentes = Comentario::contarPendentes();

$comentariosPendentes = Comentario::listarPendentes();

$ultimosPosts = Post::listarUltimos();

$totalPosts = Post::contarTodos();

$totalVisualizacoes = Post::contarTotalVisualizacoes();

$totalComentarios = Comentario::contarTodos();



?>

<?php
if (isset($_GET['msg'])): ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_GET['msg']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
<?php endif; ?>





<h2 class="mb-4">Painel de Controle</h2>
<p>Aqui você acompanha as estatísticas do seu blog e gerencia suas postagens.</p>

<!-- Conteúdo futuro -->
<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h5 class="card-title">Posts por Status</h5>
                <canvas id="graficoPostsStatus" height="150"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h5 class="card-title">Top 5 Posts Mais Visualizados</h5>
                <canvas id="graficoVisualizacoes" height="150"></canvas>
            </div>
        </div>

    </div>

    <div class="col-md-6 col-xl-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h5 class="card-title">Top 5 Posts com Mais Comentários</h5>
                <canvas id="graficoComentarios" height="150"></canvas>
            </div>
        </div>

    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center">
                <div class="icon bg-warning text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="fas fa-comments fa-lg"></i>
                </div>
                <div>
                    <h5 class="card-title mb-0">Comentários Pendentes</h5>
                    <h3 class="mb-0"><?= $totalComentariosPendentes ?></h3>
                </div>
            </div>
        </div>
    </div>



    <!-- Card 1 - Posts -->
    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center">
                <div class="icon bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="fas fa-pen fa-lg"></i>
                </div>
                <div>
                    <h5 class="card-title mb-0">Posts</h5>
                    <h3 class="mb-0" id="stat-posts">
                        <h3 class="mb-0"><?= $totalPosts ?></h3>
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2 - Visualizações -->
    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center">
                <div class="icon bg-success text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="fas fa-eye fa-lg"></i>
                </div>
                <div>
                    <h5 class="card-title mb-0">Visualizações</h5>
                    <h3 class="mb-0" id="stat-views"><?= number_format($totalVisualizacoes, 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3 - Comentários -->
    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center">
                <div class="icon bg-warning text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="fas fa-comments fa-lg"></i>
                </div>
                <div>
                    <h5 class="card-title mb-0">Comentários</h5>
                    <h3 class="mb-0" id="stat-comments"><?= $totalComentarios ?></h3>
                </div>
            </div>
        </div>
    </div>



    <!-- listagem de comentários -->

    <div class="card mt-4 shadow-sm border-0">
        <div class="card-body">
            <?php foreach ($comentariosPendentes as $comentario): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?= htmlspecialchars($comentario['newsletter_email']) ?></strong><br>
                        <small><?= mb_strimwidth(strip_tags($comentario['conteudo']), 0, 60, '...') ?></small><br>
                        <small class="text-muted"><?= date('d/m/Y H:i', strtotime($comentario['criado_em'])) ?></small>
                    </div>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#comentarioModal<?= $comentario['id'] ?>">
                        Ver
                    </button>
                </li>

                <!-- Modal -->
                <div class="modal fade" id="comentarioModal<?= $comentario['id'] ?>" tabindex="-1" aria-labelledby="comentarioModalLabel<?= $comentario['id'] ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="comentarios/status.php" method="POST">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                                <div class="modal-header">
                                    <h5 class="modal-title" id="comentarioModalLabel<?= $comentario['id'] ?>">Comentário de <?= htmlspecialchars($comentario['newsletter_email']) ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Post:</strong> <?= $comentario['titulo_post'] ?></p>
                                    <p><?= nl2br(htmlspecialchars($comentario['conteudo'])) ?></p>
                                    <input type="hidden" name="id" value="<?= $comentario['id'] ?>">
                                    <div class="form-group">
                                        <label for="status">Status:</label>
                                        <select name="status" class="form-control">
                                            <option value="pendente" selected>Pendente</option>
                                            <option value="aprovado">Aprovado</option>
                                            <option value="rejeitado">Rejeitado</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Salvar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>

</div>

<div class="d-flex justify-content-between align-items-center mb-3 mt-4">
    <h4 class="mb-0">Últimos Posts</h4>
    <a href="posts/create.php" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Novo Post
    </a>
</div>

<div class="table-responsive shadow-sm rounded-3 overflow-hidden">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th>Título</th>
                <th>Status</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ultimosPosts as $post): ?>
                <tr>
                    <td><?= htmlspecialchars($post['titulo']) ?></td>
                    <td>
                        <?php if (strtolower($post['status']) === 'publicado'): ?>
                            <span class="badge bg-success">Publicado</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark"><?= ucfirst($post['status']) ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?= date('d/m/Y', strtotime($post['criado_em'])) ?></td>
                    <td>
                        <a href="posts/edit.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>

    </table>
</div>

<?php
// Pegando os valores para o gráfico
$labels = [];
$totais = [];

foreach ($contagemPosts as $item) {
    $labels[] = ucfirst($item['status']); // Publicado, Rascunho
    $totais[] = $item['total'];
}
?>

<?php
// Preparando os labels (títulos) e os dados (visualizações)
$titulosPosts = [];
$visualizacoesPosts = [];

foreach ($topVisualizacoes as $post) {
    $titulosPosts[] = mb_strimwidth($post['titulo'], 0, 25, '...'); // Limita para o gráfico
    $visualizacoesPosts[] = $post['visualizacoes'];
}
?>

<?php
$titulosComentarios = [];
$quantidadeComentarios = [];

foreach ($topComentarios as $item) {
    $titulosComentarios[] = mb_strimwidth($item['titulo'], 0, 25, '...');
    $quantidadeComentarios[] = $item['total_comentarios'];
}
?>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('graficoPostsStatus').getContext('2d');

    const graficoPosts = new Chart(ctx, {
        type: 'doughnut', // pode ser 'bar', 'pie', 'doughnut'
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: 'Quantidade de Posts',
                data: <?= json_encode($totais) ?>,
                backgroundColor: ['#28a745', '#ffc107'], // Verde para publicado, amarelo para rascunho
                borderColor: ['#28a745', '#ffc107'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: 'Posts por Status'
                }
            }
        }
    });
</script>

<script>
    const ctx2 = document.getElementById('graficoVisualizacoes').getContext('2d');

    const graficoVisualizacoes = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: <?= json_encode($titulosPosts) ?>,
            datasets: [{
                label: 'Visualizações',
                data: <?= json_encode($visualizacoesPosts) ?>,
                backgroundColor: '#007bff', // Azul
                borderColor: '#0056b3',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Top 5 Posts Mais Visualizados'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<script>
    const ctx3 = document.getElementById('graficoComentarios').getContext('2d');

    const graficoComentarios = new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: <?= json_encode($titulosComentarios) ?>,
            datasets: [{
                label: 'Comentários',
                data: <?= json_encode($quantidadeComentarios) ?>,
                backgroundColor: '#ffc107', // Amarelo
                borderColor: '#e0a800',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Top 5 Posts com Mais Comentários'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>





<?php include 'includes/footer.php'; ?>