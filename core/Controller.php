<?php

/*
 * Classe base do controller
 * Carrega os models e views
 */
class Controller
{
    // Carregar model
    public function model($model)
    {
        // Require model file
        require_once APPROOT . '/app/models/' . $model . '.php';
        
        // Instanciar model
        return new $model();
    }
    
    // Carregar view
    public function view($view, $data = [])
    {
        // Verificar se o arquivo view existe
        if (file_exists(APPROOT . '/app/views/' . $view . '.php')) {
            require_once APPROOT . '/app/views/' . $view . '.php';
        } else {
            // View não existe
            die('View não existe');
        }
    }
}
