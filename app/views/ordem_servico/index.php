<?php require APPROOT . '/app/views/inc/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-clipboard-list"></i> Ordens de Serviço
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="<?php echo URLROOT; ?>/ordem-servico/criar" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Nova OS
            </a>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#filtros">
                <i class="fas fa-filter"></i> Filtros
            </button>
        </div>
    </div>
</div>

<?php flash('ordem_message'); ?>

<!-- Filtros -->
<div class="collapse mb-4" id="filtros">
    <div class="card">
        <div class="card-body">
            <form method="GET" action="<?php echo URLROOT; ?>/ordem-servico">
                <div class="row">
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Todos</option>
                            <option value="aberta">Aberta</option>
                            <option value="em_andamento">Em Andamento</option>
                            <option value="aguardando_peca">Aguardando Peça</option>
                            <option value="aguardando_cliente">Aguardando Cliente</option>
                            <option value="concluida">Concluída</option>
                            <option value="cancelada">Cancelada</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="dispositivo_tipo" class="form-label">Tipo de Dispositivo</label>
                        <select name="dispositivo_tipo" id="dispositivo_tipo" class="form-select">
                            <option value="">Todos</option>
                            <option value="celular">Celular</option>
                            <option value="tablet">Tablet</option>
                            <option value="notebook">Notebook</option>
                            <option value="desktop">Desktop</option>
                            <option value="tv">TV</option>
                            <option value="console">Console</option>
                            <option value="outros">Outros</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="data_inicio" class="form-label">Data Início</label>
                        <input type="date" name="data_inicio" id="data_inicio" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label for="data_fim" class="form-label">Data Fim</label>
                        <input type="date" name="data_fim" id="data_fim" class="form-control">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                        <a href="<?php echo URLROOT; ?>/ordem-servico" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Tabela de Ordens -->
<div class="card shadow">
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
                            <strong>#<?php echo gerarNumeroOS($ordem->id); ?></strong>
                        </td>
                        <td>
                            <div>
                                <strong><?php echo $ordem->cliente_nome; ?></strong><br>
                                <small class="text-muted">
                                    <i class="fas fa-phone"></i> <?php echo $ordem->cliente_telefone; ?>
                                </small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong><?php echo ucfirst($ordem->dispositivo_tipo); ?></strong><br>
                                <small class="text-muted">
                                    <?php echo $ordem->dispositivo_marca; ?> 
                                    <?php echo $ordem->dispositivo_modelo; ?>
                                </small>
                            </div>
                        </td>
                        <td>
                            <span title="<?php echo $ordem->problema_relatado; ?>">
                                <?php echo truncarTexto($ordem->problema_relatado, 40); ?>
                            </span>
                        </td>
                        <td><?php echo statusBadge($ordem->status); ?></td>
                        <td><?php echo prioridadeBadge($ordem->prioridade); ?></td>
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
                            <small>
                                <?php echo formatarData($ordem->criado_em); ?><br>
                                <span class="text-muted">por <?php echo $ordem->usuario_nome; ?></span>
                            </small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="<?php echo URLROOT; ?>/ordem-servico/visualizar/<?php echo $ordem->id; ?>" 
                                   class="btn btn-sm btn-outline-primary" title="Ver detalhes">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo URLROOT; ?>/ordem-servico/editar/<?php echo $ordem->id; ?>" 
                                   class="btn btn-sm btn-outline-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin'): ?>
                                <form method="POST" action="<?php echo URLROOT; ?>/ordem-servico/deletar/<?php echo $ordem->id; ?>" class="d-inline">
                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-delete" title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">Nenhuma ordem de serviço encontrada</h4>
            <p class="text-muted">Comece criando sua primeira ordem de serviço.</p>
            <a href="<?php echo URLROOT; ?>/ordem-servico/criar" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nova Ordem de Serviço
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require APPROOT . '/app/views/inc/footer.php'; ?>

<!-- Scripts específicos -->
<script>
$(document).ready(function() {
    // DataTable para melhor navegação
    if ($.fn.DataTable) {
        $('#ordensTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
            },
            "pageLength": 25,
            "order": [[ 7, "desc" ]], // Ordenar por data (coluna 7) decrescente
            "columnDefs": [
                { "orderable": false, "targets": 8 } // Desabilitar ordenação na coluna de ações
            ]
        });
    }
    
    // Aplicar valores dos filtros se existirem na URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('status')) {
        $('#status').val(urlParams.get('status'));
    }
    if (urlParams.get('dispositivo_tipo')) {
        $('#dispositivo_tipo').val(urlParams.get('dispositivo_tipo'));
    }
    if (urlParams.get('data_inicio')) {
        $('#data_inicio').val(urlParams.get('data_inicio'));
    }
    if (urlParams.get('data_fim')) {
        $('#data_fim').val(urlParams.get('data_fim'));
    }
});
</script>



