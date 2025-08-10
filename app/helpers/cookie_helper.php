<?php

/**
 * Helper para gerenciar cookies de autenticação
 * Sistema baseado em cookies seguros com hash de validação
 */

// Carregar configurações de cookie
if (file_exists(APPROOT . '/config/cookie_config.php')) {
    require_once APPROOT . '/config/cookie_config.php';
} else {
    // Configurações padrão se o arquivo não existir
    define('COOKIE_SECRET', 'infocell_2024_secret_key_change_this_in_production');
    define('COOKIE_NAME', 'infocell_auth');
    define('COOKIE_EXPIRE', 60 * 60 * 24 * 30); // 30 dias
    define('COOKIE_PATH', '/');
    define('COOKIE_DOMAIN', '');
    define('COOKIE_SECURE', false);
    define('COOKIE_HTTPONLY', true);
    define('COOKIE_SAMESITE', 'Lax');
    define('MAX_INACTIVITY_TIME', 60 * 60 * 24);
    define('HASH_REFRESH_INTERVAL', 60 * 60);
}

/**
 * Gera um hash único para autenticação
 */
function generateAuthHash($userId, $email, $timestamp = null)
{
    try {
        if ($timestamp === null) {
            $timestamp = time();
        }
        
        $data = $userId . '|' . $email . '|' . $timestamp . '|' . COOKIE_SECRET;
        $hash = hash('sha256', $data);
        
        error_log("Generated auth hash for user ID: " . $userId);
        return $hash;
    } catch (Exception $e) {
        error_log("Error generating auth hash: " . $e->getMessage());
        return false;
    }
}

/**
 * Cria o cookie de autenticação
 */
function createAuthCookie($userId, $email)
{
    try {
        $timestamp = time();
        $hash = generateAuthHash($userId, $email, $timestamp);
        
        $cookieData = [
            'user_id' => $userId,
            'email' => $email,
            'timestamp' => $timestamp,
            'hash' => $hash
        ];
        
        $cookieValue = base64_encode(json_encode($cookieData));
        
        // Configurar cookie com SameSite
        $cookieOptions = [
            'expires' => time() + COOKIE_EXPIRE,
            'path' => COOKIE_PATH,
            'domain' => COOKIE_DOMAIN,
            'secure' => COOKIE_SECURE,
            'httponly' => COOKIE_HTTPONLY,
            'samesite' => COOKIE_SAMESITE
        ];
        
        $result = setcookie(COOKIE_NAME, $cookieValue, $cookieOptions);
        
        if ($result) {
            error_log("Cookie created successfully for user ID: " . $userId);
            return $hash;
        } else {
            error_log("Failed to create cookie for user ID: " . $userId);
            return false;
        }
    } catch (Exception $e) {
        error_log("Error creating auth cookie: " . $e->getMessage());
        return false;
    }
}

/**
 * Valida o cookie de autenticação
 */
function validateAuthCookie()
{
    if (!isset($_COOKIE[COOKIE_NAME])) {
        error_log("No auth cookie found");
        return false;
    }
    
    try {
        $cookieData = json_decode(base64_decode($_COOKIE[COOKIE_NAME]), true);
        
        if (!$cookieData || !isset($cookieData['user_id'], $cookieData['email'], $cookieData['timestamp'], $cookieData['hash'])) {
            error_log("Invalid cookie data structure");
            clearAuthCookie();
            return false;
        }
        
        // Verificar se o cookie não expirou
        if (time() - $cookieData['timestamp'] > COOKIE_EXPIRE) {
            error_log("Cookie expired");
            clearAuthCookie();
            return false;
        }
        
        // Verificar se o hash é válido
        $expectedHash = generateAuthHash($cookieData['user_id'], $cookieData['email'], $cookieData['timestamp']);
        
        if (!hash_equals($expectedHash, $cookieData['hash'])) {
            error_log("Invalid cookie hash");
            clearAuthCookie();
            return false;
        }
        
        error_log("Cookie validated successfully for user ID: " . $cookieData['user_id']);
        return $cookieData;
        
    } catch (Exception $e) {
        error_log("Error validating auth cookie: " . $e->getMessage());
        clearAuthCookie();
        return false;
    }
}

/**
 * Limpa o cookie de autenticação
 */
function clearAuthCookie()
{
    try {
        $cookieOptions = [
            'expires' => time() - 3600,
            'path' => COOKIE_PATH,
            'domain' => COOKIE_DOMAIN,
            'secure' => COOKIE_SECURE,
            'httponly' => COOKIE_HTTPONLY,
            'samesite' => COOKIE_SAMESITE
        ];
        
        $result = setcookie(COOKIE_NAME, '', $cookieOptions);
        
        if ($result) {
            error_log("Auth cookie cleared successfully");
        } else {
            error_log("Failed to clear auth cookie");
        }
        
        unset($_COOKIE[COOKIE_NAME]);
        
    } catch (Exception $e) {
        error_log("Error clearing auth cookie: " . $e->getMessage());
    }
}

/**
 * Verifica se o usuário está logado via cookie
 */
function isLoggedInByCookie()
{
    $cookieData = validateAuthCookie();
    if ($cookieData) {
        error_log("User is logged in via cookie: " . $cookieData['user_id']);
        return true;
    } else {
        error_log("User is not logged in via cookie");
        return false;
    }
}

/**
 * Obtém os dados do usuário logado via cookie
 */
function getLoggedInUser()
{
    $cookieData = validateAuthCookie();
    if ($cookieData) {
        error_log("Retrieved logged in user data for ID: " . $cookieData['user_id']);
    } else {
        error_log("No valid logged in user data found");
    }
    return $cookieData;
}

/**
 * Obtém o ID do usuário logado
 */
function getLoggedInUserId()
{
    $cookieData = validateAuthCookie();
    if ($cookieData) {
        error_log("Retrieved logged in user ID: " . $cookieData['user_id']);
        return $cookieData['user_id'];
    } else {
        error_log("No valid logged in user ID found");
        return null;
    }
}

/**
 * Obtém o email do usuário logado
 */
function getLoggedInUserEmail()
{
    $cookieData = validateAuthCookie();
    if ($cookieData) {
        error_log("Retrieved logged in user email: " . $cookieData['email']);
        return $cookieData['email'];
    } else {
        error_log("No valid logged in user email found");
        return null;
    }
}

/**
 * Verifica se o usuário logado é admin
 * Nota: Esta função é um wrapper para o modelo User
 * Use $userModel->isAdmin() diretamente nos controllers
 */
function isAdmin()
{
    // Para usar esta função, você precisa ter uma instância do modelo User
    // Recomendado usar diretamente: $userModel->isAdmin()
    return false;
}

/**
 * Força logout do usuário
 */
function forceLogout()
{
    try {
        error_log("Force logout initiated");
        clearAuthCookie();
        
        // Limpar sessão se existir
        if (function_exists('clearSession')) {
            clearSession();
        }
        
        // Redirecionar para login
        if (function_exists('redirect')) {
            redirect('auth/login');
        } else {
            header('Location: ' . URLROOT . '/auth/login');
            exit();
        }
    } catch (Exception $e) {
        error_log("Error during force logout: " . $e->getMessage());
        // Fallback para redirecionamento simples
        header('Location: ' . URLROOT . '/auth/login');
        exit();
    }
}
