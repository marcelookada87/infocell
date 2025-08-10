/**
 * InfoCell OS - Sidebar Improvements JavaScript
 * Melhorias adicionais para o menu lateral esquerdo
 * Funcionalidades: tooltips, notificações avançadas, temas automáticos
 */

class SidebarImprovements {
    constructor() {
        this.sidebar = document.getElementById('sidebar');
        this.tooltips = new Map();
        this.notifications = new Map();
        this.autoTheme = true;
        
        this.init();
    }
    
    init() {
        this.setupTooltips();
        this.setupAutoTheme();
        this.setupNotifications();
        this.setupKeyboardShortcuts();
        this.setupPerformanceOptimizations();
        this.setupAccessibility();
    }
    
    /**
     * Configurar tooltips para itens colapsados
     */
    setupTooltips() {
        if (!this.sidebar) return;
        
        const navItems = this.sidebar.querySelectorAll('.nav-item');
        
        navItems.forEach(item => {
            const link = item.querySelector('.nav-link');
            const text = link?.querySelector('.nav-text')?.textContent;
            
            if (text) {
                item.setAttribute('data-title', text);
            }
        });
        
        // Observar mudanças no estado colapsado
        this.observeSidebarState();
    }
    
    /**
     * Observar mudanças no estado do sidebar
     */
    observeSidebarState() {
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    this.updateTooltips();
                }
            });
        });
        
        if (this.sidebar) {
            observer.observe(this.sidebar, {
                attributes: true,
                attributeFilter: ['class']
            });
        }
    }
    
    /**
     * Atualizar tooltips baseado no estado atual
     */
    updateTooltips() {
        const isCollapsed = this.sidebar?.classList.contains('collapsed');
        
        if (isCollapsed) {
            this.enableTooltips();
        } else {
            this.disableTooltips();
        }
    }
    
    /**
     * Habilitar tooltips
     */
    enableTooltips() {
        const navItems = this.sidebar?.querySelectorAll('.nav-item');
        
        navItems?.forEach(item => {
            item.addEventListener('mouseenter', this.showTooltip.bind(this));
            item.addEventListener('mouseleave', this.hideTooltip.bind(this));
        });
    }
    
    /**
     * Desabilitar tooltips
     */
    disableTooltips() {
        const navItems = this.sidebar?.querySelectorAll('.nav-item');
        
        navItems?.forEach(item => {
            item.removeEventListener('mouseenter', this.showTooltip.bind(this));
            item.removeEventListener('mouseleave', this.hideTooltip.bind(this));
        });
        
        this.hideAllTooltips();
    }
    
    /**
     * Mostrar tooltip
     */
    showTooltip(event) {
        const item = event.currentTarget;
        const title = item.getAttribute('data-title');
        
        if (!title) return;
        
        const tooltip = document.createElement('div');
        tooltip.className = 'sidebar-tooltip';
        tooltip.textContent = title;
        tooltip.style.cssText = `
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            padding: 0.5rem 0.75rem;
            border-radius: 4px;
            font-size: 0.8rem;
            white-space: nowrap;
            z-index: 1000;
            margin-left: 0.5rem;
            box-shadow: var(--sidebar-shadow);
            border: 1px solid var(--sidebar-border);
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.2s ease;
        `;
        
        item.style.position = 'relative';
        item.appendChild(tooltip);
        
        // Animar entrada
        setTimeout(() => {
            tooltip.style.opacity = '1';
        }, 10);
        
        this.tooltips.set(item, tooltip);
    }
    
    /**
     * Ocultar tooltip
     */
    hideTooltip(event) {
        const item = event.currentTarget;
        const tooltip = this.tooltips.get(item);
        
        if (tooltip) {
            tooltip.style.opacity = '0';
            setTimeout(() => {
                if (tooltip.parentNode) {
                    tooltip.parentNode.removeChild(tooltip);
                }
                this.tooltips.delete(item);
            }, 200);
        }
    }
    
    /**
     * Ocultar todos os tooltips
     */
    hideAllTooltips() {
        this.tooltips.forEach(tooltip => {
            if (tooltip.parentNode) {
                tooltip.parentNode.removeChild(tooltip);
            }
        });
        this.tooltips.clear();
    }
    
    /**
     * Configurar tema automático baseado na preferência do sistema
     */
    setupAutoTheme() {
        if (!this.autoTheme) return;
        
        // Detectar preferência do sistema
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');
        const prefersLight = window.matchMedia('(prefers-color-scheme: light)');
        
        // Aplicar tema inicial
        this.applySystemTheme();
        
        // Observar mudanças na preferência do sistema
        prefersDark.addEventListener('change', () => this.applySystemTheme());
        prefersLight.addEventListener('change', () => this.applySystemTheme());
    }
    
    /**
     * Aplicar tema do sistema
     */
    applySystemTheme() {
        if (!this.sidebar) return;
        
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const currentTheme = this.sidebar.getAttribute('data-theme');
        
        if (prefersDark && currentTheme !== 'dark') {
            this.sidebar.setAttribute('data-theme', 'dark');
        } else if (!prefersDark && currentTheme !== 'light') {
            this.sidebar.setAttribute('data-theme', 'light');
        }
    }
    
    /**
     * Configurar sistema de notificações avançado
     */
    setupNotifications() {
        // Sistema de notificações em tempo real
        this.setupRealtimeNotifications();
        
        // Sistema de notificações por tipo
        this.setupNotificationTypes();
    }
    
    /**
     * Configurar notificações em tempo real
     */
    setupRealtimeNotifications() {
        // Simular notificações em tempo real (em produção, usar WebSocket)
        setInterval(() => {
            this.checkForNewNotifications();
        }, 30000); // Verificar a cada 30 segundos
    }
    
    /**
     * Verificar novas notificações
     */
    checkForNewNotifications() {
        // Simular verificação de novas notificações
        const hasNewNotifications = Math.random() > 0.7;
        
        if (hasNewNotifications) {
            this.addNotification('.nav-item:nth-child(1)', Math.floor(Math.random() * 10) + 1);
        }
    }
    
    /**
     * Configurar tipos de notificações
     */
    setupNotificationTypes() {
        // Adicionar classes CSS para diferentes tipos de notificações
        this.addNotificationType('urgent', 'danger');
        this.addNotificationType('warning', 'warning');
        this.addNotificationType('info', 'info');
    }
    
    /**
     * Adicionar tipo de notificação
     */
    addNotificationType(type, className) {
        const style = document.createElement('style');
        style.textContent = `
            .nav-badge.${type} {
                background: var(--bs-${className}) !important;
                animation: pulse-${type} 1.5s infinite;
            }
            
            @keyframes pulse-${type} {
                0%, 100% { transform: scale(1); opacity: 1; }
                50% { transform: scale(1.2); opacity: 0.8; }
            }
        `;
        document.head.appendChild(style);
    }
    
    /**
     * Configurar atalhos de teclado
     */
    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ctrl/Cmd + B para toggle do sidebar
            if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
                e.preventDefault();
                if (window.sidebar) {
                    window.sidebar.toggle();
                }
            }
            
            // Ctrl/Cmd + Shift + T para toggle de tema
            if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'T') {
                e.preventDefault();
                if (window.sidebar) {
                    window.sidebar.toggleTheme();
                }
            }
            
            // ESC para fechar sidebar no mobile
            if (e.key === 'Escape') {
                if (window.sidebar && window.sidebar.isMobile && window.sidebar.isVisible()) {
                    window.sidebar.hide();
                }
            }
        });
    }
    
    /**
     * Configurar otimizações de performance
     */
    setupPerformanceOptimizations() {
        // Debounce para eventos de resize
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                this.handleResizeOptimized();
            }, 100);
        });
        
        // Intersection Observer para lazy loading de elementos
        this.setupIntersectionObserver();
    }
    
    /**
     * Handler otimizado para resize
     */
    handleResizeOptimized() {
        // Atualizar apenas quando necessário
        if (this.sidebar) {
            this.updateResponsiveState();
        }
    }
    
    /**
     * Atualizar estado responsivo
     */
    updateResponsiveState() {
        const isMobile = window.innerWidth <= 1024;
        const isTablet = window.innerWidth <= 1200 && window.innerWidth > 1024;
        
        if (this.sidebar) {
            this.sidebar.classList.toggle('tablet-view', isTablet);
            this.sidebar.classList.toggle('mobile-view', isMobile);
        }
    }
    
    /**
     * Configurar Intersection Observer
     */
    setupIntersectionObserver() {
        if (!('IntersectionObserver' in window)) return;
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, {
            threshold: 0.1
        });
        
        // Observar elementos do sidebar
        const sidebarElements = this.sidebar?.querySelectorAll('.nav-item, .submenu');
        sidebarElements?.forEach(element => {
            observer.observe(element);
        });
    }
    
    /**
     * Configurar melhorias de acessibilidade
     */
    setupAccessibility() {
        // Adicionar atributos ARIA
        this.addAriaAttributes();
        
        // Melhorar navegação por teclado
        this.enhanceKeyboardNavigation();
        
        // Adicionar suporte para leitores de tela
        this.addScreenReaderSupport();
    }
    
    /**
     * Adicionar atributos ARIA
     */
    addAriaAttributes() {
        if (!this.sidebar) return;
        
        this.sidebar.setAttribute('role', 'navigation');
        this.sidebar.setAttribute('aria-label', 'Menu de Navegação Principal');
        
        const navItems = this.sidebar.querySelectorAll('.nav-item');
        navItems.forEach((item, index) => {
            const link = item.querySelector('.nav-link');
            const submenu = item.querySelector('.submenu');
            
            if (link) {
                link.setAttribute('aria-expanded', submenu ? 'false' : undefined);
                link.setAttribute('aria-haspopup', submenu ? 'true' : undefined);
            }
            
            if (submenu) {
                submenu.setAttribute('role', 'menu');
                submenu.setAttribute('aria-label', `${link?.textContent} submenu`);
            }
        });
    }
    
    /**
     * Melhorar navegação por teclado
     */
    enhanceKeyboardNavigation() {
        if (!this.sidebar) return;
        
        const focusableElements = this.sidebar.querySelectorAll(
            'a[href], button, input, textarea, select, [tabindex]:not([tabindex="-1"])'
        );
        
        focusableElements.forEach((element, index) => {
            element.addEventListener('keydown', (e) => {
                switch (e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        const nextIndex = (index + 1) % focusableElements.length;
                        focusableElements[nextIndex].focus();
                        break;
                        
                    case 'ArrowUp':
                        e.preventDefault();
                        const prevIndex = index === 0 ? focusableElements.length - 1 : index - 1;
                        focusableElements[prevIndex].focus();
                        break;
                        
                    case 'Home':
                        e.preventDefault();
                        focusableElements[0].focus();
                        break;
                        
                    case 'End':
                        e.preventDefault();
                        focusableElements[focusableElements.length - 1].focus();
                        break;
                }
            });
        });
    }
    
    /**
     * Adicionar suporte para leitores de tela
     */
    addScreenReaderSupport() {
        if (!this.sidebar) return;
        
        // Adicionar live regions para mudanças dinâmicas
        const liveRegion = document.createElement('div');
        liveRegion.setAttribute('aria-live', 'polite');
        liveRegion.setAttribute('aria-atomic', 'true');
        liveRegion.className = 'sr-only';
        liveRegion.style.cssText = 'position: absolute; left: -10000px; width: 1px; height: 1px; overflow: hidden;';
        
        this.sidebar.appendChild(liveRegion);
        
        // Função para anunciar mudanças
        this.announceChange = (message) => {
            liveRegion.textContent = message;
            setTimeout(() => {
                liveRegion.textContent = '';
            }, 1000);
        };
    }
    
    /**
     * Métodos públicos para uso externo
     */
    
    /**
     * Adicionar notificação com tipo
     */
    addNotificationWithType(itemSelector, count, type = 'default') {
        const item = document.querySelector(itemSelector);
        if (item) {
            let badge = item.querySelector('.nav-badge');
            if (!badge) {
                badge = document.createElement('span');
                badge.className = 'nav-badge';
                item.appendChild(badge);
            }
            
            badge.textContent = count;
            badge.className = `nav-badge ${type}`;
            
            // Anunciar para leitores de tela
            if (this.announceChange) {
                this.announceChange(`Nova notificação: ${count} itens`);
            }
        }
    }
    
    /**
     * Alternar tema automático
     */
    toggleAutoTheme() {
        this.autoTheme = !this.autoTheme;
        
        if (this.autoTheme) {
            this.setupAutoTheme();
        } else {
            // Manter tema atual
            const currentTheme = this.sidebar?.getAttribute('data-theme') || 'light';
            this.sidebar?.setAttribute('data-theme', currentTheme);
        }
    }
    
    /**
     * Ativar modo debug
     */
    enableDebug() {
        if (this.sidebar) {
            this.sidebar.classList.add('debug');
            this.sidebar.setAttribute('data-state', 'debug');
        }
    }
    
    /**
     * Desativar modo debug
     */
    disableDebug() {
        if (this.sidebar) {
            this.sidebar.classList.remove('debug');
            this.sidebar.removeAttribute('data-state');
        }
    }
    
    /**
     * Atualizar todas as funcionalidades
     */
    refresh() {
        this.setupTooltips();
        this.updateResponsiveState();
        this.addAriaAttributes();
    }
}

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    window.sidebarImprovements = new SidebarImprovements();
});

// Exportar para uso em outros módulos
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SidebarImprovements;
}
