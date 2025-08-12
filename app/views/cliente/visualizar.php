<?php require APPROOT . '/app/views/inc/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user"></i> Perfil do Cliente
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
                            <a href="<?php echo URLROOT; ?>/cliente/editar/<?php echo $data['cliente']->id; ?>" class="btn btn-sm btn-warning">
                <i class="fas fa-edit"></i> Editar
            </a>
                            <a href="<?php echo URLROOT; ?>/ordem-servico/criar?cliente_id=<?php echo $data['cliente']->id; ?>" class="btn btn-sm btn-success">
                <i class="fas fa-plus"></i> Nova OS
            </a>
            <a href="<?php echo URLROOT; ?>/cliente" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Informações do Cliente -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user-circle"></i> Informações Pessoais
                </h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Nome Completo:</strong>
                    </div>
                    <div class="col-md-9">
                        <span class="h5"><?php echo htmlspecialchars($data['cliente']->nome); ?></span>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>CPF:</strong>
                    </div>
                    <div class="col-md-9">
                        <?php echo !empty($data['cliente']->cpf) ? $data['cliente']->cpf : '<span class="text-muted">Não informado</span>'; ?>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Telefone:</strong>
                    </div>
                    <div class="col-md-9">
                        <a href="tel:<?php echo $data['cliente']->telefone; ?>" class="text-decoration-none">
                            <i class="fas fa-phone text-primary"></i> <?php echo $data['cliente']->telefone; ?>
                        </a>
                    </div>
                </div>
                
                <?php if (!empty($data['cliente']->email)): ?>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Email:</strong>
                    </div>
                    <div class="col-md-9">
                        <a href="mailto:<?php echo $data['cliente']->email; ?>" class="text-decoration-none">
                            <i class="fas fa-envelope text-primary"></i> <?php echo $data['cliente']->email; ?>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Data de Cadastro:</strong>
                    </div>
                    <div class="col-md-9">
                        <?php echo formatarDataHora($data['cliente']->criado_em); ?>
                    </div>
                </div>
                
                <?php if ($data['cliente']->atualizado_em != $data['cliente']->criado_em): ?>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Última Atualização:</strong>
                    </div>
                    <div class="col-md-9">
                        <?php echo formatarDataHora($data['cliente']->atualizado_em); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Endereço -->
        <?php if (!empty($data['cliente']->endereco) || !empty($data['cliente']->cidade) || !empty($data['cliente']->cep)): ?>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-map-marker-alt"></i> Endereço
                </h6>
            </div>
            <div class="card-body">
                                        <?php if (!empty($data['cliente']->endereco)): ?>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <strong>Endereço:</strong>
                            </div>
                            <div class="col-md-9">
                                <?php echo htmlspecialchars($data['cliente']->endereco); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                
                                        <?php if (!empty($data['cliente']->cidade)): ?>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <strong>Cidade:</strong>
                            </div>
                            <div class="col-md-9">
                                <?php echo htmlspecialchars($data['cliente']->cidade); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                
                                        <?php if (!empty($data['cliente']->cep)): ?>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <strong>CEP:</strong>
                            </div>
                            <div class="col-md-9">
                                <?php echo $data['cliente']->cep; ?>
                            </div>
                        </div>
                        <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Histórico de Ordens de Serviço -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-clipboard-list"></i> Histórico de Ordens de Serviço
                </h6>
                                    <a href="<?php echo URLROOT; ?>/ordem-servico/criar?cliente_id=<?php echo $data['cliente']->id; ?>" class="btn btn-sm btn-success">
                    <i class="fas fa-plus"></i> Nova OS
                </a>
            </div>
            <div class="card-body">
                <?php if (!empty($data['ordens_servico'])): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>OS</th>
                                <th>Dispositivo</th>
                                <th>Problema</th>
                                <th>Status</th>
                                <th>Valor</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['ordens_servico'] as $ordem): ?>
                            <tr>
                                <td>
                                    <strong>#<?php echo gerarNumeroOS($ordem->id); ?></strong>
                                </td>
                                <td>
                                    <?php echo ucfirst($ordem->dispositivo_tipo); ?><br>
                                    <small class="text-muted">
                                        <?php echo $ordem->dispositivo_marca; ?> 
                                        <?php echo $ordem->dispositivo_modelo; ?>
                                    </small>
                                </td>
                                <td>
                                    <span title="<?php echo $ordem->problema_relatado; ?>">
                                        <?php echo truncarTexto($ordem->problema_relatado, 30); ?>
                                    </span>
                                </td>
                                <td><?php echo statusBadge($ordem->status); ?></td>
                                <td>
                                    <?php if ($ordem->valor_final > 0): ?>
                                        <?php echo formatarValor($ordem->valor_final); ?>
                                    <?php elseif ($ordem->valor_estimado > 0): ?>
                                        <small class="text-muted">Est: <?php echo formatarValor($ordem->valor_estimado); ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small><?php echo formatarData($ordem->criado_em); ?></small>
                                </td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/ordem-servico/visualizar/<?php echo $ordem->id; ?>" 
                                       class="btn btn-sm btn-outline-primary" title="Ver detalhes">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-4">
                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Nenhuma ordem de serviço encontrada para este cliente.</p>
                    <a href="<?php echo URLROOT; ?>/ordem-servico/criar?cliente_id=<?php echo $data['cliente']->id; ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Criar primeira OS
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Sidebar com ações e estatísticas -->
    <div class="col-lg-4">
        <!-- Avatar e Ações Rápidas -->
        <div class="card shadow mb-4">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-user-circle fa-5x text-muted"></i>
                </div>
                                        <h5 class="card-title"><?php echo htmlspecialchars($data['cliente']->nome); ?></h5>
                        <p class="card-text text-muted">Cliente desde <?php echo formatarData($data['cliente']->criado_em); ?></p>
                
                <div class="d-grid gap-2">
                    <a href="<?php echo URLROOT; ?>/cliente/editar/<?php echo $data['cliente']->id; ?>" 
                       class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar Cliente
                    </a>
                    
                    <a href="<?php echo URLROOT; ?>/ordem-servico/criar?cliente_id=<?php echo $data['cliente']->id; ?>" 
                       class="btn btn-success">
                        <i class="fas fa-plus"></i> Nova OS
                    </a>
                    
                                            <a href="tel:<?php echo $data['cliente']->telefone; ?>" class="btn btn-outline-primary">
                        <i class="fas fa-phone"></i> Ligar
                    </a>
                    
                    <?php if (!empty($data['cliente']->email)): ?>
                    <a href="mailto:<?php echo $data['cliente']->email; ?>" class="btn btn-outline-info">
                        <i class="fas fa-envelope"></i> Enviar Email
                    </a>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin'): ?>
                    <form method="POST" action="<?php echo URLROOT; ?>/cliente/deletar/<?php echo $data['cliente']->id; ?>" class="d-inline">
                        <button type="submit" class="btn btn-outline-danger btn-delete w-100">
                            <i class="fas fa-trash"></i> Excluir Cliente
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Estatísticas do Cliente -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-pie"></i> Estatísticas
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-primary"><?php echo count($data['ordens_servico'] ?? []); ?></h4>
                            <small class="text-muted">Total OS</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success">
                            <?php 
                            $concluidas = 0;
                            if (!empty($data['ordens_servico'])) {
                                foreach ($data['ordens_servico'] as $ordem) {
                                    if ($ordem->status == 'concluida') $concluidas++;
                                }
                            }
                            echo $concluidas;
                            ?>
                        </h4>
                        <small class="text-muted">Concluídas</small>
                    </div>
                </div>
                
                <hr>
                
                <div class="text-center">
                    <h5 class="text-info">
                        <?php 
                        $valorTotal = 0;
                        if (!empty($data['ordens_servico'])) {
                            foreach ($data['ordens_servico'] as $ordem) {
                                if ($ordem->valor_final > 0) {
                                    $valorTotal += $ordem->valor_final;
                                }
                            }
                        }
                        echo formatarValor($valorTotal);
                        ?>
                    </h5>
                    <small class="text-muted">Valor Total em OS</small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/app/views/inc/footer.php'; ?>
