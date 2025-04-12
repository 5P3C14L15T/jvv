<?php
require_once 'config/DB.php';
require_once 'config/AtaManager.php';

// Conectar ao banco de dados
$db = new DB();
$conn = $db->connect();
$ataManager = new AtaManager($conn);

// Verificar se o formulário de atualização foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $arquivo = $_FILES['pdf'];

    // Atualizar a ATA usando o método updateAta
    $ataManager->updateAta($id, $nome, $descricao, $arquivo);
}

// Desconectar do banco de dados
$db->disconnect();
?>
