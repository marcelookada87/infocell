/**
 * InfoCell OS - Sistema de Ordem de Serviço
 * JavaScript Principal
 */

$(document).ready(function() {
    // Inicializar componentes
    initializeComponents();
    
    // Event listeners
    setupEventListeners();
    
    // Configurações globais
    setupGlobalConfigs();
});

/**
 * Inicializar componentes do Bootstrap e plugins
 */
function initializeComponents() {
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Inicializar popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    
    // Aplicar máscaras se jQuery Mask estiver disponível
    if (typeof $.fn.mask !== 'undefined') {
        applyMasks();
    }
    
    // Configurar Select2 se estiver disponível
    if (typeof $.fn.select2 !== 'undefined') {
        setupSelect2();
    }
    
    // Configurar DataTables se estiver disponível
    if (typeof $.fn.DataTable !== 'undefined') {
        setupDataTables();
    }
}

/**
 * Configurar event listeners
 */
function setupEventListeners() {
    // Confirmar exclusões
    $(document).on('click', '.btn-delete', function(e) {
        if (!confirm('Tem certeza que deseja excluir este item? Esta ação não pode ser desfeita.')) {
            e.preventDefault();
            return false;
        }
    });
    
    // Auto-hide flash messages
    setTimeout(function() {
        $('#msg-flash').fadeOut('slow');
    }, 5000);
    
    // Sidebar toggle para mobile
    $('#sidebarToggle').on('click', function() {
        $('#sidebar').toggleClass('show');
    });
    
    // Fechar sidebar ao clicar fora (mobile)
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#sidebar, #sidebarToggle').length) {
            $('#sidebar').removeClass('show');
        }
    });
    
    // Loading states para botões
    $('.btn-loading').on('click', function() {
        var btn = $(this);
        btn.addClass('loading').prop('disabled', true);
        
        setTimeout(function() {
            btn.removeClass('loading').prop('disabled', false);
        }, 2000);
    });
    
    // Validação de formulários em tempo real
    $('form').on('submit', function(e) {
        var form = $(this);
        var isValid = true;
        
        // Verificar campos obrigatórios
        form.find('[required]').each(function() {
            var field = $(this);
            if (!field.val().trim()) {
                field.addClass('is-invalid');
                isValid = false;
            } else {
                field.removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            showAlert('Por favor, preencha todos os campos obrigatórios.', 'warning');
        }
    });
    
    // Remover classe de erro ao digitar
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
}

/**
 * Configurações globais
 */
function setupGlobalConfigs() {
    // Configurar CSRF token para requisições AJAX
    $.ajaxSetup({
        beforeSend: function(xhr, settings) {
            if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type) && !this.crossDomain) {
                var token = $('meta[name=csrf-token]').attr('content');
                if (token) {
                    xhr.setRequestHeader("X-CSRFToken", token);
                }
            }
        }
    });
    
    // Configurar locale para números
    if (typeof Intl !== 'undefined') {
        window.numberFormatter = new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        });
    }
}

/**
 * Aplicar máscaras nos campos
 */
function applyMasks() {
    $('.telefone').mask('(00) 00000-0000', {
        placeholder: '(00) 00000-0000',
        translation: {
            '0': {pattern: /[0-9]/}
        }
    });
    
    $('.cpf').mask('000.000.000-00', {
        placeholder: '000.000.000-00',
        reverse: false
    });
    
    $('.cnpj').mask('00.000.000/0000-00', {
        placeholder: '00.000.000/0000-00',
        reverse: false
    });
    
    $('.cep').mask('00000-000', {
        placeholder: '00000-000'
    });
    
    $('.valor').mask('#.##0,00', {
        reverse: true,
        placeholder: '0,00'
    });
    
    $('.data').mask('00/00/0000', {
        placeholder: 'dd/mm/aaaa'
    });
    
    $('.hora').mask('00:00', {
        placeholder: 'hh:mm'
    });
}

/**
 * Configurar Select2
 */
function setupSelect2() {
    $('.select2').select2({
        theme: 'bootstrap-5',
        language: 'pt-BR',
        placeholder: 'Selecione uma opção',
        allowClear: true
    });
}

/**
 * Configurar DataTables
 */
function setupDataTables() {
    $('.datatable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json'
        },
        responsive: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success btn-sm'
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm'
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Imprimir',
                className: 'btn btn-info btn-sm'
            }
        ]
    });
}

/**
 * Mostrar alertas
 */
function showAlert(message, type = 'info', duration = 5000) {
    var alertClass = 'alert-' + type;
    var alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert" id="dynamic-alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Remover alert anterior se existir
    $('#dynamic-alert').remove();
    
    // Adicionar novo alert
    $('main').prepend(alertHtml);
    
    // Auto-hide após duração especificada
    setTimeout(function() {
        $('#dynamic-alert').fadeOut();
    }, duration);
}

/**
 * Fazer requisições AJAX
 */
function makeAjaxRequest(url, method = 'GET', data = {}, successCallback = null, errorCallback = null) {
    $.ajax({
        url: url,
        method: method,
        data: data,
        dataType: 'json',
        beforeSend: function() {
            // Mostrar loading se necessário
            showLoading();
        },
        success: function(response) {
            hideLoading();
            if (successCallback && typeof successCallback === 'function') {
                successCallback(response);
            }
        },
        error: function(xhr, status, error) {
            hideLoading();
            if (errorCallback && typeof errorCallback === 'function') {
                errorCallback(xhr, status, error);
            } else {
                showAlert('Erro na requisição: ' + error, 'danger');
            }
        }
    });
}

/**
 * Mostrar loading
 */
function showLoading() {
    if ($('#loading-overlay').length === 0) {
        $('body').append(`
            <div id="loading-overlay" class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center" 
                 style="background: rgba(0,0,0,0.5); z-index: 9999;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
            </div>
        `);
    }
}

/**
 * Esconder loading
 */
function hideLoading() {
    $('#loading-overlay').remove();
}

/**
 * Formatar valor monetário
 */
function formatCurrency(value) {
    if (window.numberFormatter) {
        return window.numberFormatter.format(value);
    }
    return 'R$ ' + parseFloat(value).toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

/**
 * Formatar data brasileira
 */
function formatDate(dateString) {
    if (!dateString) return '-';
    
    var date = new Date(dateString);
    return date.toLocaleDateString('pt-BR');
}

/**
 * Formatar data e hora brasileira
 */
function formatDateTime(dateString) {
    if (!dateString) return '-';
    
    var date = new Date(dateString);
    return date.toLocaleString('pt-BR');
}

/**
 * Validar CPF
 */
function validarCPF(cpf) {
    cpf = cpf.replace(/[^\d]+/g, '');
    
    if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) {
        return false;
    }
    
    var soma = 0;
    var resto;
    
    for (var i = 1; i <= 9; i++) {
        soma += parseInt(cpf.substring(i-1, i)) * (11 - i);
    }
    
    resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;
    if (resto !== parseInt(cpf.substring(9, 10))) return false;
    
    soma = 0;
    for (var i = 1; i <= 10; i++) {
        soma += parseInt(cpf.substring(i-1, i)) * (12 - i);
    }
    
    resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;
    if (resto !== parseInt(cpf.substring(10, 11))) return false;
    
    return true;
}

/**
 * Validar CNPJ
 */
function validarCNPJ(cnpj) {
    cnpj = cnpj.replace(/[^\d]+/g, '');
    
    if (cnpj.length !== 14) return false;
    
    if (/^(\d)\1+$/.test(cnpj)) return false;
    
    var tamanho = cnpj.length - 2;
    var numeros = cnpj.substring(0, tamanho);
    var digitos = cnpj.substring(tamanho);
    var soma = 0;
    var pos = tamanho - 7;
    
    for (var i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2) pos = 9;
    }
    
    var resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado !== parseInt(digitos.charAt(0))) return false;
    
    tamanho = tamanho + 1;
    numeros = cnpj.substring(0, tamanho);
    soma = 0;
    pos = tamanho - 7;
    
    for (var i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2) pos = 9;
    }
    
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado !== parseInt(digitos.charAt(1))) return false;
    
    return true;
}

/**
 * Buscar CEP
 */
function buscarCEP(cep, callback) {
    cep = cep.replace(/\D/g, '');
    
    if (cep.length !== 8) {
        if (callback) callback(false);
        return;
    }
    
    $.getJSON('https://viacep.com.br/ws/' + cep + '/json/', function(data) {
        if (!data.erro) {
            if (callback) callback(data);
        } else {
            if (callback) callback(false);
        }
    }).fail(function() {
        if (callback) callback(false);
    });
}

/**
 * Exportar dados para Excel
 */
function exportToExcel(tableId, filename = 'dados') {
    var table = document.getElementById(tableId);
    var wb = XLSX.utils.table_to_book(table, {sheet: "Sheet JS"});
    XLSX.writeFile(wb, filename + '.xlsx');
}

/**
 * Imprimir elemento específico
 */
function printElement(elementId) {
    var printContents = document.getElementById(elementId).innerHTML;
    var originalContents = document.body.innerHTML;
    
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload();
}

// Exportar funções para uso global
window.InfoCellOS = {
    showAlert,
    makeAjaxRequest,
    showLoading,
    hideLoading,
    formatCurrency,
    formatDate,
    formatDateTime,
    validarCPF,
    validarCNPJ,
    buscarCEP,
    exportToExcel,
    printElement
};

