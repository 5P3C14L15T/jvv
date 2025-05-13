<?php
require_once __DIR__ . '/../includes/header.php';

$filtro = $_GET['busca'] ?? '';
$status = $_GET['status'] ?? '';
$pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$limite = 10;
$offset = ($pagina - 1) * $limite;

$comentarios = Comentario::buscarTodos($filtro, $status, $limite, $offset);
$total = Comentario::contarTodosComFiltro($filtro, $status);
$totalPaginas = ceil($total / $limite);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <form method="GET" class="d-flex gap-2 flex-wrap">
        <input type="text" name="busca" placeholder="Buscar por e-mail ou conteúdo" class="form-control" value="<?= htmlspecialchars($filtro) ?>" />
        <select name="status" class="form-select">
            <option value="">Todos</option>
            <option value="pendente" <?= $status === 'pendente' ? 'selected' : '' ?>>Pendente</option>
            <option value="aprovado" <?= $status === 'aprovado' ? 'selected' : '' ?>>Aprovado</option>
            <option value="rejeitado" <?= $status === 'rejeitado' ? 'selected' : '' ?>>Rejeitado</option>
        </select>
        <button type="submit" class="btn btn-primary">Filtrar</button>
    </form>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>E-mail</th>
                <th>Post</th>
                <th>Comentário</th>
                <th>Status</th>
                <th>Data</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($comentarios as $comentario): ?>
                <tr>
                    <td><?= htmlspecialchars($comentario['newsletter_email']) ?></td>
                    <td><?= htmlspecialchars($comentario['titulo_post']) ?></td>
                    <td><?= mb_strimwidth(strip_tags($comentario['conteudo']), 0, 60, '...') ?></td>
                    <td>
                        <?php if ($comentario['status'] === 'pendente'): ?>
                            <span class="badge bg-warning text-dark">Pendente</span>
                        <?php elseif ($comentario['status'] === 'aprovado'): ?>
                            <span class="badge bg-success">Aprovado</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Rejeitado</span>
                        <?php endif; ?>
                    </td>
                    <td><?= date('d/m/Y H:i', strtotime($comentario['criado_em'])) ?></td>
                    <td>
                        <form action="status.php" method="POST" class="d-flex gap-1">
                            <input type="hidden" name="id" value="<?= $comentario['id'] ?>">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <select name="status" class="form-select form-select-sm">
                                <option value="pendente" <?= $comentario['status'] === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                                <option value="aprovado" <?= $comentario['status'] === 'aprovado' ? 'selected' : '' ?>>Aprovado</option>
                                <option value="rejeitado" <?= $comentario['status'] === 'rejeitado' ? 'selected' : '' ?>>Rejeitado</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-success">Salvar</button>
                        </form>

                        <!-- Formulário para responder comentário (somente se aprovado) -->
                        <?php if ($comentario['status'] === 'aprovado'): ?>
                            <form action="responder.php" method="POST">
                                <input type="hidden" name="id" value="<?= $comentario['id'] ?>">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <textarea name="resposta" rows="2" class="form-control mb-1" placeholder="Responder comentário"><?= htmlspecialchars($comentario['resposta_admin'] ?? '') ?></textarea>
                                <button type="submit" class="btn btn-sm btn-info w-100">Responder</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php if ($totalPaginas > 1): ?>
    <nav class="mt-4">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <li class="page-item <?= $i === $pagina ? 'active' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $i ?>&busca=<?= urlencode($filtro) ?>&status=<?= urlencode($status) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>



<?php include __DIR__ . '/../includes/footer.php'; ?>