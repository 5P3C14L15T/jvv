<?php
require_once '../includes/header.php';
require_once '../models/Categoria.php';

$categorias = Categoria::listarTodas();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Categorias</h2>
    <a href="create.php" class="btn btn-primary">Nova Categoria</a>
</div>

<?php if (isset($_GET['deletada'])): ?>
    <div class="alert alert-success">Categoria excluída com sucesso.</div>
<?php elseif (isset($_GET['erro'])): ?>
    <div class="alert alert-danger">Erro ao excluir a categoria.</div>
<?php endif; ?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Slug</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categorias as $cat): ?>
            <tr>
                <td><?= htmlspecialchars($cat['nome']) ?></td>
                <td><?= htmlspecialchars($cat['slug']) ?></td>
                <td class="d-flex gap-2">
                    <a href="edit.php?id=<?= $cat['id'] ?>" class="btn btn-sm btn-warning">Editar</a>

                    <form action="delete.php" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta categoria?')" class="d-inline">
                        <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once '../includes/footer.php'; ?>
