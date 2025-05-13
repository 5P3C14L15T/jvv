<?php
setlocale(LC_TIME, 'portuguese');
include 'includes/header.php';


$postModel = new Post();
$destaques = $postModel->buscarDestaques();
$populares = $postModel::listarMaisPopulares();

$quintoPost = $postModel->buscarPostPorPosicao(4); // 5º post (offset 4)
$proximosTresPosts = $postModel->buscarPostPorPosicao(5, 3); // 6º, 7º e 8º posts

$categorias = Categoria::listarTodas(); // para a seção por categorias
$ultimosPorCategoriaUnica = $postModel->listarUltimosPorCategoriaUnica(3);

?>



<!-- HERO -->

<?php if (!empty($destaques)): ?>
    <!-- HERO -->
    <section class="hero" id="inicial">
        <div class="hero-bg">
            <img src="<?= UPLOADS_URL .  $destaques[0]['imagem'] ?>" alt="<?= $destaques[0]['titulo'] ?>">
        </div>
        <div class="container hero-content text-white">
            <span class="badge text-white badge-<?= strtolower($categorias[0]['slug']) ?>"><?= $destaques[0]['categoria'] ?></span>

            <h1 class="hero-title"><a href="<?= $destaques[0]['slug'] ?>.html"><?= $destaques[0]['titulo'] ?></a></h1>
            <p class="meta">Por <?= $destaques[0]['autor'] ?> - <?= strftime('%d de %B de %Y', strtotime($destaques[0]['criado_em'])) ?></p>
        </div>
    </section>
<?php endif; ?>



<!-- HERO CARDS FORA DA HERO -->
<section class="hero-cards-wrapper">
    <div class="container">
        <div class="row cards-all">
            <?php for ($i = 1; $i < count($destaques); $i++): ?>
                <div class="col-md-4 p-1">
                    <div class="card">
                        <img src="<?= UPLOADS_URL .  htmlspecialchars($destaques[$i]['imagem']) ?>" class="img-fluid" alt="<?= htmlspecialchars($destaques[$i]['titulo']) ?>">
                        <div class="card-content">
                            <h3>
                                <a href="<?= $destaques[$i]['slug'] ?>.html">
                                    <?= htmlspecialchars($destaques[$i]['titulo']) ?>
                                </a>
                            </h3>
                            <span class="date"><?= strftime('%d de %B de %Y', strtotime($destaques[$i]['criado_em'])) ?></span>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</section>



<section class="principal">
    <div class="container">
        <span class="card-categoria-princial text-white badge">Últimas Notícias</span>

        <div class="row d-flex">

            <div class="col-md-8  principal-esquerdo">
                <div class="d-md-flex principal-esquerdo-main">


                    <?php if (!empty($quintoPost)): ?>
                        <div class="col-md-6">
                            <div class="card-principal p-2">
                                <div class="card-img-principal mb-4">
                                    <img src="<?= UPLOADS_URL .  htmlspecialchars($quintoPost['imagem']) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($quintoPost['titulo']) ?>">
                                </div>
                                <div class="card-texto-principal mb-4">
                                    <h5 class="card-title text-white fw-bold mb-0">
                                        <a href="<?= htmlspecialchars($quintoPost['slug']) ?>.html">
                                            <?= mb_strimwidth(htmlspecialchars($quintoPost['titulo']), 0, 50, '...') ?>
                                        </a>
                                    </h5>
                                    <p class="card-text"><?= substr(strip_tags($quintoPost['resumo']), 0, 90) ?>...</p>
                                    <small class="card-text-small-principal">
                                        <strong><?= htmlspecialchars($quintoPost['autor']) ?></strong> - <?= formatarDataExtenso($quintoPost['criado_em']) ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="col-md-6 p-2 card-principal-direita-all">

                        <?php foreach ($proximosTresPosts as $post): ?>
                            <div class="card-principal-direito d-block mb-3">
                                <div class="card-principal-direito-img-text d-flex">
                                    <img src="<?= UPLOADS_URL .  htmlspecialchars($post['imagem']) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($post['titulo']) ?>">
                                    <div>
                                    <span class="my-0 badge text-white badge-<?= strtolower($post['categoria_slug']) ?>">
                                        <?= htmlspecialchars($post['categoria_slug']) ?>
                                    </span>
                                        <h5 class="card-title-principal-direito fw-bold">
                                            <a href="<?= $post['slug'] ?>.html">
                                                <?= mb_strimwidth(htmlspecialchars($post['titulo']), 0, 50, '...') ?>
                                            </a>
                                        </h5>
                                        <p class="card-text-small-principal-direito"><?= strftime('%d de %B de %Y', strtotime($post['criado_em'])) ?></p>
                                    </div>
                                </div>
                            </div>
                            
                        <?php endforeach; ?>


                    </div>



                </div>

                <!-- últimas notícias -->
                <section class="lastnews py-5">
                    <h3 class="title-section ms-4">Últimas Notícias por <strong>Categorias</strong></h3>
                    <div class="d-md-flex">
                        <?php foreach ($ultimosPorCategoriaUnica as $post): ?>
                            <div class="col-md-4 d-flex flex-column">
                                <div class="card-principal p-4 flex-grow-1 d-flex flex-column">
                                <span class="mb-2 badge text-white badge-<?= strtolower($post['categoria_slug']) ?>">
                                        <?= htmlspecialchars($post['categoria']) ?>
                                    </span>

                                    <div class="card-img-principal-news mb-1">
                                        <img src="<?= UPLOADS_URL .  htmlspecialchars($post['imagem']) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($post['titulo']) ?>">
                                    </div>
                                    <div class="card-texto-principal mb-3">
                                        <h5 class="card-title-news fw-bold mb-1">
                                            <a href="<?= htmlspecialchars($post['slug']) ?>.html">
                                                <?= mb_strimwidth(htmlspecialchars($post['titulo']), 0, 50, '...') ?>
                                            </a>
                                        </h5>
                                        <p class="card-text-news"><?= substr(strip_tags($post['resumo']), 0, 90) ?>...</p>
                                        <small class="card-text-small-principal-news">
                                            <strong><?= htmlspecialchars($post['autor']) ?></strong> - <?= formatarDataExtenso($post['criado_em']) ?>
                                        </small>
                                    </div>
                                </div>
                                <!-- BOTÃO VER MAIS, fora do card mas dentro da coluna -->
                                <div class="mt-3 text-center">
                                    <a href="categoria.php?slug=<?= urlencode($post['categoria_slug']) ?>" class="botao-vermais btn btn-secondary text-white w-75">
                                        + <?= htmlspecialchars($post['categoria']) ?> <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>








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
<?php include 'includes/footer.php'; ?>