<?php
/**
 * Sidebar Lateral Esquerdo - InfoCell OS
 * Menu de navegação principal com design moderno e responsivo
 */
?>

<!-- Sidebar Lateral Esquerdo -->
<aside class="sidebar" id="sidebar">
    <!-- Header do Sidebar -->
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <div class="brand-icon">
                <i class="fas fa-mobile-alt"></i>
            </div>
            <div class="brand-text">
                <span class="brand-title">InfoCell OS</span>
                <small class="brand-subtitle">Sistema de OS</small>
            </div>
        </div>
        <button class="sidebar-toggle" id="sidebarToggle" type="button">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Navegação do Usuário -->
    <div class="sidebar-user">
        <div class="user-avatar">
            <i class="fas fa-user-circle"></i>
        </div>
        <div class="user-info">
            <span class="user-name">
                <?php 
                $userName = getLoggedInUserName();
                echo $userName ? $userName : 'Usuário'; 
                ?>
            </span>
            <small class="user-role">
                <?php 
                $userType = getLoggedInUserType();
                echo $userType ? ucfirst($userType) : 'Usuário'; 
                ?>
            </small>
        </div>
        <div class="user-menu">
            <button class="user-menu-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-ellipsis-v"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="<?php echo URLROOT; ?>/dashboard/perfil">
                        <i class="fas fa-user-edit me-2"></i>
                        <span>Meu Perfil</span>
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item text-danger" href="<?php echo URLROOT; ?>/auth/logout">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        <span>Sair</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Menu de Navegação -->
    <nav class="sidebar-nav">
        <ul class="nav-menu">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link <?php echo isCurrentPage('dashboard') ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/dashboard">
                    <div class="nav-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <span class="nav-text">Dashboard</span>
                    <div class="nav-badge" data-badge="new" style="display: none;"></div>
                </a>
            </li>

            <!-- Ordens de Serviço -->
            <li class="nav-item has-submenu <?php echo isCurrentPage('ordem-servico') ? 'open' : ''; ?>">
                <a class="nav-link" href="#" data-toggle="submenu">
                    <div class="nav-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <span class="nav-text">Ordens de Serviço</span>
                    <div class="nav-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
                <ul class="submenu">
                    <li>
                        <a class="submenu-link <?php echo isCurrentPage('ordem-servico', 'index') ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/ordem-servico">
                            <i class="fas fa-list me-2"></i>
                            <span>Listar OS</span>
                        </a>
                    </li>
                    <li>
                        <a class="submenu-link <?php echo isCurrentPage('ordem-servico', 'criar') ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/ordem-servico/criar">
                            <i class="fas fa-plus me-2"></i>
                            <span>Nova OS</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Clientes -->
            <li class="nav-item has-submenu <?php echo isCurrentPage('cliente') ? 'open' : ''; ?>">
                <a class="nav-link" href="#" data-toggle="submenu">
                    <div class="nav-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <span class="nav-text">Clientes</span>
                    <div class="nav-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
                <ul class="submenu">
                    <li>
                        <a class="submenu-link <?php echo isCurrentPage('cliente', 'index') ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/cliente">
                            <i class="fas fa-list me-2"></i>
                            <span>Listar Clientes</span>
                        </a>
                    </li>
                    <li>
                        <a class="submenu-link <?php echo isCurrentPage('cliente', 'criar') ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/cliente/criar">
                            <i class="fas fa-user-plus me-2"></i>
                            <span>Novo Cliente</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Relatórios -->
            <li class="nav-item has-submenu <?php echo isCurrentPage('relatorio') ? 'open' : ''; ?>">
                <a class="nav-link" href="#" data-toggle="submenu">
                    <div class="nav-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <span class="nav-text">Relatórios</span>
                    <div class="nav-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
                <ul class="submenu">
                    <li>
                        <a class="submenu-link <?php echo isCurrentPage('relatorio', 'index') ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/relatorio">
                            <i class="fas fa-chart-line me-2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a class="submenu-link <?php echo isCurrentPage('relatorio', 'ordens') ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/relatorio/ordens">
                            <i class="fas fa-file-alt me-2"></i>
                            <span>Relatório de OS</span>
                        </a>
                    </li>
                    <li>
                        <a class="submenu-link <?php echo isCurrentPage('relatorio', 'financeiro') ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/relatorio/financeiro">
                            <i class="fas fa-dollar-sign me-2"></i>
                            <span>Financeiro</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Separador -->
            <li class="nav-separator">
                <span>Ferramentas</span>
            </li>

            <!-- Configurações -->
            <li class="nav-item">
                <a class="nav-link <?php echo isCurrentPage('configuracoes') ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/configuracoes">
                    <div class="nav-icon">
                        <i class="fas fa-cog"></i>
                    </div>
                    <span class="nav-text">Configurações</span>
                </a>
            </li>

            <!-- Suporte -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLROOT; ?>/suporte">
                    <div class="nav-icon">
                        <i class="fas fa-life-ring"></i>
                    </div>
                    <span class="nav-text">Suporte</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Footer do Sidebar -->
    <div class="sidebar-footer">
        <div class="sidebar-version">
            <span class="version-text">v1.0.0</span>
        </div>
        <div class="sidebar-theme-toggle">
            <button class="theme-toggle-btn" id="themeToggle" type="button" title="Alternar Tema">
                <i class="fas fa-moon"></i>
            </button>
        </div>
    </div>
</aside>

<!-- Overlay para Mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<?php
/**
 * Função para verificar se é a página atual
 */
function isCurrentPage($controller, $method = null) {
    $currentUrl = $_GET['url'] ?? '';
    $urlParts = explode('/', trim($currentUrl, '/'));
    
    if (empty($urlParts[0])) return false;
    
    $currentController = $urlParts[0];
    $currentMethod = isset($urlParts[1]) ? $urlParts[1] : 'index';
    
    if ($method) {
        return $currentController === $controller && $currentMethod === $method;
    }
    
    return $currentController === $controller;
}
?>
