<div class="card shadow-sm">
    <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #2d862d;">
        <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Abrir Novo Chamado</h5>
    </div>
    <div class="card-body">
        <form method="post" action="<?= site_url('registrar/chamado') ?>" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="motivo" class="form-label">Motivo do Chamado <span class="text-danger">*</span></label>
                <input type="text" name="motivo" id="motivo" class="form-control" placeholder="Ex: Problema na conexão" required>
            </div>

            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição / Solução <span class="text-danger">*</span></label>
                <textarea name="descricao" id="descricao" class="form-control" rows="5" placeholder="Descreva o problema ou o que deve ser feito" maxlength="1000" required></textarea>
                <small class="form-text text-muted"><span id="char-count">0</span>/1000 caracteres</small>
            </div>

            <div class="mb-3">
                <label for="fotos" class="form-label">Anexar Imagens (prints ou fotos)</label>
                <input type="file" name="fotos[]" id="fotos" class="form-control" accept="image/*" multiple>
                <small>Tamanho máximo suportado 2 MB (selecione quantas imagens precisar ao abrir a janela de escolha)</small>
                <div id="preview" class="row mt-2"></div>
            </div>

            <div class="mb-3">
                <label for="whatsapp" class="form-label">WhatsApp para Contato <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fab fa-whatsapp text-success"></i></span>
                    <input type="tel" name="whatsapp" id="whatsapp" class="form-control" placeholder="(99) 99999-9999" required>
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-success">
                    Enviar Chamado <i class="fas fa-paper-plane ms-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const descricao = document.getElementById('descricao');
    const charCount = document.getElementById('char-count');

    descricao.addEventListener('input', () => {
        charCount.textContent = descricao.value.length;
    });

    const fotosInput = document.getElementById('fotos');
    const previewContainer = document.getElementById('preview');

    fotosInput.addEventListener('change', function() {
        previewContainer.innerHTML = '';
        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-4 col-md-2 mb-2';
                col.innerHTML = `<img src="${e.target.result}" class="img-fluid rounded border">`;
                previewContainer.appendChild(col);
            }
            reader.readAsDataURL(file);
        });
    });

    document.getElementById('whatsapp').addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '').slice(0, 11);
        let formatado = value;

        if (value.length >= 2) {
            formatado = `(${value.slice(0, 2)}`;
            if (value.length >= 7) {
                formatado += `) ${value.slice(2, 7)}-${value.slice(7, 11)}`;
            } else if (value.length > 2) {
                formatado += `) ${value.slice(2)}`;
            }
        }

        this.value = formatado;
    });
</script>