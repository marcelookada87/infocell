<?php require APPROOT . '/app/views/inc/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus"></i> Nova Ordem de Serviço
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo URLROOT; ?>/ordem-servico" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-body">
                <form action="<?php echo URLROOT; ?>/ordem-servico/criar" method="post">
                    <div class="row">
                        <!-- Dados do Cliente -->
                        <div class="col-md-6">
                            <h5 class="mb-3">
                                <i class="fas fa-user"></i> Dados do Cliente
                            </h5>
                            
                            <div class="mb-3">
                                <label for="cliente_id" class="form-label">Cliente *</label>
                                <select name="cliente_id" id="cliente_id" class="form-select <?php echo (!empty($data['cliente_id_err'])) ? 'is-invalid' : ''; ?>" required>
                                    <option value="">Selecione o cliente</option>
                                    <?php foreach ($data['clientes'] as $cliente): ?>
                                    <option value="<?php echo $cliente->id; ?>" <?php echo ($data['cliente_id'] == $cliente->id) ? 'selected' : ''; ?>>
                                        <?php echo $cliente->nome; ?> - <?php echo $cliente->telefone; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">
                                    <?php echo $data['cliente_id_err']; ?>
                                </div>
                                <div class="form-text">
                                    <a href="<?php echo URLROOT; ?>/cliente/criar" target="_blank">
                                        <i class="fas fa-plus"></i> Cadastrar novo cliente
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Dados do Dispositivo -->
                        <div class="col-md-6">
                            <h5 class="mb-3">
                                <i class="fas fa-mobile-alt"></i> Dados do Dispositivo
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="dispositivo_tipo" class="form-label">Tipo de Dispositivo *</label>
                                    <select name="dispositivo_tipo" id="dispositivo_tipo" class="form-select <?php echo (!empty($data['dispositivo_tipo_err'])) ? 'is-invalid' : ''; ?>" required>
                                        <option value="">Selecione o tipo</option>
                                        <option value="celular" <?php echo ($data['dispositivo_tipo'] == 'celular') ? 'selected' : ''; ?>>Celular</option>
                                        <option value="tablet" <?php echo ($data['dispositivo_tipo'] == 'tablet') ? 'selected' : ''; ?>>Tablet</option>
                                        <option value="notebook" <?php echo ($data['dispositivo_tipo'] == 'notebook') ? 'selected' : ''; ?>>Notebook</option>
                                        <option value="desktop" <?php echo ($data['dispositivo_tipo'] == 'desktop') ? 'selected' : ''; ?>>Desktop</option>
                                        <option value="tv" <?php echo ($data['dispositivo_tipo'] == 'tv') ? 'selected' : ''; ?>>TV</option>
                                        <option value="console" <?php echo ($data['dispositivo_tipo'] == 'console') ? 'selected' : ''; ?>>Console</option>
                                        <option value="outros" <?php echo ($data['dispositivo_tipo'] == 'outros') ? 'selected' : ''; ?>>Outros</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        <?php echo $data['dispositivo_tipo_err']; ?>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="prioridade" class="form-label">Prioridade</label>
                                    <select name="prioridade" id="prioridade" class="form-select">
                                        <option value="baixa" <?php echo ($data['prioridade'] == 'baixa') ? 'selected' : ''; ?>>Baixa</option>
                                        <option value="media" <?php echo ($data['prioridade'] == 'media') ? 'selected' : ''; ?>>Média</option>
                                        <option value="alta" <?php echo ($data['prioridade'] == 'alta') ? 'selected' : ''; ?>>Alta</option>
                                        <option value="urgente" <?php echo ($data['prioridade'] == 'urgente') ? 'selected' : ''; ?>>Urgente</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="dispositivo_marca" class="form-label">Marca</label>
                                    <input type="text" name="dispositivo_marca" id="dispositivo_marca" class="form-control" 
                                           value="<?php echo $data['dispositivo_marca']; ?>" placeholder="Ex: Samsung, Apple, LG">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="dispositivo_modelo" class="form-label">Modelo</label>
                                    <input type="text" name="dispositivo_modelo" id="dispositivo_modelo" class="form-control" 
                                           value="<?php echo $data['dispositivo_modelo']; ?>" placeholder="Ex: Galaxy S21, iPhone 12">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="dispositivo_serial_number" class="form-label">Serial Number</label>
                                    <input type="text" name="dispositivo_serial_number" id="dispositivo_serial_number" class="form-control" 
                                           value="<?php echo $data['dispositivo_serial_number']; ?>" placeholder="Ex: SN123456789">
                                    <div class="form-text">
                                        Número de série do dispositivo (opcional)
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="dispositivo_imei" class="form-label">IMEI</label>
                                    <input type="text" name="dispositivo_imei" id="dispositivo_imei" class="form-control" 
                                           value="<?php echo $data['dispositivo_imei']; ?>" placeholder="Ex: 123456789012345" maxlength="15">
                                    <div class="form-text">
                                        IMEI do dispositivo (opcional)
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="row">
                        <!-- Problema Relatado -->
                        <div class="col-md-8">
                            <h5 class="mb-3">
                                <i class="fas fa-exclamation-triangle"></i> Problema Relatado
                            </h5>
                            
                            <div class="mb-3">
                                <label for="problema_relatado" class="form-label">Descrição do Problema *</label>
                                <textarea name="problema_relatado" id="problema_relatado" rows="4" 
                                          class="form-control <?php echo (!empty($data['problema_relatado_err'])) ? 'is-invalid' : ''; ?>" 
                                          placeholder="Descreva detalhadamente o problema relatado pelo cliente..." required><?php echo $data['problema_relatado']; ?></textarea>
                                <div class="invalid-feedback">
                                    <?php echo $data['problema_relatado_err']; ?>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="observacoes" class="form-label">Observações Iniciais</label>
                                <textarea name="observacoes" id="observacoes" rows="3" class="form-control" 
                                          placeholder="Observações adicionais, estado do dispositivo, acessórios..."><?php echo $data['observacoes']; ?></textarea>
                            </div>
                        </div>
                        
                        <!-- Valores -->
                        <div class="col-md-4">
                            <h5 class="mb-3">
                                <i class="fas fa-dollar-sign"></i> Valores
                            </h5>
                            
                            <div class="mb-3">
                                <label for="valor_estimado" class="form-label">Valor Estimado</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" name="valor_estimado" id="valor_estimado" class="form-control valor" 
                                           value="<?php echo $data['valor_estimado']; ?>" placeholder="0,00">
                                </div>
                                <div class="form-text">
                                    Valor estimado para o reparo (opcional)
                                </div>
                            </div>
                            
                            <!-- Informações Adicionais -->
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-info-circle"></i> Informações
                                    </h6>
                                    <ul class="list-unstyled small mb-0">
                                        <li><strong>Status inicial:</strong> Aberta</li>
                                        <li><strong>Data de entrada:</strong> <?php echo date('d/m/Y'); ?></li>
                                        <li><strong>Responsável:</strong> <?php echo $_SESSION['user_name']; ?></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Botões de Ação -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="<?php echo URLROOT; ?>/ordem-servico" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Criar Ordem de Serviço
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<?php require APPROOT . '/app/views/inc/footer.php'; ?>

<!-- Scripts específicos -->
<script>
$(document).ready(function() {
    // Select2 para melhor experiência na seleção de cliente
    if ($.fn.select2) {
        $('#cliente_id').select2({
            placeholder: 'Digite para buscar cliente...',
            allowClear: true
        });
    }
    
    // Sugestões de marcas baseadas no tipo de dispositivo
    const marcasSugestoes = {
        'celular': ['Samsung', 'Apple', 'Xiaomi', 'Motorola', 'LG', 'Huawei', 'Nokia'],
        'tablet': ['Samsung', 'Apple', 'Lenovo', 'Multilaser', 'Positivo'],
        'notebook': ['Dell', 'HP', 'Lenovo', 'Acer', 'Asus', 'Samsung', 'Apple'],
        'desktop': ['Dell', 'HP', 'Lenovo', 'Positivo', 'Custom'],
        'tv': ['Samsung', 'LG', 'Sony', 'Philips', 'TCL', 'Panasonic'],
        'console': ['Sony', 'Microsoft', 'Nintendo'],
        'outros': []
    };
    
    $('#dispositivo_tipo').change(function() {
        const tipo = $(this).val();
        const marcaField = $('#dispositivo_marca');
        
        // Limpar campo marca
        marcaField.val('');
        
        // Adicionar datalist com sugestões
        if (marcasSugestoes[tipo] && marcasSugestoes[tipo].length > 0) {
            let datalistId = 'marcas-' + tipo;
            
            // Remover datalist anterior se existir
            $('#' + datalistId).remove();
            
            // Criar novo datalist
            let datalist = '<datalist id="' + datalistId + '">';
            marcasSugestoes[tipo].forEach(function(marca) {
                datalist += '<option value="' + marca + '">';
            });
            datalist += '</datalist>';
            
            $('body').append(datalist);
            marcaField.attr('list', datalistId);
        } else {
            marcaField.removeAttr('list');
        }
    });
    
    // Trigger change no carregamento se já houver valor selecionado
    if ($('#dispositivo_tipo').val()) {
        $('#dispositivo_tipo').trigger('change');
    }
    
    // Validação do campo IMEI (apenas números, máximo 15 dígitos)
    $('#dispositivo_imei').on('input', function() {
        let value = $(this).val();
        // Remove caracteres não numéricos
        value = value.replace(/\D/g, '');
        // Limita a 15 dígitos
        if (value.length > 15) {
            value = value.substring(0, 15);
        }
        $(this).val(value);
    });
    
    // Validação do campo Serial Number (remove caracteres especiais perigosos)
    $('#dispositivo_serial_number').on('input', function() {
        let value = $(this).val();
        // Remove caracteres potencialmente perigosos para SQL
        value = value.replace(/['";\\]/g, '');
        $(this).val(value);
    });
});
</script>


