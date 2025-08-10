<?php
// Definir o diretório raiz da aplicação
define('APPROOT', dirname(dirname(__FILE__)));

// Carregar arquivo de configuração
require_once APPROOT . '/config/config.php';

// Carregar helpers
require_once APPROOT . '/app/helpers/url_helper.php';
require_once APPROOT . '/app/helpers/session_helper.php';

// Autoload das classes core (não controllers)
spl_autoload_register(function($className) {
    // Só carregar classes core, não controllers
    if (file_exists(APPROOT . '/core/' . $className . '.php')) {
        require_once APPROOT . '/core/' . $className . '.php';
    }
});
