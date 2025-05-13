<?php
require_once 'models/Newsletter.php';
require_once '../core/Conexao.php';
require_once 'helpers/Email.php';

file_put_contents(__DIR__ . '/log_news.txt', "📩 " . date('H:i:s') . " - POST: " . print_r($_POST, true), FILE_APPEND);


header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Método inválido']);
    exit;
}

$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$postId = (int) ($_POST['post_id'] ?? 0);
$ip = $_SERVER['REMOTE_ADDR'] ?? '';
$ip = $ip === '::1' ? '127.0.0.1' : $ip;
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Desconhecido';


if (empty($nome) || empty($email)) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Preencha todos os campos.']);
    exit;
}

if (Newsletter::emailExiste($email)) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'E-mail já cadastrado.']);
    exit;
}

if (Newsletter::ipEnviouRecentemente($ip)) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Aguarde um pouco antes de tentar novamente.']);
    exit;
}

// Geolocalização
$pais = 'Desconhecido';
$cidade = 'Desconhecida';

$geoData = @file_get_contents("http://ip-api.com/json/?fields=status,country,city");

if ($geoData) {
    $geo = json_decode($geoData, true);
    if ($geo['status'] === 'success') {
        $pais = $geo['country'] ?? $pais;
        $cidade = $geo['city'] ?? $cidade;
    }
}


$sucesso = Newsletter::cadastrar($nome, $email, $postId, $ip, $cidade, $pais, $userAgent);


if ($sucesso) {
    enviarConfirmacaoNewsletter($nome, $email);
    echo json_encode(['status' => 'ok', 'mensagem' => 'E-mail cadastrado com sucesso!']);
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao cadastrar no banco.']);
}
