<?php
require_once 'models/Estatisticas.php';
require_once '../core/Conexao.php';

// Mostrar erros caso existam
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log de requisição
file_put_contents(__DIR__ . '/log.txt', "REQUISIÇÃO RECEBIDA EM: " . date('H:i:s') . "\n", FILE_APPEND);

$data = json_decode(file_get_contents("php://input"), true);

file_put_contents(__DIR__ . '/log.txt', "DADOS: " . json_encode($data) . "\n", FILE_APPEND);

if (isset($data['post_id'], $data['tempo'])) {
    $postId = (int) $data['post_id'];
    $tempo = (int) $data['tempo'];

    if ($postId > 0 && $tempo > 0) {
        Estatisticas::registrarTempoPermanencia($postId, $tempo);
    }
}
