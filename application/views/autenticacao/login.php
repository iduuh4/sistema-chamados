<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $title ?></title>

	<link rel="icon" href="<?php echo base_url('assets/image/icon.png'); ?>" type="image/x-icon">
	<link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body class="d-flex align-items-center" style="height: 100vh; background-color: #212529;">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-4">

				<?php
				if ($this->session->flashdata('mensagem')):
					$msg = $this->session->flashdata('mensagem');
				?>
					<div class="alert alert-<?= $msg['tipo'] ?> alert-dismissible fade show" role="alert">
						<?= $msg['texto'] ?>
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
					</div>
				<?php endif; ?>

				<div class="card shadow-sm">
					<h4 class="card-header card-title text-center mb-2 text-white" style="background-color:#2d862d">Acesso ao Sistema</h4>
					<div class="card-body">
						<form method="post" action="<?= site_url('autenticar/login') ?>">
							<div class="mb-3">
								<label for="usuario" class="form-label">Login</label>
								<input type="text" class="form-control" id="usuario" name="usuario" placeholder="Digite seu login" required>
							</div>
							<div class="mb-4">
								<label for="senha" class="form-label">Senha</label>
								<input type="password" class="form-control" id="senha" name="senha" placeholder="Digite sua senha" required>
							</div>
							<button type="submit" class="btn btn-primary w-100" style="background-color:#2d862d">Entrar <i class="fa-solid fa-arrow-right-to-bracket"></i></button>
						</form>
					</div>
				</div>
				<div class="text-center mt-3">
					<a href="<?= site_url('registrar') ?>" class="text-decoration-none text-white">
						<i class="fa-solid fa-user-plus me-1"></i> Cadastrar Usuário
					</a>
				</div>
			</div>
		</div>
	</div>

	<script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>