<?php
require_once '../includes/header.php';
require_once '../models/Categoria.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

$categoria = Categoria::buscarPorId($id);
if (!$categoria) {
    echo "<div class='alert alert-danger'>Categoria não encontrada.</div>";
    require_once '../includes/footer.php';
    exit;
}

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    if ($nome) {
        if (Categoria::atualizar($id, $nome)) {
            header('Location: index.php?editada=1');
            exit;
        } else {
            $mensagem = "Erro ao atualizar categoria.";
        }
    }
}
?>

<h2>Editar Categoria</h2>

<?php if ($mensagem): ?>
    <div class="alert alert-danger"><?= $mensagem ?></div>
<?php endif; ?>

<form method="POST">
    <div class="mb-3">
        <label for="nome" class="form-label">Nome da categoria</label>
        <input type="text" name="nome" id="nome" class="form-control" value="<?= htmlspecialchars($categoria['nome']) ?>" required>
    </div>

    <button type="submit" class="btn btn-primary">Atualizar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php require_once '../includes/footer.php'; ?>
