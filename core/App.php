<?php

class App
{
    protected $controller = 'Home';
    protected $method = 'index';
    protected $params = [];
    
    public function __construct()
    {
        $url = $this->parseUrl();
        error_log("Parsed URL: " . print_r($url, true));
        
        // Verificar se o controller existe
        if (isset($url[0])) {
            $controllerName = ucfirst($url[0]);
            error_log("Initial controller name: " . $controllerName);
            
            // Mapear URLs amigáveis
            $controllerMap = [
                'home' => 'Home',
                'auth' => 'Auth',
                'dashboard' => 'Dashboard',
                'ordem-servico' => 'OrdemServico',
                'cliente' => 'Cliente',
                'relatorio' => 'Relatorio'
            ];
            
            if (array_key_exists($url[0], $controllerMap)) {
                $controllerName = $controllerMap[$url[0]];
                error_log("Mapped controller name: " . $controllerName);
            }
            
            $controllerFile = APPROOT . '/app/controllers/' . $controllerName . 'Controller.php';
            error_log("Looking for controller file: " . $controllerFile);
            
            if (file_exists($controllerFile)) {
                error_log("Controller file found, setting controller to: " . $controllerName);
                $this->controller = $controllerName;
                unset($url[0]);
            } else {
                error_log("Controller file not found: " . $controllerFile);
                // Se não encontrar o controller, usar Home como padrão
                $this->controller = 'Home';
            }
        }
        
        $controllerFile = APPROOT . '/app/controllers/' . $this->controller . 'Controller.php';
        error_log("Loading controller file: " . $controllerFile);
        
        if (!file_exists($controllerFile)) {
            error_log("Controller file not found: " . $controllerFile);
            die('Controller não encontrado: ' . $this->controller);
        }
        
        require_once $controllerFile;
        
        $controllerClass = $this->controller . 'Controller';
        error_log("Instantiating controller class: " . $controllerClass);
        
        if (!class_exists($controllerClass)) {
            error_log("Controller class not found: " . $controllerClass);
            die('Classe do controller não encontrada: ' . $controllerClass);
        }
        
        $this->controller = new $controllerClass();
        
        // Verificar se o método existe
        if (isset($url[1])) {
            error_log("Looking for method: " . $url[1]);
            if (method_exists($this->controller, $url[1])) {
                error_log("Method found, setting method to: " . $url[1]);
                $this->method = $url[1];
                unset($url[1]);
            } else {
                error_log("Method not found: " . $url[1]);
                // Se o método não existir, usar index como padrão
                $this->method = 'index';
            }
        }
        
        $this->params = $url ? array_values($url) : [];
        error_log("Final parameters: " . print_r($this->params, true));
        error_log("Calling method: " . $this->method . " on controller: " . get_class($this->controller));
        
        try {
            call_user_func_array([$this->controller, $this->method], $this->params);
        } catch (Exception $e) {
            error_log("Error calling controller method: " . $e->getMessage());
            die('Erro ao executar o controller: ' . $e->getMessage());
        }
    }
    
    public function parseUrl()
    {
        if (isset($_GET['url'])) {
            $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
            error_log("Parsed URL from GET['url']: " . print_r($url, true));
            return $url;
        } else {
            error_log("No GET['url'] parameter found");
            return [];
        }
    }
}
