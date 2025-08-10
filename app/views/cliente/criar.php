<?php require APPROOT . '/app/views/inc/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user-plus"></i> Novo Cliente
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo URLROOT; ?>/cliente" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-body">
                <form action="<?php echo URLROOT; ?>/cliente/criar" method="post" id="formCliente">
                    <div class="row">
                        <!-- Dados Pessoais -->
                        <div class="col-md-8">
                            <h5 class="mb-3">
                                <i class="fas fa-user"></i> Dados Pessoais
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="nome" class="form-label">Nome Completo *</label>
                                    <input type="text" name="nome" id="nome" 
                                           class="form-control <?php echo (!empty($data['nome_err'])) ? 'is-invalid' : ''; ?>" 
                                           value="<?php echo $data['nome']; ?>" 
                                           placeholder="Digite o nome completo do cliente" required>
                                    <div class="invalid-feedback">
                                        <?php echo $data['nome_err']; ?>
                                    </div>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="cpf" class="form-label">CPF</label>
                                    <input type="text" name="cpf" id="cpf" 
                                           class="form-control cpf <?php echo (!empty($data['cpf_err'])) ? 'is-invalid' : ''; ?>" 
                                           value="<?php echo $data['cpf']; ?>" 
                                           placeholder="000.000.000-00">
                                    <div class="invalid-feedback">
                                        <?php echo $data['cpf_err']; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <h5 class="mb-3 mt-4">
                                <i class="fas fa-phone"></i> Contato
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="telefone" class="form-label">Telefone *</label>
                                    <input type="text" name="telefone" id="telefone" 
                                           class="form-control telefone <?php echo (!empty($data['telefone_err'])) ? 'is-invalid' : ''; ?>" 
                                           value="<?php echo $data['telefone']; ?>" 
                                           placeholder="(00) 00000-0000" required>
                                    <div class="invalid-feedback">
                                        <?php echo $data['telefone_err']; ?>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email" 
                                           class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" 
                                           value="<?php echo $data['email']; ?>" 
                                           placeholder="email@exemplo.com">
                                    <div class="invalid-feedback">
                                        <?php echo $data['email_err']; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <h5 class="mb-3 mt-4">
                                <i class="fas fa-map-marker-alt"></i> Endereço
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="cep" class="form-label">CEP</label>
                                    <input type="text" name="cep" id="cep" 
                                           class="form-control cep" 
                                           value="<?php echo $data['cep']; ?>" 
                                           placeholder="00000-000">
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Preenchimento automático
                                    </small>
                                </div>
                                
                                <div class="col-md-5 mb-3">
                                    <label for="endereco" class="form-label">Endereço</label>
                                    <input type="text" name="endereco" id="endereco" 
                                           class="form-control" 
                                           value="<?php echo $data['endereco']; ?>" 
                                           placeholder="Rua, Avenida, etc.">
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="cidade" class="form-label">Cidade</label>
                                    <input type="text" name="cidade" id="cidade" 
                                           class="form-control" 
                                           value="<?php echo $data['cidade']; ?>" 
                                           placeholder="Nome da cidade">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sidebar com informações e preview -->
                        <div class="col-md-4">
                            <h5 class="mb-3">
                                <i class="fas fa-info-circle"></i> Informações
                            </h5>
                            
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <i class="fas fa-user-circle fa-4x text-muted"></i>
                                        <h6 class="mt-2" id="preview-nome">Nome do Cliente</h6>
                                    </div>
                                    
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <i class="fas fa-phone text-primary"></i>
                                            <span id="preview-telefone">Telefone</span>
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-envelope text-primary"></i>
                                            <span id="preview-email">Email</span>
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-id-card text-primary"></i>
                                            <span id="preview-cpf">CPF</span>
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-map-marker-alt text-primary"></i>
                                            <span id="preview-cidade">Cidade</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="card bg-primary text-white mt-3">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-lightbulb"></i> Dica
                                    </h6>
                                    <p class="card-text small mb-0">
                                        Preencha pelo menos o nome e telefone. 
                                        Os demais campos são opcionais mas ajudam 
                                        no controle e contato com o cliente.
                                    </p>
                                </div>
                            </div>
                            
                            <div class="card bg-info text-white mt-3">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-shield-alt"></i> Privacidade
                                    </h6>
                                    <p class="card-text small mb-0">
                                        Todos os dados pessoais são protegidos 
                                        e utilizados apenas para fins de 
                                        atendimento e contato.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Botões de Ação -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="<?php echo URLROOT; ?>/cliente" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Cadastrar Cliente
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts específicos -->
<script>
$(document).ready(function() {
    // Preview em tempo real
    function updatePreview() {
        $('#preview-nome').text($('#nome').val() || 'Nome do Cliente');
        $('#preview-telefone').text($('#telefone').val() || 'Telefone');
        $('#preview-email').text($('#email').val() || 'Email');
        $('#preview-cpf').text($('#cpf').val() || 'CPF');
        $('#preview-cidade').text($('#cidade').val() || 'Cidade');
    }
    
    // Atualizar preview quando campos mudarem
    $('#nome, #telefone, #email, #cpf, #cidade').on('input', updatePreview);
    
    // Buscar CEP automaticamente
    $('#cep').on('blur', function() {
        var cep = $(this).val().replace(/\D/g, '');
        
        if (cep.length === 8) {
            // Mostrar loading
            $('#endereco, #cidade').prop('disabled', true).val('Carregando...');
            
            $.getJSON('https://viacep.com.br/ws/' + cep + '/json/', function(data) {
                if (!data.erro) {
                    $('#endereco').val(data.logradouro);
                    $('#cidade').val(data.localidade + ' - ' + data.uf);
                    updatePreview();
                } else {
                    InfoCellOS.showAlert('CEP não encontrado', 'warning');
                }
            }).fail(function() {
                InfoCellOS.showAlert('Erro ao buscar CEP', 'danger');
            }).always(function() {
                $('#endereco, #cidade').prop('disabled', false);
                if ($('#endereco').val() === 'Carregando...') {
                    $('#endereco, #cidade').val('');
                }
            });
        }
    });
    
    // Validação de CPF em tempo real
    $('#cpf').on('blur', function() {
        var cpf = $(this).val();
        if (cpf && !InfoCellOS.validarCPF(cpf)) {
            $(this).addClass('is-invalid');
            $(this).siblings('.invalid-feedback').text('CPF inválido');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    
    // Validação de email em tempo real
    $('#email').on('blur', function() {
        var email = $(this).val();
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && !emailRegex.test(email)) {
            $(this).addClass('is-invalid');
            $(this).siblings('.invalid-feedback').text('Email inválido');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    
    // Validação do formulário
    $('#formCliente').on('submit', function(e) {
        var isValid = true;
        
        // Validar nome
        if (!$('#nome').val().trim()) {
            $('#nome').addClass('is-invalid');
            isValid = false;
        }
        
        // Validar telefone
        if (!$('#telefone').val().trim()) {
            $('#telefone').addClass('is-invalid');
            isValid = false;
        }
        
        // Validar CPF se preenchido
        var cpf = $('#cpf').val();
        if (cpf && !InfoCellOS.validarCPF(cpf)) {
            $('#cpf').addClass('is-invalid');
            isValid = false;
        }
        
        // Validar email se preenchido
        var email = $('#email').val();
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email && !emailRegex.test(email)) {
            $('#email').addClass('is-invalid');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            InfoCellOS.showAlert('Por favor, corrija os campos destacados', 'warning');
        }
    });
    
    // Inicializar preview
    updatePreview();
});
</script>

<style>
.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.card .card-body {
    padding: 1.5rem;
}

.preview-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: none;
    border-radius: 10px;
}

@media (max-width: 768px) {
    .col-md-4 {
        margin-top: 2rem;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column-reverse;
        gap: 1rem;
    }
    
    .d-flex.justify-content-between .btn {
        width: 100%;
    }
}

/* Loading animation para campos de endereço */
.form-control:disabled {
    background-color: #f8f9fa;
    opacity: 0.7;
}

/* Estilo para campos inválidos */
.is-invalid {
    border-color: #dc3545;
    background-color: #fff5f5;
}

.is-invalid:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

/* Preview responsivo */
.list-unstyled li {
    word-break: break-all;
}

.list-unstyled li i {
    width: 20px;
    text-align: center;
    margin-right: 8px;
}
</style>

<?php require APPROOT . '/app/views/inc/footer.php'; ?>

