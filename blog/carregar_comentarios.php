<?php
require_once 'models/Comentario.php';
require_once '../core/Conexao.php';
require_once 'helpers/formatarData.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);


header('Content-Type: application/json');

$postId = (int) ($_GET['post_id'] ?? 0);
$pagina = (int) ($_GET['pagina'] ?? 1);
$porPagina = 1; // você pode alterar para 5 depois se quiser

if ($postId <= 0) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'ID inválido']);
    exit;
}

$offset = ($pagina - 1) * $porPagina;
$comentarios = Comentario::listarAprovadosPaginado($postId, $offset, $porPagina);

// Se não houver mais comentários
if (empty($comentarios)) {
    echo json_encode(['status' => 'vazio']);
    exit;
}

// Montar o HTML dos comentários
$comentariosHtml = '';
foreach ($comentarios as $comentario) {
    $comentariosHtml .= '
    <div class="comentario border-bottom py-3">
        <p class="mb-1 fw-bold">' . htmlspecialchars($comentario['nome']) . '</p>
        <small class="text-white">' . formatarDataExtenso($comentario['criado_em']) . '</small>
        <p class="mb-1">' . nl2br(htmlspecialchars($comentario['conteudo'])) . '</p>

        <!-- Resposta do administrador -->
        ' . (!empty($comentario['resposta_admin']) ? '
        <div class="bg-light border-start border-4 border-primary ps-3 py-2 mt-3">
            <p class="mb-1 fw-bold text-dark">Resposta do administrador:</p>
            <p class="mb-0 text-dark">' . nl2br(htmlspecialchars($comentario['resposta_admin'])) . '</p>
            <small class="text-muted">' . formatarDataExtenso($comentario['respondido_em']) . '</small>
        </div>
        ' : '') . '

        <div class="d-flex gap-3 mt-3">
            <button class="btn btn-sm btn-outline-success btn-curtir" data-id="' . $comentario['id'] . '">
                👍 <span class="contador-curtidas">' . $comentario['curtidas'] . '</span>
            </button>
            <button class="btn btn-sm btn-outline-danger btn-descurtir" data-id="' . $comentario['id'] . '">
                👎 <span class="contador-descurtidas">' . $comentario['descurtidas'] . '</span>
            </button>
        </div>
    </div>
';
}

echo json_encode([
    'status' => 'ok',
    'html' => $comentariosHtml
]);
