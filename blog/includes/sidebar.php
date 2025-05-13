<div class="col-md-4">

    <div class="mb-4">
        <h5 class="fw-bold text-white mb-3">Siga-nos</h5>
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


    <!-- posts populares -->




    <h4>Posts Populares</h4>

    <?php if (!empty($populares)):

    ?>



        <!-- POST DESTAQUE (primeiro mais visualizado) -->
        <div class="post-destaque position-relative rounded overflow-hidden text-white">
            <img src="<?= UPLOADS_URL .  $populares[0]['imagem'] ?>" alt="<?= htmlspecialchars($populares[0]['titulo']) ?>" class="img-fluid object-cover">

            <!-- Overlay escuro -->
            <div class="overlay position-absolute top-0 start-0 w-100 h-100"></div>

            <!-- Conteúdo sobre a imagem -->
            <div class="conteudo position-absolute bottom-0 p-3 w-100">
                <a class="text-decoration-none badge badge-<?= $populares[0]['categoria_slug'] ?> title-popular-1" href="<?= BASE_URL ?>categoria/<?= urlencode($populares[0]['categoria_slug']) ?>">
                    <?= $populares[0]['categoria'] ?>
                </a>
                <h5 class="card-title-principal-direito fw-bold mb-1">
                    <a class="title-popular-1" href="<?= BASE_URL . $populares[0]['slug'] ?>.html">
                        <?= mb_strimwidth(htmlspecialchars($populares[0]['titulo']), 0, 50, '...') ?>
                    </a>
                </h5>

                <small class="text-light">
                    <?= $populares[0]['autor'] ?? 'Autor desconhecido' ?> - <span class="text-light"><?= formatarDataExtenso($populares[0]['criado_em']) ?></span>
                </small>

            </div>
        </div>

        <!-- DEMAIS POSTS POPULARES -->
        <?php for ($i = 1; $i < count($populares); $i++): ?>
            <div class="card-principal-direito d-block mb-3">
                <div class="card-principal-direito-img-text d-flex">
                    <img src="<?= UPLOADS_URL .  $populares[$i]['imagem'] ?>" class="img-fluid rounded" alt="<?= $populares[$i]['titulo'] ?>">
                    <div>
                        <h5 class="card-title-principal-direito fw-bold">
                            <a class="title-popular-2" href="<?= BASE_URL . $populares[$i]['slug'] ?>.html">
                                <?= mb_strimwidth(htmlspecialchars($populares[$i]['titulo']), 0, 50, '...') ?>
                            </a>
                        </h5>
                        <p class="card-text-small-principal-direito"><?= formatarDataExtenso($populares[$i]['criado_em']) ?></p>
                        <p class="card-text-small-principal-direito"><strong><?= $populares[$i]['autor'] ?? 'Autor desconhecido' ?></strong></p>
                    </div>
                </div>
            </div>
        <?php endfor; ?>


    <?php endif; ?>


    <!-- newsletter -->

    <!-- NEWSLETTER -->
    <aside class="newsletter-box sticky-md-top bg-dark-subtle text-white p-4 mt-4 rounded">
        <h5 class="fw-bold mb-3 text-white">Newsletter - Fique Informado</h5>
        <p class="mb-3 text-white">Inscreva-se na nossa lista de e-mails para receber as novidades.</p>

        <form id="newsletterForm">
            <div class="mb-3">
                <input type="text" class="form-control bg-dark text-white border-0" placeholder="Seu nome" name="nome" required>
            </div>
            <div class="mb-3">
                <input type="email" class="form-control bg-dark text-white border-0" placeholder="Seu melhor e-mail" name="email" required>
            </div>

            <input type="hidden" name="post_id" value="<?= $post['id'] ?? 0 ?>">

            <div id="newsletterMensagem" class="mt-3 text-white small"></div>

            <button type="submit" class="btn btn-success w-100 fw-semibold">Inscreva-se</button>
        </form>
    </aside>





</div>