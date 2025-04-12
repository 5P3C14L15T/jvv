<?php
session_start(); // Inicia a sessão
// Inclua a classe Database e AtaManager
require_once 'config/DB.php';
require_once 'config/AtaManager.php';
require_once 'config/AuthManager.php';

// var_dump($_SESSION);

// Conectar ao banco de dados
$db = new DB();
$conn = $db->connect();

$authManager = new AuthManager($conn);

if (!$authManager->is_logged_in()) {
    header("Location: ../login.php");
    exit();
}

// Para sair, destrua a sessão
if (isset($_GET['logout'])) {
    session_destroy(); // Destroi todas as sessões
    header("Location: ../login.php");
    exit(); // Garante que o script pare de executar após o redirecionamento
}


// Criar uma instância da classe AtaManager
$ataManager = new AtaManager($conn);

// Verificar se o formulário foi submetido para Inserção, Edição ou Exclusão
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['nome']) && isset($_POST['descricao']) && isset($_FILES['pdf'])) {
        // Se o formulário de inserção foi submetido
        if (!isset($_POST['id'])) {
            $nome = $_POST['nome'];
            $descricao = $_POST['descricao'];
            $arquivo = $_FILES['pdf'];
            // Inserir a ATA usando o método inserirAta
            if ($ataManager->inserirAta($nome, $descricao, $arquivo)) {
                header("Location: dashboard.php?status=inserted");
                exit();
            }
        } else {
            // Se o formulário de edição foi submetido
            $id = $_POST['id'];
            $nome = $_POST['nome'];
            $descricao = $_POST['descricao'];
            $arquivo = $_FILES['pdf'];
            // Atualizar a ATA usando o método updateAta
            if ($ataManager->updateAta($id, $nome, $descricao, $arquivo)) {
                header("Location: dashboard.php?status=updated");
                exit();
            }
        }
    } elseif (isset($_GET['action']) && $_GET['action'] == 'delete') {
        // Se o formulário de exclusão foi submetido
        $id = $_POST['id'];
        // Deletar a ATA usando o método deleteAta
        if ($ataManager->deleteAta($id)) {
            header("Location: dashboard.php?status=deleted");
            exit();
        }
    }
}

// Definir o limite de ATAs por página
$limit = 10;

// Definir a página atual
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Obter o termo de busca (se houver)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Obter as ATAs e o número total de ATAs
if ($searchTerm) {
    $totalAtas = $ataManager->getTotalSearchAtas($searchTerm);
    $atas = $ataManager->searchAtas($searchTerm, $page, $limit);
} else {
    $totalAtas = $ataManager->getTotalAtas();
    $atas = $ataManager->getAtas($page, $limit);
}

// Calcular o número total de páginas
$totalPages = ceil($totalAtas / $limit);

// Inicializar as variáveis para edição
$id = $nome = $descricao = $url = '';

// Verificar se o usuário clicou em "Editar"
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $ata = $ataManager->getAtaById($id);

    if ($ata) {
        $nome = $ata['nome'];
        $descricao = $ata['descricao'];
        $url = $ata['url'];
    } else {
        echo "<div class='alert alert-danger'>ATA não encontrada.</div>";
    }
}

// verificando o toggle
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action']) && $_GET['action'] == 'toggleStatus' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Chamar o método toggleStatus da classe AtaManager
    if ($ataManager->toggleStatus($id)) {
        // Retornar a nova situação em JSON
        echo json_encode(['newStatus' => $_GET['status']]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao alternar o status']);
    }
    exit();
}


?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - IMPÉRIO SOLUÇÕES PÚBLICAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!-- Header -->
<header class="dash_header d-flex justify-content-between align-items-center py-4 px-3">
    <!-- Logo -->
    <div>
        <img src="images/logo-branca.png" alt="Logo do Sistema" class="img-fluid">
    </div>
    
    <!-- Botões de Navegação -->
    <div>
        <a href="../index.php" class="btn btn-primary me-2">Página Inicial</a>
        <a href="dashboard.php?logout" class="btn btn-danger">Sair</a>
    </div>
</header>


    <!-- Main Content -->
    <main class="container my-5">
        <!-- Mensagens de Status -->
        <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
            <div class="alert alert-success">ATA cadastrada com sucesso!</div>
        <?php endif; ?>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'updated'): ?>
            <div class="alert alert-warning">ATA atualizada com sucesso!</div>
        <?php endif; ?>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'deleted'): ?>
            <div class="alert alert-danger">ATA excluída com sucesso!</div>
        <?php endif; ?>

        <!-- Botão de Inserir Nova ATA -->
        <div class="text-center mb-4">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAta">
                Inserir Nova ATA
            </button>
        </div>

        <!-- Sistema de Pesquisa -->
        <div class="mb-4">
            <form class="d-flex justify-content-center" method="GET" action="">
                <input class="form-control me-2" type="search" name="search" placeholder="Pesquisar por Nome" aria-label="Pesquisar" value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button class="btn btn-outline-success" type="submit">Pesquisar</button>
            </form>
        </div>

        <!-- Tabela de Resultados -->
        <table class="table table-striped dash_table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Desc</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($atas) > 0): ?>
                    <?php foreach ($atas as $ata): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($ata['id']); ?></td>
                            <td><?php echo htmlspecialchars($ata['nome']); ?></td>
                            <td><?php echo htmlspecialchars($ata['descricao']); ?></td>
                            <td><?php echo htmlspecialchars($ata['data']); ?></td>
                            <td class="d-flex align-items-center justify-content-center">
                                <a href="dashboard.php?action=edit&id=<?php echo $ata['id']; ?>" class="btn btn-sm btn-warning me-2">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="#" class="btn btn-sm btn-danger me-2" data-bs-toggle="modal" data-bs-target="#modalDelete" data-id="<?php echo $ata['id']; ?>">
                                    <i class="fas fa-trash-alt"></i> Deletar
                                </a>
                                <div class="form-check form-switch">
                                    <input class="form-check-input toggle-status" type="checkbox" id="toggleAta<?php echo $ata['id']; ?>" <?php echo $ata['status'] == 1 ? 'checked' : ''; ?> data-id="<?php echo $ata['id']; ?>">
                                    <label class="form-check-label ms-2" for="toggleAta<?php echo $ata['id']; ?>"><?php echo $ata['status'] == 1 ? 'Ativado' : 'Desativado'; ?></label>
                                </div>
                            </td>


                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Nenhuma ATA encontrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Paginação -->
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => max(1, $page - 1)])); ?>">Anterior</a>
                </li>
                <?php for ($i = max(1, $page - 3); $i <= min($totalPages, $page + 3); $i++): ?>
                    <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                        <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?php if ($page >= $totalPages) echo 'disabled'; ?>">
                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => min($totalPages, $page + 1)])); ?>">Próximo</a>
                </li>
            </ul>
        </nav>

        <!-- Modal de Editar ATA -->
        <?php if ($id): ?>
            <div class="modal fade" id="modalAtaEditar" tabindex="-1" aria-labelledby="modalAtaEditarLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalAtaEditarLabel">Editar ATA</h5>
                            <a href="dashboard.php" class="btn-close" aria-label="Close"></a>
                        </div>
                        <div class="modal-body">
                            <form id="updateAtaForm" method="POST" enctype="multipart/form-data" action="dashboard.php">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                                <div class="mb-3">
                                    <label for="nomeAtaUpdate" class="form-label">Nome</label>
                                    <input type="text" class="form-control" id="nomeAtaUpdate" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="descricaoAtaUpdate" class="form-label">Descrição</label>
                                    <textarea class="form-control" id="descricaoAtaUpdate" name="descricao" rows="3" maxlength="150" required><?php echo htmlspecialchars($descricao); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="uploadPdfUpdate" class="form-label">Upload de PDF (deixe em branco para manter o arquivo atual)</label>
                                    <input class="form-control" type="file" id="uploadPdfUpdate" name="pdf" accept="application/pdf">
                                </div>
                                <button type="submit" id="submitUpdateBtn" class="btn btn-warning">Atualizar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </main>

    <!-- Footer -->
    <footer class="dash_footer text-center py-4">
        <img src="images/logo-branca.png" alt="Logo do Sistema">
    </footer>

    <!-- Modal de Inserir ATA -->
    <div class="modal fade" id="modalAta" tabindex="-1" aria-labelledby="modalAtaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAtaLabel">Inserir Nova ATA</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ataForm" method="POST" enctype="multipart/form-data" action="dashboard.php">
                        <div class="mb-3">
                            <label for="nomeAta" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nomeAta" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="descricaoAta" class="form-label">Descrição</label>
                            <textarea class="form-control" id="descricaoAta" name="descricao" rows="3" maxlength="150" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="uploadPdf" class="form-label">Upload de PDF</label>
                            <input class="form-control" type="file" id="uploadPdf" name="pdf" accept="application/pdf" required>
                        </div>
                        <button type="submit" id="submitBtn" class="btn btn-primary">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmar Exclusão -->
    <div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDeleteLabel">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja deletar esta ATA?</p>
                    <form id="deleteForm" method="POST" action="dashboard.php?action=delete">
                        <input type="hidden" name="id" id="deleteAtaId">
                        <button type="submit" class="btn btn-danger">Deletar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Exibir o modal de edição se a URL contiver action=edit
            const urlParams = new URLSearchParams(window.location.search);
            const action = urlParams.get('action');
            const id = urlParams.get('id');
            if (action === 'edit' && id) {
                const modalEdit = new bootstrap.Modal(document.getElementById('modalAtaEditar'));
                modalEdit.show();
            }

            // Configurar o ID da ATA a ser deletada no modal de exclusão
            document.getElementById('modalDelete').addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var ataId = button.getAttribute('data-id');
                var deleteInput = document.getElementById('deleteAtaId');
                deleteInput.value = ataId;
            });
        });

        // desativando botão
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('ataForm');
            const submitBtn = document.getElementById('submitBtn');
            const nomeInput = document.getElementById('nomeAta');
            const descricaoInput = document.getElementById('descricaoAta');
            const uploadInput = document.getElementById('uploadPdf');

            // Função para validar os campos do formulário
            function validateForm() {
                const nomeValido = nomeInput.value.trim() !== '';
                const descricaoValida = descricaoInput.value.trim() !== '' && descricaoInput.value.length <= 150;
                const uploadValido = uploadInput.files.length > 0;

                // Habilita ou desabilita o botão de submit com base na validação
                submitBtn.disabled = !(nomeValido && descricaoValida && uploadValido);
            }

            // Eventos para validar o formulário em tempo real
            nomeInput.addEventListener('input', validateForm);
            descricaoInput.addEventListener('input', validateForm);
            uploadInput.addEventListener('change', validateForm);

            // Validar o formulário assim que a página carregar
            validateForm();
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Adiciona um EventListener em todos os toggle switches
            document.querySelectorAll('.toggle-status').forEach(function(toggle) {
                toggle.addEventListener('change', function() {
                    const id = this.getAttribute('data-id');
                    const status = this.checked ? 1 : 0;

                    // Faz uma requisição para o servidor para alternar o status
                    fetch(`dashboard.php?action=toggleStatus&id=${id}&status=${status}`, {
                        method: 'POST'
                    }).then(response => {
                        if (response.ok) {
                            return response.json();
                        } else {
                            throw new Error('Erro ao alterar o status.');
                        }
                    }).then(data => {
                        // Atualiza o label conforme o status
                        const label = document.querySelector(`label[for="toggleAta${id}"]`);
                        label.textContent = data.newStatus == 1 ? 'Ativado' : 'Desativado';
                    }).catch(error => {
                        console.error('Erro:', error);
                    });
                });
            });
        });
    </script>

</body>

</html>