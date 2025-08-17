<?php $usuario = $this->session->userdata('usuario_logado'); ?>

<div class="card shadow-sm mb-4">
    <div class="card-header text-white" style="background-color:#2d862d">
        <h5 class="mb-0"><i class="fa-solid fa-headset"></i> Meus Chamados</h5>
    </div>

    <div class="card-body p-0">
        <?php if (empty($chamados)): ?>
            <div class="p-4 text-muted text-center">Nenhum chamado encontrado.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0">
                    <thead class="table-light">
                        <tr class="text-center align-middle">
                            <th>ID</th>
                            <th>Motivo</th>
                            <th>Status</th>
                            <th>Data/Hora</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($chamados as $chamado): ?>
                            <tr class="align-middle text-center">
                                <td><?= $chamado->id ?></td>
                                <td class="text-center"><?= htmlspecialchars($chamado->motivo, ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <?php
                                    $badge = 'secondary';
                                    switch ($chamado->status) {
                                        case 'aguardando':
                                            $badge = 'secondary';
                                            break;
                                        case 'andamento':
                                            $badge = 'info';
                                            break;
                                        case 'finalizado':
                                            $badge = 'success';
                                            break;
                                    }
                                    ?>
                                    <span class="badge bg-<?= $badge ?>"><?= ucfirst($chamado->status) ?></span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($chamado->criado_em)) ?></td>
                                <td>
                                    <button
                                        class="btn btn-sm btn-info me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalDetalhes"
                                        title="Ver detalhes"
                                        data-bs-placement="top"
                                        data-id="<?= $chamado->id ?>"
                                        data-motivo="<?= htmlspecialchars($chamado->motivo, ENT_QUOTES, 'UTF-8') ?>"
                                        data-descricao="<?= htmlspecialchars($chamado->descricao, ENT_QUOTES, 'UTF-8') ?>"
                                        data-whatsapp="<?= htmlspecialchars($chamado->whatsapp, ENT_QUOTES, 'UTF-8') ?>"
                                        data-fotos="<?= htmlspecialchars($chamado->fotos ?? '', ENT_QUOTES, 'UTF-8') ?>">

                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <!-- caso seja prestador, os botões ficam inativos -->
                                    <?php if ($usuario['tipo'] !== 'prestador'): ?>
                                        <a href="<?= site_url('editar/chamado/' . $chamado->id) ?>" class="btn btn-sm btn-warning me-1" title="Editar chamado" data-bs-placement="top">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <a href="<?= site_url('dashboard/excluirChamado/' . $chamado->id) ?>"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Tem certeza que deseja excluir este chamado?');" title="Excluir chamado" data-bs-placement="top">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($tipo === 'prestador'): ?>
                                        <?php if ($chamado->status === 'aguardando'): ?>
                                            <a href="<?= site_url('dashboard/alterarStatus/' . $chamado->id . '/andamento') ?>"
                                                class="btn btn-sm btn-primary me-1">
                                                <i class="fas fa-play"></i> Em Andamento
                                            </a>
                                        <?php elseif ($chamado->status === 'andamento'): ?>
                                            <a href="<?= site_url('dashboard/alterarStatus/' . $chamado->id . '/finalizado') ?>"
                                                class="btn btn-sm btn-success me-1">
                                                <i class="fas fa-check"></i> Finalizar
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<div>
    <?= $paginacao ?>
</div>

<script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script>
    document.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        if (!button) return;

        const id = button.getAttribute('data-id');
        const motivo = button.getAttribute('data-motivo') || '';
        const descricao = button.getAttribute('data-descricao') || '';
        const whatsapp = button.getAttribute('data-whatsapp') || '';
        const fotos = (button.getAttribute('data-fotos') || '').split(',').map(s => s.trim()).filter(Boolean);

        const modal = document.getElementById('modalDetalhes');
        modal.querySelector('#modalLabel').textContent = `Detalhes do Chamado #${id}`;
        modal.querySelector('#md-motivo').textContent = motivo;
        modal.querySelector('#md-descricao').innerHTML = descricao.replace(/\n/g, '<br>');
        modal.querySelector('#md-whatsapp').textContent = formatarCelular(whatsapp);

        const fotosEl = modal.querySelector('#md-fotos');
        fotosEl.innerHTML = '';
        fotos.forEach(nome => {
            const col = document.createElement('div');
            col.className = 'col-6 col-md-4 mb-3';

            const img = document.createElement('img');
            img.className = 'img-fluid rounded border';
            img.alt = 'Imagem do chamado';
            img.src = '<?= base_url('uploads/chamados/') ?>' + nome;
            img.style.cursor = 'zoom-in';

            // Evento de clique para abrir no modal
            img.addEventListener('click', () => {
                const modalImagem = new bootstrap.Modal(document.getElementById('modalImagem'));
                document.getElementById('imagemExpandida').src = img.src;
                modalImagem.show();
            });

            col.appendChild(img);
            fotosEl.appendChild(col);
        });
    });

    function formatarCelular(numero) {
        if (!numero) return '';
        const match = numero.replace(/\D/g, '').match(/^(\d{2})(\d{5})(\d{4})$/);
        return match ? `(${match[1]}) ${match[2]}-${match[3]}` : numero;
    }

    // tooltip 
    document.querySelectorAll('[title][data-bs-placement]').forEach(tp => {
    const tooltip = new bootstrap.Tooltip(tp);

    tp.addEventListener('click', () => {
        tooltip.hide();
    });
});
</script>