<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Visualizar Post</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-light">

  <div class="container py-5">
    <div class="bg-white p-5 rounded-4 shadow">

      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Visualizar Post</h2>
        <a href="index.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Voltar</a>
      </div>

      <!-- Título -->
      <h3 class="fw-bold">Como criar landing pages com Bootstrap 5</h3>

      <!-- Categoria e status -->
      <div class="mb-3">
        <span class="badge bg-warning text-dark me-2">Bootstrap</span>
        <span class="badge bg-success">Publicado</span>
        <small class="text-muted ms-3">Publicado em: 10/03/2024</small>
      </div>

      <!-- Imagem de capa -->
      <div class="mb-4">
        <img src="https://picsum.photos/900/300" alt="Capa do post" class="img-fluid rounded-4 shadow">
      </div>

      <!-- Conteúdo -->
      <div class="post-body fs-5 lh-lg">
        <p>Você aprenderá como estruturar uma landing page profissional usando as classes utilitárias do Bootstrap 5, com responsividade e boas práticas.</p>

        <p>Além disso, vamos incluir:</p>
        <ul>
          <li>Formulário de captura</li>
          <li>Botões estilizados</li>
          <li>Cards de destaque</li>
          <li>Layout mobile first</li>
        </ul>

        <p>Esse conteúdo é ideal para quem está começando ou quer entregar projetos mais rápidos com um visual profissional.</p>

        <p><strong>Dica bônus:</strong> Use a ferramenta <em>Google PageSpeed Insights</em> para validar a performance da sua landing!</p>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
