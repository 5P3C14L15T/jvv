<?php
require_once '../includes/header.php';
require_once '../models/Post.php';
require_once '../models/Categoria.php';


$mensagem = '';

// função para gerar o slug

function gerarSlug($texto)
{
    $texto = iconv('UTF-8', 'ASCII//TRANSLIT', $texto);       // remove acentos
    $texto = strtolower($texto);                              // minúsculas
    $texto = preg_replace('/[^a-z0-9 ]/', '', $texto);        // remove símbolos, mantém espaços
    $texto = preg_replace('/\s+/', '-', trim($texto));        // espaços viram hífen
    return $texto;
}






if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $status = $_POST['status'] ?? '';
    $resumo = $_POST['resumo'] ?? '';
    $tags = $_POST['tags'] ?? '';
    $leitura_min = $_POST['leitura_min'] ?? null;

    $conteudo = $_POST['conteudo'] ?? '';
    $imagem = $_FILES['imagem'] ?? null;

    $autor_id = $_SESSION['usuario_id'];


    $video_url = $_POST['video_url'] ?? '';
    $destaque = isset($_POST['destaque']) ? 1 : 0;


    // Upload da imagem de capa
    $nomeImagem = '';
    $extensoesPermitidas = ['gif', 'png', 'webp', 'jpg', 'jpeg'];

    if ($imagem && $imagem['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($imagem['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $extensoesPermitidas)) {
            $nomeImagem = uniqid() . '.' . $ext;
            move_uploaded_file($imagem['tmp_name'], '../uploads/' . $nomeImagem);
        } else {
            $mensagem = "Formato de imagem não permitido. Use GIF, PNG, WEBP, JPG ou JPEG.";
        }
    }


    $slug = Post::gerarSlugUnico($titulo);



    // echo "<pre>";
    // print_r([
    //     'titulo' => $titulo,
    //     'slug' => $slug,
    //     'imagem' => $nomeImagem,
    //     'categoria' => $categoria,
    //     'resumo' => $resumo,
    //     'conteudo' => $conteudo,
    //     'tags' => $tags,
    //     'video_url' => $video_url,
    //     'leitura_min' => $leitura_min,
    //     'status' => $status,
    //     'destacado' => $destaque,
    //     'autor_id' => $autor_id
    // ]);
    // echo "</pre>";
    // exit;


    $resultado = Post::criar($titulo, $categoria, $status, $resumo, $conteudo, $tags, $video_url, $leitura_min, $destaque, $nomeImagem, $slug, $autor_id);


    if ($resultado) {
        header('Location: index.php');
        exit;
    } else {
        $mensagem = 'Erro ao criar o post. Tente novamente.';
    }
}
?>

<div class="bg-white p-5 rounded-4 shadow">
    <h2 class="mb-4">Novo Post</h2>

    <?php if ($mensagem): ?>
        <div class="alert alert-danger"><?= $mensagem ?></div>
    <?php endif; ?>

    <form action="create.php" method="POST" enctype="multipart/form-data">
        <!-- Título -->
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" required>
        </div>



        <!-- Imagem -->
        <div class="mb-3">
            <label for="imagem" class="form-label">Imagem de Capa</label>
            <input type="file" class="form-control" id="imagem" name="imagem">
        </div>

        <!-- Categoria -->
        <div class="mb-3">
            <label for="categoria" class="form-label">Categoria</label>
            <?php $categorias = Categoria::listarTodas(); ?>
            <select name="categoria" class="form-select" required>
                <option value="" disabled selected>Selecione...</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>"
                        <?= (isset($post['id_categoria']) && $post['id_categoria'] == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

        </div>


        <!-- Resumo -->
        <div class="mb-3">
            <label for="resumo" class="form-label">Resumo</label>
            <textarea class="form-control" id="resumo" name="resumo" rows="3"></textarea>
        </div>

        <!-- Conteúdo (TinyMCE) -->
        <div class="mb-3">
            <label for="conteudo" class="form-label">Conteúdo</label>
            <textarea class="form-control" id="conteudo" name="conteudo" rows="8"></textarea>
        </div>

        <!-- Tags -->
        <div class="mb-3">
            <label for="tags" class="form-label">Tags</label>
            <input type="text" class="form-control" id="tagsInput" placeholder="Digite e pressione vírgula ou Enter">
            <input type="hidden" id="tags" name="tags">
            <div id="tagsContainer" class="d-flex flex-wrap gap-2 mt-2"></div>

        </div>


        <!-- Vídeo URL -->
        <div class="mb-3">
            <label for="video_url" class="form-label">Vídeo (YouTube ou outro)</label>
            <input type="text" class="form-control" id="video_url" name="video_url" placeholder="https://...">
        </div>

        <!-- Tempo estimado -->
        <div class="mb-3">
            <label for="leitura_min" class="form-label">Tempo estimado de leitura (min)</label>
            <input type="number" class="form-control" id="leitura_min" name="leitura_min" min="1">
        </div>

        <!-- Destaque -->
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="destacado" name="destacado" value="1">
            <label class="form-check-label" for="destacado">Destacar esse post na home</label>
        </div>

        <!-- Status -->
        <div class="mb-3">
            <label class="form-label">Status</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="status" id="publicado" value="Publicado" checked>
                <label class="form-check-label" for="publicado">Publicado</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="status" id="rascunho" value="Rascunho">
                <label class="form-check-label" for="rascunho">Rascunho</label>
            </div>
        </div>

        <!-- Botões -->
        <div class="d-flex justify-content-end">
            <a href="index.php" class="btn btn-secondary me-2">Cancelar</a>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
    </form>

</div>


<?php

require_once '../includes/footer.php'; ?>

<!-- Place the first <script> tag in your HTML's <head> -->
<script src="https://cdn.tiny.cloud/1/k2gde27ifw1gwd47l4w5vw3pmy6c8i5k9u3hqbb8s0p1t5nt/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

<!-- Place the following <script> and <textarea> tags your HTML's <body> -->
<script>
    tinymce.init({
        selector: '#conteudo',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    });
</script>

<script>
    const tagsInput = document.getElementById('tagsInput');
    const tagsHiddenInput = document.getElementById('tags');
    const tagsContainer = document.getElementById('tagsContainer');
    const tags = [];

    tagsInput.addEventListener('keyup', (e) => {
        if ((e.key === ',' || e.key === 'Enter') && tagsInput === document.activeElement) {
            e.preventDefault();
            const value = tagsInput.value.trim().replace(',', '');
            if (value && !tags.includes(value)) {
                tags.push(value);
                atualizarTagsUI();
                tagsInput.value = '';
            }
        }

    });

    function atualizarTagsUI() {
        tagsContainer.innerHTML = '';
        tags.forEach(tag => {
            const badge = document.createElement('span');
            badge.className = 'badge bg-primary text-white px-3 py-1 rounded-pill d-flex align-items-center';
            badge.innerHTML = `
        ${tag}
        <span class="ms-2" style="cursor:pointer;" onclick="removerTag('${tag}')">×</span>
      `;
            tagsContainer.appendChild(badge);
        });
        tagsHiddenInput.value = tags.join(',');
    }

    function removerTag(tag) {
        const index = tags.indexOf(tag);
        if (index !== -1) {
            tags.splice(index, 1);
            atualizarTagsUI();
        }
    }
</script>