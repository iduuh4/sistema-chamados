<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header text-white text-center mb-3" style="background-color:#2d862d">
                <h5 class="mb-0"><i class="fa-solid fa-user-cog me-2"></i>Editar Perfil</h5>
            </div>
            <div class="card-body">
                <form method="post" action="<?= site_url('perfil/salvar') ?>">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" id="nome" name="nome"
                            value="<?= $usuario->nome ?? '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuário</label>
                        <input type="text" class="form-control" id="usuario" name="usuario"
                            value="<?= $usuario->usuario ?? '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="senha" class="form-label">Nova Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha"
                            placeholder="Preencha apenas se quiser alterar">
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn w-100 text-white" style="background-color:#2d862d">
                            Salvar Alterações <i class="fa-solid fa-floppy-disk ms-1"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="text-center mt-3">
            <a href="<?= site_url('dashboard') ?>" class="text-decoration-none">
                <i class="fa-solid fa-arrow-left"></i> Voltar ao inicio
            </a>
        </div>
    </div>
</div>