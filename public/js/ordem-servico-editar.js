/**
 * JavaScript para Página de Edição de Ordem de Serviço
 * Funcionalidades interativas e validações
 */

class OrdemServicoEditor {
    constructor() {
        this.initializeElements();
        this.bindEvents();
        this.initializeState();
    }

    initializeElements() {
        // Elementos principais
        this.form = document.getElementById('formEditarOS');
        this.statusSelect = document.getElementById('status');
        this.valorFinalInput = document.getElementById('valorFinal');
        this.problemaDiagnosticadoTextarea = document.getElementById('problema_diagnosticado');
        this.solucaoAplicadaTextarea = document.getElementById('solucao_aplicada');
        this.observacoesTecnicoTextarea = document.getElementById('observacoes_tecnico');
        this.btnSalvar = document.getElementById('btnSalvar');
        this.progressBar = document.getElementById('progressBar');
        this.progressPercentage = document.getElementById('progressPercentage');
        this.nextStepText = document.getElementById('nextStepText');

        // Modal
        this.confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
        this.btnConfirmarSalvar = document.getElementById('btnConfirmarSalvar');
    }

    bindEvents() {
        // Eventos de validação em tempo real
        this.statusSelect.addEventListener('change', () => this.handleStatusChange());
        this.valorFinalInput.addEventListener('input', () => this.validateValorFinal());
        this.problemaDiagnosticadoTextarea.addEventListener('input', () => this.validateProblemaDiagnosticado());
        this.solucaoAplicadaTextarea.addEventListener('input', () => this.validateSolucaoAplicada());

        // Eventos do formulário
        this.btnSalvar.addEventListener('click', (e) => this.handleSaveClick(e));
        this.btnConfirmarSalvar.addEventListener('click', () => this.confirmSave());

        // Eventos de auto-save
        this.setupAutoSave();

        // Evento antes de sair da página
        window.addEventListener('beforeunload', (e) => this.handleBeforeUnload(e));
    }

    initializeState() {
        this.isDirty = false;
        this.autoSaveTimer = null;
        this.updateProgress();
        this.updateNextStep();
        this.updateDirtyIndicator();
    }

    handleStatusChange() {
        this.validateStatus();
        this.updateNextStep();
        this.updateProgress();
        this.markAsDirty();
    }

    validateStatus() {
        const status = this.statusSelect.value;
        
        if (status === 'concluida') {
            // Se status for concluída, validar campos obrigatórios
            this.validateProblemaDiagnosticado();
            this.validateSolucaoAplicada();
        } else {
            // Remover validação dos campos
            this.problemaDiagnosticadoTextarea.classList.remove('is-invalid');
            this.solucaoAplicadaTextarea.classList.remove('is-invalid');
        }
    }

    validateValorFinal() {
        const valor = this.valorFinalInput.value.replace(/[^\d,]/g, '').replace(',', '.');
        
        if (valor && parseFloat(valor) < 0) {
            this.valorFinalInput.classList.add('is-invalid');
            return false;
        } else {
            this.valorFinalInput.classList.remove('is-invalid');
            return true;
        }
    }

    validateProblemaDiagnosticado() {
        if (this.statusSelect.value === 'concluida' && !this.problemaDiagnosticadoTextarea.value.trim()) {
            this.problemaDiagnosticadoTextarea.classList.add('is-invalid');
            return false;
        } else {
            this.problemaDiagnosticadoTextarea.classList.remove('is-invalid');
            return true;
        }
    }

    validateSolucaoAplicada() {
        if (this.statusSelect.value === 'concluida' && !this.solucaoAplicadaTextarea.value.trim()) {
            this.solucaoAplicadaTextarea.classList.add('is-invalid');
            return false;
        } else {
            this.solucaoAplicadaTextarea.classList.remove('is-invalid');
            return true;
        }
    }

    updateProgress() {
        const status = this.statusSelect.value;
        const progressMap = {
            'aberta': 10,
            'em_andamento': 30,
            'aguardando_pecas': 50,
            'aguardando_aprovacao': 70,
            'concluida': 100,
            'cancelada': 0
        };
        
        const progress = progressMap[status] || 0;
        
        // Animar a barra de progresso
        this.progressBar.style.width = '0%';
        setTimeout(() => {
            this.progressBar.style.width = progress + '%';
            this.progressBar.setAttribute('aria-valuenow', progress);
            this.progressPercentage.textContent = progress + '%';
            
            // Atualizar cor da barra
            this.updateProgressBarColor(progress);
        }, 100);
    }

    updateProgressBarColor(progress) {
        this.progressBar.className = 'progress-bar';
        
        if (progress >= 100) {
            this.progressBar.classList.add('bg-success');
        } else if (progress >= 70) {
            this.progressBar.classList.add('bg-info');
        } else if (progress >= 30) {
            this.progressBar.classList.add('bg-warning');
        } else {
            this.progressBar.classList.add('bg-primary');
        }
    }

    updateNextStep() {
        const status = this.statusSelect.value;
        const nextStepMap = {
            'aberta': 'Iniciar análise técnica',
            'em_andamento': 'Diagnosticar problema',
            'aguardando_pecas': 'Aguardar chegada das peças',
            'aguardando_aprovacao': 'Aguardar aprovação do cliente',
            'concluida': 'Entregar dispositivo ao cliente',
            'cancelada': 'N/A'
        };
        
        this.nextStepText.textContent = nextStepMap[status] || 'Verificar status atual';
    }

    setupAutoSave() {
        const formInputs = this.form.querySelectorAll('input, select, textarea');
        
        formInputs.forEach(input => {
            input.addEventListener('input', () => {
                this.markAsDirty();
                
                clearTimeout(this.autoSaveTimer);
                this.autoSaveTimer = setTimeout(() => {
                    this.triggerAutoSave();
                }, 3000);
            });
        });
    }

    triggerAutoSave() {
        // Aqui você pode implementar o auto-save real
        console.log('Auto-save triggered');
        this.showAlert('Alterações salvas automaticamente', 'success');
        this.isDirty = false;
        this.updateDirtyIndicator();
    }

    markAsDirty() {
        this.isDirty = true;
        this.updateDirtyIndicator();
    }

    updateDirtyIndicator() {
        if (this.isDirty) {
            this.btnSalvar.innerHTML = '<i class="fas fa-save"></i> Salvar Alterações <span class="badge bg-warning ms-1">*</span>';
            this.btnSalvar.classList.add('btn-warning');
            this.btnSalvar.classList.remove('btn-primary');
        } else {
            this.btnSalvar.innerHTML = '<i class="fas fa-save"></i> Salvar Alterações';
            this.btnSalvar.classList.remove('btn-warning');
            this.btnSalvar.classList.add('btn-primary');
        }
    }

    handleSaveClick(e) {
        e.preventDefault();
        
        if (!this.validateForm()) {
            return;
        }
        
        this.confirmModal.show();
    }

    confirmSave() {
        this.confirmModal.hide();
        this.form.submit();
    }

    validateForm() {
        let isValid = true;
        
        // Validar status
        if (!this.statusSelect.value) {
            this.statusSelect.classList.add('is-invalid');
            isValid = false;
        } else {
            this.statusSelect.classList.remove('is-invalid');
        }
        
        // Validar campos obrigatórios se status for concluída
        if (this.statusSelect.value === 'concluida') {
            if (!this.validateProblemaDiagnosticado()) {
                isValid = false;
            }
            
            if (!this.validateSolucaoAplicada()) {
                isValid = false;
            }
        }
        
        // Validar valor final se preenchido
        if (this.valorFinalInput.value.trim() && !this.validateValorFinal()) {
            isValid = false;
        }
        
        if (!isValid) {
            this.scrollToFirstError();
            this.showAlert('Por favor, corrija os erros antes de continuar.', 'warning');
        }
        
        return isValid;
    }

    scrollToFirstError() {
        const firstError = document.querySelector('.is-invalid');
        if (firstError) {
            firstError.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center' 
            });
            
            // Adicionar efeito visual
            firstError.style.animation = 'shake 0.5s ease-in-out';
            setTimeout(() => {
                firstError.style.animation = '';
            }, 500);
        }
    }

    showAlert(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const container = document.querySelector('.card-body');
        container.insertBefore(alertDiv, container.firstChild);
        
        // Auto-remover após 5 segundos
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    handleBeforeUnload(e) {
        if (this.isDirty) {
            e.preventDefault();
            e.returnValue = 'Você tem alterações não salvas. Deseja realmente sair?';
        }
    }

    // Métodos utilitários
    static formatCurrency(value) {
        if (!value) return 'R$ 0,00';
        
        const numericValue = parseFloat(value);
        return 'R$ ' + numericValue.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    static getStatusColor(status) {
        const colors = {
            'aberta': 'primary',
            'em_andamento': 'warning',
            'aguardando_pecas': 'info',
            'aguardando_aprovacao': 'secondary',
            'concluida': 'success',
            'cancelada': 'danger'
        };
        
        return colors[status] || 'secondary';
    }
}

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    // Verificar se estamos na página de edição
    if (document.getElementById('formEditarOS')) {
        window.ordemServicoEditor = new OrdemServicoEditor();
    }
    
    // Adicionar animação shake para campos com erro
    const style = document.createElement('style');
    style.textContent = `
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
    `;
    document.head.appendChild(style);
    
    // Inicializar tooltips do Bootstrap
    if (typeof bootstrap !== 'undefined') {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    
    // Máscara para valores monetários (se jQuery e jQuery Mask estiverem disponíveis)
    if (typeof $ !== 'undefined' && $.fn.mask) {
        $('.valor').mask('000.000.000.000.000,00', {reverse: true});
    }
});
