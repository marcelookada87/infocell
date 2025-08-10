<?php

class App
{
    protected $controller = 'Home';
    protected $method = 'index';
    protected $params = [];
    
    public function __construct()
    {
        $url = $this->parseUrl();
        
        // Verificar se o controller existe
        if (isset($url[0])) {
            $controllerName = ucfirst($url[0]);
            
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
            }
            
            if (file_exists(APPROOT . '/app/controllers/' . $controllerName . 'Controller.php')) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }
        
        require_once APPROOT . '/app/controllers/' . $this->controller . 'Controller.php';
        
        $controllerClass = $this->controller . 'Controller';
        $this->controller = new $controllerClass();
        
        // Verificar se o método existe
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }
        
        $this->params = $url ? array_values($url) : [];
        
        call_user_func_array([$this->controller, $this->method], $this->params);
    }
    
    public function parseUrl()
    {
        if (isset($_GET['url'])) {
            return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
    }
}
