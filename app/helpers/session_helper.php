<?php
// Iniciar sessão apenas se não foi iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar se é admin
function isAdmin()
{
    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin') {
        return true;
    } else {
        return false;
    }
}

// Verificar se o usuário está logado (compatibilidade com sistema de cookies e sessões)
function isLoggedIn()
{
    // Primeiro verificar se há sessão ativa
    if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
        return true;
    }
    
    // Se não há sessão, verificar cookie de autenticação
    if (function_exists('isLoggedInByCookie')) {
        return isLoggedInByCookie();
    }
    
    return false;
}

// Obter nome do usuário logado (compatibilidade com sistema de cookies e sessões)
function getLoggedInUserName()
{
    // Primeiro verificar se há sessão ativa
    if (isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])) {
        return $_SESSION['user_name'];
    }
    
    // Se não há sessão, tentar obter do cookie
    if (function_exists('getLoggedInUserEmail')) {
        $cookieData = getLoggedInUser();
        if ($cookieData && isset($cookieData['email'])) {
            // Retornar email como fallback se não tivermos o nome
            return $cookieData['email'];
        }
    }
    
    return 'Usuário';
}

// Limpar sessão
function clearSession()
{
    unset($_SESSION['user_id']);
    unset($_SESSION['user_email']);
    unset($_SESSION['user_name']);
    unset($_SESSION['user_type']);
    session_destroy();
}

// Função para definir dados da sessão após login bem-sucedido
function setUserSession($user)
{
    $_SESSION['user_id'] = $user->id;
    $_SESSION['user_email'] = $user->email;
    $_SESSION['user_name'] = $user->nome;
    $_SESSION['user_type'] = $user->tipo;
}

