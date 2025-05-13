<?php
require_once 'models/Comentario.php';
require_once '../core/Conexao.php';

header('Content-Type: application/json');

ini_set('display_errors', 1);
error_reporting(E_ALL);

$id = (int) ($_POST['id'] ?? 0);
$acao = $_POST['acao'] ?? '';

if ($id <= 0 || !in_array($acao, ['curtir', 'descurtir'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Dados inválidos']);
    exit;
}

if (Comentario::atualizarReacao($id, $acao)) {
    // Após atualizar, pegamos os novos números:
    $pdo = Conexao::conectar();
    $stmt = $pdo->prepare("SELECT curtidas, descurtidas FROM comentarios WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $reacoes = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'ok',
        'curtidas' => $reacoes['curtidas'],
        'descurtidas' => $reacoes['descurtidas']
    ]);
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Você já reagiu ou houve erro interno.']);
}
