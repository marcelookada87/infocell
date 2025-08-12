<?php require APPROOT . '/app/views/inc/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-dollar-sign"></i> Relatório Financeiro
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
                    <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/relatorio/exportarPdf/financeiro"><i class="fas fa-file-pdf me-2"></i>PDF</a></li>
                    <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/relatorio/exportarExcel/financeiro"><i class="fas fa-file-excel me-2"></i>Excel</a></li>
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
            <form method="GET" action="<?php echo URLROOT; ?>/relatorio/financeiro">
                <div class="row">
                    <div class="col-md-3">
                        <label for="ano" class="form-label">Ano</label>
                        <select name="ano" id="ano" class="form-select">
                            <?php for ($i = date('Y'); $i >= date('Y') - 5; $i--): ?>
                                <option value="<?php echo $i; ?>" <?php echo ($data['ano_selecionado'] == $i) ? 'selected' : ''; ?>>
                                    <?php echo $i; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="mes" class="form-label">Mês</label>
                        <select name="mes" id="mes" class="form-select">
                            <option value="">Todos os meses</option>
                            <option value="01" <?php echo ($data['mes_selecionado'] == '01') ? 'selected' : ''; ?>>Janeiro</option>
                            <option value="02" <?php echo ($data['mes_selecionado'] == '02') ? 'selected' : ''; ?>>Fevereiro</option>
                            <option value="03" <?php echo ($data['mes_selecionado'] == '03') ? 'selected' : ''; ?>>Março</option>
                            <option value="04" <?php echo ($data['mes_selecionado'] == '04') ? 'selected' : ''; ?>>Abril</option>
                            <option value="05" <?php echo ($data['mes_selecionado'] == '05') ? 'selected' : ''; ?>>Maio</option>
                            <option value="06" <?php echo ($data['mes_selecionado'] == '06') ? 'selected' : ''; ?>>Junho</option>
                            <option value="07" <?php echo ($data['mes_selecionado'] == '07') ? 'selected' : ''; ?>>Julho</option>
                            <option value="08" <?php echo ($data['mes_selecionado'] == '08') ? 'selected' : ''; ?>>Agosto</option>
                            <option value="09" <?php echo ($data['mes_selecionado'] == '09') ? 'selected' : ''; ?>>Setembro</option>
                            <option value="10" <?php echo ($data['mes_selecionado'] == '10') ? 'selected' : ''; ?>>Outubro</option>
                            <option value="11" <?php echo ($data['mes_selecionado'] == '11') ? 'selected' : ''; ?>>Novembro</option>
                            <option value="12" <?php echo ($data['mes_selecionado'] == '12') ? 'selected' : ''; ?>>Dezembro</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="tipo_receita" class="form-label">Tipo de Receita</label>
                        <select name="tipo_receita" id="tipo_receita" class="form-select">
                            <option value="">Todas</option>
                            <option value="reparacao">Reparação</option>
                            <option value="manutencao">Manutenção</option>
                            <option value="venda">Venda</option>
                            <option value="consulta">Consulta</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                        <a href="<?php echo URLROOT; ?>/relatorio/financeiro" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Resumo dos Filtros Aplicados -->
<div class="alert alert-info mb-4">
    <h6 class="alert-heading">
        <i class="fas fa-info-circle"></i> Período Selecionado
    </h6>
    <div class="row">
        <div class="col-md-4">
            <strong>Ano:</strong> <?php echo $data['ano_selecionado']; ?>
        </div>
        <div class="col-md-4">
            <strong>Mês:</strong> 
            <?php 
            if ($data['mes_selecionado']) {
                $meses = [
                    '01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março', '04' => 'Abril',
                    '05' => 'Maio', '06' => 'Junho', '07' => 'Julho', '08' => 'Agosto',
                    '09' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'
                ];
                echo $meses[$data['mes_selecionado']];
            } else {
                echo 'Todos os meses';
            }
            ?>
        </div>
        <div class="col-md-4">
            <strong>Total de Registros:</strong> <?php echo count($data['receita_por_mes']); ?>
        </div>
    </div>
</div>

<!-- Cards de Estatísticas Financeiras -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Receita Total
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            R$ <?php echo number_format($data['receita_total'], 2, ',', '.'); ?>
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
                            R$ <?php echo number_format($data['ticket_medio'], 2, ',', '.'); ?>
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
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Ordens Finalizadas
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $data['ordens_pagas']; ?>
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
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Receita Média Mensal
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            R$ <?php 
                                $receitaMensal = count($data['receita_por_mes']) > 0 ? $data['receita_total'] / count($data['receita_por_mes']) : 0;
                                echo number_format($receitaMensal, 2, ',', '.'); 
                            ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gráficos -->
<div class="row mb-4">
    <!-- Gráfico de Receita por Mês -->
    <div class="col-xl-8 col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-line"></i> Evolução da Receita por Mês
                </h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                        <a class="dropdown-item" href="#" onclick="exportarGrafico()">
                            <i class="fas fa-download me-2"></i>Exportar Gráfico
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="receitaPorMesChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Gráfico de Receita por Tipo -->
    <div class="col-xl-4 col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-pie"></i> Receita por Tipo
                </h6>
            </div>
            <div class="card-body">
                <canvas id="receitaPorTipoChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Tabelas de Dados -->
<div class="row">
    <!-- Receita Mensal Detalhada -->
    <div class="col-xl-8 col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-table"></i> Receita Mensal Detalhada
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
                <?php if (!empty($data['receita_por_mes'])): ?>
                <div class="table-responsive">
                    <table class="table table-bordered" id="receitaTable">
                        <thead>
                            <tr>
                                <th>Mês</th>
                                <th>Receita (R$)</th>
                                <th>Ordens</th>
                                <th>Ticket Médio</th>
                                <th>% do Total</th>
                                <th>Tendência</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $meses = [
                                '01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março', '04' => 'Abril',
                                '05' => 'Maio', '06' => 'Junho', '07' => 'Julho', '08' => 'Agosto',
                                '09' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'
                            ];
                            $receitaAnterior = 0;
                            foreach ($data['receita_por_mes'] as $receita): 
                                $percentual = $data['receita_total'] > 0 ? ($receita->valor / $data['receita_total']) * 100 : 0;
                                $tendencia = '';
                                $tendenciaClass = '';
                                if ($receitaAnterior > 0) {
                                    if ($receita->valor > $receitaAnterior) {
                                        $tendencia = '↗️ Crescimento';
                                        $tendenciaClass = 'text-success';
                                    } elseif ($receita->valor < $receitaAnterior) {
                                        $tendencia = '↘️ Queda';
                                        $tendenciaClass = 'text-danger';
                                    } else {
                                        $tendencia = '→ Estável';
                                        $tendenciaClass = 'text-warning';
                                    }
                                } else {
                                    $tendencia = '→ Primeiro mês';
                                    $tendenciaClass = 'text-info';
                                }
                                $receitaAnterior = $receita->valor;
                            ?>
                            <tr>
                                <td><strong><?php echo $meses[$receita->mes] ?? $receita->mes; ?></strong></td>
                                <td><strong>R$ <?php echo number_format($receita->valor, 2, ',', '.'); ?></strong></td>
                                <td><?php echo $receita->quantidade ?? 0; ?></td>
                                <td>R$ <?php echo number_format(($receita->quantidade > 0 ? $receita->valor / $receita->quantidade : 0), 2, ',', '.'); ?></td>
                                <td><?php echo number_format($percentual, 1); ?>%</td>
                                <td><span class="<?php echo $tendenciaClass; ?>"><?php echo $tendencia; ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-primary">
                                <td><strong>TOTAL</strong></td>
                                <td><strong>R$ <?php echo number_format($data['receita_total'], 2, ',', '.'); ?></strong></td>
                                <td><strong><?php echo array_sum(array_column($data['receita_por_mes'], 'quantidade')); ?></strong></td>
                                <td><strong>R$ <?php echo number_format($data['ticket_medio'], 2, ',', '.'); ?></strong></td>
                                <td><strong>100%</strong></td>
                                <td><strong>-</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Nenhum dado financeiro encontrado</h5>
                    <p class="text-muted">Tente ajustar os filtros ou verificar se há ordens de serviço no período.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Resumo por Status -->
    <div class="col-xl-4 col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-bar"></i> Resumo por Status
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Concluídas</span>
                        <span class="text-success"><?php echo $data['ordens_pagas']; ?></span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: <?php echo $data['ordens_pagas'] > 0 ? 100 : 0; ?>%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Em Andamento</span>
                        <span class="text-info"><?php echo isset($data['ordens_em_andamento']) ? $data['ordens_em_andamento'] : 0; ?></span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-info" style="width: <?php echo isset($data['ordens_em_andamento']) && $data['ordens_em_andamento'] > 0 ? 100 : 0; ?>%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Aguardando</span>
                        <span class="text-warning"><?php echo isset($data['ordens_aguardando']) ? $data['ordens_aguardando'] : 0; ?></span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-warning" style="width: <?php echo isset($data['ordens_aguardando']) && $data['ordens_aguardando'] > 0 ? 100 : 0; ?>%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Canceladas</span>
                        <span class="text-danger"><?php echo isset($data['ordens_canceladas']) ? $data['ordens_canceladas'] : 0; ?></span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-danger" style="width: <?php echo isset($data['ordens_canceladas']) && $data['ordens_canceladas'] > 0 ? 100 : 0; ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Análise de Performance -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-analytics"></i> Análise de Performance
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center mb-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-trending-up fa-2x text-success mb-2"></i>
                            <h5 class="text-success"><?php echo number_format($receitaMensal, 2, ',', '.'); ?></h5>
                            <small class="text-muted">Receita Média Mensal</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-chart-line fa-2x text-info mb-2"></i>
                            <h5 class="text-info"><?php echo number_format($data['ticket_medio'], 2, ',', '.'); ?></h5>
                            <small class="text-muted">Ticket Médio</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-percentage fa-2x text-warning mb-2"></i>
                            <h5 class="text-warning">
                                <?php 
                                $crescimento = 0;
                                if (count($data['receita_por_mes']) >= 2) {
                                    $meses = array_values($data['receita_por_mes']);
                                    $mesAtual = end($meses)->valor;
                                    $mesAnterior = prev($meses)->valor;
                                    $crescimento = $mesAnterior > 0 ? (($mesAtual - $mesAnterior) / $mesAnterior) * 100 : 0;
                                }
                                echo number_format($crescimento, 1);
                                ?>%
                            </h5>
                            <small class="text-muted">Crescimento Mensal</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-calendar-check fa-2x text-primary mb-2"></i>
                            <h5 class="text-primary"><?php echo $data['ordens_pagas']; ?></h5>
                            <small class="text-muted">Ordens Finalizadas</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/app/views/inc/footer.php'; ?>

<script>
// Dados para os gráficos
const receitaPorMesData = <?php echo json_encode($data['receita_por_mes'] ?? []); ?>;
const receitaTotal = <?php echo $data['receita_total']; ?>;

// Gráfico de Receita por Mês
const ctx1 = document.getElementById('receitaPorMesChart').getContext('2d');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: receitaPorMesData.map(item => {
            const meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
            return meses[parseInt(item.mes) - 1] || item.mes;
        }),
        datasets: [{
            label: 'Receita (R$)',
            data: receitaPorMesData.map(item => item.valor),
            borderColor: '#1cc88a',
            backgroundColor: 'rgba(28, 200, 138, 0.1)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#1cc88a',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Receita: R$ ' + context.parsed.y.toLocaleString('pt-BR', {minimumFractionDigits: 2});
                    }
                }
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

// Gráfico de Receita por Tipo (exemplo)
const ctx2 = document.getElementById('receitaPorTipoChart').getContext('2d');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: ['Reparação', 'Manutenção', 'Venda', 'Consulta'],
        datasets: [{
            data: [
                receitaTotal * 0.6, // 60% reparação
                receitaTotal * 0.25, // 25% manutenção
                receitaTotal * 0.1,  // 10% venda
                receitaTotal * 0.05  // 5% consulta
            ],
            backgroundColor: [
                '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true
                }
            }
        }
    }
});

// Função para exportar gráfico
function exportarGrafico() {
    alert('Funcionalidade de exportação de gráfico será implementada em breve.');
}

// Função para exportar tabela
function exportarTabela() {
    alert('Funcionalidade de exportação de tabela será implementada em breve.');
}

// Função para imprimir tabela
function imprimirTabela() {
    window.print();
}

// Inicializar DataTable se houver dados
<?php if (!empty($data['receita_por_mes'])): ?>
$(document).ready(function() {
    $('#receitaTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json'
        },
        pageLength: 12,
        order: [[0, 'asc']],
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
});
<?php endif; ?>
</script>

