<?php
require_once '../models/Post.php';

// Pega o ID pela URL
$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php');
    exit;
}

// Busca o post pelo ID
$post = Post::buscarPorId($id);

if (!$post) {
    header('Location: index.php?erro=nao_encontrado');
    exit;
}
include '../includes/header.php'; ?>

<div class="container py-5">
    <div class="bg-white rounded-4 shadow p-5 text-center">

        <h2 class="text-danger mb-4"><i class="fas fa-trash-alt me-2"></i>Excluir Post</h2>

        <p class="lead mb-4">
            Tem certeza que deseja excluir o post <strong>"<?= htmlspecialchars($post['titulo']) ?>"</strong>?
            Essa ação não poderá ser desfeita.
        </p>

        <div class="d-flex justify-content-center gap-3">
            <a href="index.php" class="btn btn-secondary px-4">
                <i class="fas fa-times me-1"></i> Cancelar
            </a>

            <form action="delete.php" method="POST">
                <input type="hidden" name="id" value="<?= $post['id'] ?>">
                <button type="submit" class="btn btn-danger px-4">
                    <i class="fas fa-trash me-1"></i> Excluir Post
                </button>
            </form>
        </div>

    </div>
</div>

<?php include '../includes/footer.php'; ?>