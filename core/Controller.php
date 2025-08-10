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
        $modelFile = APPROOT . '/app/models/' . $model . '.php';
        
        // Verificar se o arquivo do model existe
        if (!file_exists($modelFile)) {
            error_log("Model file not found: " . $modelFile);
            die('Model não encontrado: ' . $model);
        }
        
        // Require model file
        require_once $modelFile;
        
        // Verificar se a classe existe
        if (!class_exists($model)) {
            error_log("Model class not found: " . $model);
            die('Classe do model não encontrada: ' . $model);
        }
        
        // Instanciar model
        return new $model();
    }
    
    // Carregar view
    public function view($view, $data = [])
    {
        error_log("Loading view: " . $view . " with data: " . print_r($data, true));
        
        // Verificar se o arquivo view existe
        $viewFile = APPROOT . '/app/views/' . $view . '.php';
        if (file_exists($viewFile)) {
            error_log("View file found: " . $viewFile);
            require_once $viewFile;
        } else {
            error_log("View file not found: " . $viewFile);
            // View não existe
            die('View não existe: ' . $view);
        }
    }
}
