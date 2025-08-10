<?php
// Teste direto do dashboard sem MVC

define('APPROOT', dirname(__DIR__));
define('URLROOT', 'http://localhost/infocell');
define('SITENAME', 'InfoCell - Dashboard Teste');

// Simular dados de sessão para teste
session_start();
$_SESSION['user_id'] = 1;
$_SESSION['user_name'] = 'Admin Teste';
$_SESSION['user_email'] = 'admin@teste.com';
$_SESSION['user_type'] = 'admin';

// Dados mock para dashboard
$data = [
    'total_ordens' => 15,
    'ordens_abertas' => 5,
    'ordens_andamento' => 3,
    'ordens_concluidas' => 7,
    'total_clientes' => 25,
    'ordens_recentes' => [],
    'dispositivos_mais_reparados' => [],
    'receita_mensal' => 1250.50,
    'user_name' => 'Admin Teste'
];

// Funções auxiliares
function formatarValor($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

function formatarData($data) {
    return date('d/m/Y', strtotime($data));
}

function gerarNumeroOS($id) {
    return str_pad($id, 6, '0', STR_PAD_LEFT);
}

function statusBadge($status) {
    return '<span class="badge badge-primary">' . ucfirst($status) . '</span>';
}

function prioridadeBadge($prioridade) {
    return '<span class="badge badge-warning">' . ucfirst($prioridade) . '</span>';
}

// Incluir helpers
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Carregar a view do dashboard
include APPROOT . '/app/views/dashboard/index.php';
?>
