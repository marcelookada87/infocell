<?php
/**
 * Configurações de Cookie para Sistema de Autenticação
 * 
 * IMPORTANTE: Altere estas configurações em produção!
 */

// Chave secreta para gerar hashes de autenticação
// ALTERE ESTA CHAVE EM PRODUÇÃO!
define('COOKIE_SECRET', 'infocell_2024_secret_key_change_this_in_production');

// Nome do cookie de autenticação
define('COOKIE_NAME', 'infocell_auth');

// Tempo de expiração do cookie (em segundos)
// Padrão: 30 dias
define('COOKIE_EXPIRE', 60 * 60 * 24 * 30);

// Caminho do cookie
define('COOKIE_PATH', '/');

// Domínio do cookie (deixe vazio para o domínio atual)
define('COOKIE_DOMAIN', '');

// Cookie seguro (HTTPS) - true em produção
define('COOKIE_SECURE', false);

// Cookie HTTP only (não acessível via JavaScript)
define('COOKIE_HTTPONLY', true);

// Configurações de segurança adicionais
define('COOKIE_SAMESITE', 'Lax'); // Lax, Strict, None

// Tempo máximo de inatividade (em segundos)
// Padrão: 24 horas
define('MAX_INACTIVITY_TIME', 60 * 60 * 24);

// Regenerar hash a cada X minutos
// Padrão: 60 minutos
define('HASH_REFRESH_INTERVAL', 60 * 60);
