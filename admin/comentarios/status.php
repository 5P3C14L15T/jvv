<?php
session_start();

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("CSRF token inválido.");
}

require_once __DIR__ . '/../../core/Conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $status = $_POST['status'] ?? null;

    if ($id && in_array($status, ['pendente', 'aprovado', 'rejeitado'])) {
        $pdo = Conexao::conectar();

        // Verifica se o comentário existe
        $verifica = $pdo->prepare("SELECT COUNT(*) FROM comentarios WHERE id = :id");
        $verifica->execute([':id' => $id]);

        if ($verifica->fetchColumn() > 0) {
            $sql = "UPDATE comentarios SET status = :status WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':status', $status);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $mensagem = "Comentário atualizado com sucesso.";
        } else {
            $mensagem = "Comentário não encontrado.";
        }
    } else {
        $mensagem = "Requisição inválida.";
    }
}

// Redireciona de volta para o dashboard
header('Location: index.php');
exit;
