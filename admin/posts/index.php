<?php include '../includes/header.php';
require_once '../models/Post.php';

if (isset($_GET['sucesso'])): ?>
    <div class="alert alert-success">Post excluído com sucesso.</div>
<?php elseif (isset($_GET['erro'])): ?>
    <div class="alert alert-danger">Erro ao excluir o post.</div>
<?php endif;

$busca = $_GET['busca'] ?? '';
$posts = Post::buscarPorTitulo($busca);

$limite = 10;
$pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;
$offset = ($pagina - 1) * $limite;
$totalPosts = Post::contarTodos($busca);
$totalPaginas = ceil($totalPosts / $limite);
$posts = Post::listarPaginado($pagina, $limite, $busca);







// $posts = Post::listarTodos();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Meus Posts</h2>
    <a href="create.php" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Novo Post</a>
</div>

<form method="GET" class="mb-4 d-flex gap-2">
    <input type="text" name="busca" class="form-control" placeholder="Buscar por título..." value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>">
    <button type="submit" class="btn btn-outline-primary"><i class="fas fa-search"></i> Buscar</button>
</form>


<table class="table table-bordered table-hover">
    <thead class="table-light">
        <tr>
            <th>Título</th>
            <th>Categoria</th>
            <th>Status</th>
            <th>Data</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($posts as $post): ?>
            <tr>
                <td><?= htmlspecialchars($post['titulo']) ?></td>
                <td><span class="badge bg-info text-white"><?= ucfirst($post['nome_categoria']) ?></span> </td>
                <td>
                    <span class="badge <?= $post['status'] === 'publicado' ? 'bg-success' : 'bg-secondary' ?>">
                        <?= $post['status'] ?>
                    </span>
                </td>
                <td><?= date('d/m/Y', strtotime($post['criado_em'])) ?></td>
                <td>
                    <a href="view.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                    <a href="edit.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                    <a href="excluir.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir?')"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<nav>
    <ul class="pagination justify-content-center mt-4">
        <?php
        $range = 3; // número de páginas antes e depois da atual
        $start = max(1, $pagina - $range);
        $end = min($totalPaginas, $pagina + $range);

        // Botão de página anterior
        if ($pagina > 1) {
            $anterior = $pagina - 1;
            echo "<li class='page-item'><a class='page-link' href='?pagina=$anterior&busca=" . urlencode($busca) . "'>&laquo;</a></li>";
        }

        for ($i = $start; $i <= $end; $i++):
        ?>
            <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                <a class="page-link" href="?pagina=<?= $i ?>&busca=<?= urlencode($busca) ?>"><?= $i ?></a>
            </li>
        <?php endfor;

        // Botão de próxima página
        if ($pagina < $totalPaginas) {
            $proxima = $pagina + 1;
            echo "<li class='page-item'><a class='page-link' href='?pagina=$proxima&busca=" . urlencode($busca) . "'>&raquo;</a></li>";
        }
        ?>
    </ul>

</nav>


<?php include '../includes/footer.php'; ?>