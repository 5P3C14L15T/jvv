<?php
// echo 'Slug capturado pela URL: ' . $_GET['slug'];
// exit;

require_once 'includes/config.php';
require_once 'models/Categoria.php';
require_once 'models/Post.php';

$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    echo "<p class='text-danger text-center mt-5'>Categoria não encontrada!</p>";
    exit;
}

$categoria = Categoria::buscarPorSlug($slug);
// var_dump($slug);
// var_dump($categoria);
// exit;

if (!$categoria) {
    echo "<p class='text-danger text-center mt-5'>Categoria inválida!</p>";
    exit;
}

// Definindo metas dinâmicas ANTES do header
$metaTitle = 'Categoria: ' . $categoria['nome'] . ' - Agência João Victor Vieira';
$metaDescription = 'Explore conteúdos da categoria ' . $categoria['nome'] . ' sobre marketing digital, desenvolvimento web e programação.';
$metaKeywords = strtolower($categoria['nome']) . ', marketing digital, desenvolvimento web, programação, blog';
$metaImage = !empty($categoria['imagem_og']) ? BASE_URL . 'admin/uploads/' . $categoria['imagem_og'] : BASE_URL . 'assets/images/og-default.jpg';
// se quiser, pode alterar para algo mais específico por categoria
$metaUrl = BASE_URL . 'categoria/' . urlencode($categoria['slug']);

include 'includes/header.php';



$limite = 6;
$paginaAtual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$offset = ($paginaAtual - 1) * $limite;

$totalPosts = Categoria::contarPostsPorCategoria($categoria['id']);
$totalPaginas = ceil($totalPosts / $limite);

// var_dump($posts[0]); exit;
$populares = Post::listarMaisPopulares();


$posts = Categoria::listarPostsPorCategoriaSlug($slug, $offset, $limite);

// var_dump($slug);
// var_dump($categoria);
// exit;

// var_dump($posts);
?>

<section class="principal pt-5">
    <div class="container">
        <div class="row d-flex">
            <div class="col-md-8 principal-esquerdo">
                <!-- Título da Categoria -->
                <section class="lastnews py-5">
                    <h3 class="title-section ms-4">
                        Últimas Novidades em <strong><?= htmlspecialchars($categoria['nome']) ?></strong>
                    </h3>

                    <!-- Verifica se há posts -->
                    <?php if (!empty($posts)): ?>
                        <div class="row g-4">


                            <?php foreach ($posts as $post): ?>
                                <div class="col-md-4">
                                    <div class="card-principal p-4">
                                        <span class="badge-news"><?= htmlspecialchars($post['categoria_slug']) ?></span>
                                        <div class="card-img-principal-news mb-1">
                                            <img src="<?= UPLOADS_URL .  htmlspecialchars($post['imagem']) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($post['titulo']) ?>">
                                        </div>
                                        <div class="card-texto-principal">
                                            <h5 class="card-title-news fw-bold mb-1">
                                                <a href="<?= BASE_URL . htmlspecialchars($post['slug']) ?>.html">
                                                    <?= mb_strimwidth(htmlspecialchars($post['titulo']), 0, 50, '...') ?>
                                                </a>
                                            </h5>
                                            <p class="card-text-news"><?= substr(strip_tags($post['resumo']), 0, 90) ?>...</p>
                                            <small class="card-text-small-principal-news">
                                                <strong><?= !empty($post['autor']) ? htmlspecialchars($post['autor']) : 'Autor desconhecido' ?></strong> - <?= formatarDataExtenso($post['criado_em']) ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>


                        </div>
                    <?php else: ?>
                        <p class="text-warning mt-4 ms-4">🚫 Nenhuma postagem nesta categoria até o momento.</p>
                    <?php endif; ?>
                    <?php if ($totalPaginas > 1): ?>
                        <nav aria-label="Paginação" class="mt-4">
                            <ul class="pagination justify-content-center">

                                <?php
                                $maxLinks = 3;
                                $start = max(1, $paginaAtual - $maxLinks);
                                $end = min($totalPaginas, $paginaAtual + $maxLinks);
                                ?>

                                <!-- Primeiro -->
                                <?php if ($paginaAtual > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link bg-dark text-warning border-warning" href="categoria.php?slug=<?= urlencode($categoria['slug']) ?>&pagina=1">
                                            &laquo; Primeiro
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <!-- Links anteriores -->
                                <?php for ($i = $start; $i <= $end; $i++): ?>
                                    <li class="page-item <?= ($i === $paginaAtual) ? 'active' : '' ?>">
                                        <a class="page-link <?= ($i === $paginaAtual) ? 'bg-warning text-dark border-warning' : 'bg-dark text-warning border-warning' ?>"
                                            href="categoria.php?slug=<?= urlencode($categoria['slug']) ?>&pagina=<?= $i ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>

                                <!-- Último -->
                                <?php if ($paginaAtual < $totalPaginas): ?>
                                    <li class="page-item">
                                        <a class="page-link bg-dark text-warning border-warning" href="categoria.php?slug=<?= urlencode($categoria['slug']) ?>&pagina=<?= $totalPaginas ?>">
                                            Último &raquo;
                                        </a>
                                    </li>
                                <?php endif; ?>

                            </ul>
                        </nav>
                    <?php endif; ?>


                </section>
            </div>

            <!-- SIDEBAR -->
            <?php include 'includes/sidebar.php'; ?>
        </div>
    </div>
</section>

<script>
    let startTime = Date.now();

    window.addEventListener('beforeunload', function() {
        let tempoSegundos = Math.floor((Date.now() - startTime) / 1000);
        console.log("⏱ Enviando tempo de permanência:", tempoSegundos);

        navigator.sendBeacon(
            'registra_tempo.php',
            JSON.stringify({
                post_id: <?= $post['id'] ?>,
                tempo: tempoSegundos
            })
        );
    });


    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('pre code').forEach(codeBlock => {
            const pre = codeBlock.parentElement;
            if (!pre.classList.contains('line-numbers')) {
                pre.classList.add('line-numbers');
            }
        });
        Prism.highlightAll();
    });


    document.getElementById('newsletterForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);
        const mensagem = document.getElementById('newsletterMensagem');

        mensagem.innerHTML = '⏳ Enviando...';

        fetch('cadastrar_newsletter.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(resultado => {
                if (resultado.status === 'ok') {
                    mensagem.innerHTML = '✅ ' + resultado.mensagem;
                    mensagem.classList.remove('text-danger');
                    mensagem.classList.add('text-success');
                    form.reset();
                } else {
                    mensagem.innerHTML = '⚠️ ' + resultado.mensagem;
                    mensagem.classList.remove('text-success');
                    mensagem.classList.add('text-danger');
                }
            })
            .catch(error => {
                mensagem.innerHTML = '❌ Erro na comunicação. Tente novamente.';
                mensagem.classList.add('text-danger');
                console.error('Erro:', error);
            });
    });

    document.getElementById('formComentario').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);
        const msg = document.getElementById('comentarioMensagem');
        msg.innerHTML = '⏳ Enviando...';

        fetch('cadastrar_comentario.php', {
                method: 'POST',
                body: formData
            })
            .then(resp => resp.json())
            .then(data => {
                if (data.status === 'ok') {
                    msg.innerHTML = '✅ ' + data.mensagem;
                    msg.classList.remove('text-danger');
                    msg.classList.add('text-success');
                    form.reset();
                } else {
                    msg.innerHTML = '⚠️ ' + data.mensagem;
                    msg.classList.remove('text-success');
                    msg.classList.add('text-danger');
                }
            })
            .catch(error => {
                msg.innerHTML = '❌ Erro na comunicação.';
                msg.classList.add('text-danger');
                console.error('Erro:', error);
            });
    });

    document.addEventListener('DOMContentLoaded', () => {
        let pagina = 1;
        const postId = <?= $post['id'] ?>;
        const area = document.getElementById('areaComentarios');
        const btn = document.getElementById('btnVerMais');

        function carregarComentarios() {
            fetch(`carregar_comentarios.php?post_id=${postId}&pagina=${pagina}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'ok') {
                        area.insertAdjacentHTML('beforeend', data.html);
                        pagina++;
                    } else {
                        btn.remove(); // Remove o botão se não houver mais comentários
                    }
                })
                .catch(err => {
                    console.error('Erro ao carregar comentários:', err);
                });
        }

        // Inicia com o primeiro comentário
        carregarComentarios();

        // Clique para carregar mais
        btn.addEventListener('click', carregarComentarios);
    });

    document.addEventListener('click', function(event) {
        if (event.target.closest('.btn-curtir') || event.target.closest('.btn-descurtir')) {
            const btn = event.target.closest('button');
            const id = btn.dataset.id;
            const acao = btn.classList.contains('btn-curtir') ? 'curtir' : 'descurtir';
            const bloco = btn.closest('.comentario');
            const contadorCurtir = bloco.querySelector('.contador-curtidas');
            const contadorDescurtir = bloco.querySelector('.contador-descurtidas');

            fetch('curtir_comentario.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id=${id}&acao=${acao}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'ok') {
                        contadorCurtir.textContent = data.curtidas;
                        contadorDescurtir.textContent = data.descurtidas;
                    } else {
                        alert(data.mensagem || 'Você já reagiu.');
                    }
                })
                .catch(err => {
                    console.error('Erro AJAX:', err);
                });
        }
    });
</script>

<?php include 'includes/footer.php'; ?>