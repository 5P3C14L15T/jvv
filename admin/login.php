<?php
session_start();
require_once 'models/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'] ?? '';
  $senha = $_POST['senha'] ?? '';

  $usuario = Usuario::autenticar($email, $senha);

  if ($usuario) {
    $_SESSION['usuario'] = $usuario;
    header('Location: dashboard.php');
    exit;
  } else {
    header('Location: login.php?erro=1');
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title>Login - Painel Administrativo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/login.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>

  <div class="login-container">
    <!-- Formulário -->
    <div class="form-side">
      <div class="form-box">
        <h2>Login</h2>


        <form action="controllers/AuthController.php" method="POST">
          <?php if (isset($_GET['erro'])): ?>
            <div class="error-message">
              <p>Email ou senha inválidos.</p>
            </div>
          <?php endif; ?>
          <label for="email">Email</label>
          <input type="email" name="email" placeholder="Digite seu email" required autofocus>

          <label for="senha">Senha</label>
          <input type="password" name="senha" placeholder="Digite sua senha" required>

          <button type="submit">Entrar</button>
        </form>
      </div>
    </div>

    <!-- Imagem -->
    <div class="image-side">
      <div class="overlay">
        <h3>Bem-vindo de volta 👋</h3>
        <p>Acesse seu painel administrativo com segurança.</p>
      </div>
    </div>
  </div>

</body>

</html>