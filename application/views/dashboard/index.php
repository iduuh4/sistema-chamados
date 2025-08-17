<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>

    <link rel="icon" href="<?php echo base_url('assets/image/icon.png'); ?>" type="image/x-icon">
    <link href="<?= base_url('assets/css-site/DashboardStyle.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <?php $usuario = $this->session->userdata('usuario_logado'); ?>

    <nav class="navbar navbar-dark fixed-top">
        <div class="container-fluid">
            <button class="btn btn-outline-light d-md-none" id="menu-toggle"><i class="fas fa-bars"></i></button>
            <a class="navbar-brand ms-3" href="<?= site_url('dashboard') ?>">Sistema de Chamados</a>
            <div class="d-flex align-items-center">
                <span class="text-light me-3 d-none d-md-inline">
                    <i class="fa-solid fa-user-circle me-1"></i> <?= $this->session->userdata('usuario_logado')['nome'] ?? 'Usuário' ?>
                </span>
                <a href="<?= site_url('logout') ?>" class="btn btn-sm btn-outline-light"><i class="fas fa-sign-out-alt"></i> Sair</a>
            </div>
        </div>
    </nav>

    <div class="sidebar" id="sidebar">
        <a href="<?= site_url('dashboard') ?>" class="<?= strpos(uri_string(), 'dashboard') === 0 ? 'active' : '' ?>"><i class="fas fa-ticket-alt me-2"></i> Chamados
            <?php if (isset($total_chamados)): ?>
                <span class="badge" style="background-color:#212529"><?= $total_chamados ?></span>
            <?php endif; ?>
        </a>
        <?php if ($usuario['tipo'] !== 'prestador'): ?>
            <a href="<?= site_url('abrirchamado') ?>" class="<?= uri_string() === 'abrirchamado' ? 'active' : '' ?>"><i class="fas fa-plus-circle me-2"></i> Novo Chamado</a>
        <?php endif; ?>
        <a href="<?= site_url('perfil') ?>" class="<?= uri_string() === 'perfil' ? 'active' : '' ?>"><i class="fas fa-user-cog me-2"></i> Perfil</a>
    </div>

    <div class="content">
        <div class="container-fluid">
            <?php if ($this->session->flashdata('mensagem')):
                $msg = $this->session->flashdata('mensagem'); ?>
                <div class="alert alert-<?= $msg['tipo'] ?> alert-dismissible fade show" role="alert">
                    <?= $msg['texto'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                </div>
            <?php endif; ?>

            <?= $conteudo ?? '<h5 class="text-muted">Bem-vindo ao sistema</h5>' ?>
        </div>
    </div>

    <!-- Modal detalhes do chamado -->
    <div class="modal fade" id="modalDetalhes" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-white" style="background-color:#2d862d">
                    <h5 class="modal-title" id="modalLabel">Detalhes do Chamado</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Motivo:</strong><br><span id="md-motivo"></span></p>
                    <p><strong>Descrição:</strong><br><span id="md-descricao"></span></p>
                    <p><strong>WhatsApp:</strong><br><span id="md-whatsapp"></span></p>
                    <div id="md-fotos" class="row mt-3"></div>
                    <small>Pressione na imagem para ampliar.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para visualizar imagem -->
    <div class="modal fade" id="modalImagem" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content bg-dark">
                <div class="modal-body p-0">
                    <img id="imagemExpandida" src="" alt="Imagem ampliada" class="img-fluid w-100">
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("menu-toggle").addEventListener("click", function() {
            document.getElementById("sidebar").classList.toggle("show");
        });
    </script>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>