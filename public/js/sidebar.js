/**
 * InfoCell OS - Sidebar JavaScript
 * Controle completo do menu lateral esquerdo
 * Funcionalidades: toggle, submenus, responsividade, temas
 */

class Sidebar {
    constructor() {
        this.sidebar = document.getElementById('sidebar');
        this.sidebarToggle = document.getElementById('sidebarToggle');
        this.overlay = document.querySelector('.sidebar-overlay');
        this.navItems = document.querySelectorAll('.nav-item');
        this.themeToggle = document.querySelector('.theme-toggle-btn');
        this.userMenuToggle = document.querySelector('.user-menu-toggle');
        this.userMenu = document.querySelector('.user-menu .dropdown-menu');
        
        this.isCollapsed = false;
        this.isMobile = window.innerWidth <= 1024;
        this.currentTheme = localStorage.getItem('sidebar-theme') || 'light';
        
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.setupTheme();
        this.setupActiveState();
        this.setupResponsive();
        this.setupSubmenus();
        this.setupUserMenu();
        this.setupKeyboardNavigation();
    }
    
    bindEvents() {
        // Toggle do sidebar
        if (this.sidebarToggle) {
            this.sidebarToggle.addEventListener('click', () => this.toggle());
        }
        
        // Overlay para mobile
        if (this.overlay) {
            this.overlay.addEventListener('click', () => this.hide());
        }
        
        // Toggle de tema
        if (this.themeToggle) {
            this.themeToggle.addEventListener('click', () => this.toggleTheme());
        }
        
        // Toggle de usuário
        if (this.userMenuToggle) {
            this.userMenuToggle.addEventListener('click', () => this.toggleUserMenu());
        }
        
        // Fechar menu ao clicar fora
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.user-menu')) {
                this.closeUserMenu();
            }
        });
        
        // Resize da janela
        window.addEventListener('resize', () => this.handleResize());
        
        // ESC para fechar sidebar no mobile
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isMobile && this.isVisible()) {
                this.hide();
            }
        });
    }
    
    toggle() {
        if (this.isMobile) {
            this.toggleMobile();
        } else {
            this.toggleDesktop();
        }
    }
    
    toggleDesktop() {
        this.isCollapsed = !this.isCollapsed;
        
        if (this.isCollapsed) {
            this.sidebar.classList.add('collapsed');
            this.sidebarToggle.innerHTML = '<i class="fas fa-chevron-right"></i>';
            localStorage.setItem('sidebar-collapsed', 'true');
        } else {
            this.sidebar.classList.remove('collapsed');
            this.sidebarToggle.innerHTML = '<i class="fas fa-bars"></i>';
            localStorage.setItem('sidebar-collapsed', 'false');
        }
        
        // Ajustar conteúdo principal
        this.adjustMainContent();
    }
    
    toggleMobile() {
        if (this.isVisible()) {
            this.hide();
        } else {
            this.show();
        }
    }
    
    show() {
        this.sidebar.classList.add('show');
        if (this.overlay) {
            this.overlay.classList.add('show');
        }
        document.body.style.overflow = 'hidden';
    }
    
    hide() {
        this.sidebar.classList.remove('show');
        if (this.overlay) {
            this.overlay.classList.remove('show');
        }
        document.body.style.overflow = '';
    }
    
    isVisible() {
        return this.sidebar.classList.contains('show');
    }
    
    setupTheme() {
        document.documentElement.setAttribute('data-theme', this.currentTheme);
        this.updateThemeIcon();
    }
    
    toggleTheme() {
        this.currentTheme = this.currentTheme === 'light' ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', this.currentTheme);
        localStorage.setItem('sidebar-theme', this.currentTheme);
        this.updateThemeIcon();
    }
    
    updateThemeIcon() {
        if (this.themeToggle) {
            if (this.currentTheme === 'dark') {
                this.themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
                this.themeToggle.classList.add('active');
            } else {
                this.themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
                this.themeToggle.classList.remove('active');
            }
        }
    }
    
    setupActiveState() {
        // Marcar item ativo baseado na URL atual
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.nav-link, .submenu-link');
        
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href && currentPath.includes(href.replace('index.php', ''))) {
                link.classList.add('active');
                
                // Abrir submenu se for um item de submenu
                const parentItem = link.closest('.nav-item');
                if (parentItem && link.classList.contains('submenu-link')) {
                    parentItem.classList.add('open');
                }
            }
        });
    }
    
    setupSubmenus() {
        this.navItems.forEach(item => {
            const link = item.querySelector('.nav-link');
            const submenu = item.querySelector('.submenu');
            
            if (submenu && link) {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.toggleSubmenu(item);
                });
            }
        });
    }
    
    toggleSubmenu(item) {
        const isOpen = item.classList.contains('open');
        
        // Fechar outros submenus
        this.navItems.forEach(otherItem => {
            if (otherItem !== item) {
                otherItem.classList.remove('open');
            }
        });
        
        // Toggle do submenu atual
        if (isOpen) {
            item.classList.remove('open');
        } else {
            item.classList.add('open');
        }
    }
    
    setupUserMenu() {
        if (this.userMenuToggle && this.userMenu) {
            this.userMenuToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                this.toggleUserMenu();
            });
        }
    }
    
    toggleUserMenu() {
        if (this.userMenu) {
            this.userMenu.classList.toggle('show');
        }
    }
    
    closeUserMenu() {
        if (this.userMenu) {
            this.userMenu.classList.remove('show');
        }
    }
    
    setupResponsive() {
        // Restaurar estado do sidebar
        const wasCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
        
        if (this.isMobile) {
            this.sidebar.classList.remove('collapsed');
            this.sidebar.classList.add('show');
            this.hide();
        } else {
            if (wasCollapsed) {
                this.sidebar.classList.add('collapsed');
                this.sidebarToggle.innerHTML = '<i class="fas fa-chevron-right"></i>';
                this.isCollapsed = true;
            }
        }
        
        this.adjustMainContent();
    }
    
    handleResize() {
        const wasMobile = this.isMobile;
        this.isMobile = window.innerWidth <= 1024;
        
        if (wasMobile !== this.isMobile) {
            this.setupResponsive();
        }
    }
    
    adjustMainContent() {
        const mainContent = document.querySelector('.main-content, main, .content');
        if (mainContent) {
            if (this.isCollapsed && !this.isMobile) {
                mainContent.style.marginLeft = '70px';
            } else if (!this.isMobile) {
                mainContent.style.marginLeft = '280px';
            } else {
                mainContent.style.marginLeft = '0';
            }
        }
    }
    
    setupKeyboardNavigation() {
        // Navegação por teclado
        this.sidebar.addEventListener('keydown', (e) => {
            const focusableElements = this.sidebar.querySelectorAll(
                'a[href], button, input, textarea, select, [tabindex]:not([tabindex="-1"])'
            );
            
            let currentIndex = Array.from(focusableElements).indexOf(document.activeElement);
            
            switch (e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    currentIndex = (currentIndex + 1) % focusableElements.length;
                    focusableElements[currentIndex].focus();
                    break;
                    
                case 'ArrowUp':
                    e.preventDefault();
                    currentIndex = currentIndex === 0 ? focusableElements.length - 1 : currentIndex - 1;
                    focusableElements[currentIndex].focus();
                    break;
                    
                case 'Enter':
                case ' ':
                    if (document.activeElement.classList.contains('nav-link')) {
                        e.preventDefault();
                        const parentItem = document.activeElement.closest('.nav-item');
                        if (parentItem && parentItem.querySelector('.submenu')) {
                            this.toggleSubmenu(parentItem);
                        }
                    }
                    break;
            }
        });
    }
    
    // Métodos públicos para uso externo
    collapse() {
        if (!this.isMobile) {
            this.toggleDesktop();
        }
    }
    
    expand() {
        if (!this.isMobile && this.isCollapsed) {
            this.toggleDesktop();
        }
    }
    
    refresh() {
        this.setupActiveState();
        this.setupResponsive();
    }
    
    // Método para adicionar notificações
    addNotification(itemSelector, count) {
        const item = document.querySelector(itemSelector);
        if (item) {
            let badge = item.querySelector('.nav-badge');
            if (!badge) {
                badge = document.createElement('span');
                badge.className = 'nav-badge';
                item.appendChild(badge);
            }
            badge.textContent = count;
        }
    }
    
    // Método para remover notificações
    removeNotification(itemSelector) {
        const item = document.querySelector(itemSelector);
        if (item) {
            const badge = item.querySelector('.nav-badge');
            if (badge) {
                badge.remove();
            }
        }
    }
}

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    window.sidebar = new Sidebar();
});

// Exportar para uso em outros módulos
if (typeof module !== 'undefined' && module.exports) {
    module.exports = Sidebar;
}
