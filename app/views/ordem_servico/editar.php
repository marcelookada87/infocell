<?php require APPROOT . '/app/views/inc/header.php'; ?>

<!-- CSS Personalizado para Ordem de Serviço -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/ordem-servico.css">

<div class="ordem-servico-editar">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit"></i> Editar Ordem de Serviço #<?php echo $data['ordem']->id; ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo URLROOT; ?>/ordem-servico/visualizar/<?php echo $data['ordem']->id; ?>" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-eye"></i> Visualizar
        </a>
        <a href="<?php echo URLROOT; ?>/ordem-servico" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-body">
                <form action="<?php echo URLROOT; ?>/ordem-servico/editar/<?php echo $data['ordem']->id; ?>" method="post" id="formEditarOS">
                    <div class="row">
                        <!-- Dados do Cliente (Somente Leitura) -->
                        <div class="col-md-6">
                            <h5 class="mb-3">
                                <i class="fas fa-user"></i> Dados do Cliente
                            </h5>
                            
                            <div class="mb-3">
                                <label class="form-label">Cliente</label>
                                <input type="text" class="form-control" value="<?php echo $data['cliente']->nome; ?> - <?php echo $data['cliente']->telefone; ?>" readonly>
                                <div class="form-text">
                                    <i class="fas fa-info-circle"></i> Cliente não pode ser alterado após criação
                                </div>
                            </div>
                        </div>
                        
                        <!-- Dados do Dispositivo (Somente Leitura) -->
                        <div class="col-md-6">
                            <h5 class="mb-3">
                                <i class="fas fa-mobile-alt"></i> Dados do Dispositivo
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tipo</label>
                                    <input type="text" class="form-control" value="<?php echo ucfirst($data['ordem']->dispositivo_tipo); ?>" readonly>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Prioridade</label>
                                    <input type="text" class="form-control" value="<?php echo ucfirst($data['ordem']->prioridade); ?>" readonly>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Marca</label>
                                    <input type="text" class="form-control" value="<?php echo $data['ordem']->dispositivo_marca; ?>" readonly>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Modelo</label>
                                    <input type="text" class="form-control" value="<?php echo $data['ordem']->dispositivo_modelo; ?>" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="row">
                        <!-- Problema Relatado (Somente Leitura) -->
                        <div class="col-md-8">
                            <h5 class="mb-3">
                                <i class="fas fa-exclamation-triangle"></i> Problema Relatado
                            </h5>
                            
                            <div class="mb-3">
                                <label class="form-label">Descrição do Problema</label>
                                <textarea class="form-control" rows="4" readonly><?php echo $data['ordem']->problema_relatado; ?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Observações Iniciais</label>
                                <textarea class="form-control" rows="3" readonly><?php echo $data['ordem']->observacoes; ?></textarea>
                            </div>
                        </div>
                        
                        <!-- Valores (Somente Leitura) -->
                        <div class="col-md-4">
                            <h5 class="mb-3">
                                <i class="fas fa-dollar-sign"></i> Valores
                            </h5>
                            
                            <div class="mb-3">
                                <label class="form-label">Valor Estimado</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" class="form-control" value="<?php echo $data['ordem']->valor_estimado; ?>" readonly>
                                </div>
                            </div>
                            
                            <!-- Informações da OS -->
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-info-circle"></i> Informações da OS
                                    </h6>
                                    <ul class="list-unstyled small mb-0">
                                        <li><strong>Status atual:</strong> <span class="<?php echo getStatusBadgeClass($data['ordem']->status); ?>"><?php echo getStatusName($data['ordem']->status); ?></span></li>
                                        <li><strong>Data de entrada:</strong> <?php echo date('d/m/Y', strtotime($data['ordem']->criado_em)); ?></li>
                                        <li><strong>Responsável:</strong> <?php echo $data['ordem']->usuario_nome; ?></li>
                                        <?php if ($data['ordem']->atualizado_em): ?>
                                        <li><strong>Última atualização:</strong> <?php echo date('d/m/Y H:i', strtotime($data['ordem']->atualizado_em)); ?></li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Seção de Edição - Apenas para Técnicos -->
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="mb-3">
                                <i class="fas fa-tools"></i> Atualizações Técnicas
                            </h5>
                            
                            <!-- Barra de Progresso -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Progresso da OS</span>
                                    <span class="badge bg-primary" id="progressPercentage">0%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar" id="progressBar" role="progressbar" style="width: 0%"></div>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    Próxima etapa: <span id="nextStepText"><?php echo getNextStep($data['ordem']->status); ?></span>
                                </small>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Status da OS *</label>
                                    <select name="status" id="status" class="form-select" required 
                                            data-bs-toggle="tooltip" data-bs-placement="top" 
                                            title="Selecione o status atual da ordem de serviço">
                                        <option value="aberta" <?php echo ($data['ordem']->status == 'aberta') ? 'selected' : ''; ?>>Aberta</option>
                                        <option value="em_andamento" <?php echo ($data['ordem']->status == 'em_andamento') ? 'selected' : ''; ?>>Em Andamento</option>
                                        <option value="aguardando_pecas" <?php echo ($data['ordem']->status == 'aguardando_pecas') ? 'selected' : ''; ?>>Aguardando Peças</option>
                                        <option value="aguardando_aprovacao" <?php echo ($data['ordem']->status == 'aguardando_aprovacao') ? 'selected' : ''; ?>>Aguardando Aprovação</option>
                                        <option value="concluida" <?php echo ($data['ordem']->status == 'concluida') ? 'selected' : ''; ?>>Concluída</option>
                                        <option value="cancelada" <?php echo ($data['ordem']->status == 'cancelada') ? 'selected' : ''; ?>>Cancelada</option>
                                    </select>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle"></i> Atualize o status conforme o progresso
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="valor_final" class="form-label">Valor Final</label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="text" name="valor_final" id="valor_final" class="form-control valor" 
                                               value="<?php echo $data['ordem']->valor_final; ?>" placeholder="0,00"
                                               data-bs-toggle="tooltip" data-bs-placement="top" 
                                               title="Valor final cobrado do cliente (opcional)">
                                    </div>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle"></i> Valor final cobrado do cliente
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="problema_diagnosticado" class="form-label">Problema Diagnosticado</label>
                                <textarea name="problema_diagnosticado" id="problema_diagnosticado" rows="4" 
                                          class="form-control" placeholder="Descreva o problema real encontrado durante a análise..."
                                          data-bs-toggle="tooltip" data-bs-placement="top" 
                                          title="Descreva o problema real encontrado durante a análise técnica"><?php echo $data['ordem']->problema_diagnosticado; ?></textarea>
                                <div class="form-text">
                                    <i class="fas fa-info-circle"></i> Problema real encontrado durante a análise técnica
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="solucao_aplicada" class="form-label">Solução Aplicada</label>
                                <textarea name="solucao_aplicada" id="solucao_aplicada" rows="4" 
                                          class="form-control" placeholder="Descreva a solução aplicada para resolver o problema..."
                                          data-bs-toggle="tooltip" data-bs-placement="top" 
                                          title="Descreva a solução técnica aplicada para resolver o problema"><?php echo $data['ordem']->solucao_aplicada; ?></textarea>
                                <div class="form-text">
                                    <i class="fas fa-info-circle"></i> Solução técnica aplicada para resolver o problema
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="observacoes_tecnico" class="form-label">Observações Técnicas</label>
                                <textarea name="observacoes_tecnico" id="observacoes_tecnico" rows="3" 
                                          class="form-control" placeholder="Observações técnicas, peças utilizadas, testes realizados..."
                                          data-bs-toggle="tooltip" data-bs-placement="top" 
                                          title="Observações técnicas, peças utilizadas, testes realizados (opcional)"><?php echo $data['ordem']->observacoes_tecnico; ?></textarea>
                                <div class="form-text">
                                    <i class="fas fa-info-circle"></i> Observações técnicas, peças utilizadas, testes realizados
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Histórico de Atualizações -->
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="mb-3">
                                <i class="fas fa-history"></i> Histórico de Atualizações
                            </h5>
                            
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="timeline">
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-primary">
                                                <i class="fas fa-plus"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h6 class="timeline-title">OS Criada</h6>
                                                <p class="timeline-text">Ordem de serviço criada por <?php echo $data['ordem']->usuario_nome; ?></p>
                                                <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($data['ordem']->criado_em)); ?></small>
                                            </div>
                                        </div>
                                        
                                        <?php if ($data['ordem']->atualizado_em): ?>
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-warning">
                                                <i class="fas fa-edit"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h6 class="timeline-title">OS Atualizada</h6>
                                                <p class="timeline-text">Última atualização realizada</p>
                                                <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($data['ordem']->atualizado_em)); ?></small>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Botões de Ação -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="<?php echo URLROOT; ?>/ordem-servico/visualizar/<?php echo $data['ordem']->id; ?>" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary" id="btnSalvar">
                                    <i class="fas fa-save"></i> Salvar Alterações
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Modal de Confirmação -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirmar Alterações</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja salvar as alterações na Ordem de Serviço #<?php echo $data['ordem']->id; ?>?</p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Lembrete:</strong> As alterações não podem ser desfeitas após o salvamento.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnConfirmarSalvar">Confirmar e Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- CSS para Timeline -->
<style>
.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline-item {
    position: relative;
    padding-left: 50px;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 0;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 12px;
}

.timeline-content {
    background: white;
    padding: 15px;
    border-radius: 5px;
    border-left: 3px solid #dee2e6;
}

.timeline-title {
    margin: 0 0 5px 0;
    font-weight: 600;
    color: #495057;
}

.timeline-text {
    margin: 0 0 5px 0;
    color: #6c757d;
}

.timeline-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 15px;
    top: 30px;
    bottom: -20px;
    width: 2px;
    background: #dee2e6;
}

.badge {
    font-size: 0.8em;
}

.bg-aberta { background-color: #17a2b8 !important; }
.bg-em_andamento { background-color: #ffc107 !important; color: #212529 !important; }
.bg-aguardando_pecas { background-color: #fd7e14 !important; }
.bg-aguardando_aprovacao { background-color: #6f42c1 !important; }
.bg-concluida { background-color: #28a745 !important; }
.bg-cancelada { background-color: #dc3545 !important; }

/* Animações */
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
}

.btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

/* Validação visual */
.form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.is-invalid {
    border-color: #dc3545;
}

.is-valid {
    border-color: #28a745;
}
</style>

<!-- JavaScript para Interatividade -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Máscara para valores monetários
    if (typeof $ !== 'undefined' && $.fn.mask) {
        $('.valor').mask('000.000.000.000.000,00', {reverse: true});
    }
    
    // Validação em tempo real
    const statusSelect = document.getElementById('status');
    const valorFinalInput = document.getElementById('valor_final');
    const problemaDiagnosticadoTextarea = document.getElementById('problema_diagnosticado');
    const solucaoAplicadaTextarea = document.getElementById('solucao_aplicada');
    
    // Validação do status
    statusSelect.addEventListener('change', function() {
        validateStatus();
    });
    
    // Validação do valor final
    valorFinalInput.addEventListener('input', function() {
        validateValorFinal();
    });
    
    // Validação do problema diagnosticado
    problemaDiagnosticadoTextarea.addEventListener('input', function() {
        validateProblemaDiagnosticado();
    });
    
    // Validação da solução aplicada
    solucaoAplicadaTextarea.addEventListener('input', function() {
        validateSolucaoAplicada();
    });
    
    // Funções de validação
    function validateStatus() {
        const status = statusSelect.value;
        if (status === 'concluida') {
            // Se status for concluída, validar campos obrigatórios
            if (!problemaDiagnosticadoTextarea.value.trim()) {
                problemaDiagnosticadoTextarea.classList.add('is-invalid');
            } else {
                problemaDiagnosticadoTextarea.classList.remove('is-invalid');
            }
            
            if (!solucaoAplicadaTextarea.value.trim()) {
                solucaoAplicadaTextarea.classList.add('is-invalid');
            } else {
                solucaoAplicadaTextarea.classList.remove('is-invalid');
            }
        } else {
            problemaDiagnosticadoTextarea.classList.remove('is-invalid');
            solucaoAplicadaTextarea.classList.remove('is-invalid');
        }
    }
    
    function validateValorFinal() {
        const valor = valorFinalInput.value.replace(/[^\d,]/g, '').replace(',', '.');
        if (valor && parseFloat(valor) < 0) {
            valorFinalInput.classList.add('is-invalid');
        } else {
            valorFinalInput.classList.remove('is-invalid');
        }
    }
    
    function validateProblemaDiagnosticado() {
        if (statusSelect.value === 'concluida' && !problemaDiagnosticadoTextarea.value.trim()) {
            problemaDiagnosticadoTextarea.classList.add('is-invalid');
        } else {
            problemaDiagnosticadoTextarea.classList.remove('is-invalid');
        }
    }
    
    function validateSolucaoAplicada() {
        if (statusSelect.value === 'concluida' && !solucaoAplicadaTextarea.value.trim()) {
            solucaoAplicadaTextarea.classList.add('is-invalid');
        } else {
            solucaoAplicadaTextarea.classList.remove('is-invalid');
        }
    }
    
    // Formulário com confirmação
    const form = document.getElementById('formEditarOS');
    const btnSalvar = document.getElementById('btnSalvar');
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
    const btnConfirmarSalvar = document.getElementById('btnConfirmarSalvar');
    
    btnSalvar.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Validar antes de mostrar modal
        if (!validateForm()) {
            return;
        }
        
        confirmModal.show();
    });
    
    btnConfirmarSalvar.addEventListener('click', function() {
        confirmModal.hide();
        form.submit();
    });
    
    function validateForm() {
        let isValid = true;
        
        // Validar status
        if (!statusSelect.value) {
            statusSelect.classList.add('is-invalid');
            isValid = false;
        } else {
            statusSelect.classList.remove('is-invalid');
        }
        
        // Validar campos obrigatórios se status for concluída
        if (statusSelect.value === 'concluida') {
            if (!problemaDiagnosticadoTextarea.value.trim()) {
                problemaDiagnosticadoTextarea.classList.add('is-invalid');
                isValid = false;
            }
            
            if (!solucaoAplicadaTextarea.value.trim()) {
                solucaoAplicadaTextarea.classList.add('is-invalid');
                isValid = false;
            }
        }
        
        // Validar valor final se preenchido
        if (valorFinalInput.value.trim()) {
            const valor = valorFinalInput.value.replace(/[^\d,]/g, '').replace(',', '.');
            if (parseFloat(valor) < 0) {
                valorFinalInput.classList.add('is-invalid');
                isValid = false;
            }
        }
        
        if (!isValid) {
            // Scroll para o primeiro erro
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            
            // Mostrar alerta
            showAlert('Por favor, corrija os erros antes de continuar.', 'warning');
        }
        
        return isValid;
    }
    
    // Função para mostrar alertas
    function showAlert(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const container = document.querySelector('.card-body');
        container.insertBefore(alertDiv, container.firstChild);
        
        // Auto-remover após 5 segundos
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
    
    // Auto-save (opcional)
    let autoSaveTimer;
    const formInputs = form.querySelectorAll('input, select, textarea');
    
    formInputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(() => {
                // Aqui você pode implementar auto-save se desejar
                console.log('Auto-save triggered');
            }, 3000);
        });
    });
    
    // Atualizar validação inicial
    validateStatus();
    
    // Calcular e atualizar progresso
    updateProgress();
    
    // Atualizar próxima etapa quando status mudar
    statusSelect.addEventListener('change', function() {
        updateNextStep();
        updateProgress();
    });
    
    // Função para calcular progresso baseado no status
    function updateProgress() {
        const status = statusSelect.value;
        const progressMap = {
            'aberta': 10,
            'em_andamento': 30,
            'aguardando_pecas': 50,
            'aguardando_aprovacao': 70,
            'concluida': 100,
            'cancelada': 0
        };
        
        const progress = progressMap[status] || 0;
        const progressBar = document.getElementById('progressBar');
        const progressPercentage = document.getElementById('progressPercentage');
        
        progressBar.style.width = progress + '%';
        progressBar.setAttribute('aria-valuenow', progress);
        progressPercentage.textContent = progress + '%';
        
        // Atualizar cor da barra de progresso
        progressBar.className = 'progress-bar';
        if (progress >= 100) {
            progressBar.classList.add('bg-success');
        } else if (progress >= 70) {
            progressBar.classList.add('bg-info');
        } else if (progress >= 30) {
            progressBar.classList.add('bg-warning');
        } else {
            progressBar.classList.add('bg-primary');
        }
    }
    
    // Função para atualizar próxima etapa
    function updateNextStep() {
        const status = statusSelect.value;
        const nextStepMap = {
            'aberta': 'Iniciar análise técnica',
            'em_andamento': 'Diagnosticar problema',
            'aguardando_pecas': 'Aguardar chegada das peças',
            'aguardando_aprovacao': 'Aguardar aprovação do cliente',
            'concluida': 'Entregar dispositivo ao cliente',
            'cancelada': 'N/A'
        };
        
        const nextStepText = document.getElementById('nextStepText');
        nextStepText.textContent = nextStepMap[status] || 'Verificar status atual';
    }
    
    // Adicionar tooltips aos campos
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Auto-save com indicador visual
    let autoSaveTimer;
    let isDirty = false;
    
    formInputs.forEach(input => {
        input.addEventListener('input', function() {
            isDirty = true;
            updateDirtyIndicator();
            
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(() => {
                // Aqui você pode implementar auto-save se desejar
                console.log('Auto-save triggered');
                showAlert('Alterações salvas automaticamente', 'success');
                isDirty = false;
                updateDirtyIndicator();
            }, 3000);
        });
    });
    
    // Função para atualizar indicador de alterações não salvas
    function updateDirtyIndicator() {
        const btnSalvar = document.getElementById('btnSalvar');
        if (isDirty) {
            btnSalvar.innerHTML = '<i class="fas fa-save"></i> Salvar Alterações <span class="badge bg-warning ms-1">*</span>';
            btnSalvar.classList.add('btn-warning');
            btnSalvar.classList.remove('btn-primary');
        } else {
            btnSalvar.innerHTML = '<i class="fas fa-save"></i> Salvar Alterações';
            btnSalvar.classList.remove('btn-warning');
            btnSalvar.classList.add('btn-primary');
        }
    }
    
    // Adicionar confirmação antes de sair se houver alterações
    window.addEventListener('beforeunload', function(e) {
        if (isDirty) {
            e.preventDefault();
            e.returnValue = 'Você tem alterações não salvas. Deseja realmente sair?';
        }
    });
    
    // Atualizar indicador inicial
    updateDirtyIndicator();
});
</script>

<?php require APPROOT . '/app/views/inc/footer.php'; ?>
