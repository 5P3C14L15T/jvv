<?php
require_once 'models/Comentario.php';
require_once 'models/Newsletter.php';
require_once '../core/Conexao.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Método inválido']);
    exit;
}

$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$conteudo = trim($_POST['conteudo'] ?? '');
$postId = (int) ($_POST['post_id'] ?? 0);

if (strlen($conteudo) > 500) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'O comentário deve ter no máximo 500 caracteres.']);
    exit;
}


if (empty($nome) || empty($email) || empty($conteudo) || $postId <= 0) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Preencha todos os campos.']);
    exit;
}

// Verifica se e-mail está na newsletter
if (!Newsletter::emailExiste($email)) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'E-mail não inscrito na newsletter. Cadastre-se antes.']);
    exit;
}

// Verifica se comentou recentemente
if (Comentario::comentouRecentemente($email, $postId, 1800)) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Aguarde 30 minutos para comentar novamente.']);
    exit;
}

if (Comentario::totalComentariosPost($postId) >= 20) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Limite de comentários atingido para este post.']);
    exit;
}


// Insere comentário
$sucesso = Comentario::cadastrar($email, $postId, $conteudo);

if ($sucesso) {
    echo json_encode(['status' => 'ok', 'mensagem' => 'Comentário enviado com sucesso! Aguarde moderação.']);
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao salvar o comentário.']);
}
