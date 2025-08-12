<?php

/* PDO database functions */
if (!class_exists('pdoclass')) {
    class pdoclass {

        public static $current = "default";

        public static $data = [
          "default" => [
            "dbstring" => NULL,
            "dbuser"   => NULL,
            "dbpass"   => NULL,
            "con"      => NULL,
          ],
        ];

        public function __construct($connectionstr = NULL,$dbuser = NULL,$dbpass = NULL) {
            // Inicializar configuração padrão se não foi feita
            if (self::$data["default"]["dbstring"] === NULL) {
                // Verificar se as constantes estão definidas
                if (defined('DB_HOST') && defined('DB_NAME') && defined('DB_USER') && defined('DB_PASS')) {
                    self::$data["default"]["dbstring"] = "mysql:host=".DB_HOST.":3306;dbname=".DB_NAME."";
                    self::$data["default"]["dbuser"] = DB_USER;
                    self::$data["default"]["dbpass"] = DB_PASS;
                } else {
                    throw new Exception("Constantes de banco de dados não definidas. Verifique se config.php foi carregado.");
                }
            }

            self::$current = md5(($connectionstr = ($connectionstr ?? self::$data["default"]["dbstring"])).($dbuser = ($dbuser ?? self::$data["default"]["dbuser"])).($dbpass = ($dbpass ?? self::$data["default"]["dbpass"])));
            if (is_object(pdoclass::$data[self::$current]["con"] ?? NULL)) {
                return FALSE;
            }
            if (!pdo_connect($connectionstr,$dbuser,$dbpass,self::$current)) {
                return FALSE;
            } else {
                return pdoclass::$data[self::$current]["con"];
            }
        }

        public static function __callStatic($name,$arguments) {

            if (!function_exists($name)) {
                return [];
            }
            $arguments[] = self::$current;
            return call_user_func_array($name,$arguments);
        }

        public static function database() {

            // Criar tabela de log se necessário
            try {
                $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $sql = "CREATE TABLE IF NOT EXISTS log_query (
                    id bigint(20) NOT NULL AUTO_INCREMENT,
                    query longtext NULL DEFAULT NULL,
                    parameters longtext NULL DEFAULT NULL,
                    response longtext NULL DEFAULT NULL,
                    runat int NULL,
                    PRIMARY KEY (id))";
                
                $pdo->exec($sql);
            } catch (PDOException $e) {
                // Silenciar erro se a tabela já existir
            }
        }

    }
}

if (!function_exists('pdo_connect')) {
    function pdo_autoconfig($dir = NULL,$file = 'database.php') {

        if ($dir === NULL) {
            $dir = __DIR__;
        }
        if (($_SERVER[$on = 'autocreatedbtables_'.md5("$dir/$file")] ?? '') == '1') {
            return;
        } else {
            $_SERVER[$on] = '1';
        }
        if (!isset($_SERVER['GROUP']) && class_exists('group',TRUE)) {
            new group();
        }
        if (function_exists('autocreatedbtables')) {
            return autocreatedbtables();
        }
        for ($i = 0;$i < 5;$i++)
            if (file_exists("$dir/$file")) {
                return @include_once("$dir/$file");
            } else {
                $dir = realpath("$dir/../");
            }
    }

    function pdo_connect($connectionstr = NULL,$dbuser = NULL,$dbpass = NULL,$cname = NULL,$defresult = FALSE) {

        if (!isset($_SERVER['GROUP']) && class_exists('group',TRUE)) {
            new group();
        }
        $connectionstr = ($connectionstr ?? (pdoclass::$data[pdoclass::$current]["dbstring"] ?? (pdoclass::$data["default"]["dbstring"] ?? '')));
        $dbuser = ($dbuser ?? (pdoclass::$data[pdoclass::$current]["dbuser"] ?? (pdoclass::$data["default"]["dbuser"] ?? '')));
        $dbpass = ($dbpass ?? (pdoclass::$data[pdoclass::$current]["dbpass"] ?? (pdoclass::$data["default"]["dbpass"] ?? '')));
        if ($cname === NULL) {
            $cname = pdoclass::$current = md5($connectionstr.$dbuser.$dbpass);
        }
        try {
            if (is_object(pdoclass::$data[$cname]['con'] ?? NULL)) {
                return $defresult;
            }
            pdoclass::$data[$cname] = [
              "dbstring" => $connectionstr,
              "dbuser"   => $dbuser,
              "dbpass"   => $dbpass,
              "con"      => NULL,
            ];
            pdoclass::$data[$cname]['con'] = new PDO($connectionstr,$dbuser,$dbpass);
            pdoclass::$data[$cname]['con']->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $defresult = $cname;
        } catch(PDOException $e) {
            $defresult = FALSE;
        }
        if (!$defresult) {
            pdo_autoconfig();
        }
        return $defresult;
    }
}
?>