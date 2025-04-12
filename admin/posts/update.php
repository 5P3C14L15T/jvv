<?php
session_start();
require_once '../models/Post.php';
require_once '../models/Categoria.php';

// Verifica se é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

// Recebe os dados
$titulo = $_POST['titulo'] ?? '';
$id_categoria = $_POST['categoria'] ?? null;
$resumo = $_POST['resumo'] ?? '';
$conteudo = $_POST['conteudo'] ?? '';
$tags = $_POST['tags'] ?? '';
$video_url = $_POST['video_url'] ?? '';
$leitura_min = $_POST['leitura_min'] ?? null;
$status = $_POST['status'] ?? 'Rascunho';
$destacado = isset($_POST['destacado']) ? 1 : 0;

// Verifica imagem
$imagem = $_FILES['imagem'] ?? null;
$imagemAtual = Post::buscarPorId($id)['imagem'] ?? '';
$nomeImagem = $imagemAtual;

$extensoesPermitidas = ['gif', 'png', 'webp', 'jpg', 'jpeg'];

if ($imagem && $imagem['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($imagem['name'], PATHINFO_EXTENSION));
    if (in_array($ext, $extensoesPermitidas)) {
        $nomeImagem = uniqid() . '.' . $ext;
        move_uploaded_file($imagem['tmp_name'], '../uploads/' . $nomeImagem);
        if (!empty($imagemAtual) && file_exists('../uploads/' . $imagemAtual)) {
            unlink('../uploads/' . $imagemAtual);
        }
    } else {
        header("Location: edit.php?id=$id&erro=imagem");
        exit;
    }
}

$slug = Post::gerarSlugUnico($titulo, $id);

$resultado = Post::atualizar(
    $id,
    $titulo,
    $slug,
    $nomeImagem,
    $id_categoria,
    $resumo,
    $conteudo,
    $tags,
    $video_url,
    $leitura_min,
    $status,
    $destacado
);

if ($resultado) {
    header('Location: index.php?atualizado=1');
} else {
    header("Location: edit.php?id=$id&erro=1");
}
exit;
