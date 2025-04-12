<?php
session_start(); // Inicia a sessão
// var_dump($_SESSION['usuario_logado']);
// Verifica se o usuário já está logado
if (isset($_SESSION['usuario_logado'])) {
    
    header("Location: admin/dashboard.php");
    exit(); // Garante que o script pare de executar após o redirecionamento
}

require_once 'admin/config/DB.php';
require_once 'admin/config/AuthManager.php';

$db = new DB();
$conn = $db->connect();
$authManager = new AuthManager($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($authManager->login($email, $password)) {
        header("Location: admin/dashboard.php");
        exit();
    } else {
        $error_message = "Credenciais inválidas, tente novamente.";
    }
}

?>

<!doctype html>
<html lang="pt-br">
<head>
    <title>Login - Império Soluções Públicas</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Entre em contato com a Império Soluções Públicas. Estamos prontos para ajudar com soluções inovadoras para o setor público, garantindo eficiência e qualidade em todos os processos.">
    
    <!-- Meta Author -->
    <meta name="author" content="Império Soluções Públicas">
    
    <!-- Favicon -->
    <link rel="icon" href="images/icon.png" type="image/x-icon">
    

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-page">
        <div class="login-form-container">
            <div class="login-form">
                <h2 class="form-title">Painel</h2>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>
                <form action="login.php" method="post">
                    <div class="form-group">
                        <input type="email" id="email" name="email" placeholder="E-mail" required>
                    </div>
                    <div class="form-group">
                        <input type="password" id="password" name="password" placeholder="Senha" required>
                    </div>
                    <button type="submit" class="btn-login">Entrar</button>
                </form>
            </div>
        </div>
        <div class="login-image">
            <img src="images/bg.jpg" alt="Imagem Login">
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
