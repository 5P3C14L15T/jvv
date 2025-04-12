<?php
require_once '../admin/models/Post.php';

$posts = Post::listarTodos(10, 0); // Pegando os 10 mais recentes
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Blog | Agência JVV</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg-dark text-white">

  <header class="bg-black border-bottom border-danger py-3 mb-4">
    <div class="container d-flex justify-content-between align-items-center">
      <img src="assets/images/logo.png" height="40" alt="Logo">
      <h1 class="h4 mb-0 text-warning">Blog Agência JVV</h1>
    </div>
  </header>

  <main class="container">
    <div class="row">
      <?php foreach ($posts as $post): ?>
        <div class="col-md-6 col-lg-4 mb-4">
          <div class="card h-100 border-0 shadow-sm bg-black text-white">
            <?php if ($post['imagem']): ?>
              <img src="../admin/uploads/<?= $post['imagem'] ?>" class="card-img-top" alt="<?= htmlspecialchars($post['titulo']) ?>">
            <?php endif; ?>
            <div class="card-body">
              <span class="badge bg-warning text-dark mb-2"><?= strtoupper($post['nome_categoria'] ?? 'Geral') ?></span>
              <h5 class="card-title"><?= htmlspecialchars($post['titulo']) ?></h5>
              <p class="card-text small text-secondary">
                <?= date('d/m/Y', strtotime($post['criado_em'])) ?>
              </p>
              <p class="card-text"><?= nl2br(htmlspecialchars($post['resumo'])) ?></p>
              <a href="post.php?slug=<?= $post['slug'] ?>" class="btn btn-sm btn-outline-warning">Leia mais</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </main>

  <footer class="bg-black text-center text-secondary py-4 mt-5">
    &copy; <?= date('Y') ?> Agência JVV • Todos os direitos reservados
  </footer>

</body>
</html>
