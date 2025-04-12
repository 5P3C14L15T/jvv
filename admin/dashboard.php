<?php include 'includes/header.php'; ?>





<h2 class="mb-4">Painel de Controle</h2>
<p>Aqui você acompanha as estatísticas do seu blog e gerencia suas postagens.</p>

<!-- Conteúdo futuro -->
<div class="row g-4 mb-4">

    <!-- Card 1 - Posts -->
    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center">
                <div class="icon bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="fas fa-pen fa-lg"></i>
                </div>
                <div>
                    <h5 class="card-title mb-0">Posts</h5>
                    <h3 class="mb-0" id="stat-posts">0</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2 - Visualizações -->
    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center">
                <div class="icon bg-success text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="fas fa-eye fa-lg"></i>
                </div>
                <div>
                    <h5 class="card-title mb-0">Visualizações</h5>
                    <h3 class="mb-0" id="stat-views">0</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3 - Comentários -->
    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center">
                <div class="icon bg-warning text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="fas fa-comments fa-lg"></i>
                </div>
                <div>
                    <h5 class="card-title mb-0">Comentários</h5>
                    <h3 class="mb-0" id="stat-comments">0</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 4 - Usuários -->
    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center">
                <div class="icon bg-danger text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="fas fa-users fa-lg"></i>
                </div>
                <div>
                    <h5 class="card-title mb-0">Usuários</h5>
                    <h3 class="mb-0" id="stat-users">0</h3>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="d-flex justify-content-between align-items-center mb-3 mt-4">
    <h4 class="mb-0">Últimos Posts</h4>
    <a href="posts/create.php" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Novo Post
    </a>
</div>

<div class="table-responsive shadow-sm rounded-3 overflow-hidden">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th>Título</th>
                <th>Status</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Como usar Bootstrap 5 em projetos reais</td>
                <td><span class="badge bg-success">Publicado</span></td>
                <td>02/04/2025</td>
                <td>
                    <a href="posts/edit.php?id=1" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-edit"></i>
                    </a>
                </td>
            </tr>
            <tr>
                <td>Melhores práticas para SEO em 2025</td>
                <td><span class="badge bg-warning text-dark">Rascunho</span></td>
                <td>28/03/2025</td>
                <td>
                    <a href="posts/edit.php?id=2" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-edit"></i>
                    </a>
                </td>
            </tr>
            <tr>
                <td>Como criar uma landing page matadora</td>
                <td><span class="badge bg-success">Publicado</span></td>
                <td>20/03/2025</td>
                <td>
                    <a href="posts/edit.php?id=3" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-edit"></i>
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
</div>


<?php include 'includes/footer.php'; ?>

