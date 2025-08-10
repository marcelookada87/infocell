<?php
// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'infocell_os');

// URL da aplicação
define('URLROOT', 'http://localhost/infocell');

// Nome da aplicação
define('SITENAME', 'InfoCell - Sistema de Ordem de Serviço');

// Versão da aplicação
define('APPVERSION', '1.0.0');

// Configurações de sessão
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0); // Mudar para 1 em produção com HTTPS
ini_set('session.use_only_cookies', 1);

// Timezone
date_default_timezone_set('America/Sao_Paulo');

// Configurações de erro (desabilitar em produção)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configurações de upload
define('UPLOAD_PATH', '../public/uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB

