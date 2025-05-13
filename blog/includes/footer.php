<?php
require_once 'models/Categoria.php';
$categoriasMaisPostadas = Categoria::listarTopCategorias(10); // método que você deve ter no model
?>


<!-- Modal LGPD -->
<div id="lgpdModal" class="modal fade" tabindex="-1" aria-labelledby="lgpdModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
        <h5 class="modal-title" id="lgpdModalLabel">🍪 Política de Privacidade e Cookies</h5>
      </div>
      <div class="modal-body">
        Usamos cookies para garantir a melhor experiência em nosso site. Ao continuar navegando, você concorda com nossa <a href="politica-privacidade.php" class="text-warning">Política de Privacidade</a>.
      </div>
      <div class="modal-footer">
        <button type="button" id="aceitarLgpd" class="btn btn-success">Aceitar e Continuar</button>
      </div>
    </div>
  </div>
</div>

<script>
// Função para definir cookie
function setCookie(nome, valor, dias) {
  const data = new Date();
  data.setTime(data.getTime() + (dias * 24 * 60 * 60 * 1000));
  const expires = "expires=" + data.toUTCString();
  document.cookie = nome + "=" + valor + ";" + expires + ";path=/";
}

// Função para pegar cookie
function getCookie(nome) {
  const nomeEQ = nome + "=";
  const ca = document.cookie.split(';');
  for(let i = 0; i < ca.length; i++) {
    let c = ca[i].trim();
    if (c.indexOf(nomeEQ) === 0) return c.substring(nomeEQ.length, c.length);
  }
  return null;
}

// Verifica se cookie existe, se não, abre modal
document.addEventListener('DOMContentLoaded', function() {
  if (!getCookie('lgpd_aceito')) {
    const lgpdModal = new bootstrap.Modal(document.getElementById('lgpdModal'));
    lgpdModal.show();

    document.getElementById('aceitarLgpd').addEventListener('click', function() {
      setCookie('lgpd_aceito', 'true', 30);
      lgpdModal.hide();
    });
  }
});
</script>




<footer class="footer bg-black text-white pt-5">
    <div class="container">
        <div class="row">

            <!-- Logo e Descrição + Redes Sociais -->
            <div class="col-md-6 mb-4">
                <div class="d-flex align-items-center mb-3">
                    <img src="<?= BASE_URL ?>assets/images/logo.png" alt="Logo" height="40" class="me-2">
                    <h5 class="mb-0 fw-bold"></h5>
                </div>
                <p class="text-white">Inspiração, estratégia e inovação para quem vive o digital. Atualize-se com conteúdos que transformam ideias em resultados.</p>
                <div class="d-flex gap-3 mt-3">
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

            <!-- Categorias Dinâmicas -->
            <div class="col-md-6 mb-4">
                <h6 class="fw-bold mb-3">Categorias mais populares</h6>
                <ul class="list-unstyled">
                    <?php foreach ($categoriasMaisPostadas as $categoria): ?>
                        <li>
                            <a href="categoria.php?slug=<?= urlencode($categoria['slug']) ?>" class="text-white text-decoration-none">
                                <?= htmlspecialchars($categoria['nome']) ?> (<?= $categoria['total_posts'] ?>)
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

        </div>

        <!-- Rodapé inferior -->
        <div class="border-top border-secondary pb-4 mt-4 pt-3 d-flex flex-column flex-md-row justify-content-between text-white small">
            <p class="mb-2 mb-md-0">© 2025 - Todos os direitos reservados - João Victor Vieira</p>
            <div class="d-flex gap-3">
                <a href="index.php" class="text-white text-decoration-none">Início</a>
                <a href="../index.html#sobre" class="text-white text-decoration-none">Sobre</a>
                <a href="politica-de-privacidade.php" class="text-white text-decoration-none">Política de Privacidade</a>
                <a href="../index.html#contato" class="text-white text-decoration-none">Contato</a>
            </div>
        </div>
    </div>
</footer>

<script src="<?= BASE_URL ?>assets/js/scripts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const searchButton = document.getElementById('openSearch');
    const searchOverlay = document.getElementById('searchOverlay');
    const closeSearch = document.getElementById('closeSearch');

    searchButton.addEventListener('click', () => {
        searchOverlay.classList.add('active');
    });

    closeSearch.addEventListener('click', () => {
        searchOverlay.classList.remove('active');
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === "Escape") {
            searchOverlay.classList.remove('active');
        }
    });
</script>



</body>

</html>