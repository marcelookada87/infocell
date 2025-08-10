<?php
session_start();

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

