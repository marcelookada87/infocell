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
        error_log("Loading view: " . $view . " with data: " . print_r($data, true));
        
        // Verificar se o arquivo view existe
        if (file_exists(APPROOT . '/app/views/' . $view . '.php')) {
            error_log("View file found: " . APPROOT . '/app/views/' . $view . '.php');
            require_once APPROOT . '/app/views/' . $view . '.php';
        } else {
            error_log("View file not found: " . APPROOT . '/app/views/' . $view . '.php');
            // View não existe
            die('View não existe');
        }
    }
}
