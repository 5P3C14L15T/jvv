<?php
require_once '../includes/header.php';
require_once '../models/Categoria.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    if ($nome) {
        if (Categoria::criar($nome)) {
            header('Location: index.php?sucesso=1');
            exit;
        } else {
            $mensagem = "Erro ao salvar categoria.";
        }
    }
}
?>

<h2>Nova Categoria</h2>

<?php if ($mensagem): ?>
    <div class="alert alert-danger"><?= $mensagem ?></div>
<?php endif; ?>

<form method="POST">
    <div class="mb-3">
        <label for="nome" class="form-label">Nome da categoria</label>
        <input type="text" name="nome" id="nome" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success">Salvar</button>
    <a href="index.php" class="btn btn-secondary">Voltar</a>
</form>

<?php require_once '../includes/footer.php'; ?>
