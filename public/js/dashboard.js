/**
 * InfoCell OS - Dashboard JavaScript
 * Funcionalidades específicas para o dashboard
 */

class Dashboard {
    constructor() {
        this.charts = {};
        this.stats = {};
        this.updateInterval = null;
        this.isInitialized = false;
        this.chartJsReady = false;
        
        this.init();
    }
    
    init() {
        if (this.isInitialized) return;
        
        // Aguardar Chart.js estar pronto
        if (typeof Chart !== 'undefined') {
            this.chartJsReady = true;
            this.initializeDashboard();
        } else {
            // Aguardar evento de Chart.js pronto
            window.addEventListener('chartjs-ready', () => {
                this.chartJsReady = true;
                this.initializeDashboard();
            });
            
            // Fallback: verificar a cada 100ms se Chart.js está disponível
            const checkChartJs = setInterval(() => {
                if (typeof Chart !== 'undefined') {
                    this.chartJsReady = true;
                    clearInterval(checkChartJs);
                    this.initializeDashboard();
                }
            }, 100);
            
            // Timeout de segurança
            setTimeout(() => {
                if (!this.chartJsReady) {
                    clearInterval(checkChartJs);
                    console.error('Chart.js não pôde ser carregado');
                    this.showNotification('Erro ao carregar gráficos. Recarregue a página.', 'danger');
                }
            }, 10000);
        }
    }
    
    /**
     * Inicializar dashboard após Chart.js estar pronto
     */
    initializeDashboard() {
        this.initializeCharts();
        this.initializeStats();
        this.initializeQuickActions();
        this.initializeRealTimeUpdates();
        this.initializeAnimations();
        this.initializeThemeToggle();
        
        this.isInitialized = true;
        console.log('Dashboard inicializado com sucesso!');
    }
    
    /**
     * Inicializar gráficos do dashboard
     */
    initializeCharts() {
        if (!this.chartJsReady) {
            console.warn('Chart.js não está pronto para inicializar gráficos');
            return;
        }
        
        // Gráfico de Ordens por Status
        this.initOrdensChart();
        
        // Gráfico de Receita Mensal
        this.initReceitaChart();
        
        // Gráfico de Produtividade
        this.initProdutividadeChart();
    }
    
    /**
     * Gráfico de Ordens por Status
     */
    initOrdensChart() {
        const ctx = document.getElementById('chartOrdens');
        if (!ctx) return;
        
        this.charts.ordens = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Abertas', 'Em Andamento', 'Aguardando Cliente', 'Concluídas', 'Canceladas'],
                datasets: [{
                    data: [12, 8, 5, 45, 3],
                    backgroundColor: [
                        '#f59e0b', // warning
                        '#06b6d4', // info
                        '#2563eb', // primary
                        '#10b981', // success
                        '#ef4444'  // danger
                    ],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverBorderWidth: 4,
                    hoverBorderColor: '#f8fafc'
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
                            usePointStyle: true,
                            font: {
                                size: 12,
                                weight: '600'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#2563eb',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: true
                    }
                },
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 1000,
                    easing: 'easeOutQuart'
                }
            }
        });
    }
    
    /**
     * Gráfico de Receita Mensal
     */
    initReceitaChart() {
        const ctx = document.getElementById('chartReceita');
        if (!ctx) return;
        
        this.charts.receita = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                datasets: [{
                    label: 'Receita (R$)',
                    data: [2500, 3200, 2800, 4100, 3800, 4500, 5200, 4800, 6100, 5800, 7200, 6800],
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#2563eb',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointHoverBorderWidth: 4
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
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#2563eb',
                        borderWidth: 1,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return 'Receita: R$ ' + context.parsed.y.toLocaleString('pt-BR');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)',
                            borderColor: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            callback: function(value) {
                                return 'R$ ' + value.toLocaleString('pt-BR');
                            },
                            font: {
                                size: 12,
                                weight: '600'
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)',
                            borderColor: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            font: {
                                size: 12,
                                weight: '600'
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                }
            }
        });
    }
    
    /**
     * Gráfico de Produtividade
     */
    initProdutividadeChart() {
        const ctx = document.getElementById('chartProdutividade');
        if (!ctx) return;
        
        this.charts.produtividade = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                datasets: [{
                    label: 'Ordens Concluídas',
                    data: [8, 12, 15, 10, 18, 5, 2],
                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    borderColor: '#10b981',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false
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
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#10b981',
                        borderWidth: 1,
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)',
                            borderColor: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            stepSize: 5,
                            font: {
                                size: 12,
                                weight: '600'
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12,
                                weight: '600'
                            }
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                }
            }
        });
    }
    
    /**
     * Inicializar estatísticas
     */
    initializeStats() {
        this.updateStats();
        this.animateStats();
    }
    
    /**
     * Atualizar dados em tempo real com melhor tratamento de erro
     */
    updateStats() {
        try {
            // Simular dados em tempo real
            const stats = {
                ordensAbertas: Math.floor(Math.random() * 20) + 8,
                ordensConcluidas: Math.floor(Math.random() * 60) + 30,
                emAndamento: Math.floor(Math.random() * 15) + 5,
                totalClientes: Math.floor(Math.random() * 50) + 140
            };
            
            this.updateStatCard('ordens-abertas', stats.ordensAbertas);
            this.updateStatCard('ordens-concluidas', stats.ordensConcluidas);
            this.updateStatCard('em-andamento', stats.emAndamento);
            this.updateStatCard('total-clientes', stats.totalClientes);
            
            this.stats = stats;
        } catch (error) {
            console.error('Erro ao atualizar estatísticas:', error);
            this.showNotification('Erro ao atualizar estatísticas', 'danger');
        }
    }
    
    /**
     * Atualizar card de estatística
     */
    updateStatCard(selector, value) {
        const element = document.querySelector(`[data-stat="${selector}"]`);
        if (!element) return;
        
        const currentValue = parseInt(element.textContent) || 0;
        this.animateNumber(element, currentValue, value);
    }
    
    /**
     * Animar número
     */
    animateNumber(element, start, end) {
        const duration = 1000;
        const startTime = performance.now();
        
        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            const easeOutQuart = 1 - Math.pow(1 - progress, 4);
            const current = Math.floor(start + (end - start) * easeOutQuart);
            
            element.textContent = current;
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };
        
        requestAnimationFrame(animate);
    }
    
    /**
     * Animar estatísticas
     */
    animateStats() {
        const statCards = document.querySelectorAll('.stats-card');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });
        
        statCards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
            observer.observe(card);
        });
    }
    
    /**
     * Inicializar ações rápidas
     */
    initializeQuickActions() {
        const quickActions = document.querySelectorAll('.quick-action-btn');
        
        quickActions.forEach(action => {
            action.addEventListener('click', (e) => {
                this.handleQuickAction(e, action);
            });
        });
    }
    
    /**
     * Manipular ação rápida
     */
    handleQuickAction(e, action) {
        const actionType = action.dataset.action;
        
        // Adicionar efeito de clique
        action.style.transform = 'scale(0.95)';
        setTimeout(() => {
            action.style.transform = 'scale(1)';
        }, 150);
        
        // Executar ação baseada no tipo
        switch (actionType) {
            case 'nova-os':
                window.location.href = '/ordem-servico/criar';
                break;
            case 'novo-cliente':
                window.location.href = '/cliente/criar';
                break;
            case 'relatorio':
                window.location.href = '/relatorio';
                break;
            case 'configuracoes':
                this.showSettingsModal();
                break;
            default:
                console.log('Ação não implementada:', actionType);
        }
    }
    
    /**
     * Mostrar modal de configurações
     */
    showSettingsModal() {
        // Implementar modal de configurações
        console.log('Abrindo configurações...');
    }
    
    /**
     * Inicializar atualizações em tempo real
     */
    initializeRealTimeUpdates() {
        // Atualizar estatísticas a cada 30 segundos
        this.updateInterval = setInterval(() => {
            this.updateStats();
        }, 30000);
        
        // Atualizar gráficos a cada 2 minutos
        setInterval(() => {
            this.updateCharts();
        }, 120000);
    }
    
    /**
     * Atualizar gráficos com dados simulados
     */
    updateCharts() {
        try {
            if (this.charts.receita) {
                const newData = this.charts.receita.data.datasets[0].data.map(value => 
                    Math.max(0, value + Math.floor(Math.random() * 500) - 250)
                );
                
                this.charts.receita.data.datasets[0].data = newData;
                this.charts.receita.update('none');
            }
            
            if (this.charts.produtividade) {
                const newData = this.charts.produtividade.data.datasets[0].data.map(() => 
                    Math.floor(Math.random() * 20) + 5
                );
                
                this.charts.produtividade.data.datasets[0].data = newData;
                this.charts.produtividade.update('none');
            }
        } catch (error) {
            console.error('Erro ao atualizar gráficos:', error);
            this.showNotification('Erro ao atualizar gráficos', 'danger');
        }
    }
    
    /**
     * Inicializar animações
     */
    initializeAnimations() {
        // Animar elementos quando entrarem na viewport
        const animatedElements = document.querySelectorAll('.chart-container, .data-table-container');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, { threshold: 0.1 });
        
        animatedElements.forEach(element => {
            observer.observe(element);
        });
    }
    
    /**
     * Inicializar toggle de tema
     */
    initializeThemeToggle() {
        const themeToggle = document.querySelector('#theme-toggle');
        if (!themeToggle) return;
        
        themeToggle.addEventListener('click', () => {
            this.toggleTheme();
        });
        
        // Carregar tema salvo
        this.loadTheme();
    }
    
    /**
     * Alternar tema
     */
    toggleTheme() {
        const body = document.body;
        const currentTheme = body.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        body.setAttribute('data-theme', newTheme);
        localStorage.setItem('dashboard-theme', newTheme);
        
        // Atualizar gráficos para o novo tema
        this.updateChartsTheme(newTheme);
        
        // Mostrar notificação
        this.showNotification(`Tema alterado para: ${newTheme === 'dark' ? 'Escuro' : 'Claro'}`, 'info');
    }
    
    /**
     * Carregar tema salvo
     */
    loadTheme() {
        const savedTheme = localStorage.getItem('dashboard-theme');
        if (savedTheme) {
            document.body.setAttribute('data-theme', savedTheme);
            this.updateChartsTheme(savedTheme);
        }
    }
    
    /**
     * Mostrar notificação
     */
    showNotification(message, type = 'info') {
        // Remover notificações existentes
        const existingNotifications = document.querySelectorAll('.dashboard-notification');
        existingNotifications.forEach(notification => {
            notification.remove();
        });
        
        // Criar nova notificação
        const notification = document.createElement('div');
        notification.className = `dashboard-notification ${type}`;
        notification.innerHTML = `
            <div class="p-3">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas ${this.getNotificationIcon(type)} text-${type}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">${message}</div>
                    </div>
                    <button type="button" class="btn-close btn-close-sm" onclick="this.parentElement.parentElement.parentElement.remove()"></button>
                </div>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto-remover após 5 segundos
        setTimeout(() => {
            if (notification.parentElement) {
                notification.classList.add('hiding');
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }
        }, 5000);
    }
    
    /**
     * Obter ícone da notificação
     */
    getNotificationIcon(type) {
        const icons = {
            success: 'fa-check-circle',
            warning: 'fa-exclamation-triangle',
            danger: 'fa-times-circle',
            info: 'fa-info-circle'
        };
        return icons[type] || icons.info;
    }
    
    /**
     * Mostrar loading nos gráficos
     */
    showChartLoading() {
        const chartContainers = document.querySelectorAll('.chart-container');
        chartContainers.forEach(container => {
            const chartBody = container.querySelector('.chart-body');
            if (chartBody) {
                chartBody.innerHTML = `
                    <div class="d-flex align-items-center justify-content-center h-100">
                        <div class="text-center">
                            <div class="spinner-border text-primary mb-2" role="status">
                                <span class="visually-hidden">Carregando...</span>
                            </div>
                            <div class="text-muted">Carregando gráfico...</div>
                        </div>
                    </div>
                `;
            }
        });
    }
    
    /**
     * Mostrar erro nos gráficos
     */
    showChartError(message = 'Erro ao carregar gráfico') {
        const chartContainers = document.querySelectorAll('.chart-container');
        chartContainers.forEach(container => {
            const chartBody = container.querySelector('.chart-body');
            if (chartBody) {
                chartBody.innerHTML = `
                    <div class="d-flex align-items-center justify-content-center h-100">
                        <div class="text-center text-danger">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                            <div>${message}</div>
                            <button class="btn btn-outline-primary btn-sm mt-2" onclick="window.dashboard.retryCharts()">
                                <i class="fas fa-redo me-1"></i> Tentar Novamente
                            </button>
                        </div>
                    </div>
                `;
            }
        });
    }
    
    /**
     * Tentar novamente os gráficos
     */
    retryCharts() {
        this.showNotification('Tentando carregar gráficos novamente...', 'info');
        this.initializeCharts();
    }
    
    /**
     * Verificar se os gráficos estão funcionando
     */
    checkChartsHealth() {
        if (!this.chartJsReady) {
            return false;
        }
        
        const chartIds = ['chartOrdens', 'chartReceita', 'chartProdutividade'];
        let healthyCharts = 0;
        
        chartIds.forEach(id => {
            const canvas = document.getElementById(id);
            if (canvas && this.charts[id.replace('chart', '').toLowerCase()]) {
                healthyCharts++;
            }
        });
        
        return healthyCharts === chartIds.length;
    }
    
    /**
     * Atualizar tema dos gráficos com melhor suporte
     */
    updateChartsTheme(theme) {
        const isDark = theme === 'dark';
        const textColor = isDark ? '#e2e8f0' : '#334155';
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
        const borderColor = isDark ? 'rgba(255, 255, 255, 0.2)' : 'rgba(0, 0, 0, 0.1)';
        
        Object.values(this.charts).forEach(chart => {
            try {
                if (chart.options.scales) {
                    if (chart.options.scales.y) {
                        chart.options.scales.y.grid.color = gridColor;
                        chart.options.scales.y.ticks.color = textColor;
                        chart.options.scales.y.border.color = borderColor;
                    }
                    if (chart.options.scales.x) {
                        chart.options.scales.x.grid.color = gridColor;
                        chart.options.scales.x.ticks.color = textColor;
                        chart.options.scales.x.border.color = borderColor;
                    }
                }
                
                if (chart.options.plugins.legend) {
                    chart.options.plugins.legend.labels.color = textColor;
                }
                
                chart.update('none');
            } catch (error) {
                console.warn('Erro ao atualizar tema do gráfico:', error);
            }
        });
    }
    
    /**
     * Exportar dados com melhor formatação
     */
    exportData(format) {
        const formats = {
            pdf: { name: 'PDF', icon: 'fa-file-pdf' },
            excel: { name: 'Excel', icon: 'fa-file-excel' },
            csv: { name: 'CSV', icon: 'fa-file-csv' }
        };
        
        if (!formats[format]) {
            this.showNotification('Formato não suportado', 'warning');
            return;
        }
        
        const formatInfo = formats[format];
        this.showNotification(`Exportando para ${formatInfo.name}...`, 'info');
        
        // Simular exportação
        setTimeout(() => {
            this.showNotification(`${formatInfo.name} exportado com sucesso!`, 'success');
        }, 2000);
    }
    
    /**
     * Inicializar sistema de notificações
     */
    initializeNotifications() {
        // Verificar se há notificações do sistema
        if ('Notification' in window) {
            if (Notification.permission === 'granted') {
                this.showNotification('Notificações ativadas', 'success');
            } else if (Notification.permission !== 'denied') {
                Notification.requestPermission().then(permission => {
                    if (permission === 'granted') {
                        this.showNotification('Notificações ativadas', 'success');
                    }
                });
            }
        }
    }
    
    /**
     * Verificar saúde do sistema
     */
    checkSystemHealth() {
        const health = {
            charts: this.checkChartsHealth(),
            stats: Object.keys(this.stats).length > 0,
            theme: document.body.getAttribute('data-theme') || 'light'
        };
        
        console.log('Status do sistema:', health);
        return health;
    }
    
    /**
     * Destruir dashboard com limpeza adequada
     */
    destroy() {
        try {
            if (this.updateInterval) {
                clearInterval(this.updateInterval);
            }
            
            // Destruir gráficos
            Object.values(this.charts).forEach(chart => {
                if (chart && typeof chart.destroy === 'function') {
                    chart.destroy();
                }
            });
            
            // Limpar notificações
            const notifications = document.querySelectorAll('.dashboard-notification');
            notifications.forEach(notification => notification.remove());
            
            // Remover event listeners
            window.removeEventListener('chartjs-ready', this.handleChartJsReady);
            
            this.isInitialized = false;
            this.chartJsReady = false;
            this.charts = {};
            this.stats = {};
            
            console.log('Dashboard destruído com sucesso');
        } catch (error) {
            console.error('Erro ao destruir dashboard:', error);
        }
    }
}

// Inicializar dashboard quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    // Verificar se estamos na página do dashboard
    if (document.querySelector('.dashboard-page') || document.querySelector('#chartOrdens')) {
        // Aguardar um pouco para garantir que Chart.js seja carregado
        setTimeout(() => {
            window.dashboard = new Dashboard();
        }, 100);
    }
});

// Exportar para uso global
window.Dashboard = Dashboard;
