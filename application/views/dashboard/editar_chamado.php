<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header text-white text-center" style="background-color:#2d862d">
                <h5 class="mb-0"><i class="fa-solid fa-pen-to-square me-2"></i>Editar Chamado</h5>
            </div>

            <div class="card-body">
                <form method="post" enctype="multipart/form-data" action="<?= site_url('dashboard/atualizarChamado/' . $chamado->id) ?>">
                    <div class="mb-3">
                        <label for="motivo" class="form-label">Motivo</label>
                        <input type="text" class="form-control" id="motivo" name="motivo" required
                            value="<?= htmlspecialchars($chamado->motivo, ENT_QUOTES, 'UTF-8') ?>">
                    </div>

                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="4" required><?= htmlspecialchars($chamado->descricao, ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="whatsapp" class="form-label">WhatsApp</label>
                        <input type="text" class="form-control" id="whatsapp" name="whatsapp" required
                            value="<?= htmlspecialchars($chamado->whatsapp, ENT_QUOTES, 'UTF-8') ?>">
                    </div>

                    <?php if (!empty($chamado->fotos)): ?>
                        <div class="mb-3">
                            <label class="form-label">Imagens atuais</label>
                            <div class="row">
                                <?php foreach (explode(',', $chamado->fotos) as $foto): ?>
                                    <div class="col-6 col-md-4 mb-2">
                                        <img src="<?= base_url('uploads/chamados/' . trim($foto)) ?>"
                                            class="img-fluid rounded border" alt="Imagem">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="fotos" class="form-label">Substituir imagens (opcional)</label>
                        <input type="file" class="form-control" id="fotos" name="fotos[]" multiple accept="image/*">
                        <small class="text-muted">Deixe em branco para manter as imagens atuais</small>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn w-100 text-white" style="background-color:#2d862d">
                            Salvar Alterações <i class="fa-solid fa-save ms-1"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="text-center mt-3">
            <a href="<?= site_url('dashboard') ?>" class="text-decoration-none">
                <i class="fa-solid fa-arrow-left"></i> Voltar à listagem
            </a>
        </div>
    </div>
</div>