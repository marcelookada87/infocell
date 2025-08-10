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
                self::$data["default"]["dbstring"] = "mysql:host=".DB_HOST.":3306;dbname=".DB_NAME."";
                self::$data["default"]["dbuser"] = DB_USER;
                self::$data["default"]["dbpass"] = DB_PASS;
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

            pdo_query("CREATE TABLE IF NOT EXISTS log_query (
				id bigint(20) NOT NULL AUTO_INCREMENT,
				query longtext NULL DEFAULT NULL,
				parameters longtext NULL DEFAULT NULL,
				response longtext NULL DEFAULT NULL,
				runat int NULL,
				PRIMARY KEY (id))");
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

    function pdo_log($statm,$eo = NULL,$save = FALSE) {

        if (!is_object($pdodb = (pdoclass::$data[pdoclass::$current]['con'] ?? NULL))) {
            return NULL;
        }
        if (!(($save ?? FALSE) || ($statm['l'] ?? FALSE))) {
            return $eo;
        }
        try {
            $e = $eo;
            if (is_array($e) || is_object($e)) {
                $e = json_encode($e);
            }
            if (($clear = @$pdodb->prepare("select count(*) qtd from log_query"))->execute()) {
                $qtd = intval($clear->fetchAll()[0]['qtd'] ?? -1);
                if ($qtd < 0) {
                    return;
                } else if ($qtd > 1000) {
                    @$pdodb->prepare("delete from log_query order by id asc limit 100")->execute();
                }
            }
            if ((!empty($statm['s'] ?? '')) && (($statm['c'] ?? '') == 'default')) {
                if (!(strpos(preg_replace('/[^a-z]/','',strtolower(explode(' ',trim($statm['s']))[0] ?? '')),'set') !== FALSE)) {
                    @$pdodb->prepare("INSERT INTO log_query (query, parameters, response, runat) VALUES (:q, :p, :r, :t)")->execute([
                                                                                                                                      'q' => ($statm['s'] ?? NULL),
                                                                                                                                      'p' => json_encode($statm['v'] ?? []),
                                                                                                                                      'r' => ($statm['d'] ?? ($e ?? NULL)),
                                                                                                                                      't' => strtotime('now'),
                                                                                                                                    ]);
                }
            }
        } catch(PDOException $e) {
        }
        return $eo;
    }

    function pdo_insert_id($cname = NULL) {

        if ($cname === NULL) {
            $cname = pdoclass::$current;
        }
        if (!is_object($pdodb = (pdoclass::$data[$cname]['con'] ?? NULL))) {
            return NULL;
        }
        return $pdodb->lastInsertId();
    }

    function pdo_num_rows($statm) {

        if ($statm == NULL) {
            return 0;
        }
        if (!isset($statm['q'])) {
            return 0;
        }
        try {
            @$statm['q']->execute($statm['v'] ?? []);
            return pdo_log($statm,@$statm['q']->rowCount());
        } catch(PDOException $e) {
            pdo_log($statm,$e,TRUE);
            return 0;
        }
    }

    function pdo_fetch_array($statm,$convertjson = FALSE) {

        if ($statm == NULL) {
            return [];
        }
        if (!isset($statm['q'])) {
            return [];
        }
        try {
            @$statm['q']->execute($statm['v'] ?? []);
            $r = pdo_log($statm,arrayval($statm['q']->fetchAll(PDO::FETCH_OBJ)));
            if ($convertjson) {
                $r = recursive_jsonconvert($r,TRUE);
            }
            return $r;
        } catch(PDOException $e) {
            pdo_log($statm,$e,TRUE);
            return [];
        }
    }

    function pdo_fetch_object($statm,$convertjson = FALSE) {

        if ($statm == NULL) {
            return [];
        }
        if (!isset($statm['q'])) {
            return [];
        }
        try {
            @$statm['q']->execute($statm['v'] ?? []);
            $r = pdo_log($statm,$statm['q']->fetchAll(PDO::FETCH_OBJ));
            if ($convertjson) {
                $r = recursive_jsonconvert($r,FALSE);
            }
            return $r;
        } catch(PDOException $e) {
            pdo_log($statm,$e,TRUE);
            return [];
        }
    }

    function pdo_query($select,$vname = [],$cname = NULL,$log = FALSE) {

        if (is_string($vname)) {
            $cname = $vname;
            $vname = [];
        }
        if ($cname === NULL) {
            $cname = pdoclass::$current;
        }
        if (!is_object($pdodb = (pdoclass::$data[$cname]['con'] ?? NULL))) {
            if (!pdo_connect(NULL,NULL,NULL,$cname)) {
                return NULL;
            } else if (!is_object($pdodb = (pdoclass::$data[pdoclass::$current]['con'] ?? NULL))) {
                return NULL;
            }
        }
        try {
            $statm = [
              'q' => @$pdodb->prepare(jsonextractalias($select)),
              's' => $select,
              'c' => $cname,
              'v' => $vname,
              'l' => $log,
            ];
            return (!(strpos(preg_replace('/[^a-z]/','',strtolower(explode(' ',trim($select))[0] ?? '')),'select') !== FALSE)) ? pdo_num_rows($statm) : $statm;
        } catch(PDOException $e) {
            pdo_log($statm,$e,TRUE);
            echo "<!-- Error: ".$e->getMessage()." -->";
            if ($cname == "default") {
                pdo_autoconfig();
            }
            return [];
        }
    }

    function pdo_prepare($select,$cname = NULL) {

        if ($cname === NULL) {
            $cname = pdoclass::$current;
        }
        if (!is_object($pdodb = (pdoclass::$data[$cname]['con'] ?? NULL))) {
            return NULL;
        }
        try {
            $statm = $pdodb->prepare($select);
        } catch(PDOException $e) {
            if (@$_GET['debug'] == "2") {
                echo "<!-- Error: ".$e->getMessage()." -->";
            }
        }
        return $statm;
    }

    function pdo_execute($statm,$array) {

        if ($statm == NULL) {
            return [];
        }
        return $statm->execute($array);
    }

    function pdo_fetch_item($statm,$convertjson = FALSE) {

        if ($statm == NULL) {
            return [];
        }
        return (pdo_fetch_array($statm,$convertjson)[0] ?? []);
    }

    function pdo_fetch_row($statm,$convertjson = FALSE) {

        if ($statm == NULL) {
            return [];
        }
        return @pdo_fetch_item($statm,$convertjson);
    }

    function pdo_start_transaction($cname = NULL) {

        if ($cname === NULL) {
            $cname = pdoclass::$current;
        }
        if (!is_object($pdodb = (pdoclass::$data[$cname]['con'] ?? NULL))) {
            return NULL;
        }
        return ($pdodb == NULL) ? NULL : $pdodb->beginTransaction();
    }

    function pdo_commit($cname = NULL) {

        if ($cname === NULL) {
            $cname = pdoclass::$current;
        }
        if (!is_object($pdodb = (pdoclass::$data[$cname]['con'] ?? NULL))) {
            return NULL;
        }
        return ($pdodb == NULL) ? NULL : $pdodb->commit();
    }

    function pdo_rollback($cname = NULL) {

        if ($cname === NULL) {
            $cname = pdoclass::$current;
        }
        if (!is_object($pdodb = (pdoclass::$data[$cname]['con'] ?? NULL))) {
            return NULL;
        }
        return ($pdodb == NULL) ? NULL : $pdodb->rollBack();
    }

    function pdo_close($cname = NULL) {

        if ($cname === NULL) {
            $cname = pdoclass::$current;
        }
        if (!is_object($pdodb = (pdoclass::$data[$cname]['con'] ?? NULL))) {
            return NULL;
        }
        $pdodb = NULL;
        return TRUE;
    }

    function arrayval($data) {

        $result = [];
        if (is_array($data) || is_object($data)) {
            foreach ($data as $key => $value)
                $result[$key] = (is_array($value) || is_object($value)) ? arrayval($value) : $value;
            return $result;
        }
        return $data;
    }

    function jsonextractalias($query) {

        if (strpos(preg_replace('/[^a-z]/','',strtolower(explode(' ',trim($query))[0] ?? '')),'update') !== FALSE) {
            if (!empty($fp = explode(' WHERE ',str_ireplace(' where ',' WHERE ',($query.' WHERE ')))[0] ?? '')) {
                if (!empty($np = preg_replace('!(.*?)([\ |\,|\(])?([^\ \,\(\-]+)\-\>\'(.*?)\'(.*?)\'(.*?)\'(.*?)!',"$1$2$3=json_insert(coalesce($3,'{}'),'$4','$6')$7",preg_replace('!(.*?)([\ |\,|\(])?([^\ \,\(\-]+)\-\>\>\'(.*?)\'(.*?)\'(.*?)\'(.*?)!',"$1$2$3=json_set(coalesce($3,'{}'),'$4','$6')$7",$fp)))) {
                    $query = str_replace($fp,$np,$query);
                }
            }
        }
        return preg_replace('!(.*?)([\ |\,|\(])?([^\ \,\(\-]+)\-\>\'(.*?)\'(.*?)!',"$1$2json_extract($3,'$4')$5",preg_replace('!(.*?)([\ |\,|\(])?([^\ \,\(\-]+)\-\>\>\'(.*?)\'(.*?)!',"$1$2json_unquote(json_extract($3,'$4'))$5",$query));
    }

    function recursive_jsonconvert($data,$returnarray = FALSE) {

        $result = [];
        if (is_array($data) || is_object($data)) {
            foreach ($data as $key => $value)
                $result[$key] = (is_array($value) || is_object($value)) ? recursive_jsonconvert($value,$returnarray) : ((($convert = @json_decode($value,TRUE)) && (json_last_error() === JSON_ERROR_NONE)) ? $convert : $value);
            return (($returnarray) ? $result : json_decode(json_encode($result),$returnarray));
        }
        return $data;
    }

    function pdo_return_id($table){
        if(empty($table ?? '')) return;
        return intval(pdo_fetch_row(pdo_query("SELECT MAX(id) as id FROM `$table`"))['id']);
    }
    
    function pdo_tables_exist($table){
        $resultado = pdo_fetch_item(pdo_query("SELECT COUNT(*) AS existe
            FROM information_schema.tables
            WHERE table_schema = '".DB_NAME."'
              AND table_name = '{$table}'" ))['existe'] ?? 0;
        if ($resultado == 0) {
            return FALSE;
        }else{
            return TRUE;
        }
    }
    
    function pdo_column_exists($table, $column) {
        $resultado = pdo_fetch_item(pdo_query("SELECT COUNT(*) AS existe
        FROM information_schema.COLUMNS
        WHERE table_schema = '" . DB_NAME . "'
          AND table_name = '{$table}'
          AND column_name = '{$column}'" ))['existe'] ?? 0;
        if ($resultado == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    function pdo_function_exists($function_name) {
        $resultado = pdo_fetch_item(pdo_query("SELECT COUNT(*) AS existe
        FROM information_schema.ROUTINES
        WHERE routine_schema = '" . DB_NAME . "'
          AND routine_name = '{$function_name}'
          AND routine_type = 'FUNCTION'"))['existe'] ?? 0;
        
        return $resultado != 0;
    }
}
?>