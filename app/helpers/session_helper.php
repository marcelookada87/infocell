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

