<?php

require_once 'includes/config.php'; // <- Primeiro inclui a config

require_once 'models/Post.php';

$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    echo "<p class='text-danger text-center mt-5'>Slug não fornecido!</p>";
    exit;
}

$postModel = new Post();
$post = $postModel->buscarPorSlug($slug);

if (!$post) {
    echo "<p class='text-danger text-center mt-5'>Post não encontrado!</p>";
    exit;
}

// Definição das metas DINÂMICAS após carregar o post:
$metaTitle = $post['titulo'] ?? 'Blog - Agência João Victor Vieira';
$metaDescription = $post['resumo'] ?? 'Conteúdo de marketing digital, desenvolvimento web e programação.';
$metaKeywords = $post['tags'] ?? 'marketing digital, desenvolvimento web, programação, blog';
$metaImage = isset($post['imagem']) ? BASE_URL . 'admin/uploads/' . $post['imagem'] : BASE_URL . 'assets/images/og-default.jpg';
$metaUrl = BASE_URL . ($post['slug'] ?? '');

// Só agora inclui o header (com os metas já setados):
include 'includes/header.php';




$relacionados = Post::buscarRelacionados($post['id_categoria'], $slug);


// estatísticas
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$ip = getUserIP();
$sistema = detectarSO($userAgent);
$navegador = detectarNavegador($userAgent);
$dispositivo = detectarDispositivo($userAgent);

// Debug (temporário)
// echo "<pre>";
// print_r([
//     'ip' => $ip,
//     'user_agent' => $userAgent,
//     'sistema_operacional' => $sistema,
//     'navegador' => $navegador,
//     'dispositivo' => $dispositivo
// ]);
// echo "</pre>";

// estatísticas

if ($post) {
    Estatisticas::registrarVisualizacao($post['id']);
}

$populares = Post::listarMaisPopulares();

?>

<section class="principal pt-5">
    <div class="container">


        <div class="row d-flex">

            <div class="col-md-8  principal-esquerdo">


                <!-- últimas notícias -->
                <main class="post-content container py-5">
                    <!-- Breadcrumb -->
                    <nav class="breadcrumb mb-4">
                        <a href="<?= BASE_URL ?>">Inicial </a> &gt;
                        <a href="<?= BASE_URL ?>categoria/<?= urlencode($post['categoria_slug']) ?>">
                            <?= htmlspecialchars($post['categoria']) ?>
                        </a>
                    </nav>



                    <!-- Título -->
                    <h1 class="post-title"><?= htmlspecialchars($post['titulo']) ?></h1>

                    <!-- Autor e Data + Visualizações -->
                    <div class="post-meta d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center">
                            <img src="../admin/uploads/<?= $post['autor_foto'] ?>" class="rounded-circle me-2" width="40" height="40" alt="<?= $post['autor'] ?>">
                            <div>
                                <strong class="author-name text-white"><?= $post['autor'] ?></strong><br>
                                <small class="text-white">Publicado em <?= formatarDataExtenso($post['criado_em']) ?></small>
                            </div>
                        </div>

                        <div>
                            <small class="text-white">
                                <i class="bi bi-eye me-1"></i> <?= $post['visualizacoes'] ?>
                            </small>
                        </div>
                    </div>


                    <!-- Imagem principal -->
                    <img src="../admin/uploads/<?= $post['imagem'] ?>" class="img-fluid rounded mb-4" alt="<?= $post['titulo'] ?>">

                    <!-- Conteúdo do post -->
                    <article class="post-body">
                        <?= $post['conteudo'] ?>

                    </article>

                </main>


                <!-- AUTOR DO POST -->
                <!-- AUTOR DO POST -->
                <div class="autor-box p-4 rounded mb-5">
                    <div class="d-flex align-items-center">
                        <img src="../admin/uploads/<?= $post['foto'] ?? 'foto.jpg' ?>" alt="<?= $post['autor'] ?>" class="rounded-circle me-3" width="60" height="60">
                        <div>
                            <h5 class="fw-bold mb-1"><?= htmlspecialchars($post['autor']) ?> - Desenvolvedor Web & Estrategista em MKT Digital</h5>
                            <p class="mb-2">Especialista em soluções digitais, criador de conteúdo, web developer e estrategista digital. Apaixonado por impactar negócios com tecnologia.</p>
                            <div class="d-flex gap-3">
                                <ul class="social list-inline mt-2">
                                    <li class="list-inline-item">
                                        <a href="https://www.instagram.com/agenciajoaovictorvieira/" target="_blank">
                                            <i class="fab fa-instagram fa-2x text-white"></i>
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="https://www.linkedin.com/in/devjoaovictor/" target="_blank">
                                            <i class="fab fa-linkedin fa-2x text-white"></i>
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="https://www.youtube.com/channel/UCFvo8j7CqWjiFvJAeIrEHQw?sub_confirmation=1" target="_blank">
                                            <i class="fab fa-youtube fa-2x text-white"></i>
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="https://github.com/5P3C14L15T" target="_blank">
                                            <i class="fab fa-github fa-2x text-white"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- POSTS RELACIONADOS -->
                <div class="relacionados mb-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-bold">Você também pode gostar</h4>
                        <a href="<?= BASE_URL ?>categoria/<?= urlencode($post['categoria_slug']) ?>" class="btn btn-primary text-white small">Ver todos <i class="bi bi-arrow-right"></i></a>
                    </div>
                    <div class="row g-3">
                        <?php foreach ($relacionados as $rel): ?>
                            <div class="col-md-4">
                                <div class="card-principal p-4">



                                    <div class="card-img-principal-news mb-1">
                                        <img src="../admin/uploads/<?= $rel['imagem'] ?>" class="img-fluid rounded" alt="<?= $rel['titulo'] ?>">
                                    </div>
                                    <div class="card-texto-principal">
                                        <h5 class="card-title-news fw-bold mb-1">
                                            <a href="<?= $rel['slug'] ?>.html"><?= mb_strimwidth(htmlspecialchars($rel['titulo']), 0, 50, '...') ?></a>
                                        </h5>
                                        <p class="card-text-news"><?= substr(strip_tags($rel['resumo']), 0, 90) ?>...</p>
                                        <small class="card-text-small-principal-news">
                                            <strong><?= $rel['autor'] ?></strong> - <?= formatarDataExtenso($rel['criado_em']) ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>

                <!-- AVISO DE COMENTÁRIO -->
                <p class="text-white fst-italic mt-4">
                    Para ser publicado, o comentário deve passar por moderação do administrador. *
                </p>

                <?php
                $comentarios = Comentario::listarAprovados($post['id']);

                $comentariosAprovados = count($comentarios); // já está carregado em $comentarios
                ?>

                <button class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalComentario">
                    Postar Comentário (<?= $comentariosAprovados ?>)
                </button>



                <!-- MODAL COMENTÁRIO -->
                <div class="modal fade" id="modalComentario" tabindex="-1" aria-labelledby="modalComentarioLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form id="formComentario" class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
                            <div class="modal-header bg-dark text-white border-0">
                                <h5 class="modal-title fw-semibold" id="modalComentarioLabel">💬 Deixe um comentário</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                            </div>

                            <div class="modal-body bg-dark text-white">
                                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-warning">Nome</label>
                                    <input type="text" name="nome" class="form-control bg-dark-subtle text-white border-warning" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-warning">Email</label>
                                    <input type="email" name="email" class="form-control bg-dark-subtle text-white border-warning" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-warning">Comentário</label>
                                    <textarea name="conteudo" class="form-control bg-dark-subtle text-white border-warning" rows="4" maxlength="500" required></textarea>
                                    <small class="text-white-50">Limite: 500 caracteres</small>

                                </div>

                                <div id="comentarioMensagem" class="text-danger small mt-2"></div>
                            </div>

                            <div class="modal-footer bg-dark border-0">
                                <button type="submit" class="btn btn-warning fw-bold px-4 py-2 rounded-pill">Enviar Comentário</button>
                            </div>
                        </form>
                    </div>
                </div>


                <?php $comentarios = Comentario::listarAprovados($post['id']);

                ?>




                <h4 class="mt-5 text-white">Comentários</h4>

                <div id="areaComentarios">
                    <!-- Comentários iniciais virão aqui via JS -->
                </div>

                <div class="text-center mt-3">
                    <button id="btnVerMais" class="btn btn-outline-light">Ver mais comentários</button>
                </div>

            </div>

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


<!-- PrismJS core -->
<script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/prism.min.js"></script>



<!-- Linguagens -->
<script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-markup.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-markup-templating.min.js"></script> <!-- NECESSÁRIO p/ PHP -->
<script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-php.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-javascript.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-css.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-python.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/plugins/line-numbers/prism-line-numbers.min.js"></script>



<?php include 'includes/footer.php'; ?>