<?php

/**
 * Helper para Ordem de Serviço
 * Funções auxiliares para manipulação de dados de OS
 */

/**
 * Retorna a cor CSS para o status da OS
 * @param string $status Status da ordem de serviço
 * @return string Classe CSS para cor
 */
function getStatusColor($status) {
    $statusColors = [
        'aberta' => 'primary',
        'em_andamento' => 'warning',
        'aguardando_pecas' => 'info',
        'aguardando_aprovacao' => 'secondary',
        'concluida' => 'success',
        'cancelada' => 'danger'
    ];
    
    return $statusColors[$status] ?? 'secondary';
}

/**
 * Retorna o nome formatado do status
 * @param string $status Status da ordem de serviço
 * @return string Nome formatado do status
 */
function getStatusName($status) {
    $statusNames = [
        'aberta' => 'Aberta',
        'em_andamento' => 'Em Andamento',
        'aguardando_pecas' => 'Aguardando Peças',
        'aguardando_aprovacao' => 'Aguardando Aprovação',
        'concluida' => 'Concluída',
        'cancelada' => 'Cancelada'
    ];
    
    return $statusNames[$status] ?? ucfirst($status);
}

/**
 * Retorna o ícone FontAwesome para o status
 * @param string $status Status da ordem de serviço
 * @return string Classe do ícone FontAwesome
 */
function getStatusIcon($status) {
    $statusIcons = [
        'aberta' => 'fas fa-folder-open',
        'em_andamento' => 'fas fa-tools',
        'aguardando_pecas' => 'fas fa-boxes',
        'aguardando_aprovacao' => 'fas fa-clock',
        'concluida' => 'fas fa-check-circle',
        'cancelada' => 'fas fa-times-circle'
    ];
    
    return $statusIcons[$status] ?? 'fas fa-question-circle';
}

/**
 * Retorna a classe CSS para o badge do status
 * @param string $status Status da ordem de serviço
 * @return string Classe CSS completa para o badge
 */
function getStatusBadgeClass($status) {
    $color = getStatusColor($status);
    return "badge bg-{$color}";
}

/**
 * Verifica se o status permite edição
 * @param string $status Status da ordem de serviço
 * @return bool True se permite edição
 */
function canEditStatus($status) {
    $editableStatuses = ['aberta', 'em_andamento', 'aguardando_pecas', 'aguardando_aprovacao'];
    return in_array($status, $editableStatuses);
}

/**
 * Retorna a próxima etapa sugerida baseada no status atual
 * @param string $status Status atual da ordem de serviço
 * @return string Próxima etapa sugerida
 */
function getNextStep($status) {
    $nextSteps = [
        'aberta' => 'Iniciar análise técnica',
        'em_andamento' => 'Diagnosticar problema',
        'aguardando_pecas' => 'Aguardar chegada das peças',
        'aguardando_aprovacao' => 'Aguardar aprovação do cliente',
        'concluida' => 'Entregar dispositivo ao cliente',
        'cancelada' => 'N/A'
    ];
    
    return $nextSteps[$status] ?? 'Verificar status atual';
}

/**
 * Calcula o tempo decorrido desde a criação da OS
 * @param string $createdAt Data de criação (formato MySQL)
 * @return string Tempo decorrido formatado
 */
function getTimeElapsed($createdAt) {
    $created = new DateTime($createdAt);
    $now = new DateTime();
    $interval = $created->diff($now);
    
    if ($interval->days > 0) {
        return $interval->days . ' dia(s)';
    } elseif ($interval->h > 0) {
        return $interval->h . ' hora(s)';
    } elseif ($interval->i > 0) {
        return $interval->i . ' minuto(s)';
    } else {
        return 'Agora mesmo';
    }
}

/**
 * Formata valor monetário para exibição
 * @param float|string $value Valor a ser formatado
 * @return string Valor formatado
 */
function formatCurrency($value) {
    if (empty($value)) {
        return 'R$ 0,00';
    }
    
    $value = floatval($value);
    return 'R$ ' . number_format($value, 2, ',', '.');
}

/**
 * Retorna a prioridade formatada
 * @param string $priority Prioridade da OS
 * @return string Prioridade formatada
 */
function getPriorityName($priority) {
    $priorities = [
        'baixa' => 'Baixa',
        'media' => 'Média',
        'alta' => 'Alta',
        'urgente' => 'Urgente'
    ];
    
    return $priorities[$priority] ?? ucfirst($priority);
}

/**
 * Retorna a cor da prioridade
 * @param string $priority Prioridade da OS
 * @return string Classe CSS para cor
 */
function getPriorityColor($priority) {
    $priorityColors = [
        'baixa' => 'success',
        'media' => 'info',
        'alta' => 'warning',
        'urgente' => 'danger'
    ];
    
    return $priorityColors[$priority] ?? 'secondary';
}
