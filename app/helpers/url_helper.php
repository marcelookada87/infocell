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

// Flash message helper
function flash($name = '', $message = '', $class = 'alert alert-success')
{
    if (!empty($name)) {
        if (!empty($message) && empty($_SESSION[$name])) {
            if (!empty($_SESSION[$name])) {
                unset($_SESSION[$name]);
            }
            
            if (!empty($_SESSION[$name . '_class'])) {
                unset($_SESSION[$name . '_class']);
            }
            
            $_SESSION[$name] = $message;
            $_SESSION[$name . '_class'] = $class;
        } elseif (empty($message) && !empty($_SESSION[$name])) {
            $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
            echo '<div class="' . $class . '" id="msg-flash">' . $_SESSION[$name] . '</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_class']);
        }
    }
}

// Verificar se está logado (sessão ou cookie)
function isLoggedIn()
{
    // Primeiro verifica se há sessão ativa
    if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
        return true;
    }
    
    // Se não há sessão, verifica se há cookie válido
    if (function_exists('isLoggedInByCookie')) {
        return isLoggedInByCookie();
    }
    
    return false;
}

// Formatar data brasileira
function formatarData($data)
{
    if ($data && $data != '0000-00-00' && $data != '0000-00-00 00:00:00') {
        return date('d/m/Y', strtotime($data));
    }
    return '-';
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
        'em_andamento' => 'badge bg-info text-white',
        'aguardando_peca' => 'badge bg-secondary text-white',
        'aguardando_cliente' => 'badge bg-primary text-white',
        'concluida' => 'badge bg-success text-white',
        'cancelada' => 'badge bg-danger text-white'
    ];
    
    $labels = [
        'aberta' => 'Aberta',
        'em_andamento' => 'Em Andamento',
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
    return str_pad($id, 6, '0', STR_PAD_LEFT);
}

