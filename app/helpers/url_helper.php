<?php
// Redirecionar simples
function redirect($page)
{
    $redirectUrl = URLROOT . '/' . $page;
    error_log("Redirecting to: " . $redirectUrl);
    
    // Limpar qualquer saída anterior
    if (ob_get_length()) {
        ob_clean();
    }
    
    // Garantir que não há saída antes do header
    if (!headers_sent()) {
        header('Location: ' . $redirectUrl);
        exit();
    } else {
        // Se headers já foram enviados, usar JavaScript
        echo '<script>window.location.href = "' . $redirectUrl . '";</script>';
        echo '<noscript><meta http-equiv="refresh" content="0;url=' . $redirectUrl . '"></noscript>';
        exit();
    }
}

// Flash message helper simplificado (sem sessão)
function flash($name = '', $message = '', $class = 'alert alert-success')
{
    // Como removemos sessões, vamos apenas ignorar flash messages por enquanto
    // Em uma implementação futura, poderia usar cookies ou localStorage
    return;
}

// Verificar se está logado via cookie
// Nota: Esta função está definida em cookie_helper.php

// Formatar data brasileira
if (!function_exists('formatarData')) {
    function formatarData($data)
    {
        if ($data && $data != '0000-00-00' && $data != '0000-00-00 00:00:00') {
            return date('d/m/Y', strtotime($data));
        }
        return '-';
    }
}

// Formatar data e hora brasileira
function formatarDataHora($data)
{
    if ($data && $data != '0000-00-00 00:00:00') {
        return date('d/m/Y H:i', strtotime($data));
    }
    return '-';
}

// Formatar valor monetário
function formatarValor($valor)
{
    if ($valor && $valor > 0) {
        return 'R$ ' . number_format($valor, 2, ',', '.');
    }
    return 'R$ 0,00';
}

// Truncar texto
function truncarTexto($texto, $limite = 50)
{
    if (strlen($texto) > $limite) {
        return substr($texto, 0, $limite) . '...';
    }
    return $texto;
}

// Status badge
function statusBadge($status)
{
    $badges = [
        'aberta' => 'badge bg-warning text-dark',
        'emandamento' => 'badge bg-info text-white',
        'aguardando_peca' => 'badge bg-secondary text-white',
        'aguardando_cliente' => 'badge bg-primary text-white',
        'concluida' => 'badge bg-success text-white',
        'cancelada' => 'badge bg-danger text-white'
    ];
    
    $labels = [
        'aberta' => 'Aberta',
        'emandamento' => 'Em Andamento',
        'aguardando_peca' => 'Aguardando Peça',
        'aguardando_cliente' => 'Aguardando Cliente',
        'concluida' => 'Concluída',
        'cancelada' => 'Cancelada'
    ];
    
    $class = isset($badges[$status]) ? $badges[$status] : 'badge bg-secondary text-white';
    $label = isset($labels[$status]) ? $labels[$status] : ucfirst($status);
    
    return '<span class="' . $class . '">' . $label . '</span>';
}

// Prioridade badge
function prioridadeBadge($prioridade)
{
    $badges = [
        'baixa' => 'badge bg-success text-white',
        'media' => 'badge bg-warning text-dark',
        'alta' => 'badge bg-danger text-white',
        'urgente' => 'badge bg-dark text-white'
    ];
    
    $labels = [
        'baixa' => 'Baixa',
        'media' => 'Média',
        'alta' => 'Alta',
        'urgente' => 'Urgente'
    ];
    
    $class = isset($badges[$prioridade]) ? $badges[$prioridade] : 'badge bg-secondary text-white';
    $label = isset($labels[$prioridade]) ? $labels[$prioridade] : ucfirst($prioridade);
    
    return '<span class="' . $class . '">' . $label . '</span>';
}

// Gerar número da OS
function gerarNumeroOS($id)
{
    if (is_null($id)) {
        $id = '';
    }
    return str_pad((string)$id, 6, '0', STR_PAD_LEFT);
}
