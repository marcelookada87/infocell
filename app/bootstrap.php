<?php
// Definir o diretório raiz da aplicação
define('APPROOT', dirname(dirname(__FILE__)));

// Carregar arquivo de configuração PRIMEIRO (para ter as constantes DB)
require_once APPROOT . '/config/config.php';

// Carregar PDO personalizado DEPOIS das constantes
require_once APPROOT . '/core/PDOclass.php';

// Carregar helpers
require_once APPROOT . '/app/helpers/url_helper.php';
require_once APPROOT . '/app/helpers/session_helper.php';
require_once APPROOT . '/app/helpers/cookie_helper.php';
require_once APPROOT . '/app/helpers/sanitize_helper.php';
require_once APPROOT . '/app/helpers/ordem_servico_helper.php';

// Autoload das classes
spl_autoload_register(function($className) {
    // Carregar classes core
    if (file_exists(APPROOT . '/core/' . $className . '.php')) {
        require_once APPROOT . '/core/' . $className . '.php';
    }
    // Carregar classes de modelo
    elseif (file_exists(APPROOT . '/app/models/' . $className . '.php')) {
        require_once APPROOT . '/app/models/' . $className . '.php';
    }
    // Carregar controllers
    elseif (file_exists(APPROOT . '/app/controllers/' . $className . 'Controller.php')) {
        require_once APPROOT . '/app/controllers/' . $className . 'Controller.php';
    }
});
