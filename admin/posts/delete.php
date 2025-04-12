<?php
session_start();
require_once '../models/Post.php';
// echo "<pre>";
// print_r($_POST);
// echo "</pre>";
// exit;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'] ?? null;

  if (!$id) {
    header('Location: index.php?erro=id_nao_informado');
    exit;
  }

  $post = Post::buscarPorId($id);
  if (!$post) {
    header('Location: index.php?erro=post_nao_encontrado');
    exit;
  }

  // Remove imagem, se existir
  if (!empty($post['imagem'])) {
    $caminhoImagem = '../uploads/' . $post['imagem'];
    if (file_exists($caminhoImagem)) {
      unlink($caminhoImagem);
    }
  }

  // Deleta o post do banco
  if (Post::deletar($id)) {
    header('Location: index.php?deletado=1');
    exit;
  } else {
    header('Location: index.php?erro=erro_ao_deletar');
    exit;
  }
} else {
  header('Location: index.php');
  exit;
}
