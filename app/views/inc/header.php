<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo SITENAME; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="<?php echo URLROOT; ?>/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="<?php echo URLROOT; ?>/css/fontawesome-all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="<?php echo URLROOT; ?>/css/style.css" rel="stylesheet">
    
    <!-- Relatórios CSS -->
    <link href="<?php echo URLROOT; ?>/css/relatorios.css" rel="stylesheet">
    
    <!-- Forçar tema claro -->
    <style>
        html, body {
            background-color: #f8f9fa !important;
            color: #212529 !important;
        }
        .main-content {
            background-color: #f8f9fa !important;
            color: #212529 !important;
        }
        .card {
            background-color: #ffffff !important;
            color: #212529 !important;
        }
        .card-body {
            background-color: #ffffff !important;
            color: #212529 !important;
        }
        .container-fluid {
            background-color: transparent !important;
        }
        h1, h2, h3, h4, h5, h6 {
            color: #212529 !important;
        }
        .form-label, label {
            color: #495057 !important;
        }
        .text-muted {
            color: #6c757d !important;
        }
        /* Forçar cores escuras em textos */
        * {
            color: inherit !important;
        }
        
        /* NAVBAR - Manter texto branco na navbar azul */
        .navbar-dark .navbar-brand,
        .navbar-dark .navbar-nav .nav-link,
        .navbar-dark .navbar-nav .dropdown-toggle,
        .navbar-dark .navbar-toggler-icon {
            color: white !important;
        }
        
        .navbar-dark .navbar-brand *,
        .navbar-dark .navbar-nav .nav-link *,
        .navbar-dark .navbar-nav .dropdown-toggle * {
            color: white !important;
        }
        
        /* Dropdown menus - manter texto escuro */
        .dropdown-menu .dropdown-item {
            color: #212529 !important;
        }
        
        .dropdown-menu .dropdown-item * {
            color: #212529 !important;
        }
        
        /* Sidebar - manter texto escuro */
        .sidebar * {
            color: inherit;
        }
    </style>
    
    <!-- Chart.js -->
    <script src="<?php echo URLROOT; ?>/js/chart.min.js"></script>
    
    <!-- Favicon (adicione seu favicon.ico na pasta public/img/) -->
    <!-- <link rel="icon" type="image/x-icon" href="<?php echo URLROOT; ?>/img/favicon.ico"> -->
</head>
<body>
    <?php if (isLoggedIn()): ?>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo URLROOT; ?>/dashboard">
                <i class="fas fa-mobile-alt"></i>
                <strong>InfoCell OS</strong>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo URLROOT; ?>/dashboard">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-clipboard-list"></i> Ordens de Serviço
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/ordem-servico">
                                <i class="fas fa-list"></i> Listar OS
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/ordem-servico/criar">
                                <i class="fas fa-plus"></i> Nova OS
                            </a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-users"></i> Clientes
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/cliente">
                                <i class="fas fa-list"></i> Listar Clientes
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/cliente/criar">
                                <i class="fas fa-user-plus"></i> Novo Cliente
                            </a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-chart-bar"></i> Relatórios
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/relatorio">
                                <i class="fas fa-chart-line"></i> Dashboard
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/relatorio/ordens">
                                <i class="fas fa-file-alt"></i> Relatório de OS
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/relatorio/financeiro">
                                <i class="fas fa-dollar-sign"></i> Financeiro
                            </a></li>
                        </ul>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i> 
                            <?php echo getLoggedInUserName(); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/dashboard/perfil">
                                <i class="fas fa-user-edit"></i> Meu Perfil
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/auth/logout">
                                <i class="fas fa-sign-out-alt"></i> Sair
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Sidebar para telas maiores -->
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="<?php echo URLROOT; ?>/dashboard">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo URLROOT; ?>/ordem-servico">
                                <i class="fas fa-clipboard-list"></i>
                                Ordens de Serviço
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo URLROOT; ?>/cliente">
                                <i class="fas fa-users"></i>
                                Clientes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo URLROOT; ?>/relatorio">
                                <i class="fas fa-chart-bar"></i>
                                Relatórios
                            </a>
                        </li>
                    </ul>
                    
                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>Ações Rápidas</span>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo URLROOT; ?>/ordem-servico/criar">
                                <i class="fas fa-plus"></i>
                                Nova OS
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo URLROOT; ?>/cliente/criar">
                                <i class="fas fa-user-plus"></i>
                                Novo Cliente
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <?php endif; ?>

