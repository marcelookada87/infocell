<?php require APPROOT . '/app/views/inc/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-users"></i> Clientes
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="<?php echo URLROOT; ?>/cliente/criar" class="btn btn-sm btn-primary">
                <i class="fas fa-user-plus"></i> Novo Cliente
            </a>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#filtros">
                <i class="fas fa-search"></i> Buscar
            </button>
        </div>
    </div>
</div>

<?php flash('cliente_message'); ?>

<!-- Filtros de Busca -->
<div class="collapse mb-4" id="filtros">
    <div class="card">
        <div class="card-body">
            <form method="GET" action="<?php echo URLROOT; ?>/cliente" id="formBusca">
                <div class="row">
                    <div class="col-md-4">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" name="nome" id="nome" class="form-control" 
                               placeholder="Digite o nome do cliente" value="<?php echo $_GET['nome'] ?? ''; ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="text" name="telefone" id="telefone" class="form-control telefone" 
                               placeholder="(00) 00000-0000" value="<?php echo $_GET['telefone'] ?? ''; ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" 
                               placeholder="email@exemplo.com" value="<?php echo $_GET['email'] ?? ''; ?>">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="btn-group w-100">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                            <a href="<?php echo URLROOT; ?>/cliente" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Lista de Clientes -->
<div class="card shadow">
    <div class="card-body">
        <?php if (!empty($data['clientes'])): ?>
        <div class="table-responsive">
            <table class="table table-hover" id="clientesTable">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Contato</th>
                        <th>CPF</th>
                        <th>Cidade</th>
                        <th>Cadastro</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['clientes'] as $cliente): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-3">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <strong><?php echo htmlspecialchars($cliente->nome); ?></strong>
                                    <?php if (!empty($cliente->email)): ?>
                                    <br><small class="text-muted">
                                        <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($cliente->email); ?>
                                    </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="tel:<?php echo $cliente->telefone; ?>" class="text-decoration-none">
                                <i class="fas fa-phone text-primary"></i> <?php echo $cliente->telefone; ?>
                            </a>
                        </td>
                        <td>
                            <?php echo !empty($cliente->cpf) ? $cliente->cpf : '<span class="text-muted">-</span>'; ?>
                        </td>
                        <td>
                            <?php echo !empty($cliente->cidade) ? $cliente->cidade : '<span class="text-muted">-</span>'; ?>
                        </td>
                        <td>
                            <small class="text-muted">
                                <?php echo formatarData($cliente->criado_em); ?>
                            </small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="<?php echo URLROOT; ?>/cliente/visualizar/<?php echo $cliente->id; ?>" 
                                   class="btn btn-sm btn-outline-primary" title="Ver detalhes">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo URLROOT; ?>/cliente/editar/<?php echo $cliente->id; ?>" 
                                   class="btn btn-sm btn-outline-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?php echo URLROOT; ?>/ordem-servico/criar?cliente_id=<?php echo $cliente->id; ?>" 
                                   class="btn btn-sm btn-outline-success" title="Nova OS">
                                    <i class="fas fa-plus"></i>
                                </a>
                                <?php if ($_SESSION['user_type'] == 'admin'): ?>
                                <form method="POST" action="<?php echo URLROOT; ?>/cliente/deletar/<?php echo $cliente->id; ?>" class="d-inline">
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
        
        <!-- Paginação (se necessário) -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <small class="text-muted">
                Mostrando <?php echo count($data['clientes']); ?> cliente(s)
            </small>
            <div>
                <!-- Aqui pode ser implementada a paginação se necessário -->
            </div>
        </div>
        
        <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-users fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">Nenhum cliente encontrado</h4>
            <?php if (!empty($_GET['nome']) || !empty($_GET['telefone']) || !empty($_GET['email'])): ?>
                <p class="text-muted">Tente ajustar os filtros de busca ou</p>
                <a href="<?php echo URLROOT; ?>/cliente" class="btn btn-outline-primary me-2">
                    <i class="fas fa-times"></i> Limpar Filtros
                </a>
            <?php else: ?>
                <p class="text-muted">Comece cadastrando seu primeiro cliente.</p>
            <?php endif; ?>
            <a href="<?php echo URLROOT; ?>/cliente/criar" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Novo Cliente
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Estatísticas Rápidas -->
<?php if (!empty($data['clientes'])): ?>
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Total de Clientes</h5>
                        <h2><?php echo count($data['clientes']); ?></h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Com Email</h5>
                        <h2>
                            <?php 
                            $comEmail = 0;
                            foreach ($data['clientes'] as $cliente) {
                                if (!empty($cliente->email)) $comEmail++;
                            }
                            echo $comEmail;
                            ?>
                        </h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-envelope fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Cadastros Hoje</h5>
                        <h2>
                            <?php 
                            $hoje = 0;
                            $dataHoje = date('Y-m-d');
                            foreach ($data['clientes'] as $cliente) {
                                if (date('Y-m-d', strtotime($cliente->criado_em)) == $dataHoje) $hoje++;
                            }
                            echo $hoje;
                            ?>
                        </h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar-day fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Scripts específicos -->
<script>
$(document).ready(function() {
    // DataTable para melhor navegação
    if ($.fn.DataTable && $('#clientesTable tbody tr').length > 0) {
        $('#clientesTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
            },
            "pageLength": 25,
            "order": [[ 0, "asc" ]], // Ordenar por nome
            "columnDefs": [
                { "orderable": false, "targets": 5 } // Desabilitar ordenação na coluna de ações
            ]
        });
    }
    
    // Busca em tempo real (opcional)
    let timeoutId;
    $('#nome, #telefone, #email').on('input', function() {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(function() {
            // Pode implementar busca AJAX aqui se necessário
        }, 500);
    });
});
</script>

<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
}

.table td {
    vertical-align: middle;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

@media (max-width: 768px) {
    .btn-group {
        display: flex;
        flex-direction: column;
        width: 100%;
    }
    
    .btn-group .btn {
        margin-right: 0;
        margin-bottom: 2px;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>

<?php require APPROOT . '/app/views/inc/footer.php'; ?>

