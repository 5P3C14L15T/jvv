<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../models/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'] ?? '';
  $senha = $_POST['senha'] ?? '';

  $usuario = Usuario::autenticar($email, $senha);

  if ($usuario) {
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    header('Location: ../dashboard.php');
    exit;
  } else {
    header('Location: ../login.php?erro=1');
    exit;
  }
} else {
  header('Location: ../login.php');
  exit;
}
