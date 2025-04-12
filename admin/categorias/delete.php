<?php
require_once '../models/Categoria.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if (!$id) {
        header('Location: index.php?erro=id_nao_informado');
        exit;
    }

    if (Categoria::deletar($id)) {
        header('Location: index.php?deletada=1');
    } else {
        header('Location: index.php?erro=erro_ao_excluir');
    }
    exit;
} else {
    header('Location: index.php');
    exit;
}
