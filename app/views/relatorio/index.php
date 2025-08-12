<?php require APPROOT . '/app/views/inc/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chart-bar"></i> Relatórios
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
                    <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/relatorio/exportarPdf/dashboard"><i class="fas fa-file-pdf me-2"></i>PDF</a></li>
                    <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/relatorio/exportarExcel/dashboard"><i class="fas fa-file-excel me-2"></i>Excel</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="collapse mb-4" id="filtros">
    <div class="card">
        <div class="card-body">
            <form method="GET" action="<?php echo URLROOT; ?>/relatorio">
                <div class="row">
                    <div class="col-md-3">
                        <label for="ano" class="form-label">Ano</label>
                        <select name="ano" id="ano" class="form-select">
                            <?php for ($i = date('Y'); $i >= date('Y') - 5; $i--): ?>
                                <option value="<?php echo $i; ?>" <?php echo (isset($_GET['ano']) && $_GET['ano'] == $i) ? 'selected' : ''; ?>>
                                    <?php echo $i; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="mes" class="form-label">Mês</label>
                        <select name="mes" id="mes" class="form-select">
                            <option value="">Todos os meses</option>
                            <option value="01" <?php echo (isset($_GET['mes']) && $_GET['mes'] == '01') ? 'selected' : ''; ?>>Janeiro</option>
                            <option value="02" <?php echo (isset($_GET['mes']) && $_GET['mes'] == '02') ? 'selected' : ''; ?>>Fevereiro</option>
                            <option value="03" <?php echo (isset($_GET['mes']) && $_GET['mes'] == '03') ? 'selected' : ''; ?>>Março</option>
                            <option value="04" <?php echo (isset($_GET['mes']) && $_GET['mes'] == '04') ? 'selected' : ''; ?>>Abril</option>
                            <option value="05" <?php echo (isset($_GET['mes']) && $_GET['mes'] == '05') ? 'selected' : ''; ?>>Maio</option>
                            <option value="06" <?php echo (isset($_GET['mes']) && $_GET['mes'] == '06') ? 'selected' : ''; ?>>Junho</option>
                            <option value="07" <?php echo (isset($_GET['mes']) && $_GET['mes'] == '07') ? 'selected' : ''; ?>>Julho</option>
                            <option value="08" <?php echo (isset($_GET['mes']) && $_GET['mes'] == '08') ? 'selected' : ''; ?>>Agosto</option>
                            <option value="09" <?php echo (isset($_GET['mes']) && $_GET['mes'] == '09') ? 'selected' : ''; ?>>Setembro</option>
                            <option value="10" <?php echo (isset($_GET['mes']) && $_GET['mes'] == '10') ? 'selected' : ''; ?>>Outubro</option>
                            <option value="11" <?php echo (isset($_GET['mes']) && $_GET['mes'] == '11') ? 'selected' : ''; ?>>Novembro</option>
                            <option value="12" <?php echo (isset($_GET['mes']) && $_GET['mes'] == '12') ? 'selected' : ''; ?>>Dezembro</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="tipo_relatorio" class="form-label">Tipo de Relatório</label>
                        <select name="tipo_relatorio" id="tipo_relatorio" class="form-select">
                            <option value="">Todos</option>
                            <option value="dashboard" <?php echo (isset($_GET['tipo_relatorio']) && $_GET['tipo_relatorio'] == 'dashboard') ? 'selected' : ''; ?>>Dashboard</option>
                            <option value="ordens" <?php echo (isset($_GET['tipo_relatorio']) && $_GET['tipo_relatorio'] == 'ordens') ? 'selected' : ''; ?>>Ordens de Serviço</option>
                            <option value="financeiro" <?php echo (isset($_GET['tipo_relatorio']) && $_GET['tipo_relatorio'] == 'financeiro') ? 'selected' : ''; ?>>Financeiro</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                        <a href="<?php echo URLROOT; ?>/relatorio" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cards de Estatísticas -->
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
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Receita Total
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            R$ <?php 
                                $receitaTotal = 0;
                                if (isset($data['receita_por_mes']['total'])) {
                                    $receitaTotal = $data['receita_por_mes']['total'];
                                } else {
                                    // Fallback: calcular a partir dos dados
                                    foreach ($data['receita_por_mes'] as $receita) {
                                        $receitaTotal += $receita->valor ?? 0;
                                    }
                                }
                                echo number_format($receitaTotal, 2, ',', '.'); 
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
                                $totalOrdens = $data['total_ordens'];
                                $ticketMedio = $totalOrdens > 0 ? $receitaTotal / $totalOrdens : 0;
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
                            Clientes Ativos
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo count($data['clientes_mais_ativos']); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gráficos e Tabelas -->
<div class="row">
    <!-- Gráfico de Ordens por Status -->
    <div class="col-xl-6 col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-pie"></i> Ordens por Status
                </h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                        <a class="dropdown-item" href="<?php echo URLROOT; ?>/relatorio/ordens">Ver Detalhes</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="ordensPorStatusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Gráfico de Receita por Mês -->
    <div class="col-xl-6 col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-line"></i> Receita por Mês
                </h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                        <a class="dropdown-item" href="<?php echo URLROOT; ?>/relatorio/financeiro">Ver Detalhes</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="receitaPorMesChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Tabelas de Dados -->
<div class="row">
    <!-- Dispositivos Mais Reparados -->
    <div class="col-xl-6 col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-mobile-alt"></i> Dispositivos Mais Reparados
                </h6>
            </div>
            <div class="card-body">
                <?php if (!empty($data['dispositivos_mais_reparados'])): ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Dispositivo</th>
                                <th>Quantidade</th>
                                <th>Percentual</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($data['dispositivos_mais_reparados'], 0, 5) as $dispositivo): ?>
                            <tr>
                                <td><?php echo $dispositivo->tipo; ?></td>
                                <td><?php echo $dispositivo->quantidade; ?></td>
                                <td><?php echo number_format($dispositivo->percentual, 1); ?>%</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p class="text-muted text-center">Nenhum dado disponível</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Clientes Mais Ativos -->
    <div class="col-xl-6 col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-users"></i> Clientes Mais Ativos
                </h6>
            </div>
            <div class="card-body">
                <?php if (!empty($data['clientes_mais_ativos'])): ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>OS</th>
                                <th>Valor Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($data['clientes_mais_ativos'], 0, 5) as $cliente): ?>
                            <tr>
                                <td><?php echo $cliente->nome; ?></td>
                                <td><?php echo $cliente->total_ordens; ?></td>
                                <td>R$ <?php echo number_format($cliente->valor_total, 2, ',', '.'); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p class="text-muted text-center">Nenhum dado disponível</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Links para Relatórios Detalhados -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-link"></i> Relatórios Detalhados
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card border-left-primary h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-clipboard-list fa-3x text-primary mb-3"></i>
                                <h5 class="card-title">Relatório de OS</h5>
                                <p class="card-text">Análise detalhada das ordens de serviço com filtros avançados.</p>
                                <a href="<?php echo URLROOT; ?>/relatorio/ordens" class="btn btn-primary">
                                    <i class="fas fa-eye"></i> Visualizar
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-left-success h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-dollar-sign fa-3x text-success mb-3"></i>
                                <h5 class="card-title">Relatório Financeiro</h5>
                                <p class="card-text">Análise financeira com receitas, despesas e indicadores.</p>
                                <a href="<?php echo URLROOT; ?>/relatorio/financeiro" class="btn btn-success">
                                    <i class="fas fa-chart-line"></i> Visualizar
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-left-info h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-download fa-3x text-info mb-3"></i>
                                <h5 class="card-title">Exportar Dados</h5>
                                <p class="card-text">Exporte relatórios em PDF, Excel ou outros formatos.</p>
                                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#exportModal">
                                    <i class="fas fa-download"></i> Exportar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Exportação -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-download"></i> Exportar Relatório
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="exportForm">
                    <div class="mb-3">
                        <label for="exportTipo" class="form-label">Tipo de Relatório</label>
                        <select class="form-select" id="exportTipo" required>
                            <option value="">Selecione...</option>
                            <option value="dashboard">Dashboard Geral</option>
                            <option value="ordens">Ordens de Serviço</option>
                            <option value="financeiro">Financeiro</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="exportFormato" class="form-label">Formato</label>
                        <select class="form-select" id="exportFormato" required>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="exportPeriodo" class="form-label">Período</label>
                        <select class="form-select" id="exportPeriodo" required>
                            <option value="mes_atual">Mês Atual</option>
                            <option value="trimestre">Último Trimestre</option>
                            <option value="ano_atual">Ano Atual</option>
                            <option value="personalizado">Personalizado</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="exportarRelatorio()">
                    <i class="fas fa-download"></i> Exportar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Dados para os gráficos
const ordensPorStatusData = <?php echo json_encode($data['ordens_por_status'] ?? []); ?>;
const receitaPorMesData = <?php 
    if (isset($data['receita_por_mes']['dados'])) {
        echo json_encode($data['receita_por_mes']['dados']);
    } else {
        echo json_encode($data['receita_por_mes'] ?? []);
    }
?>;

// Gráfico de Ordens por Status
const ctx1 = document.getElementById('ordensPorStatusChart').getContext('2d');
new Chart(ctx1, {
    type: 'doughnut',
    data: {
        labels: ordensPorStatusData.map(item => item.status),
        datasets: [{
            data: ordensPorStatusData.map(item => item.quantidade),
            backgroundColor: [
                '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Gráfico de Receita por Mês
const ctx2 = document.getElementById('receitaPorMesChart').getContext('2d');
new Chart(ctx2, {
    type: 'line',
    data: {
        labels: receitaPorMesData.map(item => item.mes),
        datasets: [{
            label: 'Receita (R$)',
            data: receitaPorMesData.map(item => item.valor),
            borderColor: '#1cc88a',
            backgroundColor: 'rgba(28, 200, 138, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'R$ ' + value.toLocaleString('pt-BR');
                    }
                }
            }
        }
    }
});

// Função para exportar relatório
function exportarRelatorio() {
    const tipo = document.getElementById('exportTipo').value;
    const formato = document.getElementById('exportFormato').value;
    const periodo = document.getElementById('exportPeriodo').value;
    
    if (!tipo || !formato || !periodo) {
        alert('Por favor, preencha todos os campos.');
        return;
    }
    
    // Aqui você implementaria a lógica de exportação
    const url = `<?php echo URLROOT; ?>/relatorio/exportar${formato.charAt(0).toUpperCase() + formato.slice(1)}/${tipo}?periodo=${periodo}`;
    window.open(url, '_blank');
    
    // Fechar modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('exportModal'));
    modal.hide();
}
</script>

<?php require APPROOT . '/app/views/inc/footer.php'; ?>
