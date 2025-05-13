<?php
session_start();
require_once __DIR__ . '/../../core/Conexao.php';
require_once __DIR__ . '/../models/Comentario.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        isset($_POST['id'], $_POST['resposta'], $_POST['csrf_token']) &&
        $_POST['csrf_token'] === $_SESSION['csrf_token']
    ) {
        $id = (int) $_POST['id'];
        $resposta = trim($_POST['resposta']);

        if (!empty($resposta)) {
            Comentario::responderComentario($id, $resposta);
            $msg = "Resposta enviada com sucesso.";
        } else {
            $msg = "Resposta não pode estar vazia.";
        }
    } else {
        $msg = "Requisição inválida.";
    }

    header("Location: index.php?msg=" . urlencode($msg));
    exit;
}
