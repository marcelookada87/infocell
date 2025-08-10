<?php
// Configurações de teste (sem banco de dados)

// URL da aplicação
define('URLROOT', 'http://localhost/infocell');

// Nome da aplicação
define('SITENAME', 'InfoCell - Sistema de Ordem de Serviço (TESTE)');

// Versão da aplicação
define('APPVERSION', '1.0.0');

// Configurações de sessão
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0);
ini_set('session.use_only_cookies', 1);

// Timezone
date_default_timezone_set('America/Sao_Paulo');

// Configurações de erro
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configurações de upload
define('UPLOAD_PATH', '../public/uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB

// Modo de teste - sem banco
define('TESTE_MODE', true);

// Configurações de banco (para quando o banco estiver disponível)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'infocell_os');
