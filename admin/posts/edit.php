<?php
include '../includes/header.php';
require_once '../models/Post.php';
require_once '../models/Categoria.php';

// Verifica se ID foi passado
$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

// Busca o post
$post = Post::buscarPorId($id);
if (!$post) {
    echo "<div class='alert alert-danger'>Post não encontrado.</div>";
    include '../includes/footer.php';
    exit;
}
$categorias = Categoria::listarTodas();
?>

<h2 class="mb-4">Editar Post</h2>

<form action="update.php?id=<?= $post['id'] ?>" method="POST" enctype="multipart/form-data">

    <!-- Título -->
    <div class="mb-3">
        <label for="titulo" class="form-label">Título</label>
        <input type="text" class="form-control" id="titulo" name="titulo" value="<?= htmlspecialchars($post['titulo']) ?>" required>
    </div>

    <!-- Slug -->
    <input type="hidden" id="slug" name="slug" value="<?= htmlspecialchars($post['slug']) ?>">

    <!-- Imagem -->
    <div class="mb-3">
        <label class="form-label">Imagem Atual</label><br>
        <?php if ($post['imagem']): ?>
            <img src="../uploads/<?= $post['imagem'] ?>" alt="Imagem atual" width="150" class="mb-2 rounded shadow">
        <?php else: ?>
            <p><em>Nenhuma imagem cadastrada</em></p>
        <?php endif; ?>
        <label for="imagem" class="form-label mt-2">Alterar Imagem</label>
        <input type="file" class="form-control" id="imagem" name="imagem">
    </div>

    <!-- Categoria -->
    <div class="mb-3">
        <label for="categoria" class="form-label">Categoria</label>
        <?php $categorias = Categoria::listarTodas(); ?>
        <select name="categoria" class="form-select" required>
            <option disabled>Selecione...</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $post['id_categoria'] == $cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>


    <!-- Resumo -->
    <div class="mb-3">
        <label for="resumo" class="form-label">Resumo</label>
        <textarea class="form-control" id="resumo" name="resumo" rows="3"><?= htmlspecialchars($post['resumo']) ?></textarea>
    </div>

    <!-- Conteúdo -->
    <div class="mb-3">
        <label for="conteudo" class="form-label">Conteúdo</label>
        <textarea class="form-control" id="conteudo" name="conteudo" rows="8"><?= htmlspecialchars($post['conteudo']) ?></textarea>
    </div>

    <!-- Tags -->
    <div class="mb-3">
        <label for="tags" class="form-label">Tags</label>
        <input type="text" class="form-control" id="tagsInput" placeholder="Digite e pressione vírgula ou Enter">
        <input type="hidden" id="tags" name="tags" value="<?= htmlspecialchars($post['tags']) ?>">
        <div id="tagsContainer" class="d-flex flex-wrap gap-2 mt-2"></div>
    </div>

    <!-- Vídeo -->
    <div class="mb-3">
        <label for="video_url" class="form-label">Vídeo (YouTube ou outro)</label>
        <input type="text" class="form-control" id="video_url" name="video_url" value="<?= htmlspecialchars($post['video_url']) ?>">
    </div>

    <!-- Leitura -->
    <div class="mb-3">
        <label for="leitura_min" class="form-label">Tempo de leitura (min)</label>
        <input type="number" class="form-control" id="leitura_min" name="leitura_min" value="<?= $post['leitura_min'] ?>">
    </div>

    <!-- Destaque -->
    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="destacado" name="destacado" value="1" <?= $post['destacado'] ? 'checked' : '' ?>>
        <label class="form-check-label" for="destacado">Destacar esse post na home</label>
    </div>

    <!-- Status -->
    <div class="mb-3">
        <label class="form-label">Status</label>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="status" id="publicado" value="Publicado" <?= $post['status'] == 'Publicado' ? 'checked' : '' ?>>
            <label class="form-check-label" for="publicado">Publicado</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="status" id="rascunho" value="Rascunho" <?= $post['status'] == 'Rascunho' ? 'checked' : '' ?>>
            <label class="form-check-label" for="rascunho">Rascunho</label>
        </div>
    </div>

    <!-- Botões -->
    <div class="d-flex justify-content-end">
        <a href="index.php" class="btn btn-secondary me-2">Cancelar</a>
        <button type="submit" class="btn btn-primary">Atualizar Post</button>
    </div>
</form>

<?php include '../includes/footer.php'; ?>

<!-- Scripts -->
<script src="https://cdn.tiny.cloud/1/k2gde27ifw1gwd47l4w5vw3pmy6c8i5k9u3hqbb8s0p1t5nt/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  
tinymce.init({
    selector: '#conteudo',
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | codesample | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    menubar: false, // opcional: remove menu de cima
    content_css: 'default',
    codesample_languages: [
        {text: 'HTML/XML', value: 'markup'},
        {text: 'JavaScript', value: 'javascript'},
        {text: 'PHP', value: 'php'},
        {text: 'CSS', value: 'css'},
        {text: 'Python', value: 'python'}
    ]
});



    const tagsInput = document.getElementById('tagsInput');
    const tagsHiddenInput = document.getElementById('tags');
    const tagsContainer = document.getElementById('tagsContainer');
    const tags = tagsHiddenInput.value ? tagsHiddenInput.value.split(',') : [];

    const atualizarTagsUI = () => {
        tagsContainer.innerHTML = '';
        tags.forEach(tag => {
            const badge = document.createElement('span');
            badge.className = 'badge bg-primary text-white px-3 py-1 rounded-pill d-flex align-items-center';
            badge.innerHTML = `${tag} <span class="ms-2" style="cursor:pointer;" onclick="removerTag('${tag}')">×</span>`;
            tagsContainer.appendChild(badge);
        });
        tagsHiddenInput.value = tags.join(',');
    };

    const removerTag = (tag) => {
        const index = tags.indexOf(tag);
        if (index !== -1) {
            tags.splice(index, 1);
            atualizarTagsUI();
        }
    };

    tagsInput.addEventListener('keyup', (e) => {
        if (e.key === ',' || e.key === 'Enter') {
            const valor = tagsInput.value.trim().replace(',', '');
            if (valor && !tags.includes(valor)) {
                tags.push(valor);
                atualizarTagsUI();
                tagsInput.value = '';
            }
            e.preventDefault();
        }
    });

    atualizarTagsUI();

    document.getElementById('titulo').addEventListener('input', function() {
        let slug = this.value.toLowerCase()
            .normalize("NFD").replace(/[\u0300-\u036f]/g, "")
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/(^-|-$)/g, '');
        document.getElementById('slug').value = slug;
    });
</script>