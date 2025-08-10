<?php require APPROOT . '/app/views/inc/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="<?php echo URLROOT; ?>/ordem-servico/criar" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Nova OS
            </a>
            <a href="<?php echo URLROOT; ?>/cliente/criar" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-user-plus"></i> Novo Cliente
            </a>
        </div>
    </div>
</div>

<?php flash('ordem_message'); ?>

<!-- Cards de estatísticas -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total de Ordens
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $data['total_ordens']; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Ordens Abertas
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $data['ordens_abertas']; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Em Andamento
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $data['ordens_andamento']; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tools fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Receita Mensal
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo formatarValor($data['receita_mensal']); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Segunda linha de cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Concluídas
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $data['ordens_concluidas']; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-secondary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                            Total Clientes
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $data['total_clientes']; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-6 col-md-12 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-clock"></i> Ações Rápidas
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 mb-2">
                        <a href="<?php echo URLROOT; ?>/ordem-servico/criar" class="btn btn-primary btn-block">
                            <i class="fas fa-plus"></i> Nova OS
                        </a>
                    </div>
                    <div class="col-sm-6 mb-2">
                        <a href="<?php echo URLROOT; ?>/cliente/criar" class="btn btn-success btn-block">
                            <i class="fas fa-user-plus"></i> Novo Cliente
                        </a>
                    </div>
                    <div class="col-sm-6 mb-2">
                        <a href="<?php echo URLROOT; ?>/ordem-servico" class="btn btn-info btn-block">
                            <i class="fas fa-list"></i> Ver Todas OS
                        </a>
                    </div>
                    <div class="col-sm-6 mb-2">
                        <a href="<?php echo URLROOT; ?>/relatorio" class="btn btn-warning btn-block">
                            <i class="fas fa-chart-bar"></i> Relatórios
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabelas de dados recentes -->
<div class="row">
    <!-- Ordens Recentes -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-clipboard-list"></i> Ordens Recentes
                </h6>
                <a href="<?php echo URLROOT; ?>/ordem-servico" class="btn btn-sm btn-primary">
                    Ver Todas <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="card-body">
                <?php if (!empty($data['ordens_recentes'])): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>OS</th>
                                <th>Cliente</th>
                                <th>Dispositivo</th>
                                <th>Status</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['ordens_recentes'] as $ordem): ?>
                            <tr>
                                <td>
                                    <strong>#<?php echo gerarNumeroOS($ordem->id); ?></strong>
                                </td>
                                <td><?php echo $ordem->cliente_nome; ?></td>
                                <td>
                                    <small>
                                        <?php echo ucfirst($ordem->dispositivo_tipo); ?><br>
                                        <span class="text-muted"><?php echo $ordem->dispositivo_marca; ?></span>
                                    </small>
                                </td>
                                <td><?php echo statusBadge($ordem->status); ?></td>
                                <td><?php echo formatarData($ordem->criado_em); ?></td>
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
                    <p class="text-muted">Nenhuma ordem de serviço encontrada.</p>
                    <a href="<?php echo URLROOT; ?>/ordem-servico/criar" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Criar primeira OS
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Dispositivos Mais Reparados -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-mobile-alt"></i> Dispositivos Mais Reparados
                </h6>
            </div>
            <div class="card-body">
                <?php if (!empty($data['dispositivos_mais_reparados'])): ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($data['dispositivos_mais_reparados'] as $dispositivo): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div>
                            <strong><?php echo ucfirst($dispositivo->dispositivo_tipo); ?></strong><br>
                            <small class="text-muted"><?php echo $dispositivo->dispositivo_marca; ?></small>
                        </div>
                        <span class="badge badge-primary badge-pill"><?php echo $dispositivo->total; ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-4">
                    <i class="fas fa-mobile-alt fa-2x text-muted mb-2"></i>
                    <p class="text-muted small">Nenhum dado disponível</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/app/views/inc/footer.php'; ?>

