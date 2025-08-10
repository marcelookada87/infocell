<?php require APPROOT . '/app/views/inc/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-eye"></i> Ordem de Serviço #<?php echo gerarNumeroOS($data['ordem']->id); ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="<?php echo URLROOT; ?>/ordem-servico/editar/<?php echo $data['ordem']->id; ?>" class="btn btn-sm btn-warning">
                <i class="fas fa-edit"></i> Editar
            </a>
            <button onclick="window.print()" class="btn btn-sm btn-info">
                <i class="fas fa-print"></i> Imprimir
            </button>
            <a href="<?php echo URLROOT; ?>/ordem-servico" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
</div>

<?php flash('ordem_message'); ?>

<div class="row">
    <!-- Informações Principais -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-info-circle"></i> Informações da Ordem de Serviço
                </h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Número da OS:</strong>
                    </div>
                    <div class="col-md-9">
                        <span class="h5 text-primary">#<?php echo gerarNumeroOS($data['ordem']->id); ?></span>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Status:</strong>
                    </div>
                    <div class="col-md-9">
                        <?php echo statusBadge($data['ordem']->status); ?>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Prioridade:</strong>
                    </div>
                    <div class="col-md-9">
                        <?php echo prioridadeBadge($data['ordem']->prioridade); ?>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Data de Entrada:</strong>
                    </div>
                    <div class="col-md-9">
                        <?php echo formatarData($data['ordem']->data_entrada); ?>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Criado por:</strong>
                    </div>
                    <div class="col-md-9">
                        <?php echo $data['ordem']->usuario_nome; ?>
                        <small class="text-muted">em <?php echo formatarDataHora($data['ordem']->criado_em); ?></small>
                    </div>
                </div>
                
                <?php if ($data['ordem']->atualizado_em != $data['ordem']->criado_em): ?>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Última atualização:</strong>
                    </div>
                    <div class="col-md-9">
                        <?php echo formatarDataHora($data['ordem']->atualizado_em); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Dados do Dispositivo -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-mobile-alt"></i> Dispositivo
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Tipo:</strong><br>
                            <span class="badge badge-secondary"><?php echo ucfirst($data['ordem']->dispositivo_tipo); ?></span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Marca:</strong><br>
                            <?php echo $data['ordem']->dispositivo_marca ?: '-'; ?>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Modelo:</strong><br>
                            <?php echo $data['ordem']->dispositivo_modelo ?: '-'; ?>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <?php if (!empty($data['ordem']->dispositivo_cor)): ?>
                        <div class="mb-3">
                            <strong>Cor:</strong><br>
                            <?php echo $data['ordem']->dispositivo_cor; ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($data['ordem']->dispositivo_serial_number)): ?>
                        <div class="mb-3">
                            <strong>Serial Number:</strong><br>
                            <code><?php echo $data['ordem']->dispositivo_serial_number; ?></code>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($data['ordem']->dispositivo_imei)): ?>
                        <div class="mb-3">
                            <strong>IMEI:</strong><br>
                            <code><?php echo $data['ordem']->dispositivo_imei; ?></code>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Problema e Diagnóstico -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-stethoscope"></i> Problema e Diagnóstico
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6 class="text-danger">
                        <i class="fas fa-exclamation-triangle"></i> Problema Relatado:
                    </h6>
                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($data['ordem']->problema_relatado)); ?></p>
                </div>
                
                <?php if (!empty($data['ordem']->problema_diagnosticado)): ?>
                <div class="mb-4">
                    <h6 class="text-info">
                        <i class="fas fa-search"></i> Problema Diagnosticado:
                    </h6>
                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($data['ordem']->problema_diagnosticado)); ?></p>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($data['ordem']->solucao_aplicada)): ?>
                <div class="mb-4">
                    <h6 class="text-success">
                        <i class="fas fa-tools"></i> Solução Aplicada:
                    </h6>
                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($data['ordem']->solucao_aplicada)); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Observações -->
        <?php if (!empty($data['ordem']->observacoes) || !empty($data['ordem']->observacoes_tecnico)): ?>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-sticky-note"></i> Observações
                </h6>
            </div>
            <div class="card-body">
                <?php if (!empty($data['ordem']->observacoes)): ?>
                <div class="mb-3">
                    <h6>Observações Iniciais:</h6>
                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($data['ordem']->observacoes)); ?></p>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($data['ordem']->observacoes_tecnico)): ?>
                <div class="mb-3">
                    <h6>Observações do Técnico:</h6>
                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($data['ordem']->observacoes_tecnico)); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Sidebar com informações do cliente e valores -->
    <div class="col-lg-4">
        <!-- Dados do Cliente -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user"></i> Cliente
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <i class="fas fa-user-circle fa-3x text-muted"></i>
                </div>
                
                <div class="mb-3">
                    <strong>Nome:</strong><br>
                    <?php echo $data['cliente']->nome; ?>
                </div>
                
                <div class="mb-3">
                    <strong>Telefone:</strong><br>
                    <a href="tel:<?php echo $data['cliente']->telefone; ?>">
                        <i class="fas fa-phone"></i> <?php echo $data['cliente']->telefone; ?>
                    </a>
                </div>
                
                <?php if (!empty($data['cliente']->email)): ?>
                <div class="mb-3">
                    <strong>Email:</strong><br>
                    <a href="mailto:<?php echo $data['cliente']->email; ?>">
                        <i class="fas fa-envelope"></i> <?php echo $data['cliente']->email; ?>
                    </a>
                </div>
                <?php endif; ?>
                
                <div class="d-grid">
                    <a href="<?php echo URLROOT; ?>/cliente/visualizar/<?php echo $data['cliente']->id; ?>" 
                       class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye"></i> Ver Perfil Completo
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Valores -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-dollar-sign"></i> Valores
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span><strong>Valor Estimado:</strong></span>
                        <span class="text-muted">
                            <?php echo $data['ordem']->valor_estimado > 0 ? formatarValor($data['ordem']->valor_estimado) : '-'; ?>
                        </span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span><strong>Valor Final:</strong></span>
                        <span class="<?php echo $data['ordem']->valor_final > 0 ? 'text-success' : 'text-muted'; ?>">
                            <?php echo $data['ordem']->valor_final > 0 ? formatarValor($data['ordem']->valor_final) : '-'; ?>
                        </span>
                    </div>
                </div>
                
                <?php if ($data['ordem']->valor_pago > 0): ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span><strong>Valor Pago:</strong></span>
                        <span class="text-success">
                            <?php echo formatarValor($data['ordem']->valor_pago); ?>
                        </span>
                    </div>
                </div>
                
                <?php if ($data['ordem']->valor_final > $data['ordem']->valor_pago): ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span><strong>Saldo Devedor:</strong></span>
                        <span class="text-danger">
                            <?php echo formatarValor($data['ordem']->valor_final - $data['ordem']->valor_pago); ?>
                        </span>
                    </div>
                </div>
                <?php endif; ?>
                <?php endif; ?>
                
                <?php if (!empty($data['ordem']->forma_pagamento)): ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span><strong>Forma de Pagamento:</strong></span>
                        <span class="text-info">
                            <?php 
                            $formas = [
                                'dinheiro' => 'Dinheiro',
                                'cartao_debito' => 'Cartão Débito',
                                'cartao_credito' => 'Cartão Crédito',
                                'pix' => 'PIX',
                                'transferencia' => 'Transferência'
                            ];
                            echo $formas[$data['ordem']->forma_pagamento] ?? $data['ordem']->forma_pagamento;
                            ?>
                        </span>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Ações Rápidas -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-bolt"></i> Ações Rápidas
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo URLROOT; ?>/ordem-servico/editar/<?php echo $data['ordem']->id; ?>" 
                       class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Editar OS
                    </a>
                    
                    <?php if ($data['ordem']->status != 'concluida'): ?>
                    <button type="button" class="btn btn-success btn-sm" onclick="alterarStatus('concluida')">
                        <i class="fas fa-check"></i> Marcar como Concluída
                    </button>
                    <?php endif; ?>
                    
                    <button type="button" class="btn btn-info btn-sm" onclick="window.print()">
                        <i class="fas fa-print"></i> Imprimir OS
                    </button>
                    
                    <a href="tel:<?php echo $data['cliente']->telefone; ?>" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-phone"></i> Ligar para Cliente
                    </a>
                    
                    <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin'): ?>
                    <form method="POST" action="<?php echo URLROOT; ?>/ordem-servico/deletar/<?php echo $data['ordem']->id; ?>" class="d-inline">
                        <button type="submit" class="btn btn-outline-danger btn-sm btn-delete w-100">
                            <i class="fas fa-trash"></i> Excluir OS
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para alterar status -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alterar Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo URLROOT; ?>/ordem-servico/editar/<?php echo $data['ordem']->id; ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Novo Status:</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="aberta" <?php echo ($data['ordem']->status == 'aberta') ? 'selected' : ''; ?>>Aberta</option>
                            <option value="em_andamento" <?php echo ($data['ordem']->status == 'em_andamento') ? 'selected' : ''; ?>>Em Andamento</option>
                            <option value="aguardando_peca" <?php echo ($data['ordem']->status == 'aguardando_peca') ? 'selected' : ''; ?>>Aguardando Peça</option>
                            <option value="aguardando_cliente" <?php echo ($data['ordem']->status == 'aguardando_cliente') ? 'selected' : ''; ?>>Aguardando Cliente</option>
                            <option value="concluida" <?php echo ($data['ordem']->status == 'concluida') ? 'selected' : ''; ?>>Concluída</option>
                            <option value="cancelada" <?php echo ($data['ordem']->status == 'cancelada') ? 'selected' : ''; ?>>Cancelada</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observacoes_tecnico" class="form-label">Observações:</label>
                        <textarea name="observacoes_tecnico" id="observacoes_tecnico" class="form-control" rows="3" 
                                  placeholder="Adicione observações sobre a alteração..."><?php echo $data['ordem']->observacoes_tecnico; ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alteração</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require APPROOT . '/app/views/inc/footer.php'; ?>

<script>
function alterarStatus(novoStatus) {
    $('#status').val(novoStatus);
    var modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
}

// Estilos de impressão
window.addEventListener('beforeprint', function() {
    document.body.classList.add('printing');
});

window.addEventListener('afterprint', function() {
    document.body.classList.remove('printing');
});
</script>

<style>
@media print {
    .btn, .btn-group, .btn-toolbar,
    .navbar, .sidebar,
    .card-header .btn {
        display: none !important;
    }
    
    .main-content {
        margin-left: 0 !important;
        padding-top: 0 !important;
    }
    
    .card {
        border: 1px solid #dee2e6 !important;
        box-shadow: none !important;
        page-break-inside: avoid;
    }
    
    .card-body {
        padding: 1rem !important;
    }
    
    h1, h2, h3, h4, h5, h6 {
        color: #000 !important;
    }
    
    .badge {
        border: 1px solid #000 !important;
        color: #000 !important;
        background: transparent !important;
    }
}
</style>

