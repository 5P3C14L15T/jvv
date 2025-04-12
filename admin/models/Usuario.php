<?php
require_once dirname(__DIR__, 2) . '/core/Conexao.php';

class Usuario {
  public static function autenticar($email, $senha) {
    $pdo = Conexao::conectar();

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
      return $usuario;
    }

    return false;
  }
}
