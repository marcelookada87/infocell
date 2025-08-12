<?php require APPROOT . '/app/views/inc/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-clipboard-list"></i> Relatório de Ordens de Serviço
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#filtros">
                <i class="fas fa-filter"></i> Filtros
            </button>
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-download"></i> Exportar
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/relatorio/exportarPdf/ordens"><i class="fas fa-file-pdf me-2"></i>PDF</a></li>
                    <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/relatorio/exportarExcel/ordens"><i class="fas fa-file-excel me-2"></i>Excel</a></li>
                </ul>
            </div>
            <a href="<?php echo URLROOT; ?>/relatorio" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="collapse mb-4" id="filtros">
    <div class="card">
        <div class="card-body">
            <form method="GET" action="<?php echo URLROOT; ?>/relatorio/ordens">
                <div class="row">
                    <div class="col-md-2">
                        <label for="data_inicio" class="form-label">Data Início</label>
                        <input type="date" name="data_inicio" id="data_inicio" class="form-control" value="<?php echo $data['filtros']['data_inicio'] ?? ''; ?>">
                    </div>
                    <div class="col-md-2">
                        <label for="data_fim" class="form-label">Data Fim</label>
                        <input type="date" name="data_fim" id="data_fim" class="form-control" value="<?php echo $data['filtros']['data_fim'] ?? ''; ?>">
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Todos</option>
                            <option value="aberta" <?php echo (isset($data['filtros']['status']) && $data['filtros']['status'] == 'aberta') ? 'selected' : ''; ?>>Aberta</option>
                            <option value="em_andamento" <?php echo (isset($data['filtros']['status']) && $data['filtros']['status'] == 'em_andamento') ? 'selected' : ''; ?>>Em Andamento</option>
                            <option value="aguardando_peca" <?php echo (isset($data['filtros']['status']) && $data['filtros']['status'] == 'aguardando_peca') ? 'selected' : ''; ?>>Aguardando Peça</option>
                            <option value="aguardando_cliente" <?php echo (isset($data['filtros']['status']) && $data['filtros']['status'] == 'aguardando_cliente') ? 'selected' : ''; ?>>Aguardando Cliente</option>
                            <option value="concluida" <?php echo (isset($data['filtros']['status']) && $data['filtros']['status'] == 'concluida') ? 'selected' : ''; ?>>Concluída</option>
                            <option value="cancelada" <?php echo (isset($data['filtros']['status']) && $data['filtros']['status'] == 'cancelada') ? 'selected' : ''; ?>>Cancelada</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="dispositivo_tipo" class="form-label">Tipo de Dispositivo</label>
                        <select name="dispositivo_tipo" id="dispositivo_tipo" class="form-select">
                            <option value="">Todos</option>
                            <option value="celular" <?php echo (isset($data['filtros']['dispositivo_tipo']) && $data['filtros']['dispositivo_tipo'] == 'celular') ? 'selected' : ''; ?>>Celular</option>
                            <option value="tablet" <?php echo (isset($data['filtros']['dispositivo_tipo']) && $data['filtros']['dispositivo_tipo'] == 'tablet') ? 'selected' : ''; ?>>Tablet</option>
                            <option value="notebook" <?php echo (isset($data['filtros']['dispositivo_tipo']) && $data['filtros']['dispositivo_tipo'] == 'notebook') ? 'selected' : ''; ?>>Notebook</option>
                            <option value="desktop" <?php echo (isset($data['filtros']['dispositivo_tipo']) && $data['filtros']['dispositivo_tipo'] == 'desktop') ? 'selected' : ''; ?>>Desktop</option>
                            <option value="tv" <?php echo (isset($data['filtros']['dispositivo_tipo']) && $data['filtros']['dispositivo_tipo'] == 'tv') ? 'selected' : ''; ?>>TV</option>
                            <option value="console" <?php echo (isset($data['filtros']['dispositivo_tipo']) && $data['filtros']['dispositivo_tipo'] == 'console') ? 'selected' : ''; ?>>Console</option>
                            <option value="outros" <?php echo (isset($data['filtros']['dispositivo_tipo']) && $data['filtros']['dispositivo_tipo'] == 'outros') ? 'selected' : ''; ?>>Outros</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="prioridade" class="form-label">Prioridade</label>
                        <select name="prioridade" id="prioridade" class="form-select">
                            <option value="">Todas</option>
                            <option value="baixa" <?php echo (isset($data['filtros']['prioridade']) && $data['filtros']['prioridade'] == 'baixa') ? 'selected' : ''; ?>>Baixa</option>
                            <option value="media" <?php echo (isset($data['filtros']['prioridade']) && $data['filtros']['prioridade'] == 'media') ? 'selected' : ''; ?>>Média</option>
                            <option value="alta" <?php echo (isset($data['filtros']['prioridade']) && $data['filtros']['prioridade'] == 'alta') ? 'selected' : ''; ?>>Alta</option>
                            <option value="urgente" <?php echo (isset($data['filtros']['prioridade']) && $data['filtros']['prioridade'] == 'urgente') ? 'selected' : ''; ?>>Urgente</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                        <a href="<?php echo URLROOT; ?>/relatorio/ordens" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Resumo dos Filtros Aplicados -->
<?php if (!empty($data['filtros'])): ?>
<div class="alert alert-info mb-4">
    <h6 class="alert-heading">
        <i class="fas fa-info-circle"></i> Filtros Aplicados
    </h6>
    <div class="row">
        <?php if (isset($data['filtros']['data_inicio']) && !empty($data['filtros']['data_inicio'])): ?>
        <div class="col-md-2">
            <strong>Data Início:</strong> <?php echo date('d/m/Y', strtotime($data['filtros']['data_inicio'])); ?>
        </div>
        <?php endif; ?>
        <?php if (isset($data['filtros']['data_fim']) && !empty($data['filtros']['data_fim'])): ?>
        <div class="col-md-2">
            <strong>Data Fim:</strong> <?php echo date('d/m/Y', strtotime($data['filtros']['data_fim'])); ?>
        </div>
        <?php endif; ?>
        <?php if (isset($data['filtros']['status']) && !empty($data['filtros']['status'])): ?>
        <div class="col-md-2">
            <strong>Status:</strong> <?php echo ucfirst(str_replace('_', ' ', $data['filtros']['status'])); ?>
        </div>
        <?php endif; ?>
        <?php if (isset($data['filtros']['dispositivo_tipo']) && !empty($data['filtros']['dispositivo_tipo'])): ?>
        <div class="col-md-2">
            <strong>Dispositivo:</strong> <?php echo ucfirst($data['filtros']['dispositivo_tipo']); ?>
        </div>
        <?php endif; ?>
        <?php if (isset($data['filtros']['prioridade']) && !empty($data['filtros']['prioridade'])): ?>
        <div class="col-md-2">
            <strong>Prioridade:</strong> <?php echo ucfirst($data['filtros']['prioridade']); ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- Estatísticas do Relatório -->
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
                            <?php echo count($data['ordens']); ?>
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
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Valor Total
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            R$ <?php 
                                $valorTotal = array_sum(array_column($data['ordens'], 'valor'));
                                echo number_format($valorTotal, 2, ',', '.'); 
                            ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
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
                            Ticket Médio
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            R$ <?php 
                                $totalOrdens = count($data['ordens']);
                                $ticketMedio = $totalOrdens > 0 ? $valorTotal / $totalOrdens : 0;
                                echo number_format($ticketMedio, 2, ',', '.'); 
                            ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
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
                            Em Andamento
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php 
                                $emAndamento = array_filter($data['ordens'], function($ordem) {
                                    return $ordem->status == 'em_andamento';
                                });
                                echo count($emAndamento);
                            ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tools fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabela de Ordens -->
<div class="card shadow">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-list"></i> Ordens de Serviço
        </h6>
        <div class="dropdown no-arrow">
            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown">
                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                <a class="dropdown-item" href="#" onclick="exportarTabela()">
                    <i class="fas fa-download me-2"></i>Exportar Tabela
                </a>
                <a class="dropdown-item" href="#" onclick="imprimirTabela()">
                    <i class="fas fa-print me-2"></i>Imprimir
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <?php if (!empty($data['ordens'])): ?>
        <div class="table-responsive">
            <table class="table table-hover" id="ordensTable">
                <thead>
                    <tr>
                        <th>OS</th>
                        <th>Cliente</th>
                        <th>Dispositivo</th>
                        <th>Problema</th>
                        <th>Status</th>
                        <th>Prioridade</th>
                        <th>Valor</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['ordens'] as $ordem): ?>
                    <tr>
                        <td>
                            <strong>#<?php echo $ordem->id; ?></strong>
                        </td>
                        <td>
                            <div>
                                <strong><?php echo $ordem->nome_cliente; ?></strong>
                                <br>
                                <small class="text-muted"><?php echo $ordem->telefone_cliente; ?></small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong><?php echo ucfirst($ordem->tipo_dispositivo); ?></strong>
                                <br>
                                <small class="text-muted"><?php echo $ordem->marca_dispositivo; ?> <?php echo $ordem->modelo_dispositivo; ?></small>
                            </div>
                        </td>
                        <td>
                            <div class="text-truncate" style="max-width: 200px;" title="<?php echo htmlspecialchars($ordem->problema); ?>">
                                <?php echo htmlspecialchars($ordem->problema); ?>
                            </div>
                        </td>
                        <td>
                            <?php
                            $statusClass = '';
                            $statusText = '';
                            switch ($ordem->status) {
                                case 'aberta':
                                    $statusClass = 'badge bg-primary';
                                    $statusText = 'Aberta';
                                    break;
                                case 'em_andamento':
                                    $statusClass = 'badge bg-info';
                                    $statusText = 'Em Andamento';
                                    break;
                                case 'aguardando_peca':
                                    $statusClass = 'badge bg-warning';
                                    $statusText = 'Aguardando Peça';
                                    break;
                                case 'aguardando_cliente':
                                    $statusClass = 'badge bg-warning';
                                    $statusText = 'Aguardando Cliente';
                                    break;
                                case 'concluida':
                                    $statusClass = 'badge bg-success';
                                    $statusText = 'Concluída';
                                    break;
                                case 'cancelada':
                                    $statusClass = 'badge bg-danger';
                                    $statusText = 'Cancelada';
                                    break;
                                default:
                                    $statusClass = 'badge bg-secondary';
                                    $statusText = ucfirst($ordem->status);
                            }
                            ?>
                            <span class="<?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                        </td>
                        <td>
                            <?php
                            $prioridadeClass = '';
                            switch ($ordem->prioridade) {
                                case 'baixa':
                                    $prioridadeClass = 'badge bg-success';
                                    break;
                                case 'media':
                                    $prioridadeClass = 'badge bg-warning';
                                    break;
                                case 'alta':
                                    $prioridadeClass = 'badge bg-danger';
                                    break;
                                case 'urgente':
                                    $prioridadeClass = 'badge bg-danger';
                                    break;
                                default:
                                    $prioridadeClass = 'badge bg-secondary';
                            }
                            ?>
                            <span class="<?php echo $prioridadeClass; ?>"><?php echo ucfirst($ordem->prioridade); ?></span>
                        </td>
                        <td>
                            <strong>R$ <?php echo number_format($ordem->valor, 2, ',', '.'); ?></strong>
                        </td>
                        <td>
                            <div>
                                <strong><?php echo date('d/m/Y', strtotime($ordem->data_criacao)); ?></strong>
                                <br>
                                <small class="text-muted"><?php echo date('H:i', strtotime($ordem->data_criacao)); ?></small>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="<?php echo URLROOT; ?>/ordem-servico/visualizar/<?php echo $ordem->id; ?>" 
                                   class="btn btn-sm btn-outline-primary" title="Visualizar">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo URLROOT; ?>/ordem-servico/editar/<?php echo $ordem->id; ?>" 
                                   class="btn btn-sm btn-outline-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Nenhuma ordem encontrada</h5>
            <p class="text-muted">Tente ajustar os filtros ou criar uma nova ordem de serviço.</p>
            <a href="<?php echo URLROOT; ?>/ordem-servico/criar" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nova OS
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Função para exportar tabela
function exportarTabela() {
    // Implementar exportação da tabela
    alert('Funcionalidade de exportação será implementada em breve.');
}

// Função para imprimir tabela
function imprimirTabela() {
    window.print();
}

// Inicializar DataTable se houver dados
<?php if (!empty($data['ordens'])): ?>
$(document).ready(function() {
    $('#ordensTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json'
        },
        pageLength: 25,
        order: [[0, 'desc']],
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
});
<?php endif; ?>
</script>

<?php require APPROOT . '/app/views/inc/footer.php'; ?>
